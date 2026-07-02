<?php

namespace App\Http\Controllers;

use App\Exports\CreditSalesExport;
use Illuminate\Http\Request;

use App\Models\CreditSales;

use App\Models\AccountName;

use App\Models\BillItem;
use App\Models\ItemDateRate;
use App\Models\Ledgers;
use App\Models\Product;
use App\Models\Vehicles;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class CreditSalesController extends Controller
{
    public function showcreditSales(Request $request)
    {
        $selectedDate = $request->query('date', now()->toDateString());

        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            $selectedDate = now()->toDateString();
        }

        $creditsales = $this->creditSalesForDate($selectedDate);
        $parties = AccountName::whereRaw('TRIM(under_group) = ?', ['SUNDRY DEBTORS'])
            ->orderBy('account_perticular')
            ->get();
        $products = Product::orderBy('Product_Name')->get();
        $latestRates = ItemDateRate::effectiveRatesByProductName($selectedDate);
        $vehiclesByParty = Vehicles::orderBy('Vehicle_no')
            ->get(['Party_name', 'Vehicle_no'])
            ->groupBy('Party_name')
            ->map(fn ($vehicles) => $vehicles->pluck('Vehicle_no')->values());
        $productCategories = $products->pluck('Category', 'Product_Name');
        $categorySummaries = $creditsales
            ->groupBy(fn ($sale) => $productCategories[$sale->item_name] ?? $sale->item_name)
            ->map(function ($sales, $category) {
                return (object) [
                    'category' => $category ?: 'Uncategorized',
                    'quantity' => $sales->sum(fn ($sale) => (float) $sale->quantity),
                    'amount' => $sales->sum(fn ($sale) => (float) $sale->amount),
                ];
            })
            ->values();

        return view('creditsales', compact('creditsales', 'parties', 'products', 'latestRates', 'vehiclesByParty', 'categorySummaries', 'selectedDate'));
    }


    public function storecreditsales(Request $request)
    {
        $validated = $this->validateCreditSale($request);
        $validated['amount'] = round((float) $validated['quantity'] * (float) $validated['rate'], 2);
        $validated['Narration'] = $validated['Narration'] ?? '';

        DB::transaction(function () use ($validated) {
            $creditSale = CreditSales::create($validated);
            $this->postCreditSaleLedger($creditSale);
        });

        return redirect()
            ->route('creditsales', ['date' => $validated['date']])
            ->with('success', 'Credit Sale created successfully.');
    }

    public function updatecreditsales(Request $request, CreditSales $creditsale)
    {
        $validated = $this->validateCreditSale($request, $creditsale->id);
        $validated['amount'] = round((float) $validated['quantity'] * (float) $validated['rate'], 2);
        $validated['Narration'] = $validated['Narration'] ?? '';

        DB::transaction(function () use ($creditsale, $validated) {
            $creditsale->update($validated);
            $this->postCreditSaleLedger($creditsale);
        });

        return redirect()
            ->route('creditsales', ['date' => $validated['date']])
            ->with('success', 'Credit Sale updated successfully.');
    }

    public function destroycreditsales(CreditSales $creditsale)
    {
        $selectedDate = substr((string) $creditsale->date, 0, 10) ?: now()->toDateString();

        DB::transaction(function () use ($creditsale) {
            Ledgers::query()
                ->where('VOUCHERNO', $creditsale->id)
                ->where('VTYPE', 'CREDIT SALES')
                ->delete();

            $creditsale->delete();
        });

        return redirect()
            ->route('creditsales', ['date' => $selectedDate])
            ->with('success', 'Credit Sale deleted successfully.');
    }

    public function creditsales_pdf(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $creditsales = $this->creditSalesForDate($selectedDate);

        if ($creditsales->isEmpty()) {
            return redirect()->route('creditsales', ['date' => $selectedDate])->with('error', 'No credit sales entries available to export.');
        }

        $theme = $this->exportTheme($request);
        $html = view('creditsales_pdf', compact('creditsales', 'selectedDate', 'theme'))->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('CreditSales-' . $selectedDate . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function creditsales_excel(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $creditsales = $this->creditSalesForDate($selectedDate);

        if ($creditsales->isEmpty()) {
            return redirect()->route('creditsales', ['date' => $selectedDate])->with('error', 'No credit sales entries available to export.');
        }

        return Excel::download(new CreditSalesExport($creditsales, $selectedDate, $this->exportTheme($request)), 'CreditSales-' . $selectedDate . '.xlsx');
    }

    private function validateCreditSale(Request $request, ?int $ignoreId = null): array
    {
        $request->merge([
            'Party_name' => trim((string) $request->input('Party_name')),
            'slip_no' => trim((string) $request->input('slip_no')) ?: null,
            'vehicle_no' => strtoupper(preg_replace('/[^A-Z0-9]/i', '', (string) $request->input('vehicle_no'))) ?: null,
        ]);

        return $request->validate([
            'slip_no' => ['nullable', 'string', 'max:255', Rule::unique('creditsales', 'slip_no')->ignore($ignoreId)],
            'date' => ['required', 'date'],
            'ref_no' => ['required', 'string', 'max:255'],
            'Party_name' => ['required', 'string', 'max:255', 'exists:account_name,account_perticular'],
            'vehicle_no' => ['nullable', 'string', 'max:255'],
            'item_name' => ['required', 'string', 'max:255', 'exists:produts,Product_Name'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'rate' => ['required', 'numeric', 'min:0'],
            'Narration' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function selectedDate(Request $request): string
    {
        $selectedDate = $request->query('date', now()->toDateString());

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate) ? $selectedDate : now()->toDateString();
    }

    private function postCreditSaleLedger(CreditSales $creditSale): void
    {
        Ledgers::query()
            ->where('VOUCHERNO', $creditSale->id)
            ->where('VTYPE', 'CREDIT SALES')
            ->delete();

        Ledgers::create([
            'VOUCHERNO' => $creditSale->id,
            'VTYPE' => 'CREDIT SALES',
            'TRANDATE' => $creditSale->date,
            'TRANTYPE' => 'D',
            'ACNO' => $creditSale->Party_name,
            'AMOUNT' => $creditSale->amount,
        ]);

        Ledgers::create([
            'VOUCHERNO' => $creditSale->id,
            'VTYPE' => 'CREDIT SALES',
            'TRANDATE' => $creditSale->date,
            'TRANTYPE' => 'C',
            'ACNO' => 'CREDIT SALES',
            'AMOUNT' => $creditSale->amount,
        ]);
    }

    private function creditSalesForDate(string $selectedDate)
    {
        $creditSales = CreditSales::whereDate('date', $selectedDate)
            ->orderBy('slip_no')
            ->get();

        $existingSlipNumbers = $creditSales
            ->pluck('slip_no')
            ->filter()
            ->map(fn ($slipNo) => (string) $slipNo)
            ->values();

        $billedItems = BillItem::query()
            ->with('bill:id,bill_no,party')
            ->whereDate('bill_date', $selectedDate)
            ->when($existingSlipNumbers->isNotEmpty(), fn ($query) => $query->whereNotIn('slip_no', $existingSlipNumbers))
            ->orderBy('slip_no')
            ->get()
            ->map(fn (BillItem $item) => (object) [
                'id' => null,
                'ref_no' => '-',
                'date' => substr((string) $item->bill_date, 0, 10),
                'slip_no' => $item->slip_no,
                'Party_name' => $item->bill?->party ?: '-',
                'vehicle_no' => $item->vehicle_no,
                'item_name' => $item->item_name,
                'quantity' => $item->qty,
                'rate' => $item->rate,
                'amount' => $item->amount,
                'Narration' => 'Generated Bill',
                'bill_no' => $item->bill?->bill_no,
                'is_bill_item_only' => true,
            ]);

        return $creditSales
            ->concat($billedItems)
            ->sortBy(fn ($sale) => sprintf('%s|%s', substr((string) $sale->date, 0, 10), $sale->slip_no))
            ->values();
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
