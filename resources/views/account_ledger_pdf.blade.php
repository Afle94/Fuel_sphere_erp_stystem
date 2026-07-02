<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Ledger</title>
    @php
        $theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'accent' => '#f59e0b',
        ], $theme ?? []);
    @endphp
    <style>
        body{margin:0;color:#172033;background:#fff;font-family:DejaVu Sans,Arial,sans-serif;font-size:10px}.report-header{margin-bottom:12px;padding:12px;color:#fff;background:{{ $theme['primaryDark'] }};border-bottom:3px solid {{ $theme['accent'] }}}h1{margin:0 0 4px;color:#fff;font-size:20px}.report-meta{color:#f8fbff;font-size:10px}.summary{margin:0 0 10px;padding:8px;border:1px solid #dce3ee;color:{{ $theme['primaryDark'] }};background:#fff;font-weight:bold}table{width:100%;border-collapse:collapse}th,td{padding:6px 5px;border:1px solid #dce3ee;vertical-align:top}th{color:#fff;background:{{ $theme['primary'] }};font-size:9px;text-align:left}td{background:#fff;font-size:8.5px}.text-right{text-align:right}.empty-state{padding:18px;border:1px solid #dce3ee;color:{{ $theme['primaryDark'] }};background:#fff;font-weight:bold;text-align:center}
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Account Ledger</h1>
        <div class="report-meta">
            Generated on {{ now()->format('d M Y h:i A') }} | Total Entries: {{ $rows->count() }}
        </div>
    </div>

    <div class="summary">
        Particular: {{ $accountParticular }} |
        Under Group: {{ $underGroup }} |
        From: {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} |
        To: {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}
    </div>

    @if ($rows->count())
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Particular</th>
                    <th>Vehicle No</th>
                    <th class="text-right">Debit</th>
                    <th class="text-right">Credit</th>
                    <th class="text-right">Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        <td>{{ $row->TRANDATE ? \Carbon\Carbon::parse($row->TRANDATE)->format('d M Y') : '-' }}</td>
                        <td>{{ $row->particular_label ?? '-' }}</td>
                        <td>{{ $row->vehicle_no_label ?? '-' }}</td>
                        <td class="text-right">{{ $row->debit > 0 ? number_format($row->debit, 2) : '-' }}</td>
                        <td class="text-right">{{ $row->credit > 0 ? number_format($row->credit, 2) : '-' }}</td>
                        <td class="text-right">{{ $row->balance_label ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">No account ledger records found.</div>
    @endif
</body>
</html>
