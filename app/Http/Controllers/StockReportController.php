<?php

namespace App\Http\Controllers;

use App\Models\DayFuel;
use App\Models\CashSales;
use App\Models\CreditSales;
use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StockReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $this->validDate($request->query('from_date')) ?: now()->startOfMonth()->toDateString();
        $toDate = $this->validDate($request->query('to_date')) ?: now()->toDateString();

        if ($fromDate > $toDate) {
            [$fromDate, $toDate] = [$toDate, $fromDate];
        }

        $search = trim((string) $request->query('search', ''));
        $rows = $this->stockRows($fromDate, $toDate, $search);
        $totals = [
            'opening' => $rows->sum('opening'),
            'in' => $rows->sum('in'),
            'out' => $rows->sum('out'),
            'closing' => $rows->sum('closing'),
            'value' => $rows->sum('value'),
        ];

        return view('stock_report', compact('fromDate', 'toDate', 'search', 'rows', 'totals'));
    }

    public function excel(Request $request): StreamedResponse
    {
        $exportData = $this->exportData($request);
        $filename = 'Stock-In-Out-Analysis-' . $exportData['fromDate'] . '-to-' . $exportData['toDate'] . '.csv';

        return response()->streamDownload(function () use ($exportData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Product Particulars', 'Opening', 'In', 'Out', 'Closing Stock', 'Purchase Rate', 'Value']);

            foreach ($exportData['rows'] as $row) {
                fputcsv($handle, [
                    $row['product'],
                    $row['opening'],
                    $row['in'],
                    $row['out'],
                    $row['closing'],
                    $row['purchase_rate'],
                    $row['value'],
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function pdf(Request $request)
    {
        $exportData = $this->exportData($request);
        $html = view('stock_report_pdf', $exportData)->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        $filename = 'Stock-In-Out-Analysis-' . $exportData['fromDate'] . '-to-' . $exportData['toDate'] . '.pdf';

        return response($mpdf->Output($filename, 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    private function stockRows(string $fromDate, string $toDate, string $search): Collection
    {
        $products = $this->products($search);
        $opening = $this->openingStockByItem($fromDate);
        $inward = $this->sumByItem(Purchase::query(), 'item_name', 'quantity', $fromDate, $toDate);
        $outward = $this->outwardStockByItem($fromDate, $toDate);
        $rates = $this->purchaseRates();

        return $products
            ->map(function ($product) use ($opening, $inward, $outward, $rates) {
                $key = $this->itemKey($product);
                $openingQty = (float) ($opening[$key] ?? 0);
                $inQty = (float) ($inward[$key] ?? 0);
                $outQty = (float) ($outward[$key] ?? 0);
                $closing = $openingQty + $inQty - $outQty;
                $rate = (float) ($rates[$key] ?? 0);

                return [
                    'product' => $product,
                    'opening' => $openingQty,
                    'in' => $inQty,
                    'out' => $outQty,
                    'closing' => $closing,
                    'purchase_rate' => $rate,
                    'value' => $closing * $rate,
                ];
            })
            ->values();
    }

    private function products(string $search): Collection
    {
        $items = collect();

        if (Schema::hasTable('produts')) {
            $items = $items->merge(Product::query()->pluck('Product_Name'));
        }

        if (Schema::hasTable('purchase')) {
            $items = $items->merge(Purchase::query()->pluck('item_name'));
        }

        if (Schema::hasTable('day_fuel')) {
            $items = $items->merge(DayFuel::query()->pluck('items'));
        }

        if (Schema::hasTable('cashsales')) {
            $items = $items->merge(CashSales::query()->pluck('item_name'));
        }

        if (Schema::hasTable('creditsales')) {
            $items = $items->merge(CreditSales::query()->pluck('item_name'));
        }

        return $items
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->unique(fn ($item) => $this->itemKey($item))
            ->filter(fn ($item) => $search === '' || stripos($item, $search) !== false)
            ->sort()
            ->values();
    }

    private function openingStockByItem(string $fromDate): Collection
    {
        $stock = Schema::hasTable('produts')
            ? Product::query()
                ->get(['Product_Name', 'opening_stock'])
                ->mapWithKeys(fn ($row) => [$this->itemKey($row->Product_Name) => (float) $row->opening_stock])
            : collect();

        if (Schema::hasTable('purchase')) {
            Purchase::query()
                ->whereDate('date', '<', $fromDate)
                ->selectRaw('item_name, SUM(quantity) as quantity')
                ->groupBy('item_name')
                ->get()
                ->each(function ($row) use ($stock) {
                    $key = $this->itemKey($row->item_name);
                    $stock[$key] = (float) ($stock[$key] ?? 0) + (float) $row->quantity;
                });
        }

        if (Schema::hasTable('day_fuel')) {
            DayFuel::query()
                ->whereDate('date', '<', $fromDate)
                ->selectRaw('items, SUM(Quantity) as quantity')
                ->groupBy('items')
                ->get()
                ->each(function ($row) use ($stock) {
                    $key = $this->itemKey($row->items);
                    $stock[$key] = (float) ($stock[$key] ?? 0) - (float) $row->quantity;
                });
        }

        if (Schema::hasTable('cashsales')) {
            CashSales::query()
                ->whereDate('date', '<', $fromDate)
                ->selectRaw('item_name, SUM(quantity) as quantity')
                ->groupBy('item_name')
                ->get()
                ->each(function ($row) use ($stock) {
                    $key = $this->itemKey($row->item_name);
                    $stock[$key] = (float) ($stock[$key] ?? 0) - (float) $row->quantity;
                });
        }

        if (Schema::hasTable('creditsales')) {
            CreditSales::query()
                ->whereDate('date', '<', $fromDate)
                ->selectRaw('item_name, SUM(quantity) as quantity')
                ->groupBy('item_name')
                ->get()
                ->each(function ($row) use ($stock) {
                    $key = $this->itemKey($row->item_name);
                    $stock[$key] = (float) ($stock[$key] ?? 0) - (float) $row->quantity;
                });
        }

        return $stock;
    }

    private function sumByItem($query, string $itemColumn, string $sumColumn, string $fromDate, string $toDate): Collection
    {
        if (! Schema::hasTable($query->getModel()->getTable())) {
            return collect();
        }

        return $query
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->selectRaw("{$itemColumn} as item, SUM({$sumColumn}) as total")
            ->groupBy($itemColumn)
            ->get()
            ->mapWithKeys(fn ($row) => [$this->itemKey($row->item) => (float) $row->total]);
    }

    private function purchaseRates(): Collection
    {
        return Schema::hasTable('produts')
            ? Product::query()
                ->get(['Product_Name', 'Purchase_rate'])
                ->mapWithKeys(fn ($row) => [$this->itemKey($row->Product_Name) => (float) $row->Purchase_rate])
            : collect();
    }

    private function outwardStockByItem(string $fromDate, string $toDate): Collection
    {
        $outward = collect();

        foreach ($this->outwardSources() as [$query, $itemColumn, $quantityColumn]) {
            $this->sumByItem($query, $itemColumn, $quantityColumn, $fromDate, $toDate)
                ->each(function ($quantity, $key) use ($outward) {
                    $outward[$key] = (float) ($outward[$key] ?? 0) + (float) $quantity;
                });
        }

        return $outward;
    }

    private function outwardSources(): array
    {
        return collect([
            [DayFuel::query(), 'items', 'Quantity'],
            [CashSales::query(), 'item_name', 'quantity'],
            [CreditSales::query(), 'item_name', 'quantity'],
        ])
            ->filter(fn ($source) => Schema::hasTable($source[0]->getModel()->getTable()))
            ->values()
            ->all();
    }

    private function exportData(Request $request): array
    {
        $fromDate = $this->validDate($request->query('from_date')) ?: now()->startOfMonth()->toDateString();
        $toDate = $this->validDate($request->query('to_date')) ?: now()->toDateString();

        if ($fromDate > $toDate) {
            [$fromDate, $toDate] = [$toDate, $fromDate];
        }

        $search = trim((string) $request->query('search', ''));
        $rows = $this->stockRows($fromDate, $toDate, $search);
        $totals = [
            'opening' => $rows->sum('opening'),
            'in' => $rows->sum('in'),
            'out' => $rows->sum('out'),
            'closing' => $rows->sum('closing'),
            'value' => $rows->sum('value'),
        ];

        return compact('fromDate', 'toDate', 'search', 'rows', 'totals');
    }

    private function validDate($value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $value)) {
            return Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        }

        return null;
    }

    private function itemKey($item): string
    {
        return strtolower(preg_replace('/\s+/', ' ', trim((string) $item)));
    }
}
