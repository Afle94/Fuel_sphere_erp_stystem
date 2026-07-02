<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cash Receipt List</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    @php
        $theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'accent' => '#f59e0b',
        ], $theme ?? []);
        $totalAmount = $cashreceipts->sum(fn ($receipt) => (float) ($receipt->amount ?? 0));
    @endphp
    <style>
        body{margin:0;color:#172033;background:#fff;font-family:DejaVu Sans,Arial,sans-serif;font-size:10px}.report-header{margin-bottom:14px;padding:12px;color:#fff;background:{{ $theme['primaryDark'] }};border-bottom:3px solid {{ $theme['accent'] }}}h1{margin:0 0 4px;color:#fff;font-size:20px}.report-meta{color:#f8fbff;font-size:10px}.summary{margin:0 0 10px;padding:8px;border:1px solid #dce3ee;color:{{ $theme['primaryDark'] }};background:#fff;font-weight:bold;text-align:right}table{width:100%;border-collapse:collapse}th,td{padding:6px 5px;border:1px solid #dce3ee;vertical-align:top}th{color:#fff;background:{{ $theme['primary'] }};font-size:9px;text-align:left}td{background:#fff;font-size:8.5px}.text-right{text-align:right}.empty-state{padding:18px;border:1px solid #dce3ee;color:{{ $theme['primaryDark'] }};background:#fff;font-weight:bold;text-align:center}
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Cash Receipt List</h1>
        <div class="report-meta">
            Date: {{ $selectedDate }} | Generated on {{ now()->format('d M Y h:i A') }} | Total Entries: {{ $cashreceipts->count() }}
        </div>
    </div>

    @if ($cashreceipts->count())
        <div class="summary">Total Amount: {{ number_format($totalAmount, 2) }}</div>
        <table>
            <thead>
                <tr>
                    <th>Slip No.</th>
                    <th>Credit</th>
                    <th>Debit</th>
                    <th class="text-right">Amount</th>
                    <th>Narration</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cashreceipts as $receipt)
                    <tr>
                        <td>{{ $receipt->slip_no }}</td>
                        <td>{{ $receipt->credit }}</td>
                        <td>{{ $receipt->debit }}</td>
                        <td class="text-right">{{ number_format((float) $receipt->amount, 2) }}</td>
                        <td>{{ $receipt->narration ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">No cash receipt entries found.</div>
    @endif
</body>
</html>


