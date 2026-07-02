<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exports\PurchaseExport;
use App\Exports\PurchaseSampleExport;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseSample;
use App\Models\AccountName;
use App\Models\CompanyInformation;
use App\Models\Density;
use App\Models\ItemDateRate;
use App\Models\Ledgers;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class PurchaseController extends Controller
{
    public function showpurchase(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $perticular = AccountName::whereraw('TRIM(under_group) = ?', ['SUNDRY CREDITORS'])
            ->orderBy('account_perticular')
            ->get(['account_perticular', 'address', 'city']);

        $item = Product::orderBy('Product_Name')->get('Product_Name')->pluck('Product_Name')
            ->unique()
            ->sort()
            ->values();
        $nextRefNo = $this->nextRefNo();
        $purchases = $this->purchasesForDate($selectedDate);
        $purchaseNavigationPurchases = $this->purchasesForNavigation();
        $purchaseSamples = $this->purchaseSamplesForDate($selectedDate);
        $purchaseSamplesByRef = PurchaseSample::query()
            ->whereNotNull('ref_no')
            ->latest('id')
            ->get()
            ->unique('ref_no')
            ->mapWithKeys(fn (PurchaseSample $sample) => [(string) $sample->ref_no => [
                'date' => optional($sample->date)->format('Y-m-d'),
                'tanker' => $sample->tanker,
                'transport' => $sample->transport,
                'oil_company' => $sample->oil_company,
                'invoice_no' => $sample->invoice_no,
                'product' => $sample->product,
                'hsd_temp' => $sample->hsd_temp,
                'hsd_base_density' => $sample->hsd_base_density,
                'hsd_value' => $sample->hsd_value,
                'hsd_sample' => $sample->hsd_sample,
                'hsd_invoice_sample' => $sample->hsd_invoice_sample,
                'hsd_plastic_seal' => $sample->hsd_plastic_seal,
                'hsd_aluminium_seal' => $sample->hsd_aluminium_seal,
                'ms_temp' => $sample->ms_temp,
                'ms_base_density' => $sample->ms_base_density,
                'ms_value' => $sample->ms_value,
                'ms_sample' => $sample->ms_sample,
                'ms_invoice_sample' => $sample->ms_invoice_sample,
                'ms_plastic_seal' => $sample->ms_plastic_seal,
                'ms_aluminium_seal' => $sample->ms_aluminium_seal,
                'power_ms_temp' => $sample->power_ms_temp,
                'power_ms_base_density' => $sample->power_ms_base_density,
                'power_ms_value' => $sample->power_ms_value,
                'power_ms_sample' => $sample->power_ms_sample,
                'power_ms_invoice_sample' => $sample->power_ms_invoice_sample,
                'power_ms_plastic_seal' => $sample->power_ms_plastic_seal,
                'power_ms_aluminium_seal' => $sample->power_ms_aluminium_seal,
            ]])
            ->all();
        $densityLookup = $this->densityLookupForSamples();
        $productRates = Product::query()
            ->whereNotNull('Purchase_rate')
            ->get(['Product_Name', 'Purchase_rate'])
            ->filter(fn (Product $product) => trim((string) $product->Product_Name) !== '')
            ->mapWithKeys(fn (Product $product) => [
                $product->Product_Name => number_format((float) $product->Purchase_rate, 2, '.', ''),
            ]);
        $latestRates = $productRates->merge(ItemDateRate::effectiveRatesByProductName($selectedDate));
        $previewRefNo = session('purchase_preview_ref') ?: $request->query('preview_ref');
        $previewPurchases = collect();

        if ($previewRefNo) {
            $previewPurchases = $this->purchaseItemsForDisplay(Purchase::with('items')
                ->where('Ref_no', $previewRefNo)
                ->orderBy('id')
                ->get());
        }
        $companyInformation = CompanyInformation::query()
            ->latest('id')
            ->first();

        return view('purchase', compact('perticular', 'item', 'nextRefNo', 'purchases', 'purchaseNavigationPurchases', 'purchaseSamples', 'purchaseSamplesByRef', 'selectedDate', 'densityLookup', 'latestRates', 'previewRefNo', 'previewPurchases', 'companyInformation'));
    }

    public function purchase_pdf(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $purchases = $this->purchaseItemsForDisplay($this->purchasesForDate($selectedDate));

        if ($purchases->isEmpty()) {
            return redirect()->route('purchase', ['date' => $selectedDate])->with('error', 'No purchase entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Purchase List');
        }

        $theme = $this->exportTheme($request);
        $companyInformation = CompanyInformation::query()->latest('id')->first();
        $html = view('purchase_pdf', compact('purchases', 'selectedDate', 'theme', 'companyInformation'))->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('Purchase-' . $selectedDate . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function purchase_excel(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $purchases = $this->purchaseItemsForDisplay($this->purchasesForDate($selectedDate));

        if ($purchases->isEmpty()) {
            return redirect()->route('purchase', ['date' => $selectedDate])->with('error', 'No purchase entries available to export.');
        }

        $companyInformation = CompanyInformation::query()->latest('id')->first();

        return Excel::download(new PurchaseExport($purchases, $selectedDate, $this->exportTheme($request), $companyInformation), 'Purchase-' . $selectedDate . '.xlsx');
    }

    public function purchase_sample_pdf(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $purchaseSamples = $this->purchaseSamplesForDate($selectedDate);

        if ($purchaseSamples->isEmpty()) {
            return redirect()->route('purchase', ['date' => $selectedDate])->with('error', 'No purchase sample entries available to export.');
        }

        if (! $this->shouldStreamRawPdf($request)) {
            return $this->pdfViewer($request, 'Purchase Sample List');
        }

        $theme = $this->exportTheme($request);
        $html = view('purchase_sample_pdf', compact('purchaseSamples', 'selectedDate', 'theme'))->render();
        $mpdf = new Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('PurchaseSample-' . $selectedDate . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    public function purchase_sample_excel(Request $request)
    {
        $selectedDate = $this->selectedDate($request);
        $purchaseSamples = $this->purchaseSamplesForDate($selectedDate);

        if ($purchaseSamples->isEmpty()) {
            return redirect()->route('purchase', ['date' => $selectedDate])->with('error', 'No purchase sample entries available to export.');
        }

        return Excel::download(new PurchaseSampleExport($purchaseSamples, $selectedDate, $this->exportTheme($request)), 'PurchaseSample-' . $selectedDate . '.xlsx');
    }

    public function purchaseSamplePreview(Request $request)
    {
        $validated = $request->validate([
            'product_key' => ['required', 'in:hsd,ms,power-ms'],
            'product_label' => ['nullable', 'string', 'max:50'],
            'date' => ['nullable', 'date'],
            'ref_no' => ['nullable', 'string', 'max:255'],
            'tanker' => ['nullable', 'string', 'max:255'],
            'transport' => ['nullable', 'string', 'max:255'],
            'oil_company' => ['nullable', 'string', 'max:255'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'product' => ['nullable', 'string', 'max:255'],
            'temp' => ['nullable', 'numeric'],
            'base_density' => ['nullable', 'numeric'],
            'value' => ['nullable', 'numeric'],
            'sample' => ['nullable', 'string', 'max:255'],
            'invoice_sample' => ['nullable', 'string', 'max:255'],
            'plastic_seal' => ['nullable', 'string', 'max:255'],
            'aluminium_seal' => ['nullable', 'string', 'max:255'],
        ]);

        $productLabels = [
            'hsd' => 'HSD',
            'ms' => 'MS',
            'power-ms' => 'POWER MS',
        ];

        $sample = array_merge([
            'product_label' => $productLabels[$validated['product_key']],
            'date' => '',
            'tanker' => '',
            'transport' => '',
            'oil_company' => '',
            'invoice_no' => '',
            'product' => '',
            'temp' => '0.00',
            'base_density' => '0.0000',
            'value' => '0.0000',
            'sample' => '',
            'invoice_sample' => '',
            'plastic_seal' => '',
            'aluminium_seal' => '',
        ], $validated);

        $sample['product_label'] = $productLabels[$validated['product_key']];

        $theme = $this->exportTheme($request);

        if ($request->boolean('raw_pdf')) {
            $html = view('purchase_sample_preview_pdf', [
                'sample' => $sample,
                'theme' => $theme,
                'isPdf' => true,
            ])->render();
            $mpdf = new Mpdf(['orientation' => 'P']);
            set_error_handler(static function () {
                return true;
            }, E_WARNING | E_NOTICE);

            try {
                $mpdf->WriteHTML($html);
            } finally {
                restore_error_handler();
            }

            return response($mpdf->Output($sample['product_label'] . '-Sample-Preview.pdf', 'S'))
                ->header('Content-Type', 'application/pdf');
        }

        return view('purchase_sample_preview', [
            'sample' => $sample,
            'theme' => $theme,
            'isPdf' => false,
        ]);
    }

    public function storePurchaseSample(Request $request)
    {
        $validated = $this->validatedSampleData($request);
        $numericSampleFields = [
            'hsd_temp' => 2,
            'hsd_base_density' => 4,
            'hsd_value' => 4,
            'ms_temp' => 2,
            'ms_base_density' => 4,
            'ms_value' => 4,
            'power_ms_temp' => 2,
            'power_ms_base_density' => 4,
            'power_ms_value' => 4,
        ];

        if (! empty($validated['ref_no'])) {
            $previousSample = PurchaseSample::where('ref_no', $validated['ref_no'])->latest('id')->first();

            if ($previousSample) {
                $validated = array_merge($previousSample->only((new PurchaseSample())->getFillable()), $validated);
            }
        }

        foreach ($numericSampleFields as $field => $precision) {
            if (array_key_exists($field, $validated) && $validated[$field] !== null && $validated[$field] !== '') {
                $validated[$field] = round((float) $validated[$field], $precision);
            }
        }

        $purchaseSample = PurchaseSample::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Purchase sample saved successfully.',
                'sample' => [
                    'date' => optional($purchaseSample->date)->format('Y-m-d'),
                    'ref_no' => $purchaseSample->ref_no,
                    'tanker' => $purchaseSample->tanker,
                    'transport' => $purchaseSample->transport,
                    'oil_company' => $purchaseSample->oil_company,
                    'invoice_no' => $purchaseSample->invoice_no,
                    'product' => $purchaseSample->product,
                    'hsd_temp' => $purchaseSample->hsd_temp,
                    'hsd_base_density' => $purchaseSample->hsd_base_density,
                    'hsd_value' => $purchaseSample->hsd_value,
                    'hsd_sample' => $purchaseSample->hsd_sample,
                    'hsd_invoice_sample' => $purchaseSample->hsd_invoice_sample,
                    'hsd_plastic_seal' => $purchaseSample->hsd_plastic_seal,
                    'hsd_aluminium_seal' => $purchaseSample->hsd_aluminium_seal,
                    'ms_temp' => $purchaseSample->ms_temp,
                    'ms_base_density' => $purchaseSample->ms_base_density,
                    'ms_value' => $purchaseSample->ms_value,
                    'ms_sample' => $purchaseSample->ms_sample,
                    'ms_invoice_sample' => $purchaseSample->ms_invoice_sample,
                    'ms_plastic_seal' => $purchaseSample->ms_plastic_seal,
                    'ms_aluminium_seal' => $purchaseSample->ms_aluminium_seal,
                    'power_ms_temp' => $purchaseSample->power_ms_temp,
                    'power_ms_base_density' => $purchaseSample->power_ms_base_density,
                    'power_ms_value' => $purchaseSample->power_ms_value,
                    'power_ms_sample' => $purchaseSample->power_ms_sample,
                    'power_ms_invoice_sample' => $purchaseSample->power_ms_invoice_sample,
                    'power_ms_plastic_seal' => $purchaseSample->power_ms_plastic_seal,
                    'power_ms_aluminium_seal' => $purchaseSample->power_ms_aluminium_seal,
                ],
            ]);
        }

        return redirect()
            ->route('purchase', array_filter([
                'date' => $validated['date'] ?? now()->toDateString(),
                'view_ref' => $validated['ref_no'] ?? null,
            ], fn ($value) => $value !== null && $value !== ''))
            ->with('success', 'Purchase sample saved successfully.');
    }

    public function storepurchase(Request $request)
    {
        $this->normalizePurchaseNumbers($request);

        // NOTE: purchase date must be in 'YYYY-MM-DD' for MySQL.
        // JS row values may pass date in other formats (e.g. '17-06-2026'),
        // so we normalize safely here to avoid SQL datetime errors.
        $this->normalizePurchaseDateInput($request);

        if ($request->has('items')) {
            return $this->storeMultiplePurchases($request);
        }


        $validated = $this->validatedData($request);
        $date = $validated['date'] ?? now()->toDateString();
        $refNo = $validated['Ref_no'] ?? $this->nextRefNo();

        DB::transaction(function () use ($validated) {
            $this->createPurchaseMasterWithItems($validated, [$validated]);
        });

        return redirect()
            ->route('purchase', ['date' => $date])
            ->with('success', 'Purchase saved successfully.')
            ->with('purchase_preview_ref', $refNo);
    }

    private function storeMultiplePurchases(Request $request)
    {
        $validated = $this->validatedMultipleData($request);
        $firstItem = $validated['items'][0] ?? [];
        $date = $firstItem['date'] ?? $validated['date'] ?? now()->toDateString();
        $refNo = $firstItem['Ref_no'] ?? $validated['Ref_no'] ?? $this->nextRefNo();

        DB::transaction(function () use ($validated) {
            $this->createPurchaseMasterWithItems($validated, $validated['items']);
        });

        return redirect()
            ->route('purchase', ['date' => $date])
            ->with('success', count($validated['items']) . ' purchase entries saved successfully under Ref ' . $refNo . '.')
            ->with('purchase_preview_ref', $refNo);
    }

    private function createPurchaseMasterWithItems(array $header, array $items): Purchase
    {
        $firstItem = $items[0] ?? [];
        $refNo = $firstItem['Ref_no'] ?? $header['Ref_no'] ?? $this->nextRefNo();
        $date = $firstItem['date'] ?? $header['date'] ?? now()->toDateString();
        $interstate = $firstItem['interstate'] ?? $header['interstate'] ?? 'No';
        $lines = collect($items)->map(fn (array $item) => $this->purchaseItemLineData($item, $header));
        $amount = $lines->sum('amount');
        $discount = $lines->sum('discountinrs');
        $taxable = $lines->sum('taxable_amount');
        $cgstAmount = $lines->sum('cgst_amount');
        $sgstAmount = $lines->sum('sgst_amount');
        $igstAmount = $lines->sum('igst_amount');
        $tax = $lines->sum('total_tax_amount');
        $total = $lines->sum('total_amount');
        $uniqueItems = $lines->pluck('item_name')->filter()->unique()->values();

        $purchase = Purchase::where('Ref_no', $refNo)->orderBy('id')->first() ?? new Purchase();

        if ($purchase->exists) {
            $purchase->items()->delete();
        }

        $purchase->fill([
            'perticulars' => $firstItem['perticulars'] ?? $header['perticulars'] ?? '',
            'Ref_no' => $refNo,
            'interstate' => $interstate,
            'postal address' => $firstItem['postal address'] ?? $header['postal address'] ?? null,
            'location' => $firstItem['location'] ?? $header['location'] ?? null,
            'date' => $date,
            'invoice_no' => $firstItem['invoice_no'] ?? $header['invoice_no'] ?? '',
            'vehicle_no' => $firstItem['vehicle_no'] ?? $header['vehicle_no'] ?? null,
            'transporter' => $firstItem['transporter'] ?? $header['transporter'] ?? null,
            'driver' => $firstItem['driver'] ?? $header['driver'] ?? null,
            'item_name' => $uniqueItems->join(', '),
            'quantity' => $lines->sum('quantity'),
            'rate' => $lines->sum('rate'),
            'amount' => $amount,
            'discount%' => (float) ($firstItem['discount%'] ?? $header['discount%'] ?? 0),
            'discountinrs' => $discount,
            'taxable_amount' => $taxable,
            'cgst' => (float) ($firstItem['cgst'] ?? $header['cgst'] ?? 0),
            'sgst' => (float) ($firstItem['sgst'] ?? $header['sgst'] ?? 0),
            'igst' => (float) ($firstItem['igst'] ?? $header['igst'] ?? 0),
            'total_amount' => $total,
            'total_tax_amount' => $tax,
            'total_cgst_amount' => $cgstAmount,
            'total_sgst_amount' => $sgstAmount,
            'total_igst_amount' => $igstAmount,
        ])->save();

        $lines->each(function (array $line) use ($purchase, $refNo, $date) {
            unset($line['cgst_amount'], $line['sgst_amount'], $line['igst_amount']);
            PurchaseItem::create(array_merge($line, [
                'purchase_id' => $purchase->id,
                'Ref_no' => $refNo,
                'date' => $date,
            ]));
        });

        $this->postPurchaseLedger($purchase);

        return $purchase;
    }

    private function purchaseItemLineData(array $item, array $header = []): array
    {
        $interstate = $item['interstate'] ?? $header['interstate'] ?? 'No';
        $quantity = (float) ($item['quantity'] ?? 0);
        $rate = (float) ($item['rate'] ?? 0);
        $amount = $quantity * $rate;
        $discountPercent = (float) ($item['discount%'] ?? 0);
        $discountAmount = ($amount * $discountPercent) / 100;
        $taxableAmount = $amount - $discountAmount;
        $cgst = round((float) ($item['cgst'] ?? 0), 2);
        $sgst = round((float) ($item['sgst'] ?? 0), 2);
        $igst = round((float) ($item['igst'] ?? 0), 2);
        $cgstAmount = 0;
        $sgstAmount = 0;
        $igstAmount = 0;

        if ($interstate === 'Yes') {
            $cgst = 0;
            $sgst = 0;
            $igstAmount = ($taxableAmount * $igst) / 100;
        } else {
            $igst = 0;
            $cgstAmount = ($taxableAmount * $cgst) / 100;
            $sgstAmount = ($taxableAmount * $sgst) / 100;
        }

        $totalTaxAmount = $cgstAmount + $sgstAmount + $igstAmount;

        return [
            'item_name' => $item['item_name'] ?? '',
            'quantity' => $quantity,
            'rate' => $rate,
            'amount' => $amount,
            'discount%' => $discountPercent,
            'discountinrs' => $discountAmount,
            'taxable_amount' => $taxableAmount,
            'cgst' => $cgst,
            'sgst' => $sgst,
            'igst' => $igst,
            'cgst_amount' => $cgstAmount,
            'sgst_amount' => $sgstAmount,
            'igst_amount' => $igstAmount,
            'total_tax_amount' => $totalTaxAmount,
            'total_amount' => $taxableAmount + $totalTaxAmount,
        ];
    }

    public function updatepurchase(Request $request, Purchase $purchase)
    {
        $this->normalizePurchaseNumbers($request);

        $validated = $this->validatedData($request);
        $quantity = (float) ($validated['quantity'] ?? 0);
        $rate = (float) ($validated['rate'] ?? 0);
        $amonut = $quantity * $rate;
        $date = $validated['date'] ?? now()->toDateString();
        $interstate = $validated['interstate'] ?? 'No';
        $discount_per = (float) ($validated['discount%'] ?? 0);
        $discount_amount = ($amonut * $discount_per) / 100;
        $taxableAmount = $amonut - $discount_amount;
        $cgst = round((float) ($validated['cgst'] ?? 0), 2);
        $sgst = round((float) ($validated['sgst'] ?? 0), 2);
        $igst = round((float) ($validated['igst'] ?? 0), 2);

        if ($interstate === 'Yes') {
            $cgst = 0;
            $sgst = 0;
            $totalTaxAmount = ($taxableAmount * $igst) / 100;
        } else {
            $igst = 0;
            $totalTaxAmount = (($taxableAmount * $cgst) / 100) + (($taxableAmount * $sgst) / 100);
        }

        DB::transaction(function () use ($purchase, $validated, $interstate, $date, $quantity, $rate, $amonut, $discount_per, $discount_amount, $taxableAmount, $cgst, $sgst, $igst, $totalTaxAmount) {
            $purchase->update([
                'perticulars' => $validated['perticulars'],
                'Ref_no' => $purchase->Ref_no,
                'interstate' => $interstate,
                'postal address' => $validated['postal address'] ?? null,
                'location' => $validated['location'] ?? null,
                'date' => $date,
                'invoice_no' => $validated['invoice_no'] ?? '',
                'vehicle_no' => $validated['vehicle_no'] ?? null,
                'transporter' => $validated['transporter'] ?? null,
                'driver' => $validated['driver'] ?? null,
                'item_name' => $validated['item_name'],
                'quantity' => $quantity,
                'rate' => $rate,
                'amount' => $amonut,
                'discount%' => $discount_per,
                'discountinrs' => $discount_amount,
                'taxable_amount' => $taxableAmount,
                'cgst' => $cgst,
                'sgst' => $sgst,
                'igst' => $igst,
                'total_amount' => $taxableAmount + $totalTaxAmount,
                'total_tax_amount' => $totalTaxAmount,
            ]);

            $this->postPurchaseLedger($purchase);
        });

        return redirect()
            ->route('purchase', ['date' => $date])
            ->with('success', 'Purchase updated successfully.');
    }

    public function destroypurchase(Purchase $purchase)
    {
        $selectedDate = substr((string) $purchase->date, 0, 10) ?: now()->toDateString();
        DB::transaction(function () use ($purchase) {
            Ledgers::query()
                ->where('VOUCHERNO', $purchase->id)
                ->where('VTYPE', 'PURCHASE')
                ->delete();

            $purchase->delete();
        });

        return redirect()
            ->route('purchase', ['date' => $selectedDate])
            ->with('success', 'Purchase deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        $vehicleNo = trim((string) $request->input('vehicle_no'));

        $request->merge([
            'vehicle_no' => $vehicleNo !== '' ? $vehicleNo : null,
        ]);

        return $request->validate([
            'perticulars' => ['required', 'string', 'max:255'],
            'Ref_no' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'interstate' => ['required', 'in:Yes,No'],
            'postal address' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'vehicle_no' => ['nullable', 'string', 'max:50'],
            'transporter' => ['nullable', 'string', 'max:255'],
            'driver' => ['nullable', 'string', 'max:255'],
            'item_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'gt:0', 'max:999999.999'],
            'rate' => ['required', 'numeric', 'gt:0', 'max:99999999.99'],
            'discount%' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'taxable_amount' => ['nullable', 'numeric', 'min:0'],
            'cgst' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sgst' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'igst' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'total_tax_amount' => ['nullable', 'numeric', 'min:0'],
        ], [
            'quantity.gt' => 'Quantity must be greater than 0.',
            'rate.gt' => 'Rate must be greater than 0.',
            'vehicle_no.max' => 'Vehicle number can be up to 50 characters.',
        ]);
    }

    private function normalizePurchaseNumbers(Request $request): void
    {
        foreach (['quantity', 'rate', 'discount%', 'taxable_amount', 'cgst', 'sgst', 'igst', 'total_amount', 'total_tax_amount'] as $field) {
            if ($request->filled($field)) {
                $request->merge([
                    $field => str_replace(',', '.', (string) $request->input($field)),
                ]);
            }
        }

        if (! $request->has('items') || ! is_array($request->input('items'))) {
            return;
        }

        $items = $request->input('items');

        foreach ($items as $index => $item) {
            foreach (['quantity', 'rate', 'discount%', 'cgst', 'sgst', 'igst'] as $field) {
                if (isset($item[$field]) && $item[$field] !== '') {
                    $items[$index][$field] = str_replace(',', '.', (string) $item[$field]);
                }
            }
        }

        $request->merge(['items' => $items]);
    }

    private function validatedMultipleData(Request $request): array
    {
        $vehicleNo = trim((string) $request->input('vehicle_no'));
        $items = $request->input('items', []);

        foreach ($items as $index => $item) {
            $itemVehicleNo = trim((string) ($item['vehicle_no'] ?? ''));
            $items[$index]['vehicle_no'] = $itemVehicleNo !== '' ? $itemVehicleNo : null;
        }

        $request->merge([
            'vehicle_no' => $vehicleNo !== '' ? $vehicleNo : null,
            'items' => $items,
        ]);

        return $request->validate([
            'perticulars' => ['nullable', 'string', 'max:255'],
            'Ref_no' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'interstate' => ['nullable', 'in:Yes,No'],
            'postal address' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'vehicle_no' => ['nullable', 'string', 'max:50'],
            'transporter' => ['nullable', 'string', 'max:255'],
            'driver' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.perticulars' => ['required', 'string', 'max:255'],
            'items.*.Ref_no' => ['required', 'string', 'max:255'],
            'items.*.date' => ['required', 'date'],
            'items.*.interstate' => ['required', 'in:Yes,No'],
            'items.*.postal address' => ['nullable', 'string', 'max:255'],
            'items.*.location' => ['nullable', 'string', 'max:255'],
            'items.*.invoice_no' => ['nullable', 'string', 'max:255'],
            'items.*.vehicle_no' => ['nullable', 'string', 'max:50'],
            'items.*.transporter' => ['nullable', 'string', 'max:255'],
            'items.*.driver' => ['nullable', 'string', 'max:255'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0', 'max:999999.999'],
            'items.*.rate' => ['required', 'numeric', 'gt:0', 'max:99999999.99'],
            'items.*.discount%' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.cgst' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.sgst' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.igst' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ], [
            'items.*.quantity.gt' => 'Each purchase item quantity must be greater than 0.',
            'items.*.rate.gt' => 'Each purchase item rate must be greater than 0.',
            'items.required' => 'Add at least one purchase item before saving.',
            'items.*.item_name.required' => 'Each purchase item needs an item name.',
        ]);
    }

    private function validatedSampleData(Request $request): array
    {
        foreach ([
            'hsd_temp',
            'hsd_base_density',
            'hsd_value',
            'ms_temp',
            'ms_base_density',
            'ms_value',
            'power_ms_temp',
            'power_ms_base_density',
            'power_ms_value',
        ] as $field) {
            if ($request->filled($field)) {
                $request->merge([
                    $field => str_replace(',', '.', $request->input($field)),
                ]);
            }
        }

        return $request->validate([
            'date' => ['nullable', 'date'],
            'ref_no' => ['nullable', 'string', 'max:255'],
            'tanker' => ['nullable', 'string', 'max:255'],
            'transport' => ['nullable', 'string', 'max:255'],
            'oil_company' => ['nullable', 'string', 'max:255'],
            'invoice_no' => ['nullable', 'string', 'max:255'],
            'product' => ['nullable', 'string', 'max:255'],
            'hsd_temp' => ['nullable', 'numeric', 'min:0'],
            'hsd_base_density' => ['nullable', 'numeric', 'min:0'],
            'hsd_value' => ['nullable', 'numeric', 'min:0'],
            'hsd_sample' => ['nullable', 'string', 'max:255'],
            'hsd_invoice_sample' => ['nullable', 'string', 'max:255'],
            'hsd_plastic_seal' => ['nullable', 'string', 'max:255'],
            'hsd_aluminium_seal' => ['nullable', 'string', 'max:255'],
            'ms_temp' => ['nullable', 'numeric', 'min:0'],
            'ms_base_density' => ['nullable', 'numeric', 'min:0'],
            'ms_value' => ['nullable', 'numeric', 'min:0'],
            'ms_sample' => ['nullable', 'string', 'max:255'],
            'ms_invoice_sample' => ['nullable', 'string', 'max:255'],
            'ms_plastic_seal' => ['nullable', 'string', 'max:255'],
            'ms_aluminium_seal' => ['nullable', 'string', 'max:255'],
            'power_ms_temp' => ['nullable', 'numeric', 'min:0'],
            'power_ms_base_density' => ['nullable', 'numeric', 'min:0'],
            'power_ms_value' => ['nullable', 'numeric', 'min:0'],
            'power_ms_sample' => ['nullable', 'string', 'max:255'],
            'power_ms_invoice_sample' => ['nullable', 'string', 'max:255'],
            'power_ms_plastic_seal' => ['nullable', 'string', 'max:255'],
            'power_ms_aluminium_seal' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function selectedDate(Request $request): string
    {
        $selectedDate = $request->query('date', now()->toDateString());

        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate) ? $selectedDate : now()->toDateString();
    }

    private function purchasesForDate(string $selectedDate)
    {
        $accountDetailsByName = AccountName::whereRaw('TRIM(under_group) = ?', ['SUNDRY CREDITORS'])
            ->get(['account_perticular', 'address', 'city'])
            ->keyBy('account_perticular');

        return Purchase::with('items')
            ->whereDate('date', $selectedDate)
            ->orderBy('Ref_no')
            ->get()
            ->map(function (Purchase $purchase) use ($accountDetailsByName) {
                $account = $accountDetailsByName->get($purchase->perticulars);
                $purchase->export_postal_address = $purchase->{'postal address'} ?: ($account->address ?? '');
                $purchase->export_location = $purchase->location ?: ($account->city ?? '');

                return $purchase;
            });
    }

    private function purchasesForNavigation()
    {
        $accountDetailsByName = AccountName::whereRaw('TRIM(under_group) = ?', ['SUNDRY CREDITORS'])
            ->get(['account_perticular', 'address', 'city'])
            ->keyBy('account_perticular');

        return Purchase::with('items')
            ->orderBy('date')
            ->orderBy('Ref_no')
            ->orderBy('id')
            ->get()
            ->map(function (Purchase $purchase) use ($accountDetailsByName) {
                $account = $accountDetailsByName->get($purchase->perticulars);
                $purchase->export_postal_address = $purchase->{'postal address'} ?: ($account->address ?? '');
                $purchase->export_location = $purchase->location ?: ($account->city ?? '');

                return $purchase;
            });
    }

    private function purchaseItemsForDisplay(Collection $purchases): Collection
    {
        return $purchases->flatMap(function (Purchase $purchase) {
            $items = $purchase->relationLoaded('items') ? $purchase->items : collect();

            if ($items->isEmpty()) {
                return [$purchase];
            }

            return $items->map(function (PurchaseItem $item) use ($purchase) {
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
                    'export_postal_address',
                    'export_location',
                ] as $field) {
                    $displayItem->setAttribute($field, $purchase->{$field} ?? $item->{$field} ?? null);
                }

                $displayItem->setAttribute('postal address', $purchase->{'postal address'} ?? '');
                $displayItem->setAttribute('purchase_id', $purchase->id);

                return $displayItem;
            });
        })->values();
    }

    private function purchaseSamplesForDate(string $selectedDate)
    {
        return PurchaseSample::whereDate('date', $selectedDate)->latest()->get();
    }

    private function densityLookupForSamples(): array
    {
        $lookup = [
            'hsd' => [],
            'ms' => [],
            'power-ms' => [],
        ];

        Density::query()
            ->select(['fuel_type', 'temperature', 'base_dens', 'chart_val'])
            ->orderBy('fuel_type')
            ->orderBy('base_dens')
            ->orderBy('temperature')
            ->get()
            ->each(function (Density $density) use (&$lookup) {
                $productKey = $this->sampleProductKey($density->fuel_type);

                if (! $productKey) {
                    return;
                }

                $temperatureKey = $this->densityLookupKey($density->temperature);
                $densityKey = $temperatureKey . '|' . $this->densityLookupKey($density->base_dens);

                if (! isset($lookup[$productKey][$densityKey])) {
                    $lookup[$productKey][$densityKey] = [
                        'chart_val' => number_format((float) $density->chart_val, 4, '.', ''),
                    ];
                }

                if (! isset($lookup[$productKey]['by_temp'][$temperatureKey])) {
                    $lookup[$productKey]['by_temp'][$temperatureKey] = [
                        'base_density' => number_format((float) $density->base_dens, 4, '.', ''),
                        'chart_val' => number_format((float) $density->chart_val, 4, '.', ''),
                    ];
                }
            });

        return $lookup;
    }

    private function sampleProductKey(?string $productName): ?string
    {
        $name = strtolower(trim((string) $productName));

        if ($name === '') {
            return null;
        }

        if (str_contains($name, 'power') || str_contains($name, 'po-ms') || str_contains($name, 'po ms')) {
            return 'power-ms';
        }

        if (str_contains($name, 'diesel') || str_contains($name, 'deisel') || str_contains($name, 'deisle') || str_contains($name, 'diesal') || str_contains($name, 'desal') || str_contains($name, 'hsd')) {
            return 'hsd';
        }

        if (str_contains($name, 'petrol') || preg_match('/\bms\b/', $name)) {
            return 'ms';
        }

        return null;
    }

    private function densityLookupKey(mixed $value): string
    {
        $formatted = number_format((float) $value, 4, '.', '');

        return rtrim(rtrim($formatted, '0'), '.');
    }

    private function postPurchaseLedger(Purchase $purchase): void
    {
        $ledgerAmount = round((float) ($purchase->total_amount ?: $purchase->amount), 2);

        Ledgers::query()
            ->where('VOUCHERNO', $purchase->id)
            ->where('VTYPE', 'PURCHASE')
            ->delete();

        Ledgers::create([
            'VOUCHERNO' => $purchase->id,
            'VTYPE' => 'PURCHASE',
            'TRANDATE' => $purchase->date,
            'TRANTYPE' => 'D',
            'ACNO' => 'PURCHASE',
            'AMOUNT' => $ledgerAmount,
        ]);

        Ledgers::create([
            'VOUCHERNO' => $purchase->id,
            'VTYPE' => 'PURCHASE',
            'TRANDATE' => $purchase->date,
            'TRANTYPE' => 'C',
            'ACNO' => $purchase->perticulars,
            'AMOUNT' => $ledgerAmount,
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

    private function normalizePurchaseDateInput(Request $request): void
    {
        // normalize main date
        $date = $request->input('date');
        $normalized = $this->normalizeDateToYmd($date);
        if ($normalized) {
            $request->merge(['date' => $normalized]);
        }

        // normalize items[].date

        $items = $request->input('items', []);
        if (is_array($items)) {
            foreach ($items as $idx => $item) {
                $itemDate = $item['date'] ?? null;
                $normItemDate = $this->normalizeDateToYmd($itemDate);
                if ($normItemDate) {
                    $items[$idx]['date'] = $normItemDate;
                }
            }
            $request->merge(['items' => $items]);
        }
    }

    private function normalizeDateToYmd(null|string $date): ?string
    {
        if (! $date) {
            return null;
        }

        $date = trim((string) $date);

        // already Y-m-d
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }

        // accept d-m-Y or d-m-Y (with leading zeros)
        if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $date, $m)) {
            $dd = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $mm = str_pad($m[2], 2, '0', STR_PAD_LEFT);
            $yyyy = $m[3];
            return sprintf('%s-%s-%s', $yyyy, $mm, $dd);
        }

        return null;
    }

    private function nextRefNo(): string
    {

        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            try {
                $maxRefNo = (int) (Purchase::query()
                    ->selectRaw("MAX(CAST(NULLIF(REGEXP_REPLACE(`Ref_no`, '[^0-9]', ''), '') AS UNSIGNED)) as max_ref")
                    ->value('max_ref') ?? 0);

                return (string) ($maxRefNo + 1);
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        $maxRefNo = Purchase::query()
            ->latest('id')
            ->limit(1000)
            ->pluck('Ref_no')
            ->map(fn ($refNo) => (int) preg_replace('/\D+/', '', (string) $refNo))
            ->max();

        return (string) (((int) $maxRefNo) + 1);
    }
}
