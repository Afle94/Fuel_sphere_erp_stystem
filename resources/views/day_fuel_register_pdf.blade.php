<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Day Fuel Sales Register</title>
    @php
        $theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'accent' => '#f59e0b',
            'bgEnd' => '#eef5f3',
        ], $theme ?? []);
    @endphp
    <style>
        body { margin:0; color:#172033; background:#fff; font-family:DejaVu Sans, Arial, sans-serif; font-size:10px; }
        .report-header { margin-bottom:14px; padding:12px; color:#fff; background:{{ $theme['primaryDark'] }}; border-bottom:3px solid {{ $theme['accent'] }}; }
        h1 { margin:0 0 4px; color:#fff; font-size:20px; }
        .report-meta { color:#f8fbff; font-size:10px; }
        table { width:100%; border-collapse:collapse; }
        th,td { padding:6px 5px; border:1px solid #dce3ee; vertical-align:top; }
        th { color:#fff; background:{{ $theme['primary'] }}; font-size:9px; text-align:left; }
        td { background:#fff; font-size:8.5px; }
        .number-cell { text-align:right; }
        .total-row td { color:#fff; background:{{ $theme['primaryDark'] }}; font-weight:bold; }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Day Fuel Sales Register</h1>
        <div class="report-meta">
            Period: {{ $periodLabel }} | Generated on {{ now()->format('d M Y h:i A') }} | Total Entries: {{ $entries->count() }}@if ($search) | Search: {{ $search }}@endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Date</th>
                <th>Nozzle Name</th>
                <th>Item</th>
                <th class="number-cell">Opening Reading</th>
                <th class="number-cell">Closing Reading</th>
                <th class="number-cell">Test</th>
                <th class="number-cell">Quantity</th>
                <th class="number-cell">Rate</th>
                <th class="number-cell">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entries as $entry)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional($entry->date)->format('d M Y') ?: '-' }}</td>
                    <td>{{ optional($entry->Nozzle)->Nozzle_Name ?: '-' }}</td>
                    <td>{{ $entry->items ?: '-' }}</td>
                    <td class="number-cell">{{ number_format((float) $entry->open, 2) }}</td>
                    <td class="number-cell">{{ number_format((float) $entry->close, 2) }}</td>
                    <td class="number-cell">{{ number_format((float) $entry->Test, 2) }}</td>
                    <td class="number-cell">{{ number_format((float) $entry->Quantity, 2) }}</td>
                    <td class="number-cell">{{ number_format((float) $entry->rate, 2) }}</td>
                    <td class="number-cell">{{ number_format((float) $entry->Amount, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="7">Total</td>
                <td class="number-cell">{{ number_format((float) $entries->sum('Quantity'), 2) }}</td>
                <td></td>
                <td class="number-cell">{{ number_format((float) $entries->sum('Amount'), 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
