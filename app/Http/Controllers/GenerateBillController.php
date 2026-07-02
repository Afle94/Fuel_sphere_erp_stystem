<?php

namespace App\Http\Controllers;

use App\Exports\BillListExport;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\CompanyInformation;
use App\Models\CreditSales;
use App\Models\ItemDateRate;
use App\Models\Ledgers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class GenerateBillController extends Controller
{
    public function index()
    {
        $nextBillNo = (string) ((int) (Bill::query()->max('bill_no') ?? 0) + 1);
        $creditSaleParties = CreditSales::query()
            ->whereNotNull('Party_name')
            ->where('Party_name', '<>', '')
            ->select('Party_name')
            ->distinct()
            ->orderBy('Party_name')
            ->pluck('Party_name')
            ->merge(
                Bill::query()
                    ->whereNotNull('party')
                    ->where('party', '<>', '')
                    ->distinct()
                    ->orderBy('party')
                    ->pluck('party')
            )
            ->unique()
            ->sort()
            ->values();
        $billedVehiclesByParty = Bill::query()
            ->whereNotNull('party')
            ->where('party', '<>', '')
            ->with('items:id,bill_id,vehicle_no')
            ->get(['id', 'party'])
            ->groupBy('party')
            ->map(fn ($bills) => $bills
                ->flatMap(fn ($bill) => $bill->items->pluck('vehicle_no'))
                ->filter()
                ->unique()
                ->values());
        $creditSaleVehiclesByParty = CreditSales::query()
            ->whereNotNull('Party_name')
            ->where('Party_name', '<>', '')
            ->orderBy('vehicle_no')
            ->get(['Party_name', 'vehicle_no'])
            ->groupBy('Party_name')
            ->map(fn ($sales) => $sales->pluck('vehicle_no')->filter()->unique()->values());
        $billedVehiclesByParty->each(function ($vehicles, $party) use (&$creditSaleVehiclesByParty) {
            $creditSaleVehiclesByParty->put(
                $party,
                collect($creditSaleVehiclesByParty->get($party, collect()))
                    ->merge($vehicles)
                    ->filter()
                    ->unique()
                    ->sort()
                    ->values()
            );
        });
        $billedSlipNumbers = BillItem::query()
            ->whereNotNull('slip_no')
            ->where('slip_no', '<>', '')
            ->pluck('slip_no');
        $unbilledCreditSalesByPartyVehicle = CreditSales::query()
            ->whereNotNull('Party_name')
            ->where('Party_name', '<>', '')
            ->where(fn ($query) => $query->whereNull('bill_no')->orWhere('bill_no', ''))
            ->when($billedSlipNumbers->isNotEmpty(), fn ($query) => $query->where(fn ($query) => $query
                ->whereNull('slip_no')
                ->orWhereNotIn('slip_no', $billedSlipNumbers)))
            ->orderBy('date')
            ->orderBy('slip_no')
            ->get()
            ->groupBy('Party_name')
            ->map(fn ($partySales) => $partySales
                ->groupBy(fn ($sale) => $sale->vehicle_no ?: '')
                ->map(fn ($vehicleSales) => $vehicleSales->map(function ($sale) {
                    $billDate = substr((string) $sale->date, 0, 10);

                    return [
                        'bill_date' => $billDate,
                        'vehicle_no' => $sale->vehicle_no,
                        'slip_no' => $sale->slip_no,
                        'item_name' => $sale->item_name,
                        'hsn_code' => '',
                        'qty' => $sale->quantity,
                        'rate' => $this->latestItemRate($sale->item_name, $billDate, $sale->rate),
                    ];
                })->values())
            );
        $billedCreditSales = CreditSales::query()
            ->whereNotNull('Party_name')
            ->where('Party_name', '<>', '')
            ->whereNotNull('bill_no')
            ->where('bill_no', '<>', '')
            ->orderBy('date')
            ->orderBy('slip_no')
            ->get(['Party_name', 'vehicle_no', 'date', 'slip_no', 'bill_no'])
            ->map(fn ($sale) => [
                'party' => $sale->Party_name,
                'bill_date' => substr((string) $sale->date, 0, 10),
                'vehicle_no' => $sale->vehicle_no,
                'slip_no' => $sale->slip_no,
                'bill_no' => $sale->bill_no,
            ]);
        $billedBillItems = BillItem::query()
            ->with('bill:id,bill_no,party')
            ->orderBy('bill_date')
            ->orderBy('slip_no')
            ->get(['id', 'bill_id', 'bill_date', 'vehicle_no', 'slip_no'])
            ->map(fn (BillItem $item) => [
                'party' => $item->bill?->party,
                'bill_date' => substr((string) $item->bill_date, 0, 10),
                'vehicle_no' => $item->vehicle_no,
                'slip_no' => $item->slip_no,
                'bill_no' => $item->bill?->bill_no,
            ]);
        $billedCreditSalesByPartyVehicle = $billedCreditSales
            ->concat($billedBillItems)
            ->filter(fn ($sale) => filled($sale['party']) && filled($sale['bill_no']))
            ->unique(fn ($sale) => $sale['party'] . '|' . $sale['vehicle_no'] . '|' . $sale['slip_no'] . '|' . $sale['bill_no'])
            ->groupBy('party')
            ->map(fn ($partySales) => $partySales
                ->groupBy(fn ($sale) => $sale['vehicle_no'] ?: '')
                ->map(fn ($vehicleSales) => $vehicleSales
                    ->map(fn ($sale) => [
                        'bill_date' => $sale['bill_date'],
                        'vehicle_no' => $sale['vehicle_no'],
                        'slip_no' => $sale['slip_no'],
                        'bill_no' => $sale['bill_no'],
                    ])
                    ->values()
                )
            );
        $productCategories = Product::query()
            ->pluck('Category', 'Product_Name');

        return view('generate_bill', compact('nextBillNo', 'creditSaleParties', 'creditSaleVehiclesByParty', 'unbilledCreditSalesByPartyVehicle', 'billedCreditSalesByPartyVehicle', 'productCategories'));
    }

    public function list(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);

        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $bills = $this->billListQuery($search)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('generate_bill_list', compact('bills', 'search', 'perPage', 'perPageOptions'));
    }

    public function listPdf(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $bills = $this->billListQuery($search)
            ->with('items:id,bill_id,vehicle_no,amount')
            ->latest()
            ->get();

        if ($bills->isEmpty()) {
            return redirect()->route('generate-bill.list', $request->query())->with('error', 'No bills available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Saved Bills List');
        }

        $theme = $this->exportTheme($request);
        $html = view('generate_bill_list_pdf', compact('bills', 'theme', 'search'))->render();

        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('Saved Bills List.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function listExcel(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        if (! $this->billListQuery($search)->exists()) {
            return redirect()->route('generate-bill.list', $request->query())->with('error', 'No bills available to export.');
        }

        return Excel::download(new BillListExport($search, $this->exportTheme($request)), 'SavedBills.xlsx');
    }

    public function show(Bill $bill)
    {
        return view('generate_bill_preview', $this->invoiceData($bill));
    }

    public function pdf(Bill $bill)
    {
        $html = view('generate_bill_pdf', array_merge($this->invoiceData($bill), [
            'isPdf' => true,
        ]))->render();

        $mpdf = new Mpdf([
            'format' => 'A4-L',
            'margin_left' => 8,
            'margin_right' => 8,
            'margin_top' => 8,
            'margin_bottom' => 8,
        ]);
        $mpdf->shrink_tables_to_fit = 1;
        $mpdf->keep_table_proportions = true;
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('Bill-' . ($bill->bill_no ?: $bill->id) . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Bill-' . ($bill->bill_no ?: $bill->id) . '.pdf"')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date', 'after_or_equal:date_from'],
            'party' => ['required', 'string', 'max:255'],
            'vehicle_no' => ['nullable', 'string', 'max:255'],
        ]);

        $creditSales = $this->unbilledCreditSalesForBill($validated);

        if ($creditSales->isEmpty()) {
            $alreadyBilled = $this->alreadyBilledNumbers($validated);

            if ($alreadyBilled->isNotEmpty()) {
                return response()->json([
                    'status' => 'billed',
                    'bill_numbers' => $alreadyBilled->values(),
                ]);
            }

            return response()->json([
                'status' => 'empty',
                'rows' => [],
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'rows' => $creditSales->map(function (CreditSales $sale) {
                $billDate = substr((string) $sale->date, 0, 10);

                return [
                    'bill_date' => $billDate,
                    'vehicle_no' => $sale->vehicle_no,
                    'slip_no' => $sale->slip_no,
                    'item_name' => $sale->item_name,
                    'hsn_code' => '',
                    'qty' => $sale->quantity,
                    'rate' => $this->latestItemRate($sale->item_name, $billDate, $sale->rate),
                ];
            })->values(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bill_no' => ['nullable', 'string', 'max:50'],
            'bill_date' => ['required', 'date'],
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date', 'after_or_equal:date_from'],
            'party' => ['required', 'string', 'max:255'],
            'vehicle_no' => ['nullable', 'string', 'max:255'],
            'items' => ['nullable', 'array'],
            'items.*.bill_date' => ['nullable', 'date'],
            'items.*.vehicle_no' => ['nullable', 'string', 'max:255'],
            'items.*.slip_no' => ['nullable', 'string', 'max:80'],
            'items.*.item_name' => ['nullable', 'string', 'max:255'],
            'items.*.hsn_code' => ['nullable', 'string', 'max:80'],
            'items.*.qty' => ['nullable', 'numeric', 'min:0'],
            'items.*.rate' => ['nullable', 'numeric', 'min:0'],
        ]);

        $creditSales = $this->unbilledCreditSalesForBill($validated);

        if ($creditSales->isEmpty()) {
            $alreadyBilled = $this->alreadyBilledNumbers($validated);

            if ($alreadyBilled->isNotEmpty()) {
                return back()
                    ->withInput()
                    ->withErrors(['party' => 'Bill already generated. Bill No: ' . $alreadyBilled->implode(', ')]);
            }

            return back()
                ->withInput()
                ->withErrors(['party' => 'No unbilled credit sales found for this party, vehicle, and date range.']);
        }

        $items = $creditSales
            ->map(function (CreditSales $sale) {
                $qty = (float) ($sale->quantity ?? 0);
                $billDate = substr((string) $sale->date, 0, 10);
                $rate = (float) $this->latestItemRate($sale->item_name, $billDate, $sale->rate);

                return [
                    'bill_date' => $billDate,
                    'vehicle_no' => $sale->vehicle_no,
                    'slip_no' => $sale->slip_no,
                    'item_name' => $sale->item_name,
                    'hsn_code' => '',
                    'qty' => $qty,
                    'rate' => $rate,
                    'amount' => round($qty * $rate, 2),
                ];
            })
            ->values();

        $bill = DB::transaction(function () use ($validated, $items, $creditSales) {
            $billNo = filled($validated['bill_no'] ?? null)
                ? $validated['bill_no']
                : (string) ((int) (Bill::query()->max('bill_no') ?? 0) + 1);

            $bill = Bill::create([
                'bill_no' => $billNo,
                'bill_date' => $validated['bill_date'],
                'date_from' => $validated['date_from'] ?? null,
                'date_to' => $validated['date_to'] ?? null,
                'party' => $validated['party'] ?? null,
                'vehicle_no' => $validated['vehicle_no'] ?? null,
                'total_slips' => $items->count(),
                'total_amount' => $items->sum('amount'),
            ]);

            $bill->items()->createMany($items->all());
            CreditSales::query()
                ->whereIn('id', $creditSales->pluck('id'))
                ->update(['bill_no' => $bill->bill_no]);

            $billDate = optional($bill->bill_date)->toDateString() ?: $validated['bill_date'];
            $billAmount = $bill->total_amount;

            Ledgers::query()
                ->where('VOUCHERNO', $bill->bill_no)
                ->where('VTYPE', 'BILL')
                ->delete();

            Ledgers::create([
                'VOUCHERNO' => $bill->bill_no,
                'VTYPE' => 'BILL',
                'TRANDATE' => $billDate,
                'TRANTYPE' => 'D',
                'ACNO' => $bill->party,
                'AMOUNT' => $billAmount,
            ]);

            Ledgers::create([
                'VOUCHERNO' => $bill->bill_no,
                'VTYPE' => 'BILL',
                'TRANDATE' => $billDate,
                'TRANTYPE' => 'C',
                'ACNO' => 'BILL',
                'AMOUNT' => $billAmount,
            ]);

            return $bill;
        });

        return redirect()
            ->route('generate-bill.show', $bill)
            ->with('success', 'Bill saved successfully.');
    }

    private function alreadyBilledNumbers(array $validated)
    {
        $alreadyBilled = CreditSales::query()
            ->whereDate('date', '>=', $validated['date_from'])
            ->whereDate('date', '<=', $validated['date_to'])
            ->where('Party_name', $validated['party'])
            ->whereNotNull('bill_no')
            ->where('bill_no', '<>', '')
            ->when(
                ! empty($validated['vehicle_no']),
                fn ($query) => $query->where('vehicle_no', $validated['vehicle_no'])
            )
            ->pluck('bill_no');

        $alreadyBilledFromItems = BillItem::query()
            ->with('bill:id,bill_no,party')
            ->whereDate('bill_date', '>=', $validated['date_from'])
            ->whereDate('bill_date', '<=', $validated['date_to'])
            ->whereHas('bill', fn ($query) => $query->where('party', $validated['party']))
            ->when(
                ! empty($validated['vehicle_no']),
                fn ($query) => $query->where('vehicle_no', $validated['vehicle_no'])
            )
            ->get()
            ->pluck('bill.bill_no');

        return $alreadyBilled
            ->merge($alreadyBilledFromItems)
            ->filter()
            ->unique()
            ->values();
    }

    private function unbilledCreditSalesForBill(array $validated)
    {
        $billedSlipNumbers = BillItem::query()
            ->whereNotNull('slip_no')
            ->where('slip_no', '<>', '')
            ->pluck('slip_no');

        return CreditSales::query()
            ->whereDate('date', '>=', $validated['date_from'])
            ->whereDate('date', '<=', $validated['date_to'])
            ->where('Party_name', $validated['party'])
            ->where(fn ($query) => $query->whereNull('bill_no')->orWhere('bill_no', ''))
            ->when(
                ! empty($validated['vehicle_no']),
                fn ($query) => $query->where('vehicle_no', $validated['vehicle_no'])
            )
            ->when(
                $billedSlipNumbers->isNotEmpty(),
                fn ($query) => $query->where(fn ($query) => $query
                    ->whereNull('slip_no')
                    ->orWhereNotIn('slip_no', $billedSlipNumbers))
            )
            ->orderBy('date')
            ->orderBy('slip_no')
            ->get();
    }

    private function latestItemRate(?string $itemName, ?string $date, mixed $fallback = 0): string
    {
        $itemName = trim((string) $itemName);

        if ($itemName === '') {
            return number_format((float) $fallback, 2, '.', '');
        }

        $rate = ItemDateRate::query()
            ->whereHas('product', fn ($query) => $query->where('Product_Name', $itemName))
            ->when($date, fn ($query) => $query->whereDate('rate_date', '<=', $date))
            ->orderByDesc('rate_date')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->value('rate');

        return number_format((float) ($rate ?? $fallback), 2, '.', '');
    }

    private function invoiceData(Bill $bill): array
    {
        $bill->load('items');

        $companyInformation = CompanyInformation::query()
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();

        $productCategories = Product::query()->pluck('Category', 'Product_Name');
        $itemTotals = $bill->items
            ->groupBy(fn ($item) => $productCategories[(string) $item->item_name] ?? ((string) $item->item_name ?: '-'))
            ->map(fn ($items, $categoryName) => [
                'name' => $categoryName ?: '-',
                'qty' => $items->sum(fn ($item) => (float) $item->qty),
                'amount' => $items->sum(fn ($item) => (float) $item->amount),
            ])
            ->values();
        $amount = (float) $bill->total_amount;

        return [
            'bill' => $bill,
            'companyInformation' => $companyInformation,
            'itemTotals' => $itemTotals,
            'amountInWords' => $this->amountInWords($amount),
            'isPdf' => false,
        ];
    }

    private function billListQuery(string $search)
    {
        return Bill::query()
            ->withCount('items')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('bill_no', 'like', "%{$search}%")
                        ->orWhere('party', 'like', "%{$search}%")
                        ->orWhere('vehicle_no', 'like', "%{$search}%")
                        ->orWhere('bill_date', 'like', "%{$search}%")
                        ->orWhereHas('items', function ($query) use ($search) {
                            $query->where('vehicle_no', 'like', "%{$search}%");
                        })
                        ->orWhere('date_from', 'like', "%{$search}%")
                        ->orWhere('date_to', 'like', "%{$search}%")
                        ->orWhere('total_amount', 'like', "%{$search}%");
                });
            });
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

    private function amountInWords(float $amount): string
    {
        $number = (int) round($amount);

        if ($number === 0) {
            return 'Zero Only';
        }

        $ones = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
            'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
            'Seventeen', 'Eighteen', 'Nineteen',
        ];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        $wordsBelowHundred = function (int $value) use ($ones, $tens): string {
            if ($value < 20) {
                return $ones[$value];
            }

            return trim($tens[intdiv($value, 10)] . ' ' . $ones[$value % 10]);
        };

        $parts = [];
        $crore = intdiv($number, 10000000);
        $number %= 10000000;
        $lakh = intdiv($number, 100000);
        $number %= 100000;
        $thousand = intdiv($number, 1000);
        $number %= 1000;
        $hundred = intdiv($number, 100);
        $number %= 100;

        if ($crore) {
            $parts[] = $wordsBelowHundred($crore) . ' Crore';
        }
        if ($lakh) {
            $parts[] = $wordsBelowHundred($lakh) . ' Lakh';
        }
        if ($thousand) {
            $parts[] = $wordsBelowHundred($thousand) . ' Thousand';
        }
        if ($hundred) {
            $parts[] = $ones[$hundred] . ' Hundred';
        }
        if ($number) {
            $parts[] = $wordsBelowHundred($number);
        }

        return trim(implode(' ', $parts)) . ' Only';
    }
}
