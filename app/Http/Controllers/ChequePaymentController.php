<?php

namespace App\Http\Controllers;

use App\Exports\ChequePaymentExport;
use App\Models\AccountName;
use App\Models\ChequePayment;
use App\Models\Ledgers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class ChequePaymentController extends Controller
{
    public function showchequepayment(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $chequepayments = ChequePayment::whereDate('date', $selectedDate)->orderBy('slip_no')->get();
        $nextSlipNo = $this->nextSlipNo();

        $Credit = AccountName::whereRaw('UPPER(TRIM(under_group)) = ?', ['BANK ACCOUNTS'])
            ->orderBy('account_perticular')
            ->get('account_perticular');

        $Debit = AccountName::whereRaw('UPPER(TRIM(under_group)) = ?', ['SUNDRY DEBTORS'])
            ->orderBy('account_perticular')
            ->get('account_perticular');

        return view('chequepayment', compact('chequepayments', 'nextSlipNo', 'Credit', 'Debit', 'selectedDate'));
    }

    public function storechequepayment(Request $request)
    {
        $validated = $this->validatedData($request);
        $validated['slip_no'] = $this->nextSlipNo();

        DB::transaction(function () use ($validated) {
            $chequePayment = ChequePayment::create($validated);
            $this->postChequePaymentLedger($chequePayment);
        });

        return redirect()
            ->route('chequepayment', ['date' => $validated['date']])
            ->with('success', 'Cheque Payment created successfully.');
    }

    public function updatechequepayment(Request $request, ChequePayment $chequepayment)
    {
        $validated = $this->validatedData($request);
        $validated['slip_no'] = $chequepayment->slip_no;

        DB::transaction(function () use ($chequepayment, $validated) {
            $chequepayment->update($validated);
            $this->postChequePaymentLedger($chequepayment);
        });

        return redirect()
            ->route('chequepayment', ['date' => $validated['date']])
            ->with('success', 'Cheque Payment updated successfully.');
    }

    public function destroychequepayment(ChequePayment $chequepayment)
    {
        $selectedDate = substr((string) $chequepayment->date, 0, 10) ?: now()->toDateString();
        DB::transaction(function () use ($chequepayment) {
            Ledgers::query()
                ->where('VOUCHERNO', $chequepayment->id)
                ->where('VTYPE', 'CHEQUE PAYMENTS')
                ->delete();

            $chequepayment->delete();
        });

        return redirect()
            ->route('chequepayment', ['date' => $selectedDate])
            ->with('success', 'Cheque Payment deleted successfully.');
    }

    public function chequepayment_pdf(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $chequepayments = ChequePayment::whereDate('date', $selectedDate)->orderBy('slip_no')->get();

        if ($chequepayments->isEmpty()) {
            return redirect()->route('chequepayment', ['date' => $selectedDate])->with('error', 'No cheque payment entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Cheque Payment List');
        }

        $theme = $this->exportTheme($request);
        $html = view('chequepayment_pdf', compact('chequepayments', 'selectedDate', 'theme'))->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('ChequePayment-' . $selectedDate . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function chequepayment_excel(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $chequepayments = ChequePayment::whereDate('date', $selectedDate)->orderBy('slip_no')->get();

        if ($chequepayments->isEmpty()) {
            return redirect()->route('chequepayment', ['date' => $selectedDate])->with('error', 'No cheque payment entries available to export.');
        }

        return Excel::download(new ChequePaymentExport($chequepayments, $selectedDate, $this->exportTheme($request)), 'ChequePayment-' . $selectedDate . '.xlsx');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'date' => 'required|date',
            'debit' => 'required|string',
            'credit' => 'required|string',
            'amount' => 'required|numeric',
            'Narration' => 'nullable|string',
            'cheque_no' => ['required', 'digits:6'],
            'cheque_date' => 'required|date',
        ]);
    }

    private function selectedDate(Request $request): string
    {
        $selectedDate = $request->query('date', now()->toDateString());

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate) ? $selectedDate : now()->toDateString();
    }

    private function nextSlipNo(): string
    {
        $maxSlipNo = ChequePayment::pluck('slip_no')
            ->map(fn ($slipNo) => (int) preg_replace('/\D+/', '', (string) $slipNo))
            ->max();

        return (string) (((int) $maxSlipNo) + 1);
    }

    private function postChequePaymentLedger(ChequePayment $chequePayment): void
    {
        Ledgers::query()
            ->where('VOUCHERNO', $chequePayment->id)
            ->where('VTYPE', 'CHEQUE PAYMENTS')
            ->delete();

        Ledgers::create([
            'VOUCHERNO' => $chequePayment->id,
            'VTYPE' => 'CHEQUE PAYMENTS',
            'TRANDATE' => $chequePayment->date,
            'TRANTYPE' => 'D',
            'ACNO' => $chequePayment->debit,
            'AMOUNT' => $chequePayment->amount,
        ]);

        Ledgers::create([
            'VOUCHERNO' => $chequePayment->id,
            'VTYPE' => 'CHEQUE PAYMENTS',
            'TRANDATE' => $chequePayment->date,
            'TRANTYPE' => 'C',
            'ACNO' => $chequePayment->credit,
            'AMOUNT' => $chequePayment->amount,
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
