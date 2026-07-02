<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Master List</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    @php
        $theme = array_merge([
            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'accent' => '#f59e0b',
            'bgEnd' => '#eef5f3',
        ], $theme ?? []);
    @endphp
    <style>
        body {
            margin: 0;
            color: #172033;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            background: #ffffff;
        }

        .report-header {
            margin-bottom: 14px;
            padding: 12px;
            color: #ffffff;
            background: {{ $theme['primaryDark'] }};
            border-bottom: 3px solid {{ $theme['accent'] }};
        }

        h1 {
            margin: 0 0 4px;
            color: #ffffff;
            font-size: 20px;
        }

        .report-meta {
            color: #f8fbff;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 6px 5px;
            border: 1px solid #dce3ee;
            vertical-align: top;
        }

        th {
            color: #ffffff;
            background: {{ $theme['primary'] }};
            font-size: 9px;
            text-align: left;
        }

        td {
            background: #ffffff;
            font-size: 8.5px;
        }

        tr:nth-child(even) td {
            background: #ffffff;
        }

        .text-right {
            text-align: right;
        }

        .empty-state {
            padding: 18px;
            border: 1px solid #dce3ee;
            color: {{ $theme['primaryDark'] }};
            background: #ffffff;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Account Master List</h1>
        <div class="report-meta">
            Generated on {{ now()->format('d M Y h:i A') }} | Total Accounts: {{ $pdf->count() }}
        </div>
    </div>

    @if ($pdf->count())
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Account Name</th>
                    <th>Under Group</th>
                    <th>Opening Balance</th>
                    <th>Type</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Phone</th>
                    <th>GST No.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pdf as $account)
                    <tr>
                        <td>{{ $account->id }}</td>
                        <td>{{ $account->account_perticular }}</td>
                        <td>{{ $account->under_group }}</td>
                        <td class="text-right">{{ number_format((float) $account->opening_balance, 2) }}</td>
                        <td>{{ $account->transaction_type }}</td>
                        <td>{{ $account->address ?: '-' }}</td>
                        <td>{{ $account->city ?: '-' }}</td>
                        <td>{{ $account->state ?: '-' }}</td>
                        <td>{{ $account->email ?: '-' }}</td>
                        <td>{{ $account->mobile_number ?: '-' }}</td>
                        <td>{{ $account->phone_number ?: '-' }}</td>
                        <td>{{ $account->gst_number ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">No accounts found.</div>
    @endif
</body>
</html>


