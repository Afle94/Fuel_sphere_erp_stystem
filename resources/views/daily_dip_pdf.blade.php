<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dip Chart</title>
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
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Dip Chart</h1>
        <div class="report-meta">
            Item: {{ $selectedItem }} | Period: {{ $periodLabel }} | Generated on {{ now()->format('d M Y h:i A') }} | Total Entries: {{ $entries->count() }}@if ($search) | Search: {{ $search }}@endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Date</th>
                <th>Item</th>
                <th class="number-cell">Enter Depth</th>
                <th class="number-cell">Liter</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entries as $entry)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $entry->date ? \Carbon\Carbon::parse($entry->date)->format('d M Y') : '-' }}</td>
                    <td>{{ $entry->item ?: '-' }}</td>
                    <td class="number-cell">{{ rtrim(rtrim(number_format((float) $entry->{$depthColumn}, 2, '.', ''), '0'), '.') }}</td>
                    <td class="number-cell">{{ (int) (float) $entry->{$literColumn} }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
