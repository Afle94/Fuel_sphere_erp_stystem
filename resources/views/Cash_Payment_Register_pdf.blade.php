<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cash Payment Register</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    @php
        $theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'accent' => '#f59e0b',
        ], $theme ?? []);
    @endphp
    <style>
        body { margin: 0; color: #172033; background: #fff; font-family: DejaVu Sans, Arial, sans-serif; font-size: 9px; }
        .report-header { margin-bottom: 14px; padding: 12px; color: #fff; background: {{ $theme['primaryDark'] }}; border-bottom: 3px solid {{ $theme['accent'] }}; }
        h1 { margin: 0 0 4px; color: #fff; font-size: 20px; }
        .report-meta { color: #f8fbff; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 5px 4px; border: 1px solid #dce3ee; vertical-align: top; }
        th { color: #fff; background: {{ $theme['primary'] }}; font-size: 8.5px; text-align: left; }
        td { background: #fff; font-size: 8px; }
        .text-right { text-align: right; }
        .empty-state { padding: 18px; border: 1px solid #dce3ee; color: {{ $theme['primaryDark'] }}; background: #fff; font-weight: bold; text-align: center; }
        .total-row td { color: #fff; background: {{ $theme['primaryDark'] }}; font-weight: bold; font-size: 8.5px; }
    </style>
</head>

<body>
    <div class="report-header">
        <h1>Cash Payment Register</h1>
        <div class="report-meta">
            Period: {{ $periodLabel ?? 'All Dates' }}
            @if(!empty($search)) | Search: "{{ $search }}" @endif
            | Generated on {{ now()->format('d M Y h:i A') }}
            | Total Entries: {{ $entries->count() }}
        </div>
    </div>

    @if ($entries->count())
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Slip No.</th>
                <th>Debit</th>
                <th>Credit</th>
                <th class="text-right">Amount</th>
                <th>Narration</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entries as $entry)
            <tr>
                <td>{{ $entry->date ? \Carbon\Carbon::parse($entry->date)->format('d M Y') : '-' }}</td>
                <td>{{ $entry->slip_no ?? '-' }}</td>
                <td>{{ $entry->debit ?? '-' }}</td>
                <td>{{ $entry->credit ?? '-' }}</td>
                <td class="text-right">{{ is_numeric($entry->amount) ? number_format((float) $entry->amount, 2) : '-' }}</td>
                <td>{{ $entry->narration ?: '-' }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">Total</td>
                <td class="text-right">{{ number_format((float) $entries->sum('amount'), 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @else
    <div class="empty-state">No cash payment entries found.</div>
    @endif
</body>

</html>