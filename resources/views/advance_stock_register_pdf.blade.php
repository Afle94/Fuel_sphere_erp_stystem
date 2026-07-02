<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advance Stock Register</title>
    @php
        $theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'accent' => '#f59e0b',
            'bgEnd' => '#eef5f3',
        ], $theme ?? []);
        $formatNumber = fn ($value) => $value === null ? '-' : number_format(round((float) $value), 0);
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
        tfoot td { color:#115e59; background:{{ $theme['bgEnd'] }}; font-weight:bold; }
        .number-cell { text-align:right; }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Advance Stock Register</h1>
        <div class="report-meta">
            Product: {{ $selectedProduct }} | Period: {{ $periodLabel }} | Generated on {{ now()->format('d M Y h:i A') }} | Total Entries: {{ $rows->count() }}@if ($search) | Search: {{ $search }}@endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th class="number-cell">Opening Stock</th>
                <th class="number-cell">Receipt</th>
                <th class="number-cell">Total Stock</th>
                <th class="number-cell">Sales By Meters</th>
                <th class="number-cell">Pump Test</th>
                <th class="number-cell">Net Sales By Meters</th>
                <th class="number-cell">Cumulative Sales</th>
                <th class="number-cell">Sales By Dip</th>
                <th class="number-cell">Variation Daily</th>
                <th class="number-cell">Variation Cumm</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $entry)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($entry['date'])->format('d M Y') }}</td>
                    <td class="number-cell">{{ $formatNumber($entry['opening_stock']) }}</td>
                    <td class="number-cell">{{ $formatNumber($entry['receipt']) }}</td>
                    <td class="number-cell">{{ $formatNumber($entry['total_stock']) }}</td>
                    <td class="number-cell">{{ $formatNumber($entry['sales_by_meters']) }}</td>
                    <td class="number-cell">{{ $formatNumber($entry['pump_test']) }}</td>
                    <td class="number-cell">{{ $formatNumber($entry['net_sales_by_meters']) }}</td>
                    <td class="number-cell">{{ $formatNumber($entry['cumulative_sales']) }}</td>
                    <td class="number-cell">{{ $formatNumber($entry['sales_by_dip']) }}</td>
                    <td class="number-cell">{{ $formatNumber($entry['daily_variation']) }}</td>
                    <td class="number-cell">{{ $formatNumber($entry['cumulative_variation']) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td class="number-cell">{{ $formatNumber($totals['opening_stock']) }}</td>
                <td class="number-cell">{{ $formatNumber($totals['receipt']) }}</td>
                <td class="number-cell">{{ $formatNumber($totals['total_stock']) }}</td>
                <td class="number-cell">{{ $formatNumber($totals['sales_by_meters']) }}</td>
                <td class="number-cell">{{ $formatNumber($totals['pump_test']) }}</td>
                <td class="number-cell">{{ $formatNumber($totals['net_sales_by_meters']) }}</td>
                <td class="number-cell"></td>
                <td class="number-cell">{{ $formatNumber($totals['sales_by_dip']) }}</td>
                <td class="number-cell">{{ $formatNumber($totals['daily_variation']) }}</td>
                <td class="number-cell">{{ $formatNumber($totals['cumulative_variation']) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
