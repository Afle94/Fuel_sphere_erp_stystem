<?php

namespace App\Http\Controllers;

use App\Exports\CashSalesExport;
use App\Models\CashSales;
use App\Models\ItemDateRate;
use App\Models\Ledgers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class CashSalesController extends Controller
{
    public function showcashsales(Request $request)
    {
        $selectedDate = $request->query('date', now()->toDateString());

        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            $selectedDate = now()->toDateString();
        }

        $cashsales = CashSales::whereDate('date', $selectedDate)->get();
        $nextRefNo = ((int) CashSales::max('ref_no')) + 1;
        $products = Product::orderBy('Product_Name')->get();
        $latestRates = ItemDateRate::effectiveRatesByProductName($selectedDate);
        $productCategories = $products->pluck('Category', 'Product_Name');
        $categorySummaries = $cashsales
            ->groupBy(fn ($sale) => $productCategories[$sale->item_name] ?? $sale->item_name)
            ->map(function ($sales, $category) {
                return (object) [
                    'category' => $category ?: 'Uncategorized',
                    'quantity' => $sales->sum(fn ($sale) => (float) $sale->quantity),
                    'amount' => $sales->sum(fn ($sale) => (float) $sale->amount),
                ];
            })
            ->values();

        return view('cashsales', compact('cashsales', 'products', 'latestRates', 'categorySummaries', 'selectedDate', 'nextRefNo'));
    }

    public function storecashsales(Request $request)
    {
        $validated = $this->validateCashSale($request);
        $validated['amount'] = round((float) $validated['quantity'] * (float) $validated['rate'], 2);
        $validated['Narration'] = $validated['Narration'] ?? '';

        DB::transaction(function () use ($validated) {
            $cashSale = CashSales::create($validated);
            $this->postCashSaleLedger($cashSale);
        });

        return redirect()
            ->route('cashsales', ['date' => $validated['date']])
            ->with('success', 'Cash Sale created successfully.');
    }

    public function updatecashsales(Request $request, CashSales $cashsale)
    {
        $validated = $this->validateCashSale($request, $cashsale->id);
        $validated['amount'] = round((float) $validated['quantity'] * (float) $validated['rate'], 2);
        $validated['Narration'] = $validated['Narration'] ?? '';

        DB::transaction(function () use ($cashsale, $validated) {
            $cashsale->update($validated);
            $this->postCashSaleLedger($cashsale);
        });

        return redirect()
            ->route('cashsales', ['date' => $validated['date']])
            ->with('success', 'Cash Sale updated successfully.');
    }

    public function destroycashsales(CashSales $cashsale)
    {
        $selectedDate = substr((string) $cashsale->date, 0, 10) ?: now()->toDateString();

        DB::transaction(function () use ($cashsale) {
            Ledgers::query()
                ->where('VOUCHERNO', $cashsale->id)
                ->where('VTYPE', 'CASH SALES')
                ->delete();

            $cashsale->delete();
        });

        return redirect()
            ->route('cashsales', ['date' => $selectedDate])
            ->with('success', 'Cash Sale deleted successfully.');
    }

    public function cashsales_pdf(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $cashsales = CashSales::whereDate('date', $selectedDate)->orderBy('slip_no')->get();

        if ($cashsales->isEmpty()) {
            return redirect()->route('cashsales', ['date' => $selectedDate])->with('error', 'No cash sales entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Cash Sales List');
        }

        $theme = $this->exportTheme($request);
        $html = view('cashsales_pdf', compact('cashsales', 'selectedDate', 'theme'))->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('CashSales-' . $selectedDate . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function cashsales_excel(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $cashsales = CashSales::whereDate('date', $selectedDate)->orderBy('slip_no')->get();

        if ($cashsales->isEmpty()) {
            return redirect()->route('cashsales', ['date' => $selectedDate])->with('error', 'No cash sales entries available to export.');
        }

        return Excel::download(new CashSalesExport($cashsales, $selectedDate, $this->exportTheme($request)), 'CashSales-' . $selectedDate . '.xlsx');
    }

    private function validateCashSale(Request $request, ?int $ignoreId = null): array
    {
        $request->merge([
            'slip_no' => trim((string) $request->input('slip_no')) ?: null,
        ]);

        return $request->validate([
            'slip_no' => ['nullable', 'string', 'max:255', Rule::unique('cashsales', 'slip_no')->ignore($ignoreId)],
            'date' => ['required', 'date'],
            'ref_no' => ['required', 'string', 'max:255'],
            'item_name' => ['required', 'string', 'max:255', 'exists:produts,Product_Name'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'rate' => ['required', 'numeric', 'min:0'],
            'Narration' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function postCashSaleLedger(CashSales $cashSale): void
    {
        Ledgers::query()
            ->where('VOUCHERNO', $cashSale->id)
            ->where('VTYPE', 'CASH SALES')
            ->delete();

        Ledgers::create([
            'VOUCHERNO' => $cashSale->id,
            'VTYPE' => 'CASH SALES',
            'TRANDATE' => $cashSale->date,
            'TRANTYPE' => 'D',
            'ACNO' => 'CASH IN HAND',
            'AMOUNT' => $cashSale->amount,
        ]);

        Ledgers::create([
            'VOUCHERNO' => $cashSale->id,
            'VTYPE' => 'CASH SALES',
            'TRANDATE' => $cashSale->date,
            'TRANTYPE' => 'C',
            'ACNO' => 'CASH SALES',
            'AMOUNT' => $cashSale->amount,
        ]);
    }

    private function selectedDate(Request $request): string
    {
        $selectedDate = $request->query('date', now()->toDateString());

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate) ? $selectedDate : now()->toDateString();
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
