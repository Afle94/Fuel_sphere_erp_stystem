<?php

namespace App\Http\Controllers;

use App\Exports\DayFuelExport;
use App\Exports\DipChartExport;
use App\Models\DailyDip;
use App\Models\DayFuel;
use App\Models\Dipparameter;
use App\Models\ItemDateRate;
use App\Models\Nozzle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class DayFuelController extends Controller
{
    public function showdayfuel(Request $request)
    {
        $selectedDate = $request->input('date', now()->toDateString());

        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            $selectedDate = now()->toDateString();
        }

        $dipParameterItems = $this->dipParameterItems();
        $dipParameterLookup = $this->dipParameterLookup();
        $dailyDipLookup = $this->dailyDipLookup($selectedDate);
        $latestRates = ItemDateRate::effectiveRatesByProductName()
            ->merge(ItemDateRate::effectiveRatesByProductName($selectedDate));
        $selectedCarbonDate = Carbon::parse($selectedDate);
        $previousDate = $selectedCarbonDate->copy()->subDay()->toDateString();
        $previousDateFormatted = $selectedCarbonDate->copy()->subDay()->format('d-m-Y');
        $today = now()->startOfDay();
        $shouldShowCurrentEntries = $selectedCarbonDate->lte($today);
        $shouldReadPreviousEntries = $selectedCarbonDate->copy()->subDay()->lte($today);
        $isSessionFirstDay = $selectedCarbonDate->format('m-d') === '04-01';

        if (! $this->ensureDayFuelTableReady()) {
            return view('day_fuel', [
                'dayFuels' => collect(),
                'starterRows' => collect(),
                'selectedDate' => $selectedDate,
                'previousDateFormatted' => $previousDateFormatted,
                'previousNozzleNames' => collect(),
                'previousEntryMessage' => 'There is no entry in ' . $previousDateFormatted,
                'dipParameterItems' => $dipParameterItems,
                'dipParameterLookup' => $dipParameterLookup,
                'latestRates' => $latestRates,
                'dailyDipLookup' => $dailyDipLookup,
            ]);
        }

        $nozzleColumn = $this->dayFuelNozzleColumn();

        $previousEntries = $shouldReadPreviousEntries
            ? DayFuel::with('Nozzle')
                ->whereDate('date', $previousDate)
                ->get()
                ->filter(fn ($dayFuel) => $this->isCompleteDayFuelEntry($dayFuel))
            : collect();

        $previousNozzleNames = $previousEntries
            ->map(fn ($dayFuel) => optional($dayFuel->Nozzle)->Nozzle_Name)
            ->filter()
            ->unique()
            ->values();

        $previousEntryMessage = $previousEntries->isEmpty() && ! $isSessionFirstDay
            ? 'There is no entry in ' . $previousDateFormatted
            : null;
        $showPreviousEntryPopup = $request->filled('date') && $previousEntryMessage;

        $currentEntries = $shouldShowCurrentEntries
            ? DayFuel::with('Nozzle')
                ->whereDate('date', $selectedDate)
                ->get()
                ->filter(fn ($dayFuel) => $this->isCompleteDayFuelEntry($dayFuel))
                ->keyBy(fn ($dayFuel) => $this->dayFuelNozzleId($dayFuel))
            : collect();

        $previousEntriesByNozzle = $previousEntries->keyBy(fn ($dayFuel) => $this->dayFuelNozzleId($dayFuel));

        $starterRows = $previousEntryMessage
            ? collect()
            : Nozzle::orderBy('Nozzle_Name')->get()->map(function ($nozzle) use ($currentEntries, $previousEntriesByNozzle, $latestRates) {
                $currentEntry = $currentEntries->get($nozzle->id);

                if ($currentEntry) {
                    return $currentEntry;
                }

                $previousEntry = $previousEntriesByNozzle->get($nozzle->id);

                return $this->carryForwardRow($nozzle, $previousEntry, $latestRates);
            });

        $dayFuels = collect();

        return view('day_fuel', compact(
            'dayFuels',
            'starterRows',
            'selectedDate',
            'previousDateFormatted',
            'previousNozzleNames',
            'previousEntryMessage',
            'showPreviousEntryPopup',
            'dipParameterItems',
            'dipParameterLookup',
            'latestRates',
            'dailyDipLookup'
        ));
    }

    public function storedayfuel(Request $request)
    {
        if (! $this->ensureDayFuelTableReady()) {
            return response()->json([
                'message' => 'day_fuel table ready nahi hai. Please php artisan migrate run karo.',
            ], 422);
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'nozzle_id' => ['required', 'exists:nozzles,id'],
            'open' => ['required', 'numeric'],
            'close' => ['required', 'numeric'],
            'Test' => ['nullable', 'numeric'],
            'rate' => ['nullable', 'numeric'],
        ]);

        $nozzle = Nozzle::findOrFail($validated['nozzle_id']);
        $nozzleColumn = $this->dayFuelNozzleColumn();
        $previousDate = Carbon::parse($validated['date'])->subDay()->toDateString();
        $previousEntry = DayFuel::whereDate('date', $previousDate)
            ->where($nozzleColumn, $nozzle->id)
            ->get()
            ->first(fn ($dayFuel) => $this->isCompleteDayFuelEntry($dayFuel));
        $openingReading = $previousEntry ? (float) $previousEntry->close : (float) $validated['open'];
        $closingReading = (float) $validated['close'];
        $test = (float) ($validated['Test'] ?? 0);
        $rate = (float) ($validated['rate'] ?? 0);

        if ($rate <= 0) {
            $rate = $this->effectiveRateForItem($nozzle->Item, $validated['date']);
        }

        $quantity = max($closingReading - $openingReading - $test, 0);
        $amount = $quantity * $rate;

        $identity = [
            'date' => $validated['date'],
            $nozzleColumn => $nozzle->id,
        ];

        $values = [
            'open' => $openingReading,
            'close' => $closingReading,
            'Test' => $test,
            'Quantity' => $quantity,
            'items' => $nozzle->Item,
            'rate' => $rate,
            'Amount' => $amount,
        ];

        if (Schema::hasColumn('day_fuel', 'nozzle_id')) {
            $values['nozzle_id'] = $nozzle->id;
        }

        if (Schema::hasColumn('day_fuel', 'Nozzel_id')) {
            $values['Nozzel_id'] = $nozzle->id;
        }

        $dayFuel = DayFuel::updateOrCreate(
            $identity,
            $values
        );

        return response()->json([
            'id' => $dayFuel->id,
            'opening' => number_format($dayFuel->open, 2, '.', ''),
            'closing' => number_format($dayFuel->close, 2, '.', ''),
            'test' => number_format($dayFuel->Test, 2, '.', ''),
            'quantity' => number_format($dayFuel->Quantity, 2, '.', ''),
            'rate' => number_format($dayFuel->rate, 2, '.', ''),
            'amount' => number_format($dayFuel->Amount, 2, '.', ''),
            'item' => $dayFuel->items,
        ]);
    }

    public function storeDailyDip(Request $request)
    {
        if (! Schema::hasTable('dailydips')) {
            return response()->json([
                'message' => 'dailydips table ready nahi hai. Please php artisan migrate run karo.',
            ], 422);
        }

        $this->ensureDailyDipValueColumns();

        $depthColumn = $this->dailyDipColumn(['enter_depth', 'depth', 'dip', 'dip_depth']);
        $literColumn = $this->dailyDipColumn(['liter', 'litre', 'ltr']);

        if (! $depthColumn || ! $literColumn) {
            return response()->json([
                'message' => 'dailydips table me depth/liter columns nahi mile. Please table structure check karo.',
            ], 422);
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'item' => ['required', 'string', 'max:255'],
            'enter_depth' => ['required', 'numeric', 'min:0'],
            'liter' => ['required', 'numeric', 'min:0'],
        ]);

        $dailyDip = DailyDip::firstOrNew([
            'date' => $validated['date'],
            'item' => $validated['item'],
        ]);

        if (! $dailyDip->exists) {
            $dailyDip->fy_opening_depth = $validated['enter_depth'];
            $dailyDip->fy_opening_liter = (int) (float) $validated['liter'];
        }

        if ($dailyDip->exists && $dailyDip->fy_opening_depth === null && Schema::hasColumn('dailydips', 'fy_opening_depth')) {
            $dailyDip->fy_opening_depth = $dailyDip->{$depthColumn};
        }

        if ($dailyDip->exists && $dailyDip->fy_opening_liter === null && Schema::hasColumn('dailydips', 'fy_opening_liter')) {
            $dailyDip->fy_opening_liter = $dailyDip->{$literColumn};
        }

        $dailyDip->{$depthColumn} = $validated['enter_depth'];
        $dailyDip->{$literColumn} = (int) (float) $validated['liter'];
        $dailyDip->save();

        return response()->json([
            'id' => $dailyDip->id,
            'date' => $dailyDip->date->toDateString(),
            'item' => $dailyDip->item,
            'enter_depth' => number_format((float) $dailyDip->{$depthColumn}, 2, '.', ''),
            'liter' => (string) (int) (float) $dailyDip->{$literColumn},
            'message' => 'Dip entry saved successfully.',
        ]);
    }

    public function dipChart(Request $request)
    {
        if (! Schema::hasTable('dailydips')) {
            return view('daily_dip', [
                'entries' => collect(),
                'items' => collect(),
                'selectedFromDate' => $request->query('from_date', $request->query('date', '')),
                'selectedToDate' => $request->query('to_date', $request->query('date', '')),
                'selectedItem' => '',
                'search' => '',
                'depthColumn' => 'enter_depth',
                'literColumn' => 'liter',
                'tableReady' => false,
                'requiresItemSelection' => true,
            ]);
        }

        $this->ensureDailyDipValueColumns();

        $legacyDate = trim((string) $request->query('date', ''));
        $selectedFromDate = trim((string) $request->query('from_date', $legacyDate));
        $selectedToDate = trim((string) $request->query('to_date', $legacyDate));
        $selectedItem = trim((string) $request->query('item', ''));
        $search = trim((string) $request->query('search', ''));
        $depthColumn = $this->dailyDipColumn(['enter_depth', 'depth', 'dip', 'dip_depth']) ?: 'enter_depth';
        $literColumn = $this->dailyDipColumn(['liter', 'litre', 'ltr']) ?: 'liter';

        if ($selectedFromDate !== '' && ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedFromDate)) {
            $selectedFromDate = '';
        }

        if ($selectedToDate !== '' && ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedToDate)) {
            $selectedToDate = '';
        }

        $items = DailyDip::query()
            ->select('item')
            ->distinct()
            ->orderBy('item')
            ->pluck('item')
            ->filter()
            ->values();

        $query = DailyDip::query()->orderByDesc('date')->orderBy('item');

        if ($selectedItem === '') {
            return view('daily_dip', [
                'entries' => collect(),
                'items' => $items,
                'selectedFromDate' => $selectedFromDate,
                'selectedToDate' => $selectedToDate,
                'selectedItem' => $selectedItem,
                'search' => $search,
                'depthColumn' => $depthColumn,
                'literColumn' => $literColumn,
                'tableReady' => true,
                'requiresItemSelection' => true,
            ]);
        }

        if ($selectedFromDate !== '') {
            $query->whereDate('date', '>=', $selectedFromDate);
        }

        if ($selectedToDate !== '') {
            $query->whereDate('date', '<=', $selectedToDate);
        }

        if ($selectedItem !== '') {
            $query->where('item', $selectedItem);
        }

        if ($search !== '') {
            $query->where(function ($query) use ($search, $depthColumn, $literColumn) {
                $query->where($depthColumn, 'like', "%{$search}%")
                    ->orWhere($literColumn, 'like', "%{$search}%");
            });
        }

        return view('daily_dip', [
            'entries' => $query->paginate(25)->withQueryString(),
            'items' => $items,
            'selectedFromDate' => $selectedFromDate,
            'selectedToDate' => $selectedToDate,
            'selectedItem' => $selectedItem,
            'search' => $search,
            'depthColumn' => $depthColumn,
            'literColumn' => $literColumn,
            'tableReady' => true,
            'requiresItemSelection' => false,
        ]);
    }

    public function dipChartPdf(Request $request)
    {
        $exportData = $this->dipChartExportData($request);

        if (! $exportData) {
            return redirect()
                ->route('daily-dip.index')
                ->with('error', 'Select an item with dip chart records before exporting.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Dip Chart');
        }

        $html = view('daily_dip_pdf', $exportData)->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('DipChart.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="DipChart.pdf"');
    }

    public function dipChartExcel(Request $request)
    {
        $exportData = $this->dipChartExportData($request);

        if (! $exportData) {
            return redirect()
                ->route('daily-dip.index')
                ->with('error', 'Select an item with dip chart records before exporting.');
        }

        return Excel::download(
            new DipChartExport(
                $exportData['entries'],
                $exportData['periodLabel'],
                $exportData['selectedItem'],
                $exportData['search'],
                $exportData['depthColumn'],
                $exportData['literColumn'],
                $exportData['theme']
            ),
            'DipChart.xlsx'
        );
    }

    public function dayfuel_pdf(Request $request)
    {
        $exportData = $this->dayFuelExportData($request);

        if (! $exportData) {
            return redirect()
                ->route('day-fuel.list', ['date' => $this->selectedDateFromRequest($request)])
                ->with('error', 'do entry first');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Day Fuel Sales');
        }

        $html = view('day_fuel_pdf', $exportData)->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        $filename = 'Day Fuel Sales ' . $exportData['formattedDate'] . '.pdf';

        return response($mpdf->Output($filename, 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    public function dayfuel_excel(Request $request)
    {
        $exportData = $this->dayFuelExportData($request);

        if (! $exportData) {
            return redirect()
                ->route('day-fuel.list', ['date' => $this->selectedDateFromRequest($request)])
                ->with('error', 'do entry first');
        }

        return Excel::download(
            new DayFuelExport($exportData['entries'], $exportData['selectedDate'], $exportData['theme']),
            'DayFuelSales-' . $exportData['selectedDate'] . '.xlsx'
        );
    }

    private function ensureDayFuelTableReady(): bool
    {
        if (! Schema::hasTable('day_fuel')) {
            return false;
        }

        if (Schema::hasColumn('day_fuel', 'nozzle_id')) {
            $this->syncNozzleColumns();

            return true;
        }

        try {
            Schema::table('day_fuel', function ($table) {
                $table->unsignedBigInteger('nozzle_id')->nullable()->after('date')->index();
            });

            $this->syncNozzleColumns();

            return true;
        } catch (\Throwable $error) {
            return false;
        }
    }

    private function dipParameterItems()
    {
        if (! Schema::hasTable('dipparameters')) {
            return collect();
        }

        return Dipparameter::select('item')
            ->distinct()
            ->orderBy('item')
            ->pluck('item')
            ->filter()
            ->values();
    }

    private function dailyDipColumn(array $candidates): ?string
    {
        foreach ($candidates as $column) {
            if (Schema::hasColumn('dailydips', $column)) {
                return $column;
            }
        }

        return null;
    }

    private function ensureDailyDipValueColumns(): void
    {
        $depthColumn = $this->dailyDipColumn(['enter_depth', 'depth', 'dip', 'dip_depth']);

        if (! $depthColumn) {
            Schema::table('dailydips', function ($table) {
                $table->decimal('enter_depth', 10, 2)->default(0)->after('item');
            });

            $depthColumn = 'enter_depth';
        }

        if (! $this->dailyDipColumn(['liter', 'litre', 'ltr'])) {
            Schema::table('dailydips', function ($table) use ($depthColumn) {
                $table->decimal('liter', 12, 2)->default(0)->after($depthColumn);
            });
        }

        if (! Schema::hasColumn('dailydips', 'fy_opening_depth')) {
            Schema::table('dailydips', function ($table) use ($depthColumn) {
                $table->decimal('fy_opening_depth', 10, 2)->nullable()->after($depthColumn);
            });
        }

        if (! Schema::hasColumn('dailydips', 'fy_opening_liter')) {
            Schema::table('dailydips', function ($table) {
                $table->decimal('fy_opening_liter', 12, 2)->nullable()->after('fy_opening_depth');
            });
        }
    }

    private function dailyDipLookup(string $selectedDate): array
    {
        if (! Schema::hasTable('dailydips')) {
            return [];
        }

        $this->ensureDailyDipValueColumns();

        $depthColumn = $this->dailyDipColumn(['enter_depth', 'depth', 'dip', 'dip_depth']);
        $literColumn = $this->dailyDipColumn(['liter', 'litre', 'ltr']);

        if (! $depthColumn || ! $literColumn) {
            return [];
        }

        $lookup = [];

        DailyDip::query()
            ->whereDate('date', $selectedDate)
            ->get()
            ->each(function (DailyDip $dailyDip) use (&$lookup, $depthColumn, $literColumn) {
                $item = trim((string) $dailyDip->item);

                if ($item === '') {
                    return;
                }

                $lookup[$item] = [
                    'enter_depth' => rtrim(rtrim(number_format((float) $dailyDip->{$depthColumn}, 2, '.', ''), '0'), '.'),
                    'liter' => (string) (int) (float) $dailyDip->{$literColumn},
                ];
            });

        return $lookup;
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

    private function dipLookupKey(mixed $value): string
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

    private function selectedDateFromRequest(Request $request): string
    {
        $selectedDate = $request->input('date', now()->toDateString());

        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            return now()->toDateString();
        }

        return $selectedDate;
    }

    private function dayFuelExportData(Request $request): ?array
    {
        $selectedDate = $this->selectedDateFromRequest($request);

        if (! $this->ensureDayFuelTableReady()) {
            return null;
        }

        $selectedCarbonDate = Carbon::parse($selectedDate);
        $previousDate = $selectedCarbonDate->copy()->subDay()->toDateString();
        $isSessionFirstDay = $selectedCarbonDate->format('m-d') === '04-01';
        $nozzleColumn = $this->dayFuelNozzleColumn();

        $previousEntries = $isSessionFirstDay
            ? collect()
            : DayFuel::whereDate('date', $previousDate)
                ->get()
                ->filter(fn ($dayFuel) => $this->isCompleteDayFuelEntry($dayFuel));

        if (! $isSessionFirstDay && $previousEntries->isEmpty()) {
            return null;
        }

        $entries = DayFuel::with('Nozzle')
            ->whereDate('date', $selectedDate)
            ->orderBy($nozzleColumn)
            ->get()
            ->filter(fn ($dayFuel) => $this->isCompleteDayFuelEntry($dayFuel))
            ->values();

        if ($entries->isEmpty()) {
            return null;
        }

        return [
            'entries' => $entries,
            'selectedDate' => $selectedDate,
            'formattedDate' => $selectedCarbonDate->format('d-m-Y'),
            'theme' => $this->exportTheme($request),
        ];
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

        $themeName = $request->query('theme', 'default');
        $theme = $themes[$themeName] ?? $themes['default'];
        $theme['name'] = array_key_exists($themeName, $themes) ? $themeName : 'default';

        return $theme;
    }

    private function dipChartExportData(Request $request): ?array
    {
        if (! Schema::hasTable('dailydips')) {
            return null;
        }

        $this->ensureDailyDipValueColumns();

        $legacyDate = trim((string) $request->query('date', ''));
        $selectedFromDate = trim((string) $request->query('from_date', $legacyDate));
        $selectedToDate = trim((string) $request->query('to_date', $legacyDate));
        $selectedItem = trim((string) $request->query('item', ''));
        $search = trim((string) $request->query('search', ''));
        $depthColumn = $this->dailyDipColumn(['enter_depth', 'depth', 'dip', 'dip_depth']) ?: 'enter_depth';
        $literColumn = $this->dailyDipColumn(['liter', 'litre', 'ltr']) ?: 'liter';

        if ($selectedItem === '') {
            return null;
        }

        if ($selectedFromDate !== '' && ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedFromDate)) {
            $selectedFromDate = '';
        }

        if ($selectedToDate !== '' && ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedToDate)) {
            $selectedToDate = '';
        }

        $query = DailyDip::query()
            ->where('item', $selectedItem)
            ->orderByDesc('date')
            ->orderBy('item');

        if ($selectedFromDate !== '') {
            $query->whereDate('date', '>=', $selectedFromDate);
        }

        if ($selectedToDate !== '') {
            $query->whereDate('date', '<=', $selectedToDate);
        }

        if ($search !== '') {
            $query->where(function ($query) use ($search, $depthColumn, $literColumn) {
                $query->where($depthColumn, 'like', "%{$search}%")
                    ->orWhere($literColumn, 'like', "%{$search}%");
            });
        }

        $entries = $query->get();

        if ($entries->isEmpty()) {
            return null;
        }

        return [
            'entries' => $entries,
            'periodLabel' => $this->dipChartPeriodLabel($selectedFromDate, $selectedToDate),
            'selectedItem' => $selectedItem,
            'search' => $search,
            'depthColumn' => $depthColumn,
            'literColumn' => $literColumn,
            'theme' => $this->exportTheme($request),
        ];
    }

    private function dipChartPeriodLabel(string $fromDate, string $toDate): string
    {
        $fromLabel = $fromDate !== '' ? date('d M Y', strtotime($fromDate)) : null;
        $toLabel = $toDate !== '' ? date('d M Y', strtotime($toDate)) : null;

        if ($fromLabel && $toLabel) {
            return $fromLabel . ' to ' . $toLabel;
        }

        if ($fromLabel) {
            return 'From ' . $fromLabel;
        }

        if ($toLabel) {
            return 'Up to ' . $toLabel;
        }

        return 'All Dates';
    }

    private function dayFuelNozzleColumn(): string
    {
        return Schema::hasColumn('day_fuel', 'Nozzel_id') ? 'Nozzel_id' : 'nozzle_id';
    }

    private function dayFuelNozzleId($dayFuel): ?int
    {
        return $dayFuel->Nozzel_id ?? $dayFuel->nozzle_id ?? null;
    }

    private function isCompleteDayFuelEntry($dayFuel): bool
    {
        return (float) $dayFuel->close > 0
            && trim((string) $dayFuel->items) !== ''
            && (float) $dayFuel->rate > 0;
    }

    private function syncNozzleColumns(): void
    {
        if (! Schema::hasColumn('day_fuel', 'Nozzel_id') || ! Schema::hasColumn('day_fuel', 'nozzle_id')) {
            return;
        }

        DB::table('day_fuel')
            ->whereNull('Nozzel_id')
            ->whereNotNull('nozzle_id')
            ->update(['Nozzel_id' => DB::raw('nozzle_id')]);

        DB::table('day_fuel')
            ->whereNull('nozzle_id')
            ->whereNotNull('Nozzel_id')
            ->update(['nozzle_id' => DB::raw('Nozzel_id')]);
    }

    private function carryForwardRow(Nozzle $nozzle, ?DayFuel $previousEntry, $latestRates = null): object
    {
        $item = $previousEntry ? $previousEntry->items : $nozzle->Item;
        $rate = $latestRates ? $this->rateFromLookup($latestRates, $item) : null;

        return (object) [
            'nozzle_id' => $nozzle->id,
            'Nozzel_id' => $nozzle->id,
            'Nozzle' => $nozzle,
            'open' => $previousEntry ? $previousEntry->close : 0,
            'close' => null,
            'Test' => null,
            'Quantity' => null,
            'items' => $item,
            'rate' => $rate,
            'Amount' => null,
        ];
    }

    private function effectiveRateForItem(?string $item, string $date): float
    {
        $latestRates = ItemDateRate::effectiveRatesByProductName()
            ->merge(ItemDateRate::effectiveRatesByProductName($date));

        return (float) ($this->rateFromLookup($latestRates, $item) ?? 0);
    }

    private function rateFromLookup($latestRates, ?string $item): ?string
    {
        $item = trim((string) $item);

        if ($item === '' || ! $latestRates) {
            return null;
        }

        if ($latestRates->has($item)) {
            return $latestRates->get($item);
        }

        $normalizedItem = $this->normalizeItemName($item);

        foreach ($latestRates as $name => $rate) {
            if ($this->normalizeItemName($name) === $normalizedItem) {
                return $rate;
            }
        }

        return null;
    }

    private function normalizeItemName(?string $item): string
    {
        return strtolower(preg_replace('/\s+/', ' ', trim((string) $item)));
    }
}
