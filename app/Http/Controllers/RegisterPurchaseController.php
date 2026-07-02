<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\CompanyInformation;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseExport;
use App\Exports\PurchaseItemListExport;
use Mpdf\Mpdf;

class RegisterPurchaseController extends Controller
{
    public function filterbydate(Request $request)
    {
        [
            $query,
            $search,
            $sort,
            $direction,
            $perPage,
            $perPageOptions
        ] = $this->registerQuery($request);

        $entries = $this->paginateGroupedEntries(
            $this->groupPurchaseEntries($query->get(), $sort, $direction),
            $perPage,
            $request
        );
        $companyInformation = CompanyInformation::query()->latest('id')->first();

        return view(
            'RegisterPurchaseFilter',
            compact(
                'entries',
                'search',
                'sort',
                'direction',
                'perPage',
                'perPageOptions',
                'companyInformation'
            )
        );
    }

    public function itemList(Request $request)
    {
        [$search, $sort, $direction, $perPage, $perPageOptions, $sortableColumns] = $this->purchaseItemListOptions($request);

        $purchaseItems = $this->filteredPurchaseItemQuery($request)
            ->orderBy($sortableColumns[$sort], $direction)
            ->orderBy('purchase_items.id', $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view(
            'purchase_item_list',
            compact(
                'purchaseItems',
                'search',
                'sort',
                'direction',
                'perPage',
                'perPageOptions'
            )
        );
    }

    public function itemListPdf(Request $request)
    {
        [$search, $sort, $direction, $perPage, $perPageOptions, $sortableColumns] = $this->purchaseItemListOptions($request);
        $purchaseItems = $this->filteredPurchaseItemQuery($request)
            ->orderBy($sortableColumns[$sort], $direction)
            ->orderBy('purchase_items.id', $direction)
            ->get();

        if ($purchaseItems->isEmpty()) {
            return redirect()
                ->route('purchase-item-list.index', $request->query())
                ->with('error', 'No purchase item records available to export.');
        }

        $theme = $this->exportTheme($request);
        $periodLabel = $this->periodLabel($request);

        $html = view('purchase_item_list_pdf', compact('purchaseItems', 'theme', 'periodLabel', 'search'))->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('PurchaseItemList.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="PurchaseItemList.pdf"');
    }

    public function itemListExcel(Request $request)
    {
        [$search, $sort, $direction, $perPage, $perPageOptions, $sortableColumns] = $this->purchaseItemListOptions($request);
        $purchaseItems = $this->filteredPurchaseItemQuery($request)
            ->orderBy($sortableColumns[$sort], $direction)
            ->orderBy('purchase_items.id', $direction)
            ->get();

        if ($purchaseItems->isEmpty()) {
            return redirect()
                ->route('purchase-item-list.index', $request->query())
                ->with('error', 'No purchase item records available to export.');
        }

        return Excel::download(
            new PurchaseItemListExport(
                $purchaseItems,
                $this->periodLabel($request),
                $search,
                $this->exportTheme($request)
            ),
            'PurchaseItemList.xlsx'
        );
    }

    public function pdf(Request $request)
    {
        [
            $query,
            $search,
            $sort,
            $direction
        ] = $this->registerQuery($request);

        $entries = $this->groupPurchaseEntries($query->get(), $sort, $direction);

        if ($entries->isEmpty()) {
            return redirect()
                ->route('RegisterPurchaseFilter', $request->query())
                ->with(
                    'error',
                    'No purchase register entries available to export.'
                );
        }

        $theme = $this->exportTheme($request);
        $periodLabel = $this->periodLabel($request);

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Purchase Register');
        }

        $html = view(
            'Purchase_Register_Pdf',
            compact(
                'entries',
                'theme',
                'periodLabel',
                'search'
            )
        )->render();

        $mpdf = new Mpdf([
            'orientation' => 'L'
        ]);

        $mpdf->WriteHTML($html);

        return response(
            $mpdf->Output(
                'PurchaseRegister.pdf',
                'S'
            )
        )
            ->header('Content-Type', 'application/pdf')
            ->header(
                'Content-Disposition',
                'inline; filename="PurchaseRegister.pdf"'
            );
    }

    public function excel(Request $request)
    {
        [$query, $search, $sort, $direction] = $this->registerQuery($request);

        $entries = $this->groupPurchaseEntries($query->get(), $sort, $direction);

        if ($entries->isEmpty()) {
            return redirect()
                ->route('RegisterPurchaseFilter', $request->query())
                ->with(
                    'error',
                    'No purchase register entries available to export.'
                );
        }

        return Excel::download(
            new PurchaseExport(
                $this->decoratePurchasesForExport($entries),
                $this->periodLabel($request),
                $this->exportTheme($request),
                CompanyInformation::query()->latest('id')->first()
            ),
            'PurchaseRegister.xlsx'
        );
    }

