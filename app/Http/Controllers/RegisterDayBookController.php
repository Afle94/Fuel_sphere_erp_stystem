<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

use App\Models\DayFuel;
use App\Models\CreditSales;
use App\Models\CashSales;
use App\Models\CashReceipt;
use App\Models\ChequeReceipt;
use App\Models\CashPayment;
use App\Models\ChequePayment;
use App\Models\Purchase;
use App\Models\AccountName;

class RegisterDayBookController extends Controller
{
    
    public function index(Request $request)
    {
        $targetDate = $request->query('date', Carbon::today()->toDateString());

        $dayBookData = $this->calculateDayBookData($targetDate);
        $theme = $this->exportTheme($request);

        $sort = $request->query('sort', 'date');
        $direction = $request->query('direction', 'desc');
        $search = $request->query('search', '');
        $perPage = (int) $request->query('per_page', 10);

        return view('RegisterDayBook', compact(
            'dayBookData', 
            'targetDate', 
            'theme',
            'sort',
            'direction',
            'search',
            'perPage'
        ));
    }

    public function pdf(Request $request)
    {
        $targetDate = $request->query('date', Carbon::today()->toDateString());
        $dayBookData = $this->calculateDayBookData($targetDate);
        $theme = $this->exportTheme($request);
        
        $periodLabel = 'Date: ' . date('d-m-Y', strtotime($targetDate));

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Day Book Register');
        }

        $html = view('Day_Book_Register_pdf', compact('dayBookData', 'theme', 'periodLabel', 'targetDate'))->render();

        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        $fileName = 'DayBookRegister_' . date('d-m-Y', strtotime($targetDate)) . '.pdf';

        return response($mpdf->Output($fileName, 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }

    
    public function excel(Request $request)
    {
        $targetDate = $request->query('date', Carbon::today()->toDateString());
        $dayBookData = $this->calculateDayBookData($targetDate);
        $theme = $this->exportTheme($request);
        
        $periodLabel = 'Date: ' . date('d-m-Y', strtotime($targetDate));

        $fileName = 'DayBookRegister_' . date('d-m-Y', strtotime($targetDate)) . '.xlsx';

        return Excel::download(
            new \App\Exports\DayBookExport($dayBookData, $periodLabel, $theme),
            $fileName
        );
    }

    
    private function calculateDayBookData($targetDate): array
    {
        $openingCash = $this->cashOpeningBalance()
            + (float) CashSales::whereDate('date', '<', $targetDate)->sum('amount')
            + (float) CashReceipt::whereDate('date', '<', $targetDate)->sum('amount')
            - (float) CashPayment::whereDate('date', '<', $targetDate)->sum('amount');

        $dayFuelSale   = DayFuel::whereDate('date', $targetDate)->sum('Amount');
        $creditSales   = CreditSales::whereDate('date', $targetDate)->sum('amount');
        $cashSales     = CashSales::whereDate('date', $targetDate)->sum('amount');
        $cashReceipt   = CashReceipt::whereDate('date', $targetDate)->sum('amount');
        $chequeReceipt = ChequeReceipt::whereDate('date', $targetDate)->sum('amount');
        $cashPayment   = CashPayment::whereDate('date', $targetDate)->sum('amount');
        $chequePayment = ChequePayment::whereDate('date', $targetDate)->sum('amount');
        $purchase      = Purchase::whereDate('date', $targetDate)->sum('amount');

        $closingCash = $openingCash + $cashSales + $cashReceipt - $cashPayment;

        $itemSummaryMap = [];

        CashSales::whereDate('date', $targetDate)
            ->selectRaw('item_name, SUM(quantity) as total_qty, SUM(amount) as total_amt')
            ->groupBy('item_name')
            ->get()
            ->each(function($item) use (&$itemSummaryMap) {
                if(!empty($item->item_name)) {
                    $itemSummaryMap[$item->item_name]['quantity'] = ($itemSummaryMap[$item->item_name]['quantity'] ?? 0) + $item->total_qty;
                    $itemSummaryMap[$item->item_name]['amount']   = ($itemSummaryMap[$item->item_name]['amount'] ?? 0) + $item->total_amt;
                }
            });

        CreditSales::whereDate('date', $targetDate)
            ->selectRaw('item_name, SUM(quantity) as total_qty, SUM(amount) as total_amt')
            ->groupBy('item_name')
            ->get()
            ->each(function($item) use (&$itemSummaryMap) {
                if(!empty($item->item_name)) {
                    $itemSummaryMap[$item->item_name]['quantity'] = ($itemSummaryMap[$item->item_name]['quantity'] ?? 0) + $item->total_qty;
                    $itemSummaryMap[$item->item_name]['amount']   = ($itemSummaryMap[$item->item_name]['amount'] ?? 0) + $item->total_amt;
                }
            });

        return [
            'Opening Cash'   => $openingCash,
            'Day Fuel Sale'  => $dayFuelSale,
            'Credit Sales'   => $creditSales,
            'Cash Sales'     => $cashSales,
            'Cash Receipt'   => $cashReceipt,
            'Cheque Receipt' => $chequeReceipt,
            'Cash Payment'   => $cashPayment,
            'Cheque Payment' => $chequePayment,
            'Purchase'       => $purchase,
            'Closing Cash'   => $closingCash,
            'ItemsMatrix'    => $itemSummaryMap
        ];
    }

    private function cashOpeningBalance(): float
    {
        return AccountName::query()
            ->whereRaw('TRIM(UPPER(under_group)) = ?', ['CASH IN HAND'])
            ->get(['opening_balance', 'transaction_type'])
            ->sum(function ($account) {
                $amount = (float) ($account->opening_balance ?? 0);
                $type = strtoupper((string) ($account->transaction_type ?? 'Dr'));

                return str_starts_with($type, 'C') ? -$amount : $amount;
            });
    }

   
    private function exportTheme(Request $request): array
    {
        $themes = [
            'default' => ['primary' => '#0f766e', 'primaryDark' => '#115e59', 'accent' => '#f59e0b', 'bgEnd' => '#eef5f3'],
            'ocean'   => ['primary' => '#0369a1', 'primaryDark' => '#075985', 'accent' => '#14b8a6', 'bgEnd' => '#edf7fb'],
            'royal'   => ['primary' => '#4338ca', 'primaryDark' => '#3730a3', 'accent' => '#f59e0b', 'bgEnd' => '#f1f2ff'],
            'rose'    => ['primary' => '#be123c', 'primaryDark' => '#9f1239', 'accent' => '#0f766e', 'bgEnd' => '#fff1f4']
        ];
        return $themes[$request->query('theme', 'default')] ?? $themes['default'];
    }
}
