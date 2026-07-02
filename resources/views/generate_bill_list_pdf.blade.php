<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Saved Bills List</title>
    @php
        $theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'accent' => '#f59e0b',
            'bgEnd' => '#eef5f3',
        ], $theme ?? []);
    @endphp
    <style>
        body{margin:0;color:#172033;background:#fff;font-family:DejaVu Sans,Arial,sans-serif;font-size:10px}.report-header{margin-bottom:14px;padding:12px;color:#fff;background:{{ $theme['primaryDark'] }};border-bottom:3px solid {{ $theme['accent'] }}}h1{margin:0 0 4px;color:#fff;font-size:20px}.report-meta{color:#f8fbff;font-size:10px}table{width:100%;border-collapse:collapse}th,td{padding:6px 5px;border:1px solid #dce3ee;vertical-align:top}th{color:#fff;background:{{ $theme['primary'] }};font-size:9px;text-align:left}td{background:#fff;font-size:8.5px}.number-cell{text-align:right}.empty-state{padding:18px;border:1px solid #dce3ee;color:{{ $theme['primaryDark'] }};background:#fff;font-weight:bold;text-align:center}
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Saved Bills List</h1>
        <div class="report-meta">
            Generated on {{ now()->format('d M Y h:i A') }} | Total Bills: {{ $bills->count() }}@if ($search) | Search: {{ $search }}@endif
        </div>
    </div>

    @if ($bills->count())
        <table>
            <thead>
                <tr>
                    <th>Bill No</th>
                    <th>Bill Date</th>
                    <th>Party</th>
                    <th>Vehicle No</th>
                    <th>Date From</th>
                    <th>Date To</th>
                    <th>Slips</th>
                    <th>Total</th>
                    <th>Saved On</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bills as $bill)
                    @forelse ($bill->items as $item)
                        <tr>
                            <td>{{ $bill->bill_no ?: '-' }}</td>
                            <td>{{ optional($bill->bill_date)->format('d/m/Y') ?: '-' }}</td>
                            <td>{{ $bill->party ?: '-' }}</td>
                            <td>{{ $item->vehicle_no ?: '-' }}</td>
                            <td>{{ optional($bill->date_from)->format('d/m/Y') ?: '-' }}</td>
                            <td>{{ optional($bill->date_to)->format('d/m/Y') ?: '-' }}</td>
                            <td class="number-cell">1</td>
                            <td class="number-cell">{{ number_format((float) $item->amount, 2) }}</td>
                            <td>{{ optional($bill->created_at)->format('d/m/Y') ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>{{ $bill->bill_no ?: '-' }}</td>
                            <td>{{ optional($bill->bill_date)->format('d/m/Y') ?: '-' }}</td>
                            <td>{{ $bill->party ?: '-' }}</td>
                            <td>{{ $bill->vehicle_no ?: '-' }}</td>
                            <td>{{ optional($bill->date_from)->format('d/m/Y') ?: '-' }}</td>
                            <td>{{ optional($bill->date_to)->format('d/m/Y') ?: '-' }}</td>
                            <td class="number-cell">{{ $bill->items_count }}</td>
                            <td class="number-cell">{{ number_format((float) $bill->total_amount, 2) }}</td>
                            <td>{{ optional($bill->created_at)->format('d/m/Y') ?: '-' }}</td>
                        </tr>
                    @endforelse
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">No bills found.</div>
    @endif
</body>
</html>
