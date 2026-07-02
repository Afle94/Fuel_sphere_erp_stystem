<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Sample List</title>
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
        body{margin:0;color:#172033;background:#fff;font-family:DejaVu Sans,Arial,sans-serif;font-size:8px}.report-header{margin-bottom:10px;padding:10px;color:#fff;background:{{ $theme['primaryDark'] }};border-bottom:3px solid {{ $theme['accent'] }}}h1{margin:0 0 4px;color:#fff;font-size:18px}.report-meta{color:#f8fbff;font-size:9px}table{width:100%;border-collapse:collapse}th,td{padding:4px 3px;border:1px solid #dce3ee;vertical-align:top}th{color:#fff;background:{{ $theme['primary'] }};font-size:7px;text-align:left}td{background:#fff;font-size:6.8px}.text-right{text-align:right}.empty-state{padding:18px;border:1px solid #dce3ee;color:{{ $theme['primaryDark'] }};background:#fff;font-weight:bold;text-align:center}
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Purchase Sample List</h1>
        <div class="report-meta">
            Date: {{ $selectedDate }} | Generated on {{ now()->format('d M Y h:i A') }} | Total Entries: {{ $purchaseSamples->count() }}
        </div>
    </div>

    @if ($purchaseSamples->count())
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Tanker</th>
                    <th>Transport</th>
                    <th>Oil Company</th>
                    <th>Invoice</th>
                    <th>Product</th>
                    <th class="text-right">HSD Temp</th>
                    <th class="text-right">HSD Density</th>
                    <th class="text-right">HSD Value</th>
                    <th>HSD Sample</th>
                    <th>HSD Inv Sample</th>
                    <th>HSD Plastic</th>
                    <th>HSD Aluminium</th>
                    <th class="text-right">MS Temp</th>
                    <th class="text-right">MS Density</th>
                    <th class="text-right">MS Value</th>
                    <th>MS Sample</th>
                    <th>MS Inv Sample</th>
                    <th>MS Plastic</th>
                    <th>MS Aluminium</th>
                    <th class="text-right">PMS Temp</th>
                    <th class="text-right">PMS Density</th>
                    <th class="text-right">PMS Value</th>
                    <th>PMS Sample</th>
                    <th>PMS Inv Sample</th>
                    <th>PMS Plastic</th>
                    <th>PMS Aluminium</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseSamples as $sample)
                    <tr>
                        <td>{{ optional($sample->date)->format('Y-m-d') ?: '-' }}</td>
                        <td>{{ $sample->tanker ?: '-' }}</td>
                        <td>{{ $sample->transport ?: '-' }}</td>
                        <td>{{ $sample->oil_company ?: '-' }}</td>
                        <td>{{ $sample->invoice_no ?: '-' }}</td>
                        <td>{{ $sample->product ?: '-' }}</td>
                        <td class="text-right">{{ number_format((float) $sample->hsd_temp, 2) }}</td>
                        <td class="text-right">{{ number_format((float) $sample->hsd_base_density, 4) }}</td>
                        <td class="text-right">{{ number_format((float) $sample->hsd_value, 4) }}</td>
                        <td>{{ $sample->hsd_sample ?: '-' }}</td>
                        <td>{{ $sample->hsd_invoice_sample ?: '-' }}</td>
                        <td>{{ $sample->hsd_plastic_seal ?: '-' }}</td>
                        <td>{{ $sample->hsd_aluminium_seal ?: '-' }}</td>
                        <td class="text-right">{{ number_format((float) $sample->ms_temp, 2) }}</td>
                        <td class="text-right">{{ number_format((float) $sample->ms_base_density, 4) }}</td>
                        <td class="text-right">{{ number_format((float) $sample->ms_value, 4) }}</td>
                        <td>{{ $sample->ms_sample ?: '-' }}</td>
                        <td>{{ $sample->ms_invoice_sample ?: '-' }}</td>
                        <td>{{ $sample->ms_plastic_seal ?: '-' }}</td>
                        <td>{{ $sample->ms_aluminium_seal ?: '-' }}</td>
                        <td class="text-right">{{ number_format((float) $sample->power_ms_temp, 2) }}</td>
                        <td class="text-right">{{ number_format((float) $sample->power_ms_base_density, 4) }}</td>
                        <td class="text-right">{{ number_format((float) $sample->power_ms_value, 4) }}</td>
                        <td>{{ $sample->power_ms_sample ?: '-' }}</td>
                        <td>{{ $sample->power_ms_invoice_sample ?: '-' }}</td>
                        <td>{{ $sample->power_ms_plastic_seal ?: '-' }}</td>
                        <td>{{ $sample->power_ms_aluminium_seal ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">No purchase sample entries found.</div>
    @endif
</body>
</html>
