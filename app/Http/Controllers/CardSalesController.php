<?php

namespace App\Http\Controllers;

use App\Exports\CardSalesExport;
use Illuminate\Http\Request;

use App\Models\CardSales;

use App\Models\AccountName;
use App\Models\Ledgers;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class CardSalesController extends Controller
{
   


    public function createcardSales(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $card_types = [
            'HP CARD',
            'Bank CARD',
            'PAYTM',
            'PHONEPE',
        ];
        $Perticulars = AccountName::whereRaw('TRIM(under_group) = ?', ['BANK ACCOUNTS'])
            ->orderBy('account_perticular')
            ->get('account_perticular');

        $cardsales = CardSales::whereDate('date', $selectedDate)->orderBy('invoice_no')->get();
        $nextInvoiceNo = ((int) CardSales::max('invoice_no')) + 1;

        return view('cardsales', compact('card_types', 'Perticulars', 'cardsales', 'nextInvoiceNo', 'selectedDate'));
    }

    public function storecardSales(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'Card_type' => 'required|string',
            'Batch_no' => ['required', 'string', 'max:255', Rule::unique('cardsales', 'Batch_no')],
            'Amount' => 'required|numeric|min:0.01|max:99999999.99',
            'perticulars' => 'required|string',
            'narration' => 'nullable|string',
        ]);

        // Auto-generate invoice number if not provided
        $validated['invoice_no'] = ((int) CardSales::max('invoice_no')) + 1;

        DB::transaction(function () use ($validated) {
            $cardSale = CardSales::create($validated);
            $this->postCardSaleLedger($cardSale);
        });

        return redirect()
            ->route('cardsales', ['date' => $validated['date']])
            ->with('success', 'Card Sale created successfully.');
    }

    public function updatecardSales(Request $request, CardSales $cardsale)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'Card_type' => 'required|string',
            'Batch_no' => ['required', 'string', 'max:255', Rule::unique('cardsales', 'Batch_no')->ignore($cardsale->id)],
            'Amount' => 'required|numeric|min:0.01|max:99999999.99',
            'perticulars' => 'required|string',
            'narration' => 'nullable|string',
        ]);

        DB::transaction(function () use ($cardsale, $validated) {
            $cardsale->update($validated);
            $this->postCardSaleLedger($cardsale);
        });

        return redirect()
            ->route('cardsales', ['date' => $validated['date']])
            ->with('success', 'Card Sale updated successfully.');
    }

    public function destroycardSales(CardSales $cardsale)
    {
        $selectedDate = substr((string) $cardsale->date, 0, 10) ?: now()->toDateString();
        DB::transaction(function () use ($cardsale) {
            Ledgers::query()
                ->where('VOUCHERNO', $cardsale->id)
                ->where('VTYPE', 'CARD SALES')
                ->delete();

            $cardsale->delete();
        });

        return redirect()
            ->route('cardsales', ['date' => $selectedDate])
            ->with('success', 'Card Sale deleted successfully.');
    }

    public function cardsales_pdf(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $cardsales = CardSales::whereDate('date', $selectedDate)->orderBy('invoice_no')->get();

        if ($cardsales->isEmpty()) {
            return redirect()->route('cardsales', ['date' => $selectedDate])->with('error', 'No card sales entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Card Sales List');
        }

        $theme = $this->exportTheme($request);
        $html = view('cardsales_pdf', compact('cardsales', 'selectedDate', 'theme'))->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('CardSales-' . $selectedDate . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function cardsales_excel(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $cardsales = CardSales::whereDate('date', $selectedDate)->orderBy('invoice_no')->get();

        if ($cardsales->isEmpty()) {
            return redirect()->route('cardsales', ['date' => $selectedDate])->with('error', 'No card sales entries available to export.');
        }

        return Excel::download(new CardSalesExport($cardsales, $selectedDate, $this->exportTheme($request)), 'CardSales-' . $selectedDate . '.xlsx');
    }

    private function selectedDate(Request $request): string
    {
        $selectedDate = $request->query('date', now()->toDateString());

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate) ? $selectedDate : now()->toDateString();
    }

    private function postCardSaleLedger(CardSales $cardSale): void
    {
        Ledgers::query()
            ->where('VOUCHERNO', $cardSale->id)
            ->where('VTYPE', 'CARD SALES')
            ->delete();

        Ledgers::create([
            'VOUCHERNO' => $cardSale->id,
            'VTYPE' => 'CARD SALES',
            'TRANDATE' => $cardSale->date,
            'TRANTYPE' => 'D',
            'ACNO' => $cardSale->perticulars,
            'AMOUNT' => $cardSale->Amount,
        ]);

        Ledgers::create([
            'VOUCHERNO' => $cardSale->id,
            'VTYPE' => 'CARD SALES',
            'TRANDATE' => $cardSale->date,
            'TRANTYPE' => 'C',
            'ACNO' => 'CARD SALES',
            'AMOUNT' => $cardSale->Amount,
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
