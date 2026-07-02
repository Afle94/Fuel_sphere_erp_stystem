<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Item List</title>
    @php
        $theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'accent' => '#f59e0b',
            'bgEnd' => '#eef5f3',
        ], $theme ?? []);
        $purchaseItems = collect($purchaseItems ?? []);
    @endphp
    <style>
        body { margin:0; color:#172033; background:#fff; font-family:DejaVu Sans, Arial, sans-serif; font-size:8px; }
        .report-header { margin-bottom:10px; padding:10px; color:#fff; background:{{ $theme['primaryDark'] }}; border-bottom:3px solid {{ $theme['accent'] }}; }
        h1 { margin:0 0 4px; color:#fff; font-size:18px; }
        .report-meta { color:#f8fbff; font-size:9px; }
        table { width:100%; border-collapse:collapse; }
        th,td { padding:5px 4px; border:1px solid #dce3ee; vertical-align:top; }
        th { color:#fff; background:{{ $theme['primary'] }}; font-size:7.5px; text-align:left; }
        td { background:#fff; font-size:7.5px; }
        tfoot td { color:{{ $theme['primaryDark'] }}; background:{{ $theme['bgEnd'] }}; font-weight:bold; }
        .number-cell { text-align:right; white-space:nowrap; }
        .empty { padding:24px; border:1px solid #dce3ee; text-align:center; font-weight:bold; }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Purchase Item List</h1>
        <div class="report-meta">
            Period: {{ $periodLabel }} | Generated on {{ now()->format('d M Y h:i A') }} | Total Entries: {{ $purchaseItems->count() }}@if ($search) | Search: {{ $search }}@endif
        </div>
    </div>

    @if ($purchaseItems->count())
        <table>
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Particulars</th>
                    <th class="number-cell">Qty.</th>
                    <th class="number-cell">Rate</th>
                    <th class="number-cell">Amount</th>
                    <th class="number-cell">Discount %</th>
                    <th class="number-cell">Discount</th>
                    <th class="number-cell">Taxable Amt.</th>
                    <th class="number-cell">Total Amount</th>
                    <th class="number-cell">CGST %</th>
                    <th class="number-cell">SGST %</th>
                    <th class="number-cell">IGST %</th>
                    <th class="number-cell">Total Tax</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseItems as $purchaseItem)
                    <tr>
                        <td>{{ $purchaseItem->item_name ?: '-' }}</td>
                        <td>{{ $purchaseItem->item_name ?: '-' }}</td>
                        <td class="number-cell">{{ is_numeric($purchaseItem->quantity) ? number_format((float) $purchaseItem->quantity, 3) : '-' }}</td>
                        <td class="number-cell">{{ is_numeric($purchaseItem->rate) ? number_format((float) $purchaseItem->rate, 2) : '-' }}</td>
                        <td class="number-cell">{{ is_numeric($purchaseItem->amount) ? number_format((float) $purchaseItem->amount, 2) : '-' }}</td>
                        <td class="number-cell">{{ is_numeric($purchaseItem->{'discount%'}) ? number_format((float) $purchaseItem->{'discount%'}, 2) : '-' }}</td>
                        <td class="number-cell">{{ is_numeric($purchaseItem->discountinrs) ? number_format((float) $purchaseItem->discountinrs, 2) : '-' }}</td>
                        <td class="number-cell">{{ is_numeric($purchaseItem->taxable_amount) ? number_format((float) $purchaseItem->taxable_amount, 2) : '-' }}</td>
                        <td class="number-cell">{{ is_numeric($purchaseItem->total_amount) ? number_format((float) $purchaseItem->total_amount, 2) : '-' }}</td>
                        <td class="number-cell">{{ is_numeric($purchaseItem->cgst) ? number_format((float) $purchaseItem->cgst, 2) : '-' }}</td>
                        <td class="number-cell">{{ is_numeric($purchaseItem->sgst) ? number_format((float) $purchaseItem->sgst, 2) : '-' }}</td>
                        <td class="number-cell">{{ is_numeric($purchaseItem->igst) ? number_format((float) $purchaseItem->igst, 2) : '-' }}</td>
                        <td class="number-cell">{{ is_numeric($purchaseItem->total_tax_amount) ? number_format((float) $purchaseItem->total_tax_amount, 2) : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total</td>
                    <td class="number-cell">{{ number_format($purchaseItems->sum(fn ($item) => (float) ($item->quantity ?? 0)), 3) }}</td>
                    <td class="number-cell"></td>
                    <td class="number-cell">{{ number_format($purchaseItems->sum(fn ($item) => (float) ($item->amount ?? 0)), 2) }}</td>
                    <td class="number-cell"></td>
                    <td class="number-cell">{{ number_format($purchaseItems->sum(fn ($item) => (float) ($item->discountinrs ?? 0)), 2) }}</td>
                    <td class="number-cell">{{ number_format($purchaseItems->sum(fn ($item) => (float) ($item->taxable_amount ?? 0)), 2) }}</td>
                    <td class="number-cell">{{ number_format($purchaseItems->sum(fn ($item) => (float) ($item->total_amount ?? 0)), 2) }}</td>
                    <td class="number-cell"></td>
                    <td class="number-cell"></td>
                    <td class="number-cell"></td>
                    <td class="number-cell">{{ number_format($purchaseItems->sum(fn ($item) => (float) ($item->total_tax_amount ?? 0)), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    @else
        <div class="empty">No purchase item records found.</div>
    @endif
</body>
</html>
