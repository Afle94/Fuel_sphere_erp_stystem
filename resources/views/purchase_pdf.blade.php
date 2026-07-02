<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Invoice</title>
    @php
        $theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'accent' => '#f59e0b',
        ], $theme ?? []);
        $purchases = collect($purchases ?? []);
        $first = $purchases->first();
        $companyName = $companyInformation->company_name ?? 'FuelTracker';
        $companyOffice = $companyInformation->registered_office ?? '';
        $companyPhone = $companyInformation->phone_no ?? '';
        $companyMobile = $companyInformation->mobile_no ?? '';
        $companyEmail = $companyInformation->email_id ?? '';
        $companyGstNo = $companyInformation->gst_no ?? '';
        $subtotal = $purchases->sum(fn ($purchase) => (float) ($purchase->amount ?? 0));
        $discount = $purchases->sum(fn ($purchase) => (float) ($purchase->discountinrs ?? 0));
        $taxable = $purchases->sum(fn ($purchase) => (float) ($purchase->taxable_amount ?? 0));
        $tax = $purchases->sum(fn ($purchase) => (float) ($purchase->total_tax_amount ?? 0));
        $total = $purchases->sum(fn ($purchase) => (float) ($purchase->total_amount ?? 0));
    @endphp
    <style>
        body{margin:0;color:#172033;background:#fff;font-family:DejaVu Sans,Arial,sans-serif;font-size:10px}
        .invoice{width:100%;border:1px solid #1f2937;background:#fff}
        .company{padding:12px 14px 9px;border-bottom:2px solid #1f2937;text-align:center}
        .company h1{margin:0 0 4px;color:{{ $theme['primaryDark'] }};font-size:22px;line-height:1.15}
        .company div{line-height:1.35}
        .meta{width:100%;border-collapse:collapse}
        .meta td{padding:6px 8px;border-bottom:1px solid #1f2937;font-weight:bold}
        .title{padding:6px;border-bottom:1px solid #1f2937;color:{{ $theme['primaryDark'] }};font-size:13px;font-weight:bold;text-align:center;text-transform:uppercase}
        .party{width:100%;border-collapse:collapse}
        .party td{width:50%;padding:9px 10px;border-bottom:1px solid #dce3ee;vertical-align:top;line-height:1.45}
        .section-title{display:block;margin-bottom:4px;color:{{ $theme['primaryDark'] }};font-weight:bold}
        table.items{width:100%;border-collapse:collapse}
        .items th,.items td{padding:5px 4px;border:1px solid #1f2937;vertical-align:top}
        .items th{color:#fff;background:{{ $theme['primary'] }};font-size:9px;text-align:center}
        .items td{font-size:9px}
        .right{text-align:right;white-space:nowrap}
        .totals{width:100%;border-collapse:collapse;margin-top:10px}
        .totals td{padding:7px 8px;border:1px solid #dce3ee;font-weight:bold}
        .totals .label{width:72%;text-align:right}
        .totals .grand{color:{{ $theme['primaryDark'] }};background:#eef5f3;font-size:12px}
        .note{padding:8px 10px;color:#657089;font-weight:bold}
        .empty{padding:24px;border:1px solid #dce3ee;text-align:center;font-weight:bold}
    </style>
</head>
<body>
    @if ($purchases->count())
        <div class="invoice">
            <div class="company">
                <h1>{{ $companyName }}</h1>
                @if ($companyOffice)<div>{{ $companyOffice }}</div>@endif
                @if ($companyPhone || $companyMobile || $companyEmail)
                    <div>
                        @if ($companyPhone || $companyMobile) Phone: {{ $companyPhone ?: $companyMobile }} @endif
                        @if (($companyPhone || $companyMobile) && $companyEmail) | @endif
                        @if ($companyEmail) Email: {{ $companyEmail }} @endif
                    </div>
                @endif
                @if ($companyGstNo)<div>GSTIN: {{ $companyGstNo }}</div>@endif
            </div>

            <table class="meta">
                <tr>
                    <td>Ref No: {{ $first->Ref_no ?? $selectedDate }}</td>
                    <td class="right">Date: {{ ($first && $first->date) ? \Carbon\Carbon::parse($first->date)->format('d-m-Y') : $selectedDate }}</td>
                    <td class="right">Original</td>
                </tr>
            </table>

            <div class="title">Purchase Invoice</div>

            <table class="party">
                <tr>
                    <td>
                        <span class="section-title">Invoice Details</span>
                        Invoice No: {{ $first->invoice_no ?? '-' }}<br>
                        Interstate: {{ $first->interstate ?? 'No' }}<br>
                        Total Items: {{ $purchases->count() }}
                    </td>
                    <td>
                        <span class="section-title">Supplier</span>
                        {{ $first->perticulars ?? '-' }}<br>
                        {{ data_get($first, 'postal address') ?: (data_get($first, 'export_postal_address') ?: '-') }}<br>
                        {{ data_get($first, 'location') ?: (data_get($first, 'export_location') ?: '-') }}
                    </td>
                </tr>
            </table>

            <table class="items">
                <thead>
                    <tr>
                        <th>S.N.</th>
                        <th>Product No.</th>
                        <th>Particulars</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Disc %</th>
                        <th>Taxable Amt</th>
                        <th>CGST %</th>
                        <th>SGST %</th>
                        <th>IGST %</th>
                        <th>Total Tax</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $index => $purchase)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $purchase->item_name ?: '-' }}</td>
                            <td>{{ $purchase->item_name ?: '-' }}</td>
                            <td class="right">{{ number_format((float) $purchase->quantity, 3) }}</td>
                            <td class="right">{{ number_format((float) $purchase->rate, 2) }}</td>
                            <td class="right">{{ number_format((float) $purchase->{'discount%'}, 2) }}</td>
                            <td class="right">{{ number_format((float) $purchase->taxable_amount, 2) }}</td>
                            <td class="right">{{ number_format((float) $purchase->cgst, 2) }}</td>
                            <td class="right">{{ number_format((float) $purchase->sgst, 2) }}</td>
                            <td class="right">{{ number_format((float) $purchase->igst, 2) }}</td>
                            <td class="right">{{ number_format((float) $purchase->total_tax_amount, 2) }}</td>
                            <td class="right">{{ number_format((float) $purchase->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="totals">
                <tr><td class="label">Subtotal</td><td class="right">Rs {{ number_format($subtotal, 2) }}</td></tr>
                <tr><td class="label">Discount</td><td class="right">Rs {{ number_format($discount, 2) }}</td></tr>
                <tr><td class="label">Taxable Amount</td><td class="right">Rs {{ number_format($taxable, 2) }}</td></tr>
                <tr><td class="label">Total Tax</td><td class="right">Rs {{ number_format($tax, 2) }}</td></tr>
                <tr class="grand"><td class="label">Total Purchase Amount</td><td class="right">Rs {{ number_format($total, 2) }}</td></tr>
            </table>
            <div class="note">Generated from FuelSphere ERP</div>
        </div>
    @else
        <div class="empty">No purchase entries found.</div>
    @endif
</body>
</html>
