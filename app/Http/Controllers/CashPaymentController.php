<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exports\CashPaymentExport;
use App\Models\CashPayment;

use App\Models\AccountName;
use App\Models\Ledgers;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class CashPaymentController extends Controller
{
    public function showcashPayment(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $cashpayments = CashPayment::whereDate('date', $selectedDate)->orderBy('slip_no')->get();
        $nextSlipNo = $this->nextSlipNo();

        $Credit = AccountName::whereRaw('UPPER(TRIM(under_group)) = ?', ['CASH IN HAND'])
            ->orderBy('account_perticular')
            ->get('account_perticular');

        $debit = AccountName::whereRaw('UPPER(TRIM(under_group)) = ?', ['SUNDRY DEBTORS'])
            ->orderBy('account_perticular')
            ->get('account_perticular');

        return view('cashpayment', compact('cashpayments', 'nextSlipNo', 'Credit', 'debit', 'selectedDate'));
    }


    public function storecashPayment(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'credit' => 'required|string',
            'debit' => 'required|string',
            'amount' => 'required|numeric',
            'Narration' => 'nullable|string',
        ]);

        $validated['slip_no'] = $this->nextSlipNo();

        DB::transaction(function () use ($validated) {
            $cashPayment = CashPayment::create($validated);
            $this->postCashPaymentLedger($cashPayment);
        });

        return redirect()
            ->route('cashpayment', ['date' => $validated['date']])
            ->with('success', 'Cash Payment created successfully.');
    }

    public function updatecashPayment(Request $request, CashPayment $cashpayment)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'credit' => 'required|string',
            'debit' => 'required|string',
            'amount' => 'required|numeric',
            'Narration' => 'nullable|string',
        ]);

        $validated['slip_no'] = $cashpayment->slip_no;

        DB::transaction(function () use ($cashpayment, $validated) {
            $cashpayment->update($validated);
            $this->postCashPaymentLedger($cashpayment);
        });

        return redirect()
            ->route('cashpayment', ['date' => $validated['date']])
            ->with('success', 'Cash Payment updated successfully.');
    }

    private function nextSlipNo(): string
    {
        $maxSlipNo = CashPayment::pluck('slip_no')
            ->map(fn ($slipNo) => (int) preg_replace('/\D+/', '', (string) $slipNo))
            ->max();

        return (string) (((int) $maxSlipNo) + 1);
    }

    public function destroycashPayment(CashPayment $cashpayment)
    {
        $selectedDate = substr((string) $cashpayment->date, 0, 10) ?: now()->toDateString();
        DB::transaction(function () use ($cashpayment) {
            Ledgers::query()
                ->where('VOUCHERNO', $cashpayment->id)
                ->where('VTYPE', 'CASH PAYMENTS')
                ->delete();

            $cashpayment->delete();
        });

        return redirect()
            ->route('cashpayment', ['date' => $selectedDate])
            ->with('success', 'Cash Payment deleted successfully.');
    }

    public function cashpayment_pdf(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $cashpayments = CashPayment::whereDate('date', $selectedDate)->orderBy('slip_no')->get();

        if ($cashpayments->isEmpty()) {
            return redirect()->route('cashpayment', ['date' => $selectedDate])->with('error', 'No cash payment entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Cash Payment List');
        }

        $theme = $this->exportTheme($request);
        $html = view('cashpayment_pdf', compact('cashpayments', 'selectedDate', 'theme'))->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('CashPayment-' . $selectedDate . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function selectedDate(Request $request): string
    {
        $selectedDate = $request->query('date', now()->toDateString());

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate) ? $selectedDate : now()->toDateString();
    }

    public function cashpayment_excel(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $cashpayments = CashPayment::whereDate('date', $selectedDate)->orderBy('slip_no')->get();

        if ($cashpayments->isEmpty()) {
            return redirect()->route('cashpayment', ['date' => $selectedDate])->with('error', 'No cash payment entries available to export.');
        }

        return Excel::download(new CashPaymentExport($cashpayments, $selectedDate, $this->exportTheme($request)), 'CashPayment-' . $selectedDate . '.xlsx');
    }

    private function postCashPaymentLedger(CashPayment $cashPayment): void
    {
        Ledgers::query()
            ->where('VOUCHERNO', $cashPayment->id)
            ->where('VTYPE', 'CASH PAYMENTS')
            ->delete();

        Ledgers::create([
            'VOUCHERNO' => $cashPayment->id,
            'VTYPE' => 'CASH PAYMENTS',
            'TRANDATE' => $cashPayment->date,
            'TRANTYPE' => 'C',
            'ACNO' => $cashPayment->credit,
            'AMOUNT' => $cashPayment->amount,
        ]);

        Ledgers::create([
            'VOUCHERNO' => $cashPayment->id,
            'VTYPE' => 'CASH PAYMENTS',
            'TRANDATE' => $cashPayment->date,
            'TRANTYPE' => 'D',
            'ACNO' => $cashPayment->debit,
            'AMOUNT' => $cashPayment->amount,
        ]);
    }

    private function exportTheme(Request $request): array
    {
        $themes = [
            'default' => ['primary' => '#0f766e', 'primaryDark' => '#115e59', 'accent' => '#f59e0b', 'bgEnd' => '#eef5f3'],
            'ocean' => ['primary' => '#0369a1', 'primaryDark' => '#075985', 'accent' => '#14b8a6', 'bgEnd' => '#edf7fb'],
            'royal' => ['primary' => '#4338ca', 'primaryDark' => '#3730a3', 'accent' => '#f59e0b', 'bgEnd' => '#f1f2ff'],
            'rose' => ['primary' => '#be123c', 'primaryDark' => '#9f1239', 'accent' => '#0f766e', 'bgEnd' => '#fff1f4'],
            'charcoal' => ['primary' => '#334155', 'primaryDark' => '#1e293b', 'accent' => '#d97706', 'bgEnd' => '#eef2f7'],
            'sunset-sky' => ['primary' => '#ea580c', 'primaryDark' => '#c2410c', 'accent' => '#be123c', 'bgEnd' => '#ffe4d6'],
            'royal-print' => ['primary' => '#4c1d95', 'primaryDark' => '#3b0764', 'accent' => '#f59e0b', 'bgEnd' => '#f5f0ff'],
            'peacock-print' => ['primary' => '#0f766e', 'primaryDark' => '#134e4a', 'accent' => '#0891b2', 'bgEnd' => '#ecfeff'],
            'marigold-print' => ['primary' => '#b45309', 'primaryDark' => '#92400e', 'accent' => '#be123c', 'bgEnd' => '#fff7ed'],
            'velvet-print' => ['primary' => '#9d174d', 'primaryDark' => '#831843', 'accent' => '#7c3aed', 'bgEnd' => '#fdf2f8'],
        ];

        return $themes[$request->query('theme', 'default')] ?? $themes['default'];
    }

}