    public function referencePdf(Request $request, string $refNo)
    {
        $entries = $this->decoratePurchasesForExport(
            $this->filteredPurchaseQuery($request)
                ->with('items')
                ->where('Ref_no', $refNo)
                ->orderBy('id')
                ->get()
        );

        if ($entries->isEmpty()) {
            return redirect()
                ->route('RegisterPurchaseFilter', $request->query())
                ->with('error', 'No purchase register entries available for Ref ' . $refNo . '.');
        }

        $theme = $this->exportTheme($request);
        $selectedDate = 'Ref ' . $refNo;
        $purchases = $entries;

        $companyInformation = CompanyInformation::query()->latest('id')->first();
        $html = view('purchase_pdf', compact('purchases', 'selectedDate', 'theme', 'companyInformation'))->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('PurchaseRegister-Ref-' . $refNo . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="PurchaseRegister-Ref-' . $refNo . '.pdf"');
    }

    public function referenceExcel(Request $request, string $refNo)
    {
        $entries = $this->decoratePurchasesForExport(
            $this->filteredPurchaseQuery($request)
                ->with('items')
                ->where('Ref_no', $refNo)
                ->orderBy('id')
                ->get()
        );

        if ($entries->isEmpty()) {
            return redirect()
                ->route('RegisterPurchaseFilter', $request->query())
                ->with('error', 'No purchase register entries available for Ref ' . $refNo . '.');
        }

        return Excel::download(
            new PurchaseExport($entries, 'Ref ' . $refNo, $this->exportTheme($request), CompanyInformation::query()->latest('id')->first()),
            'PurchaseRegister-Ref-' . $refNo . '.xlsx'
        );
    }

    private function registerQuery(Request $request): array
    {
        $perPageOptions = [10, 25, 50, 100, 200, 500];

        $perPage = (int) $request->query('per_page', 10);

        $perPage = in_array(
            $perPage,
            $perPageOptions,
            true
        ) ? $perPage : 10;

        $search = trim(
            (string) $request->query('search', '')
        );

        $sort = $request->query('sort', 'date');

        $direction = $request->query('direction') === 'asc'
            ? 'asc'
            : 'desc';

        $sortableColumns = [
            'sr_no',
            'date',
            'ref_no',
            'invoice_no',
            'party',
            'vehicle_no',
            'driver',
            'transporter',
            'quantity',
            'rate',
            'amount',
            'total_amount',
            'total_cgst_amount',
            'total_sgst_amount',
            'total_igst_amount',
        ];

        if (! in_array($sort, $sortableColumns, true)) {
            $sort = 'date';
        }

        $query = $this->filteredPurchaseQuery($request)->with('items');

        return [
            $query,
            $search,
            $sort,
            $direction,
            $perPage,
            $perPageOptions,
        ];
    }

    private function filteredPurchaseQuery(Request $request)
    {
        $search = trim(
            (string) $request->query('search', '')
        );

        $query = Purchase::query();

        if ($request->filled('from_date')) {
            $query->whereDate(
                'date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $query->whereDate(
                'date',
                '<=',
                $request->to_date
            );
        }

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                    ->orWhere('Ref_no', 'like', "%{$search}%")
                    ->orWhere('invoice_no', 'like', "%{$search}%")
                    ->orWhere('perticulars', 'like', "%{$search}%")
                    ->orWhere('vehicle_no', 'like', "%{$search}%")
                    ->orWhere('driver', 'like', "%{$search}%")
                    ->orWhere('transporter', 'like', "%{$search}%")
                    ->orWhere('item_name', 'like', "%{$search}%")
                    ->orWhere('quantity', 'like', "%{$search}%")
                    ->orWhere('rate', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    private function filteredPurchaseItemQuery(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $query = PurchaseItem::query()
            ->with('purchase')
            ->leftJoin('purchase', 'purchase.id', '=', 'purchase_items.purchase_id')
            ->select('purchase_items.*');

        if ($request->filled('from_date')) {
            $query->whereDate('purchase_items.date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('purchase_items.date', '<=', $request->to_date);
        }

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('purchase_items.id', 'like', "%{$search}%")
                    ->orWhere('purchase_items.Ref_no', 'like', "%{$search}%")
                    ->orWhere('purchase.invoice_no', 'like', "%{$search}%")
                    ->orWhere('purchase.perticulars', 'like', "%{$search}%")
                    ->orWhere('purchase.vehicle_no', 'like', "%{$search}%")
                    ->orWhere('purchase.driver', 'like', "%{$search}%")
                    ->orWhere('purchase.transporter', 'like', "%{$search}%")
                    ->orWhere('purchase_items.item_name', 'like', "%{$search}%")
                    ->orWhere('purchase_items.quantity', 'like', "%{$search}%")
                    ->orWhere('purchase_items.rate', 'like', "%{$search}%")
                    ->orWhere('purchase_items.amount', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    private function purchaseItemListOptions(Request $request): array
    {
        $perPageOptions = [10, 25, 50, 100, 200, 500];
        $perPage = (int) $request->query('per_page', 25);
        $perPage = in_array($perPage, $perPageOptions, true) ? $perPage : 25;
        $search = trim((string) $request->query('search', ''));
        $sort = $request->query('sort', 'item');
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';
        $sortableColumns = [
            'item' => 'purchase_items.item_name',
            'particulars' => 'purchase_items.item_name',
            'quantity' => 'purchase_items.quantity',
            'rate' => 'purchase_items.rate',
            'amount' => 'purchase_items.amount',
            'discount_percent' => 'purchase_items.discount%',
            'discount' => 'purchase_items.discountinrs',
            'taxable_amount' => 'purchase_items.taxable_amount',
            'total_amount' => 'purchase_items.total_amount',
            'cgst' => 'purchase_items.cgst',
            'sgst' => 'purchase_items.sgst',
            'igst' => 'purchase_items.igst',
            'total_tax' => 'purchase_items.total_tax_amount',
        ];

        if (! array_key_exists($sort, $sortableColumns)) {
            $sort = 'item';
        }

        return [$search, $sort, $direction, $perPage, $perPageOptions, $sortableColumns];
    }

    private function groupPurchaseEntries(Collection $purchases, string $sort, string $direction): Collection
    {
        $entries = $purchases
            ->groupBy(fn (Purchase $purchase) => (string) $purchase->Ref_no)
            ->map(function (Collection $items) {
                $entry = clone $items->first();
                $detailRows = $items->flatMap(function (Purchase $purchase) {
                    if ($purchase->relationLoaded('items') && $purchase->items->isNotEmpty()) {
                        return $purchase->items->map(fn (PurchaseItem $item) => $this->decoratePurchaseItemForDisplay($item, $purchase));
                    }

                    return [$purchase];
                })->values();
                $uniqueItems = $detailRows->pluck('item_name')->filter()->unique()->values();

                $entry->id = $items->min('id');
                $entry->purchase_items = $detailRows;
                $entry->item_count = $detailRows->count();
                $entry->item_name = $uniqueItems->join(', ');
                $entry->quantity = $detailRows->sum(fn ($item) => (float) $item->quantity);
                $entry->amount = $items->sum(fn ($item) => (float) $item->amount);
                $entry->rate = $detailRows->sum(fn ($item) => (float) $item->rate);
                $entry->discountinrs = $items->sum(fn ($item) => (float) $item->discountinrs);
                $entry->taxable_amount = $items->sum(fn ($item) => (float) $item->taxable_amount);
                $entry->total_tax_amount = $items->sum(fn ($item) => (float) $item->total_tax_amount);
                $entry->total_amount = $items->sum(fn ($item) => (float) $item->total_amount);
                $entry->total_cgst_amount = $items->sum(fn ($item) => (float) ($item->total_cgst_amount ?? 0));
                $entry->total_sgst_amount = $items->sum(fn ($item) => (float) ($item->total_sgst_amount ?? 0));
                $entry->total_igst_amount = $items->sum(fn ($item) => (float) ($item->total_igst_amount ?? 0));

                if ((float) $entry->total_cgst_amount === 0.0 && (float) $entry->total_sgst_amount === 0.0 && (float) $entry->total_igst_amount === 0.0) {
                    $entry->total_cgst_amount = $detailRows->sum(fn ($item) => ((float) $item->taxable_amount * (float) $item->cgst) / 100);
                    $entry->total_sgst_amount = $detailRows->sum(fn ($item) => ((float) $item->taxable_amount * (float) $item->sgst) / 100);
                    $entry->total_igst_amount = $detailRows->sum(fn ($item) => ((float) $item->taxable_amount * (float) $item->igst) / 100);
                }

                return $entry;
            })
            ->values();

        $sortAccessors = [
            'sr_no' => fn ($entry) => (int) $entry->id,
            'date' => fn ($entry) => (string) $entry->date,
            'ref_no' => fn ($entry) => (int) preg_replace('/\D+/', '', (string) $entry->Ref_no),
            'invoice_no' => fn ($entry) => (string) $entry->invoice_no,
            'party' => fn ($entry) => (string) $entry->perticulars,
            'vehicle_no' => fn ($entry) => (string) $entry->vehicle_no,
            'driver' => fn ($entry) => (string) $entry->driver,
            'transporter' => fn ($entry) => (string) $entry->transporter,
            'quantity' => fn ($entry) => (float) $entry->quantity,
            'rate' => fn ($entry) => (float) ($entry->rate ?? 0),
            'amount' => fn ($entry) => (float) $entry->amount,
            'total_amount' => fn ($entry) => (float) $entry->total_amount,
            'total_cgst_amount' => fn ($entry) => (float) ($entry->total_cgst_amount ?? 0),
            'total_sgst_amount' => fn ($entry) => (float) ($entry->total_sgst_amount ?? 0),
            'total_igst_amount' => fn ($entry) => (float) ($entry->total_igst_amount ?? 0),
        ];

        $accessor = $sortAccessors[$sort] ?? $sortAccessors['date'];

        return ($direction === 'asc' ? $entries->sortBy($accessor) : $entries->sortByDesc($accessor))
            ->values();
    }

    private function paginateGroupedEntries(Collection $entries, int $perPage, Request $request): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();

        return new LengthAwarePaginator(
            $entries->forPage($page, $perPage)->values(),
            $entries->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }

    private function decoratePurchasesForExport(Collection $entries): Collection
    {
        return $entries->flatMap(function ($purchase) {
            if ($purchase instanceof Purchase && $purchase->relationLoaded('items') && $purchase->items->isNotEmpty()) {
                return $purchase->items->map(fn (PurchaseItem $item) => $this->decoratePurchaseItemForDisplay($item, $purchase));
            }

            $purchase->export_postal_address = $purchase->{'postal address'} ?? '';
            $purchase->export_location = $purchase->location ?? '';

            return [$purchase];
        })->values();
    }

    private function decoratePurchaseItemForDisplay(PurchaseItem $item, Purchase $purchase): PurchaseItem
    {
        $displayItem = clone $item;

        foreach ([
            'perticulars',
            'Ref_no',
            'interstate',
            'location',
            'date',
            'invoice_no',
            'vehicle_no',
            'transporter',
            'driver',
        ] as $field) {
            $displayItem->setAttribute($field, $purchase->{$field} ?? $item->{$field} ?? null);
        }

        $displayItem->setAttribute('postal address', $purchase->{'postal address'} ?? '');
        $displayItem->setAttribute('export_postal_address', $purchase->{'postal address'} ?? '');
        $displayItem->setAttribute('export_location', $purchase->location ?? '');

        return $displayItem;
    }

    private function periodLabel(Request $request): string
    {
        $fromDate = $request->filled('from_date')
            ? date('d M Y', strtotime($request->from_date))
            : null;

        $toDate = $request->filled('to_date')
            ? date('d M Y', strtotime($request->to_date))
            : null;

        if ($fromDate && $toDate) {
            return $fromDate . ' to ' . $toDate;
        }

        if ($fromDate) {
            return 'From ' . $fromDate;
        }

        if ($toDate) {
            return 'Up to ' . $toDate;
        }

        return 'All Dates';
    }

    private function exportTheme(Request $request): array
    {
        $themes = [
            'default' => [
                'primary' => '#0f766e',
                'primaryDark' => '#115e59',
                'accent' => '#f59e0b',
                'bgEnd' => '#eef5f3',
            ],
            'ocean' => [
                'primary' => '#0369a1',
                'primaryDark' => '#075985',
                'accent' => '#14b8a6',
                'bgEnd' => '#edf7fb',
            ],
            'royal' => [
                'primary' => '#4338ca',
                'primaryDark' => '#3730a3',
                'accent' => '#f59e0b',
                'bgEnd' => '#f1f2ff',
            ],
            'rose' => [
                'primary' => '#be123c',
                'primaryDark' => '#9f1239',
                'accent' => '#0f766e',
                'bgEnd' => '#fff1f4',
            ],
            'charcoal' => [
                'primary' => '#334155',
                'primaryDark' => '#1e293b',
                'accent' => '#d97706',
                'bgEnd' => '#eef2f7',
            ],
            'sunset-sky' => [
                'primary' => '#ea580c',
                'primaryDark' => '#c2410c',
                'accent' => '#be123c',
                'bgEnd' => '#ffe4d6',
            ],
            'royal-print' => [
                'primary' => '#4c1d95',
                'primaryDark' => '#3b0764',
                'accent' => '#f59e0b',
                'bgEnd' => '#f5f0ff',
            ],
            'peacock-print' => [
                'primary' => '#0f766e',
                'primaryDark' => '#134e4a',
                'accent' => '#0891b2',
                'bgEnd' => '#ecfeff',
            ],
            'marigold-print' => [
                'primary' => '#b45309',
                'primaryDark' => '#92400e',
                'accent' => '#be123c',
                'bgEnd' => '#fff7ed',
            ],
            'velvet-print' => [
                'primary' => '#9d174d',
                'primaryDark' => '#831843',
                'accent' => '#7c3aed',
                'bgEnd' => '#fdf2f8',
            ],
        ];

        return $themes[$request->query('theme', 'default')]
            ?? $themes['default'];
    }
}
