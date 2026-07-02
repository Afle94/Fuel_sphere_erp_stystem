<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $sample['product_label'] }} Sample Preview PDF</title>
    <style>
        @page {
            margin: 8mm;
        }

        body {
            margin: 0;
            color: #172033;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
        }

        .title {
            width: 100%;
            margin-bottom: 5mm;
            border: 1px solid #dce3ee;
            text-align: center;
        }

        .title h1 {
            margin: 8px 0 3px;
            color: {{ $theme['primaryDark'] ?? '#115e59' }};
            font-size: 17px;
        }

        .title p {
            margin: 0 0 8px;
            color: #657089;
            font-size: 10px;
            font-weight: bold;
        }

        .outer {
            width: 100%;
            border-collapse: collapse;
        }

        .outer-cell {
            width: 50%;
            padding: 4px;
            vertical-align: top;
        }

        .card {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #dce3ee;
        }

        .card-head {
            color: #ffffff;
            background-color: {{ $theme['primary'] ?? '#0f766e' }};
            font-size: 12px;
            font-weight: bold;
        }

        .card-head td {
            padding: 7px;
        }

        .copy-label {
            text-align: right;
            font-size: 9px;
        }

        .field-label {
            width: 38%;
            padding: 5px;
            border: 1px solid #dce3ee;
            color: #657089;
            background-color: #f8fafc;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .field-value {
            width: 62%;
            padding: 5px;
            border: 1px solid #dce3ee;
            color: #172033;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @php
        $fields = [
            ['Date', $sample['date']],
            ['Tanker', $sample['tanker']],
            ['Transport', $sample['transport']],
            ['Oil Company', $sample['oil_company']],
            ['Invoice No.', $sample['invoice_no']],
            ['Product', $sample['product'] ?: $sample['product_label']],
            ['Temperature', $sample['temp'] !== '' && $sample['temp'] !== null ? number_format((float) $sample['temp'], 2) : ''],
            ['Base Density', $sample['base_density'] !== '' && $sample['base_density'] !== null ? number_format((float) $sample['base_density'], 4) : ''],
            ['Chart Value', $sample['value'] !== '' && $sample['value'] !== null ? number_format((float) $sample['value'], 4) : ''],
            ['Sample', $sample['sample']],
            ['Invoice Sample', $sample['invoice_sample']],
            ['Plastic Seal', $sample['plastic_seal']],
            ['Aluminium Seal', $sample['aluminium_seal']],
        ];
    @endphp

    <div class="title">
        <h1>{{ $sample['product_label'] }} Sample Preview</h1>
        <p>Date: {{ $sample['date'] ?: '-' }} | Ref No: {{ $sample['ref_no'] ?: '-' }} | Tanker: {{ $sample['tanker'] ?: '-' }} | Invoice: {{ $sample['invoice_no'] ?: '-' }}</p>
    </div>

    <table class="outer">
        @foreach ([[1, 2], [3, 4]] as $row)
            <tr>
                @foreach ($row as $copy)
                    <td class="outer-cell">
                        <table class="card">
                            <tr class="card-head">
                                <td>{{ $sample['product_label'] }} Sample</td>
                                <td class="copy-label">Preview {{ $copy }}</td>
                            </tr>
                            @foreach ($fields as [$label, $value])
                                <tr>
                                    <td class="field-label">{{ $label }}</td>
                                    <td class="field-value">{{ $value !== '' && $value !== null ? $value : '-' }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
</body>
</html>
