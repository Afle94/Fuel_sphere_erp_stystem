<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exports\ChequeReceiptExport;
use App\Models\AccountName;
use App\Models\ChequeReceipt;
use App\Models\Ledgers;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class ChequeReceiptController extends Controller
{
    public function showchequereceipt(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $chequereceipts = ChequeReceipt::whereDate('date', $selectedDate)->orderBy('slip_no')->get();
        $nextSlipNo = $this->nextSlipNo();
        $Credit = AccountName::whereRaw('TRIM(under_group) = ?', ['SUNDRY DEBTORS'])
            ->orderBy('account_perticular')
            ->get('account_perticular');
        $Debit = AccountName::whereRaw('TRIM(under_group) = ?', ['BANK ACCOUNTS'])
            ->orderBy('account_perticular')
            ->get('account_perticular');

        return view('chequereceipt', compact('chequereceipts', 'nextSlipNo', 'Credit', 'Debit', 'selectedDate'));
        
    }

    public function storechequereceipt(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'debit' => 'required|string',
            'credit' => 'required|string',
            'amount' => 'required|numeric',
            'narration' => 'nullable|string',
            'cheque_no' => ['required', 'digits:6'],
            'datet' => 'required|date',
        ]);

        $validated['slip_no'] = $this->nextSlipNo();

        DB::transaction(function () use ($validated) {
            $chequeReceipt = ChequeReceipt::create($validated);
            $this->postChequeReceiptLedger($chequeReceipt);
        });

        return redirect()
            ->route('chequereceipt', ['date' => $validated['date']])
            ->with('success', 'Cheque Receipt created successfully.');
    }

    public function updatechequereceipt(Request $request, ChequeReceipt $chequereceipt)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'debit' => 'required|string',
            'credit' => 'required|string',
            'amount' => 'required|numeric',
            'narration' => 'nullable|string',
            'cheque_no' => ['required', 'digits:6'],
            'datet' => 'required|date',
        ]);

        $validated['slip_no'] = $chequereceipt->slip_no;

        DB::transaction(function () use ($chequereceipt, $validated) {
            $chequereceipt->update($validated);
            $this->postChequeReceiptLedger($chequereceipt);
        });

        return redirect()
            ->route('chequereceipt', ['date' => $validated['date']])
            ->with('success', 'Cheque Receipt updated successfully.');
    }

    public function destroychequereceipt(ChequeReceipt $chequereceipt)
    {
        $selectedDate = substr((string) $chequereceipt->date, 0, 10) ?: now()->toDateString();
        DB::transaction(function () use ($chequereceipt) {
            Ledgers::query()
                ->where('VOUCHERNO', $chequereceipt->id)
                ->where('VTYPE', 'CHEQUE RECEIPTS')
                ->delete();

            $chequereceipt->delete();
        });

        return redirect()
            ->route('chequereceipt', ['date' => $selectedDate])
            ->with('success', 'Cheque Receipt deleted successfully.');
    }

    public function chequereceipt_pdf(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $chequereceipts = ChequeReceipt::whereDate('date', $selectedDate)->orderBy('slip_no')->get();

        if ($chequereceipts->isEmpty()) {
            return redirect()->route('chequereceipt', ['date' => $selectedDate])->with('error', 'No cheque receipt entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Cheque Receipt List');
        }

        $theme = $this->exportTheme($request);
        $html = view('chequereceipt_pdf', compact('chequereceipts', 'selectedDate', 'theme'))->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('ChequeReceipt-' . $selectedDate . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function chequereceipt_excel(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $chequereceipts = ChequeReceipt::whereDate('date', $selectedDate)->orderBy('slip_no')->get();

        if ($chequereceipts->isEmpty()) {
            return redirect()->route('chequereceipt', ['date' => $selectedDate])->with('error', 'No cheque receipt entries available to export.');
        }

        return Excel::download(new ChequeReceiptExport($chequereceipts, $selectedDate, $this->exportTheme($request)), 'ChequeReceipt-' . $selectedDate . '.xlsx');
    }

    private function selectedDate(Request $request): string
    {
        $selectedDate = $request->query('date', now()->toDateString());

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate) ? $selectedDate : now()->toDateString();
    }

    private function nextSlipNo(): string
    {
        $maxSlipNo = ChequeReceipt::pluck('slip_no')
            ->map(fn ($slipNo) => (int) preg_replace('/\D+/', '', (string) $slipNo))
            ->max();

        return (string) (((int) $maxSlipNo) + 1);
    }

    private function postChequeReceiptLedger(ChequeReceipt $chequeReceipt): void
    {
        Ledgers::query()
            ->where('VOUCHERNO', $chequeReceipt->id)
            ->where('VTYPE', 'CHEQUE RECEIPTS')
            ->delete();

        Ledgers::create([
            'VOUCHERNO' => $chequeReceipt->id,
            'VTYPE' => 'CHEQUE RECEIPTS',
            'TRANDATE' => $chequeReceipt->date,
            'TRANTYPE' => 'D',
            'ACNO' => $chequeReceipt->debit,
            'AMOUNT' => $chequeReceipt->amount,
        ]);

        Ledgers::create([
            'VOUCHERNO' => $chequeReceipt->id,
            'VTYPE' => 'CHEQUE RECEIPTS',
            'TRANDATE' => $chequeReceipt->date,
            'TRANTYPE' => 'C',
            'ACNO' => $chequeReceipt->credit,
            'AMOUNT' => $chequeReceipt->amount,
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
