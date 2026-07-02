<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Day Book Register</title>
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
        body { margin: 0; color: #172033; background: #fff; font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; }
        .report-header { margin-bottom: 14px; padding: 12px; color: #fff; background: {{ $theme['primaryDark'] }}; border-bottom: 3px solid {{ $theme['accent'] }}; }
        h1 { margin: 0 0 4px; color: #fff; font-size: 20px; }
        .report-meta { color: #f8fbff; font-size: 10px; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 6px 5px; border: 1px solid #dce3ee; vertical-align: top; }
        th { color: #fff; background: {{ $theme['primary'] }}; font-size: 9px; text-align: left; }
        td { background: #fff; font-size: 9.5px; }
        
        .text-right { text-align: right; }
        .text-strong { font-weight: bold; }
        
        .layout-table { border: none; margin-bottom: 14px; }
        .layout-table > tbody > tr > td { border: none; padding: 0 8px; vertical-align: top; }
        
        .summary-box { padding: 10px 14px; border: 1px solid {{ $theme['primary'] }}; border-radius: 4px; font-size: 12px; font-weight: bold; margin-bottom: 14px; }
        .opening-box { background: #fff9fa; border-color: #fca5a5; }
        .closing-box { background: #fce7f3; border-color: #f472b6; color: #9d174d; }
        
        .amount-display { float: right; display: inline-block; min-width: 120px; text-align: right; background: #fffdec; padding: 2px 8px; border: 1px solid #cbd5e1; color: #172033; }
        
        .data-table th { background: linear-gradient(135deg, {{ $theme['primaryDark'] }}, {{ $theme['primary'] }}); }
        .data-table td { height: 16px; }
        
        .row-highlight { background: #f8fafc; }
    </style>
</head>
<body>

    <div class="report-header">
        <h1>Day Book Register</h1>
        <div class="report-meta">
            {{ $periodLabel }} | Generated on {{ now()->format('d-m-Y h:i A') }}
        </div>
    </div>

    <div class="summary-box opening-box">
        Opening Cash 
        <span class="amount-display">{{ number_format($dayBookData['Opening Cash'], 2, '.', '') }}</span>
    </div>

    <table class="layout-table">
        <tr>
            <td width="50%" style="padding-left: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th colspan="2" style="font-size: 10px;">Transaction Summary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-strong">Day Fuel Sale</td>
                            <td class="text-right">{{ number_format($dayBookData['Day Fuel Sale'], 2, '.', '') }}</td>
                        </tr>
                        <tr class="row-highlight">
                            <td class="text-strong">Credit Sales</td>
                            <td class="text-right">{{ number_format($dayBookData['Credit Sales'], 2, '.', '') }}</td>
                        </tr>
                        <tr>
                            <td class="text-strong">Cash Sales</td>
                            <td class="text-right">{{ number_format($dayBookData['Cash Sales'], 2, '.', '') }}</td>
                        </tr>
                        <tr class="row-highlight">
                            <td class="text-strong">Cash Receipt</td>
                            <td class="text-right" style="color: #047857; font-weight: bold;">{{ number_format($dayBookData['Cash Receipt'], 2, '.', '') }}</td>
                        </tr>
                        <tr>
                            <td class="text-strong">Cheque Receipt</td>
                            <td class="text-right">{{ number_format($dayBookData['Cheque Receipt'], 2, '.', '') }}</td>
                        </tr>
                        <tr class="row-highlight">
                            <td class="text-strong">Cash Payment</td>
                            <td class="text-right" style="color: #be123c; font-weight: bold;">{{ number_format($dayBookData['Cash Payment'], 2, '.', '') }}</td>
                        </tr>
                        <tr>
                            <td class="text-strong">Cheque Payment</td>
                            <td class="text-right">{{ number_format($dayBookData['Cheque Payment'], 2, '.', '') }}</td>
                        </tr>
                        <tr class="row-highlight">
                            <td class="text-strong">Purchase</td>
                            <td class="text-right">{{ number_format($dayBookData['Purchase'], 2, '.', '') }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>

            <td width="50%" style="padding-right: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="font-size: 10px;">Item Name</th>
                            <th class="text-right" style="font-size: 10px;">Quantity</th>
                            <th class="text-right" style="font-size: 10px;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rowCount = 0; @endphp
                        @foreach($dayBookData['ItemsMatrix'] as $itemName => $itemData)
                            @php $rowCount++; @endphp
                            <tr>
                                <td class="text-strong" style="color: {{ $theme['primaryDark'] }};">{{ $itemName }}</td>
                                <td class="text-right">{{ number_format($itemData['quantity'], 2) }}</td>
                                <td class="text-right text-strong">{{ number_format($itemData['amount'], 2) }}</td>
                            </tr>
                        @endforeach

                        @for($i = $rowCount; $i < 8; $i++)
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div class="summary-box closing-box">
        Closing Cash 
        <span class="amount-display" style="background: #fdf2f8; border-color: #f472b6;">
            {{ number_format($dayBookData['Closing Cash'], 2, '.', '') }}
        </span>
    </div>

</body>
</html>