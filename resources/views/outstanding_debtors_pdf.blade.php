<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Outstanding Debtors</title>
    @php
        $theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'accent' => '#f59e0b',
        ], $theme ?? []);
    @endphp
    <style>
        body{margin:0;color:#172033;background:#fff;font-family:DejaVu Sans,Arial,sans-serif;font-size:10px}.report-header{margin-bottom:12px;padding:12px;color:#fff;background:{{ $theme['primaryDark'] }};border-bottom:3px solid {{ $theme['accent'] }}}h1{margin:0 0 4px;color:#fff;font-size:20px}.report-meta{color:#f8fbff;font-size:10px}.summary{margin:0 0 10px;padding:8px;border:1px solid #dce3ee;color:{{ $theme['primaryDark'] }};background:#fff;font-weight:bold}table{width:100%;border-collapse:collapse}th,td{padding:7px 6px;border:1px solid #dce3ee;vertical-align:top}th{color:#fff;background:{{ $theme['primary'] }};font-size:9px;text-align:left}td{background:#fff;font-size:8.5px}.text-right{text-align:right}.total-row td{font-weight:bold;background:#f8fbff}.empty-state{padding:18px;border:1px solid #dce3ee;color:{{ $theme['primaryDark'] }};background:#fff;font-weight:bold;text-align:center}
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Outstanding Debtors</h1>
        <div class="report-meta">
            Generated on {{ now()->format('d M Y h:i A') }} | Total Entries: {{ $rows->count() }}
        </div>
    </div>

    <div class="summary">
        As On: {{ \Carbon\Carbon::parse($asOnDate)->format('d M Y') }} |
        Total Balance: {{ $totalBalanceLabel }}
    </div>

    @if ($rows->count())
        <table>
            <thead>
                <tr>
                    <th>Particulars</th>
                    <th class="text-right">Balance</th>
                    <th>Location</th>
                    <th>Mobile</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        <td>{{ $row->particulars }}</td>
                        <td class="text-right">{{ $row->balance_label }}</td>
                        <td>{{ $row->location }}</td>
                        <td>{{ $row->mobile }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total</td>
                    <td class="text-right">{{ $totalBalanceLabel }}</td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="empty-state">No outstanding debtors found.</div>
    @endif
</body>
</html>
