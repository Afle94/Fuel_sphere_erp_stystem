<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock In - Out Analysis</title>
    @php
        $formatQty = fn ($value) => number_format((float) $value, 2);
        $formatMoney = fn ($value) => number_format((float) $value, 2);
    @endphp
    <style>
        body { margin:0; color:#172033; background:#fff; font-family:DejaVu Sans, Arial, sans-serif; font-size:9px; }
        .report-header { margin-bottom:10px; padding:10px; color:#fff; background:#115e59; border-bottom:3px solid #f59e0b; }
        h1 { margin:0 0 4px; color:#fff; font-size:18px; }
        .report-meta { color:#f8fbff; font-size:9px; }
        table { width:100%; border-collapse:collapse; }
        th,td { padding:6px 5px; border:1px solid #dce3ee; vertical-align:top; }
        th { color:#fff; background:#0f766e; font-size:8px; text-align:left; }
        td { background:#fff; font-size:8px; }
        tfoot td { color:#115e59; background:#eef5f3; font-weight:bold; }
        .number-cell { text-align:right; }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Stock In - Out Analysis</h1>
        <div class="report-meta">
            Period: {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }} | Generated on {{ now()->format('d M Y h:i A') }} | Total Products: {{ $rows->count() }}@if ($search) | Search: {{ $search }}@endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product Particulars</th>
                <th class="number-cell">Opening</th>
                <th class="number-cell">In</th>
                <th class="number-cell">Out</th>
                <th class="number-cell">Closing Stock</th>
                <th class="number-cell">Purchase Rate</th>
                <th class="number-cell">Value</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row['product'] }}</td>
                    <td class="number-cell">{{ $formatQty($row['opening']) }}</td>
                    <td class="number-cell">{{ $formatQty($row['in']) }}</td>
                    <td class="number-cell">{{ $formatQty($row['out']) }}</td>
                    <td class="number-cell">{{ $formatQty($row['closing']) }}</td>
                    <td class="number-cell">{{ $formatMoney($row['purchase_rate']) }}</td>
                    <td class="number-cell">{{ $formatMoney($row['value']) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No stock records found for selected date range.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td class="number-cell">{{ $formatQty($totals['opening']) }}</td>
                <td class="number-cell">{{ $formatQty($totals['in']) }}</td>
                <td class="number-cell">{{ $formatQty($totals['out']) }}</td>
                <td class="number-cell">{{ $formatQty($totals['closing']) }}</td>
                <td></td>
                <td class="number-cell">{{ $formatMoney($totals['value']) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
