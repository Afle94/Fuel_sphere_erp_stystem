<?php

namespace App\Http\Controllers;

use App\Exports\AdvanceStockRegisterExport;
use App\Models\DailyDip;
use App\Models\DayFuel;
use App\Models\Dipparameter;
use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class AdvanceStockRegisterController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureAdvanceStockOpeningDipTable();

        $hasFilter = $request->hasAny(['filter', 'from_date', 'to_date', 'search', 'product']);
        $fromDate = $this->validDate($request->query('from_date'));
        $toDate = $this->validDate($request->query('to_date'));

        if ($hasFilter) {
            $fromDate = $fromDate ?: now()->toDateString();
            $toDate = $toDate ?: $fromDate;
        }

        if ($fromDate && $toDate && $fromDate > $toDate) {
            [$fromDate, $toDate] = [$toDate, $fromDate];
        }

        $search = trim((string) $request->query('search', ''));
        $selectedProduct = trim((string) $request->query('product', ''));
        $rows = $hasFilter && $selectedProduct !== ''
            ? $this->stockRows($fromDate, $toDate, $search, $selectedProduct)
            : collect();
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 25);
        $perPage = in_array($perPage, $perPageOptions, true) ? $perPage : 25;
        $page = LengthAwarePaginator::resolveCurrentPage();

        $entries = new LengthAwarePaginator(
            $rows->forPage($page, $perPage)->values(),
            $rows->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        $totals = [
            'opening_stock' => $rows->sum('opening_stock'),
            'receipt' => $rows->sum('receipt'),
            'total_stock' => $rows->sum('total_stock'),
            'sales_by_meters' => $rows->sum('sales_by_meters'),
            'pump_test' => $rows->sum('pump_test'),
            'net_sales_by_meters' => $rows->sum('net_sales_by_meters'),
            'sales_by_dip' => $rows->sum('sales_by_dip'),
            'daily_variation' => $rows->sum('daily_variation'),
            'cumulative_variation' => $rows->sum('daily_variation'),
        ];
        $products = Schema::hasTable('produts')
            ? Product::query()
                ->orderBy('Product_Name')
                ->pluck('Product_Name')
                ->map(fn ($product) => trim((string) $product))
                ->filter()
                ->values()
            : collect();
        $fyOpeningDip = $selectedProduct !== ''
            ? $this->openingDipForProduct($selectedProduct, $fromDate ?: now()->toDateString(), $toDate ?: now()->toDateString())
            : null;
        $dipParameterLookup = $this->dipParameterLookup();

        return view('advance_stock_register', compact(
            'entries',
            'fromDate',
            'toDate',
            'search',
            'selectedProduct',
            'perPage',
            'perPageOptions',
            'totals',
            'hasFilter',
            'products',
            'fyOpeningDip',
            'dipParameterLookup'
        ));
    }

    public function storeOpeningDip(Request $request)
    {
        $validated = $request->validate([
            'product' => ['required', 'string', 'max:255'],
            'from_date' => ['required', 'date'],
            'to_date' => ['nullable', 'date'],
            'enter_depth' => ['required', 'numeric', 'min:0'],
            'liter' => ['required', 'numeric', 'min:0'],
            'search' => ['nullable', 'string'],
            'per_page' => ['nullable', 'integer'],
        ]);

        $this->ensureAdvanceStockOpeningDipTable();

        DB::table('advance_stock_opening_dips')->updateOrInsert(
            [
                'fy_start_date' => $this->financialYearStartDate($validated['from_date']),
                'item' => trim($validated['product']),
            ],
            [
                'enter_depth' => $validated['enter_depth'],
                'liter' => (int) (float) $validated['liter'],
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return redirect()
            ->route('advance-stock-register.index', [
                'filter' => 1,
                'from_date' => $validated['from_date'],
                'to_date' => $validated['to_date'] ?? $validated['from_date'],
                'search' => $validated['search'] ?? '',
                'per_page' => $validated['per_page'] ?? 25,
                'product' => $validated['product'],
            ])
            ->with('success', 'F.Y. opening dip updated for Advance Stock Register.');
    }

    public function pdf(Request $request)
    {
        $exportData = $this->exportData($request);

        if ($exportData['rows']->isEmpty()) {
            return redirect()
                ->route('advance-stock-register.index', $request->query())
                ->with('error', 'No Advance Stock Register records available to export.');
        }

        $html = view('advance_stock_register_pdf', $exportData)->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('AdvanceStockRegister-' . $exportData['fromDate'] . '-to-' . $exportData['toDate'] . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="AdvanceStockRegister-' . $exportData['fromDate'] . '-to-' . $exportData['toDate'] . '.pdf"');
    }

    public function excel(Request $request)
    {
        $exportData = $this->exportData($request);

        if ($exportData['rows']->isEmpty()) {
            return redirect()
                ->route('advance-stock-register.index', $request->query())
                ->with('error', 'No Advance Stock Register records available to export.');
        }

        return Excel::download(
            new AdvanceStockRegisterExport(
                $exportData['rows'],
                $exportData['totals'],
                $exportData['periodLabel'],
                $exportData['selectedProduct'],
                $exportData['search'],
                $exportData['theme']
            ),
            'AdvanceStockRegister-' . $exportData['fromDate'] . '-to-' . $exportData['toDate'] . '.xlsx'
        );
    }

    private function stockRows(string $fromDate, string $toDate, string $search, string $selectedProduct = ''): Collection
    {
        $items = $this->stockItems($search, $selectedProduct);
        $openingStock = $this->openingStockByItem($fromDate);
        $purchases = $this->sumByDateAndItem(Purchase::query(), 'item_name', 'quantity', $fromDate, $toDate);
        $sales = $this->sumByDateAndItem(DayFuel::query(), 'items', 'Quantity', $fromDate, $toDate);
        $pumpTests = $this->sumByDateAndItem(DayFuel::query(), 'items', 'Test', $fromDate, $toDate);
        $dipStock = $this->dipStockByDateAndItem($fromDate, $toDate);
        $dipDetails = $this->dipDetailsByDateAndItem($fromDate, $toDate);
        $fyOpeningDipStock = $this->fyOpeningDipStockByDateAndItem($fromDate, $toDate);
        $dates = $this->datesBetween($fromDate, $toDate);
        $rows = collect();

        foreach ($items as $item) {
            $itemKey = $this->itemKey($item);
            $runningStock = (float) ($openingStock[$itemKey] ?? 0);
            $cumulativeSales = 0.0;
            $cumulativeDipSales = 0.0;
            $cumulativeVariation = 0.0;

            foreach ($dates as $date) {
                $receipt = (float) optional($purchases->get($date))->get($itemKey, 0);
                $saleQuantity = (float) optional($sales->get($date))->get($itemKey, 0);
                $pumpTest = (float) optional($pumpTests->get($date))->get($itemKey, 0);
                $salesByMeters = $saleQuantity + $pumpTest;
                $totalStock = $runningStock + $receipt;
                $netSales = $salesByMeters - $pumpTest;
                $closingDip = optional($dipStock->get($date))->get($itemKey);
                $dipEntry = optional($dipDetails->get($date))->get($itemKey);
                $fyOpeningDip = optional($fyOpeningDipStock->get($date))->get($itemKey);
                $salesByDip = $fyOpeningDip === null || $closingDip === null
                    ? null
                    : max((float) $fyOpeningDip - (float) $closingDip - $cumulativeDipSales, 0);
                $cumulativeSales += $netSales;
                $cumulativeDipSales += (float) ($salesByDip ?? 0);
                $dailyVariation = $salesByDip === null ? null : $cumulativeSales - $salesByDip;
                $cumulativeVariation += (float) ($dailyVariation ?? 0);

                $rows->push([
                    'date' => $date,
                    'item' => $item,
                    'opening_stock' => $runningStock,
                    'receipt' => $receipt,
                    'total_stock' => $totalStock,
                    'sales_by_meters' => $salesByMeters,
                    'pump_test' => $pumpTest,
                    'net_sales_by_meters' => $netSales,
                    'cumulative_sales' => $cumulativeSales,
                    'sales_by_dip' => $salesByDip,
                    'daily_variation' => $dailyVariation,
                    'cumulative_variation' => $cumulativeVariation,
                    'dip_depth' => $dipEntry['depth'] ?? null,
                    'dip_liter' => $dipEntry['liter'] ?? $closingDip,
                ]);

                $runningStock = $closingDip === null ? max($totalStock - $netSales, 0) : (float) $closingDip;
            }
        }

        return $rows->sortBy([['date', 'asc'], ['item', 'asc']])->values();
    }

    private function exportData(Request $request): array
    {
        $fromDate = $this->validDate($request->query('from_date')) ?: now()->toDateString();
        $toDate = $this->validDate($request->query('to_date')) ?: $fromDate;

        if ($fromDate > $toDate) {
            [$fromDate, $toDate] = [$toDate, $fromDate];
        }

        $search = trim((string) $request->query('search', ''));
        $selectedProduct = trim((string) $request->query('product', ''));
        $rows = $selectedProduct !== ''
            ? $this->stockRows($fromDate, $toDate, $search, $selectedProduct)
            : collect();

        $totals = [
            'opening_stock' => $rows->sum('opening_stock'),
            'receipt' => $rows->sum('receipt'),
            'total_stock' => $rows->sum('total_stock'),
            'sales_by_meters' => $rows->sum('sales_by_meters'),
            'pump_test' => $rows->sum('pump_test'),
            'net_sales_by_meters' => $rows->sum('net_sales_by_meters'),
            'sales_by_dip' => $rows->sum('sales_by_dip'),
            'daily_variation' => $rows->sum('daily_variation'),
            'cumulative_variation' => $rows->sum('daily_variation'),
        ];

        return [
            'rows' => $rows,
            'totals' => $totals,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'periodLabel' => Carbon::parse($fromDate)->format('d M Y') . ' to ' . Carbon::parse($toDate)->format('d M Y'),
            'selectedProduct' => $selectedProduct,
            'search' => $search,
            'theme' => $this->exportTheme($request),
        ];
    }

    private function stockItems(string $search, string $selectedProduct = ''): Collection
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

        if (Schema::hasTable('dailydips')) {
            $items = $items->merge(DailyDip::query()->pluck('item'));
        }

        return $items
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->unique()
            ->filter(fn ($item) => $search === '' || stripos($item, $search) !== false)
            ->filter(fn ($item) => $selectedProduct === '' || $this->itemKey($item) === $this->itemKey($selectedProduct))
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
                    $stock[$key] = max((float) ($stock[$key] ?? 0) - (float) $row->quantity, 0);
                });
        }

        return $stock;
    }

    private function sumByDateAndItem($query, string $itemColumn, string $sumColumn, string $fromDate, string $toDate): Collection
    {
        if (! Schema::hasTable($query->getModel()->getTable())) {
            return collect();
        }

        return $query
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->selectRaw("date, {$itemColumn} as item, SUM({$sumColumn}) as total")
            ->groupBy('date', $itemColumn)
            ->get()
            ->groupBy(fn ($row) => Carbon::parse($row->date)->toDateString())
            ->map(function ($group) {
                return $group->reduce(function (Collection $totals, $row) {
                    $key = $this->itemKey($row->item);
                    $totals[$key] = (float) ($totals[$key] ?? 0) + (float) $row->total;

                    return $totals;
                }, collect());
            });
    }

    private function dipStockByDateAndItem(string $fromDate, string $toDate): Collection
    {
        if (! Schema::hasTable('dailydips')) {
            return collect();
        }

        $literColumn = collect(['liter', 'litre', 'ltr'])->first(fn ($column) => Schema::hasColumn('dailydips', $column));

        if (! $literColumn) {
            return collect();
        }

        return DailyDip::query()
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->selectRaw("date, item, MAX({$literColumn}) as liter")
            ->groupBy('date', 'item')
            ->get()
            ->groupBy(fn ($row) => Carbon::parse($row->date)->toDateString())
            ->map(fn ($group) => $group->mapWithKeys(fn ($row) => [$this->itemKey($row->item) => (float) $row->liter]));
    }

    private function dipDetailsByDateAndItem(string $fromDate, string $toDate): Collection
    {
        if (! Schema::hasTable('dailydips')) {
            return collect();
        }

        $depthColumn = collect(['enter_depth', 'depth', 'dip', 'dip_depth'])->first(fn ($column) => Schema::hasColumn('dailydips', $column));
        $literColumn = collect(['liter', 'litre', 'ltr'])->first(fn ($column) => Schema::hasColumn('dailydips', $column));

        if (! $depthColumn || ! $literColumn) {
            return collect();
        }

        return DailyDip::query()
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->orderBy('id')
            ->get()
            ->groupBy(fn ($row) => Carbon::parse($row->date)->toDateString())
            ->map(function ($group) use ($depthColumn, $literColumn) {
                return $group->mapWithKeys(fn ($row) => [
                    $this->itemKey($row->item) => [
                        'depth' => rtrim(rtrim(number_format((float) $row->{$depthColumn}, 2, '.', ''), '0'), '.'),
                        'liter' => (float) $row->{$literColumn},
                    ],
                ]);
            });
    }

    private function fyOpeningDipStockByDateAndItem(string $fromDate, string $toDate): Collection
    {
        if (! Schema::hasTable('dailydips')) {
            return collect();
        }

        $literColumn = collect(['liter', 'litre', 'ltr'])->first(fn ($column) => Schema::hasColumn('dailydips', $column));
        $openingLiterColumn = Schema::hasColumn('dailydips', 'fy_opening_liter') ? 'fy_opening_liter' : $literColumn;

        if (! $literColumn || ! $openingLiterColumn) {
            return collect();
        }

        $dates = collect($this->datesBetween($fromDate, $toDate));
        $fyStartDates = $dates
            ->map(fn ($date) => $this->financialYearStartDate($date))
            ->unique()
            ->values();
        $earliestFyStart = $fyStartDates->min();

        $fyDipRows = DailyDip::query()
            ->whereDate('date', '>=', $earliestFyStart)
            ->whereDate('date', '<=', $toDate)
            ->get()
            ->groupBy(fn ($row) => $this->financialYearStartDate(Carbon::parse($row->date)->toDateString()))
            ->map(function ($fyRows) use ($openingLiterColumn, $literColumn) {
                return $fyRows
                    ->sortBy([['date', 'asc'], ['id', 'asc']])
                    ->groupBy(fn ($row) => $this->itemKey($row->item))
                    ->map(fn ($itemRows) => (float) ($itemRows->first()->{$openingLiterColumn} ?? $itemRows->first()->{$literColumn}));
            });

        $overrideRows = $this->openingDipOverrides($fyStartDates);
        $fyDipRows = $fyDipRows->map(function ($items, $fyStartDate) use ($overrideRows) {
            return $items->merge($overrideRows->get($fyStartDate, collect()));
        });

        $overrideRows->each(function ($items, $fyStartDate) use (&$fyDipRows) {
            if (! $fyDipRows->has($fyStartDate)) {
                $fyDipRows[$fyStartDate] = $items;
            }
        });

        return $dates->mapWithKeys(function ($date) use ($fyDipRows) {
            return [$date => $fyDipRows->get($this->financialYearStartDate($date), collect())];
        });
    }

    private function openingDipOverrides(Collection $fyStartDates): Collection
    {
        if (! Schema::hasTable('advance_stock_opening_dips') || $fyStartDates->isEmpty()) {
            return collect();
        }

        return DB::table('advance_stock_opening_dips')
            ->whereIn('fy_start_date', $fyStartDates)
            ->get()
            ->groupBy(fn ($row) => Carbon::parse($row->fy_start_date)->toDateString())
            ->map(fn ($group) => $group->mapWithKeys(fn ($row) => [$this->itemKey($row->item) => (float) $row->liter]));
    }

    private function openingDipForProduct(string $product, string $fromDate, string $toDate): ?array
    {
        if (! Schema::hasTable('dailydips')) {
            return null;
        }

        $depthColumn = collect(['enter_depth', 'depth', 'dip', 'dip_depth'])->first(fn ($column) => Schema::hasColumn('dailydips', $column));
        $literColumn = collect(['liter', 'litre', 'ltr'])->first(fn ($column) => Schema::hasColumn('dailydips', $column));

        if (! $depthColumn || ! $literColumn) {
            return null;
        }

        $openingDepthColumn = Schema::hasColumn('dailydips', 'fy_opening_depth') ? 'fy_opening_depth' : $depthColumn;
        $openingLiterColumn = Schema::hasColumn('dailydips', 'fy_opening_liter') ? 'fy_opening_liter' : $literColumn;
        $productKey = $this->itemKey($product);
        $fyStartDate = $this->financialYearStartDate($fromDate);

        $entry = DailyDip::query()
            ->whereDate('date', '>=', $fyStartDate)
            ->whereDate('date', '<=', $toDate)
            ->orderBy('date')
            ->orderBy('id')
            ->get()
            ->first(fn ($row) => $this->itemKey($row->item) === $productKey);

        $override = $this->openingDipOverrideForProduct($product, $fyStartDate);

        if ($override) {
            return [
                'date' => Carbon::parse($override->fy_start_date)->format('d M Y'),
                'depth' => rtrim(rtrim(number_format((float) $override->enter_depth, 2, '.', ''), '0'), '.'),
                'liter' => (string) (int) (float) $override->liter,
                'is_override' => true,
            ];
        }

        if (! $entry) {
            return null;
        }

        return [
            'date' => Carbon::parse($entry->date)->format('d M Y'),
            'depth' => rtrim(rtrim(number_format((float) ($entry->{$openingDepthColumn} ?? $entry->{$depthColumn}), 2, '.', ''), '0'), '.'),
            'liter' => (string) (int) (float) ($entry->{$openingLiterColumn} ?? $entry->{$literColumn}),
            'is_override' => false,
        ];
    }

    private function openingDipOverrideForProduct(string $product, string $fyStartDate): ?object
    {
        if (! Schema::hasTable('advance_stock_opening_dips')) {
            return null;
        }

        $productKey = $this->itemKey($product);

        return DB::table('advance_stock_opening_dips')
            ->whereDate('fy_start_date', $fyStartDate)
            ->get()
            ->first(fn ($row) => $this->itemKey($row->item) === $productKey);
    }

    private function dipParameterLookup(): array
    {
        if (! Schema::hasTable('dipparameters')) {
            return [];
        }

        $lookup = [];

        Dipparameter::query()
            ->select('item', 'depth', 'liter')
            ->orderBy('item')
            ->orderBy('depth')
            ->get()
            ->each(function (Dipparameter $dipparameter) use (&$lookup) {
                $item = trim((string) $dipparameter->item);
                $depth = $this->dipLookupKey($dipparameter->depth);

                if ($item === '' || $depth === '') {
                    return;
                }

                $lookup[$item][$depth] = (string) $dipparameter->liter;
            });

        return $lookup;
    }

    private function dipLookupKey($value): string
    {
        $normalized = trim((string) $value);

        if ($normalized === '') {
            return '';
        }

        if (is_numeric($normalized)) {
            return rtrim(rtrim(number_format((float) $normalized, 4, '.', ''), '0'), '.');
        }

        return strtolower($normalized);
    }

    private function ensureAdvanceStockOpeningDipTable(): void
    {
        if (Schema::hasTable('advance_stock_opening_dips')) {
            return;
        }

        Schema::create('advance_stock_opening_dips', function ($table) {
            $table->id();
            $table->date('fy_start_date');
            $table->string('item');
            $table->decimal('enter_depth', 10, 2)->default(0);
            $table->decimal('liter', 12, 2)->default(0);
            $table->timestamps();
            $table->unique(['fy_start_date', 'item']);
        });
    }

    private function financialYearStartDate(string $date): string
    {
        $carbonDate = Carbon::parse($date);
        $year = $carbonDate->month >= 4 ? $carbonDate->year : $carbonDate->year - 1;

        return Carbon::create($year, 4, 1)->toDateString();
    }

    private function itemKey($item): string
    {
        return strtolower(preg_replace('/\s+/', ' ', trim((string) $item)));
    }

    private function datesBetween(string $fromDate, string $toDate): array
    {
        $dates = [];
        $cursor = Carbon::parse($fromDate);
        $end = Carbon::parse($toDate);

        while ($cursor->lte($end)) {
            $dates[] = $cursor->toDateString();
            $cursor->addDay();
        }

        return $dates;
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

    private function exportTheme(Request $request): array
    {
        $themes = [
            'default' => ['primary' => '#0f766e', 'primaryDark' => '#115e59', 'accent' => '#f59e0b', 'bgEnd' => '#eef5f3'],
            'ocean' => ['primary' => '#0369a1', 'primaryDark' => '#075985', 'accent' => '#14b8a6', 'bgEnd' => '#edf7fb'],
            'royal' => ['primary' => '#4338ca', 'primaryDark' => '#3730a3', 'accent' => '#f59e0b', 'bgEnd' => '#f1f2ff'],
            'rose' => ['primary' => '#be123c', 'primaryDark' => '#9f1239', 'accent' => '#0f766e', 'bgEnd' => '#fff1f4'],
            'charcoal' => ['primary' => '#334155', 'primaryDark' => '#1e293b', 'accent' => '#d97706', 'bgEnd' => '#eef2f7'],
        ];

        return $themes[$request->query('theme', 'default')] ?? $themes['default'];
    }
}
