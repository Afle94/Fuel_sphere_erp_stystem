<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exports\CashReceiptExport;
use App\Models\AccountName;
use App\Models\CashReceipt;
use App\Models\Ledgers;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class CashReceiptController extends Controller
{
    public function showcashreceipt(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $cashreceipts = CashReceipt::whereDate('date', $selectedDate)->orderBy('slip_no')->get();
        $nextSlipNo = $this->nextSlipNo();
        $Credit = AccountName::whereRaw('TRIM(under_group) = ?', ['SUNDRY DEBTORS'])
            ->orderBy('account_perticular')
            ->get('account_perticular');
        $Debit = AccountName::whereRaw('TRIM(under_group) = ?', ['CASH IN HAND'])
            ->orderBy('account_perticular')
            ->get('account_perticular');

        return view('cashreceipt', compact('cashreceipts', 'nextSlipNo', 'Credit', 'Debit', 'selectedDate'));
    }


    public function storecashreceipt(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'credit' => 'required|string',
            'debit' => 'required|string',
            'amount' => 'required|numeric',
            'narration' => 'nullable|string',
        ]);

        $validated['slip_no'] = $this->nextSlipNo();

        DB::transaction(function () use ($validated) {
            $cashReceipt = CashReceipt::create($validated);
            $this->postCashReceiptLedger($cashReceipt);
        });

        return redirect()
            ->route('cashreceipt', ['date' => $validated['date']])
            ->with('success', 'Cash Receipt created successfully.');
    }

    public function updatecashreceipt(Request $request, CashReceipt $cashreceipt)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'credit' => 'required|string',
            'debit' => 'required|string',
            'amount' => 'required|numeric',
            'narration' => 'nullable|string',
        ]);

        $validated['slip_no'] = $cashreceipt->slip_no;

        DB::transaction(function () use ($cashreceipt, $validated) {
            $cashreceipt->update($validated);
            $this->postCashReceiptLedger($cashreceipt);
        });

        return redirect()
            ->route('cashreceipt', ['date' => $validated['date']])
            ->with('success', 'Cash Receipt updated successfully.');
    }

    public function destroycashreceipt(CashReceipt $cashreceipt)
    {
        $selectedDate = substr((string) $cashreceipt->date, 0, 10) ?: now()->toDateString();
        DB::transaction(function () use ($cashreceipt) {
            Ledgers::query()
                ->where('VOUCHERNO', $cashreceipt->id)
                ->where('VTYPE', 'CASH RECEIPTS')
                ->delete();

            $cashreceipt->delete();
        });

        return redirect()
            ->route('cashreceipt', ['date' => $selectedDate])
            ->with('success', 'Cash Receipt deleted successfully.');
    }

    public function cashreceipt_pdf(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $cashreceipts = CashReceipt::whereDate('date', $selectedDate)->orderBy('slip_no')->get();

        if ($cashreceipts->isEmpty()) {
            return redirect()->route('cashreceipt', ['date' => $selectedDate])->with('error', 'No cash receipt entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Cash Receipt List');
        }

        $theme = $this->exportTheme($request);
        $html = view('cashreceipt_pdf', compact('cashreceipts', 'selectedDate', 'theme'))->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('CashReceipt-' . $selectedDate . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function cashreceipt_excel(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $cashreceipts = CashReceipt::whereDate('date', $selectedDate)->orderBy('slip_no')->get();

        if ($cashreceipts->isEmpty()) {
            return redirect()->route('cashreceipt', ['date' => $selectedDate])->with('error', 'No cash receipt entries available to export.');
        }

        return Excel::download(new CashReceiptExport($cashreceipts, $selectedDate, $this->exportTheme($request)), 'CashReceipt-' . $selectedDate . '.xlsx');
    }

    private function selectedDate(Request $request): string
    {
        $selectedDate = $request->query('date', now()->toDateString());

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate) ? $selectedDate : now()->toDateString();
    }

    private function nextSlipNo(): string
    {
        $maxSlipNo = CashReceipt::pluck('slip_no')
            ->map(fn ($slipNo) => (int) preg_replace('/\D+/', '', (string) $slipNo))
            ->max();

        return (string) (((int) $maxSlipNo) + 1);
    }

    private function postCashReceiptLedger(CashReceipt $cashReceipt): void
    {
        Ledgers::query()
            ->where('VOUCHERNO', $cashReceipt->id)
            ->where('VTYPE', 'CASH RECEIPTS')
            ->delete();

        Ledgers::create([
            'VOUCHERNO' => $cashReceipt->id,
            'VTYPE' => 'CASH RECEIPTS',
            'TRANDATE' => $cashReceipt->date,
            'TRANTYPE' => 'D',
            'ACNO' => $cashReceipt->debit,
            'AMOUNT' => $cashReceipt->amount,
        ]);

        Ledgers::create([
            'VOUCHERNO' => $cashReceipt->id,
            'VTYPE' => 'CASH RECEIPTS',
            'TRANDATE' => $cashReceipt->date,
            'TRANTYPE' => 'C',
            'ACNO' => $cashReceipt->credit,
            'AMOUNT' => $cashReceipt->amount,
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
