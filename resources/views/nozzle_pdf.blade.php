<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nozzle Master List</title>
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
            background: #ffffff;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
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
        <h1>Nozzle Master List</h1>
        <div class="report-meta">
            Generated on {{ now()->format('d M Y h:i A') }} | Total Nozzles: {{ $nozzles->count() }}
        </div>
    </div>

    @if ($nozzles->count())
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nozzle Name</th>
                    <th>Item</th>
                    <th>Open Date</th>
                    <th>Close Date</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($nozzles as $nozzle)
                    <tr>
                        <td>{{ $nozzle->id }}</td>
                        <td>{{ $nozzle->Nozzle_Name }}</td>
                        <td>{{ $nozzle->Item }}</td>
                        <td>{{ optional($nozzle->Open_Date)->format('d M Y') ?: '-' }}</td>
                        <td>{{ optional($nozzle->Close_Date)->format('d M Y') ?: '-' }}</td>
                        <td>{{ optional($nozzle->created_at)->format('d M Y') ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">No nozzles found.</div>
    @endif
</body>
</html>


