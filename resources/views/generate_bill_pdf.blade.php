<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill {{ $bill->bill_no ?: $bill->id }}</title>
    <style>
        body {
            margin: 0;
            color: #172033;
            background: #ffffff;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .outer {
            border: 1px solid #dce3ee;
        }

        .header td {
            padding: 12px;
            color: #ffffff;
            background: #0f766e;
            vertical-align: top;
        }

        .kicker {
            color: #d6fffb;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .company {
            margin-top: 4px;
            color: #ffffff;
            font-size: 22px;
            line-height: 1.1;
            font-weight: bold;
            text-transform: uppercase;
        }

        .company-meta {
            margin-top: 6px;
            color: #efffff;
            font-size: 8px;
            line-height: 1.35;
            font-weight: bold;
        }

        .invoice-badge {
            width: 135px;
            border-collapse: collapse;
        }

        .invoice-badge td {
            padding: 7px 8px;
            border: 1px solid #9be7df;
            color: #ffffff;
            text-align: right;
        }

        .badge-label {
            color: #d6fffb;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-number {
            color: #ffffff;
            font-size: 22px;
            line-height: 1;
            font-weight: bold;
        }

        .badge-date {
            color: #efffff;
            font-size: 8px;
            font-weight: bold;
        }

        .pad {
            padding: 8px;
        }

        .summary td,
        .info td,
        .footer td {
            padding: 8px;
            border: 1px solid #dce3ee;
            vertical-align: top;
        }

        .section-label {
            color: #115e59;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .strong {
            color: #172033;
            font-size: 12px;
            line-height: 1.25;
            font-weight: bold;
        }

        .party {
            color: #172033;
            font-size: 15px;
            line-height: 1.25;
            font-weight: bold;
        }

        .muted {
            color: #657089;
            font-size: 9px;
            line-height: 1.35;
            font-weight: bold;
        }

        .details td {
            padding: 4px 0;
            border: 0;
            border-bottom: 1px solid #dce3ee;
            font-size: 9px;
        }

        .details .right {
            text-align: right;
            font-weight: bold;
        }

        .items th,
        .items td {
            padding: 6px;
            border: 1px solid #dce3ee;
            vertical-align: top;
        }

        .items th {
            color: #ffffff;
            background: #0f766e;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .items td {
            color: #172033;
            font-size: 9px;
        }

        .number-cell {
            text-align: right;
            white-space: nowrap;
        }

        .grand-total td {
            padding: 8px;
            border: 0;
            color: #ffffff;
            background: #0f766e;
        }

        .grand-total .small {
            color: #d6fffb;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .grand-total .amount {
            color: #ffffff;
            font-size: 20px;
            line-height: 1.1;
            font-weight: bold;
        }

        .signature {
            margin-top: 14px;
            color: #172033;
            font-size: 9px;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    @php
        $companyName = $companyInformation->company_name ?? 'FuelTracker';
        $office = $companyInformation->registered_office ?? '';
        $phone = $companyInformation->phone_no ?? '';
        $mobile = $companyInformation->mobile_no ?? '';
        $email = $companyInformation->email_id ?? '';
        $gstNo = $companyInformation->gst_no ?? '';
        $invoiceDate = optional($bill->bill_date)->format('d/m/Y') ?: optional($bill->created_at)->format('d/m/Y') ?: now()->format('d/m/Y');
        $dateFrom = optional($bill->date_from)->format('d/m/Y') ?: '-';
        $dateTo = optional($bill->date_to)->format('d/m/Y') ?: '-';
    @endphp

    <table class="outer">
        <tr class="header">
            <td>
                <div class="kicker">Tax Invoice</div>
                <div class="company">{{ $companyName }}</div>
                <div class="company-meta">
                    @if ($gstNo) GST No.: {{ $gstNo }} @endif
                    @if ($gstNo && $office) &nbsp; | &nbsp; @endif
                    @if ($office) {{ $office }} @endif
                    @if ($email || $mobile || $phone)
                        <br>
                        @if ($email) Email: {{ $email }} @endif
                        @if ($email && ($mobile || $phone)) &nbsp; | &nbsp; @endif
                        @if ($mobile || $phone) Phone: {{ $mobile ?: $phone }} @endif
                    @endif
                </div>
            </td>
            <td style="width: 150px;">
                <table class="invoice-badge">
                    <tr>
                        <td>
                            <div class="badge-label">Invoice No.</div>
                            <div class="badge-number">{{ $bill->bill_no ?: $bill->id }}</div>
                            <div class="badge-date">{{ $invoiceDate }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="pad">
                <table class="summary">
                    <tr>
                        <td style="width: 25%;">
                            <div class="section-label">Party</div>
                            <div class="strong">{{ $bill->party ?: '-' }}</div>
                        </td>
                        <td style="width: 25%;">
                            <div class="section-label">Bill Period</div>
                            <div class="strong">{{ $dateFrom }} to {{ $dateTo }}</div>
                        </td>
                        <td style="width: 25%;">
                            <div class="section-label">Total Slips</div>
                            <div class="strong">{{ $bill->total_slips }}</div>
                        </td>
                        <td style="width: 25%;">
                            <div class="section-label">Amount</div>
                            <div class="strong">{{ number_format((float) $bill->total_amount, 2) }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="pad">
                <table class="info">
                    <tr>
                        <td style="width: 62%;">
                            <div class="section-label">Billed To</div>
                            <div class="party">{{ $bill->party ?: '-' }}</div>
                            <div class="muted">State: MADHYA PRADESH &nbsp; | &nbsp; Code: 23</div>
                        </td>
                        <td style="width: 38%;">
                            <div class="section-label">Bill Details</div>
                            <table class="details">
                                <tr>
                                    <td>Period</td>
                                    <td class="right">{{ $dateFrom }} to {{ $dateTo }}</td>
                                </tr>
                                <tr>
                                    <td>Vehicle</td>
                                    <td class="right">{{ $bill->vehicle_no ?: 'All Vehicles' }}</td>
                                </tr>
                                <tr>
                                    <td>Total Slips</td>
                                    <td class="right">{{ $bill->total_slips }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="pad">
                <table class="items">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Date</th>
                            <th style="width: 12%;">Vehicle No.</th>
                            <th style="width: 9%;">Slip No.</th>
                            <th style="width: 25%;">Item/Particulars</th>
                            <th style="width: 11%;">HSN Code</th>
                            <th style="width: 10%;" class="number-cell">Qty</th>
                            <th style="width: 10%;" class="number-cell">Rate</th>
                            <th style="width: 13%;" class="number-cell">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bill->items as $item)
                            <tr>
                                <td>{{ optional($item->bill_date)->format('d/m/Y') ?: '-' }}</td>
                                <td>{{ $item->vehicle_no ?: '-' }}</td>
                                <td>{{ $item->slip_no ?: '-' }}</td>
                                <td>{{ $item->item_name ?: '-' }}</td>
                                <td>{{ $item->hsn_code ?: '-' }}</td>
                                <td class="number-cell">{{ number_format((float) $item->qty, 2) }}</td>
                                <td class="number-cell">{{ number_format((float) $item->rate, 2) }}</td>
                                <td class="number-cell">{{ number_format((float) $item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="pad">
                <table class="footer">
                    <tr>
                        <td style="width: 68%;">
                            <div class="section-label">Amount In Words</div>
                            <div class="strong">Rs. {{ $amountInWords }}</div>
                            <div class="muted">Terms: Interest will be charged if Bill is not paid within 7 days.</div>
                            @foreach ($itemTotals as $itemTotal)
                                <div class="muted">{{ $itemTotal['name'] }} - Qty: {{ number_format((float) $itemTotal['qty'], 2) }} | Amount: {{ number_format((float) $itemTotal['amount'], 2) }}</div>
                            @endforeach
                            <div class="muted">Total Slip: {{ $bill->total_slips }}</div>
                            <div class="strong">(SAVE FUEL)</div>
                        </td>
                        <td style="width: 32%;">
                            <table class="details">
                                <tr>
                                    <td>Total Slips</td>
                                    <td class="right">{{ $bill->total_slips }}</td>
                                </tr>
                                <tr>
                                    <td>Invoice Date</td>
                                    <td class="right">{{ $invoiceDate }}</td>
                                </tr>
                            </table>
                            <table class="grand-total">
                                <tr>
                                    <td>
                                        <div class="small">Total Amount</div>
                                        <div class="amount">{{ number_format((float) $bill->total_amount, 2) }}</div>
                                    </td>
                                </tr>
                            </table>
                            <div class="signature">
                                <div>FOR {{ $companyName }}</div>
                                <br><br>
                                <div>(Authorised Signatory)</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
