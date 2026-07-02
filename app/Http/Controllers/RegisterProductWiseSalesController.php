<?php

namespace App\Http\Controllers;

use App\Exports\ProductWiseSalesExport;
use App\Models\CashSales;
use App\Models\CreditSales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class RegisterProductWiseSalesController extends Controller
{
    public function filterbydate(Request $request)
    {
        [
            $items,
            $search,
            $totalSalesAmount,
            $perPage,
            $perPageOptions
        ] = $this->registerQuery($request, true);
        $hasDateRange = $request->filled('from_date') && $request->filled('to_date');

        return view(
            'RegisterProductWiseSales',
            compact('items', 'search', 'totalSalesAmount', 'perPage', 'perPageOptions', 'hasDateRange')
        );
    }

    public function pdf(Request $request)
    {
        [
            $items,
            $search,
            $totalSalesAmount
        ] = $this->registerQuery($request, false);

        if ($items->isEmpty()) {
            return redirect()
                ->route('RegisterProductWiseSales', $request->query())
                ->with('error', 'No product sales entries available to export.');
        }

        $theme = $this->exportTheme($request);
        $periodLabel = $this->periodLabel($request);

        $html = view(
            'Product_Wise_Sales_Register_pdf',
            compact('items', 'theme', 'periodLabel', 'search', 'totalSalesAmount')
        )->render();

        $mpdf = new Mpdf([
            'orientation' => 'L',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15
        ]);

        $mpdf->WriteHTML($html);

        return response($mpdf->Output('ProductWiseSalesRegister.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="ProductWiseSalesRegister.pdf"');
    }

    public function excel(Request $request)
    {
        [
            $items,
            $search,
            $totalSalesAmount
        ] = $this->registerQuery($request, false);

        if ($items->isEmpty()) {
            return redirect()
                ->route('RegisterProductWiseSales', $request->query())
                ->with('error', 'No product sales entries available to export.');
        }

        return Excel::download(
            new ProductWiseSalesExport(
                $items,
                $this->periodLabel($request),
                $this->exportTheme($request)
            ),
            'ProductWiseSalesRegister.xlsx'
        );
    }

    private function registerQuery(Request $request, bool $paginate = true): array
    {
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        $perPage = in_array($perPage, $perPageOptions, true) ? $perPage : 10;
        $search = trim((string) $request->query('search', ''));
        $hasDateRange = $request->filled('from_date') && $request->filled('to_date');

        if (!$hasDateRange) {
            $emptyItems = collect();

            if ($paginate) {
                $emptyItems = new \Illuminate\Pagination\LengthAwarePaginator(
                    collect(),
                    0,
                    $perPage,
                    \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage(),
                    ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
                );
                $emptyItems->withQueryString();
            }

            return [$emptyItems, $search, 0, $perPage, $perPageOptions];
        }

        $cashQuery = CashSales::query()
            ->select('item_name', DB::raw('SUM(quantity) as cash_qty'), DB::raw('SUM(amount) as cash_amt'))
            ->groupBy('item_name');

        $creditQuery = CreditSales::query()
            ->select('item_name', DB::raw('SUM(quantity) as credit_qty'), DB::raw('SUM(amount) as credit_amt'))
            ->groupBy('item_name');

        $cashNamesQuery = DB::table('cashsales')->select('item_name');
        $creditNamesQuery = DB::table('creditsales')->select('item_name');

        if ($request->filled('from_date')) {
            $cashQuery->whereDate('date', '>=', $request->from_date);
            $creditQuery->whereDate('date', '>=', $request->from_date);
            $cashNamesQuery->whereDate('date', '>=', $request->from_date);
            $creditNamesQuery->whereDate('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $cashQuery->whereDate('date', '<=', $request->to_date);
            $creditQuery->whereDate('date', '<=', $request->to_date);
            $cashNamesQuery->whereDate('date', '<=', $request->to_date);
            $creditNamesQuery->whereDate('date', '<=', $request->to_date);
        }

        $allItemNames = DB::table(function ($query) use ($cashNamesQuery, $creditNamesQuery) {
            $query->select('item_name')->from($cashNamesQuery->union($creditNamesQuery), 'scoped_union');
        }, 'unified_items')
        ->select('item_name')
        ->whereNotNull('item_name')
        ->where('item_name', '<>', '');

        if ($search !== '') {
            $allItemNames->where('item_name', 'like', "%{$search}%");
        }

        $baseItems = $allItemNames->get();
        $cashTotals = $cashQuery->get()->keyBy('item_name');
        $creditTotals = $creditQuery->get()->keyBy('item_name');

        $processedItems = $baseItems->map(function ($item) use ($cashTotals, $creditTotals) {
            $name = $item->item_name;
            $cashQty = $cashTotals->get($name)->cash_qty ?? 0;
            $cashAmt = $cashTotals->get($name)->cash_amt ?? 0;
            $creditQty = $creditTotals->get($name)->credit_qty ?? 0;
            $creditAmt = $creditTotals->get($name)->credit_amt ?? 0;

            return (object) [
                'item_name' => $name,
                'total_quantity' => $cashQty + $creditQty,
                'total_amount' => $cashAmt + $creditAmt,
            ];
        })
        ->filter(fn($item) => $item->total_amount > 0 || $item->total_quantity > 0)
        ->sortByDesc('total_amount')
        ->values();

        $totalSalesAmount = $processedItems->sum('total_amount');

        $runningCumulativeSum = 0;
        foreach ($processedItems as $item) {
            $contribution = $totalSalesAmount > 0 ? ($item->total_amount / $totalSalesAmount) * 100 : 0;
            $runningCumulativeSum += $contribution;
            $item->contribution_pct = $contribution;
            $item->cumulative_pct = min($runningCumulativeSum, 100);
        }

        if ($paginate) {
            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $currentItems = $processedItems->slice(($currentPage - 1) * $perPage, $perPage)->values();
            
            $items = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItems,
                $processedItems->count(),
                $perPage,
                $currentPage,
                ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
            );
            $items->withQueryString();
        } else {
            $items = $processedItems;
        }

        return [$items, $search, $totalSalesAmount, $perPage, $perPageOptions];
    }

    private function periodLabel(Request $request): string
    {
        $fromDate = $request->filled('from_date') ? date('d M Y', strtotime($request->from_date)) : null;
        $toDate = $request->filled('to_date') ? date('d M Y', strtotime($request->to_date)) : null;

        if ($fromDate && $toDate) return $fromDate . ' to ' . $toDate;
        if ($fromDate) return 'From ' . $fromDate;
        if ($toDate) return 'Up to ' . $toDate;
        return 'All Dates';
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
