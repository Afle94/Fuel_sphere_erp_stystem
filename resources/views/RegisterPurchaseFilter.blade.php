<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Register | FuelTracker</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">

    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">

    <style>
        :root {
            --bg: #f4f7fb;
            --panel: #fff;
            --ink: #172033;
            --muted: #657089;
            --line: #dce3ee;
            --primary: #0f766e;
            --primary-dark: #115e59;
            --shadow: 0 16px 48px rgba(23, 32, 51, .10);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left,
                    rgba(15, 118, 110, .16),
                    transparent 32rem),
                linear-gradient(135deg,
                    #f8fbff 0%,
                    var(--bg) 55%,
                    #eef5f3 100%);
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 20;
            width: 100%;
            background:
                linear-gradient(135deg,
                    rgba(8, 47, 73, .98),
                    rgba(15, 118, 110, .98));
            box-shadow: 0 10px 30px rgba(23, 32, 51, .12);
        }

        .site-header-inner {
            width: 100%;
            min-height: 64px;
            display: grid;
            grid-template-columns:
                minmax(220px, 1fr) auto minmax(220px, 1fr);
            align-items: center;
            gap: 18px;
            margin: 0 auto;
            padding: 0 8px;
        }

        .site-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-size: 21px;
            font-weight: 700;
            text-decoration: none;
        }

        .site-logo-icon {
            display: grid;
            width: 38px;
            height: 38px;
            place-items: center;
            border-radius: 999px;
            background: #fff;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .18);
            overflow: hidden;
            padding: 2px;
        }

        .app-logo-image {
            display: block;
            width: 100%;
            height: 100%;
            border-radius: inherit;
            object-fit: cover;
        }

        .header-title {
            justify-self: center;
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            white-space: nowrap;
        }

        .header-actions {
            display: flex;
            align-items: center;
            justify-self: end;
            gap: 10px;
        }

        .back-link,
        .logout-btn {
            min-height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            border: 1px solid rgba(255, 255, 255, .24);
            border-radius: 8px;
            color: #fff;
            background: rgba(255, 255, 255, .12);
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition:
                background .2s ease,
                transform .2s ease;
        }

        .back-link:hover,
        .logout-btn:hover {
            background: rgba(255, 255, 255, .2);
            transform: translateY(-1px);
        }

        .logout-btn {
            font-family: inherit;
        }

        .dayfuel-register-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 88px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
        }

        .dayfuel-register-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
        }

        .dayfuel-register-page {
            min-width: 0;
            padding: 14px;
        }

        .list-shell {
            display: grid;
            gap: 12px;
        }

        .page-title,
        .list-panel {
            border: 1px solid rgba(220, 227, 238, .86);
            border-radius: 12px;
            background: var(--panel);
            box-shadow: var(--shadow);
        }

        .page-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px;
        }

        .eyebrow {
            margin: 0 0 5px;
            color: var(--primary);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            font-size: 30px;
            line-height: 1.2;
        }

        .record-count {
            flex: 0 0 auto;
            padding: 6px 10px;
            border-radius: 999px;
            color: var(--primary-dark);
            background: rgba(15, 118, 110, .09);
            font-size: 11px;
            font-weight: 700;
        }

        .list-panel {
            overflow: hidden;
        }

        .form-alert {
            margin: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
        }

        .form-alert.success {
            color: #067647;
            background: #ecfdf3;
            border: 1px solid rgba(6, 118, 71, .22);
        }

        .form-alert.error {
            color: #b42318;
            background: #fff1f0;
            border: 1px solid rgba(180, 35, 24, .22);
        }

        .form-alert.is-hiding {
            opacity: 0;
            transform: translateY(-4px);
            transition:
                opacity .25s ease,
                transform .25s ease;
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
        }

        .toolbar-actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .search-form {
            width: min(100%, 1050px);
            display: grid;
            grid-template-columns:
                minmax(180px, 1fr) auto 132px auto 132px 74px 66px 116px;
            align-items: center;
            gap: 8px;
        }

        .search-input,
        .date-input {
            width: 100%;
            min-height: 31px;
            padding: 0 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #fbfcfe;
            font: inherit;
            font-size: 11px;
            outline: none;
        }

        .date-filter-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .date-filter-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--primary-dark);
            white-space: nowrap;
        }

        .date-filter-text {
            font-size: 11px;
            font-weight: 700;
            color: var(--primary-dark);
            white-space: nowrap;
        }

        .search-input:focus,
        .date-input:focus {
            border-color: rgba(15, 118, 110, .52);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, .13);
        }

        .search-btn,
        .reset-btn,
        .new-btn {
            min-height: 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .search-btn,
        .new-btn {
            border: 1px solid transparent;
            color: #fff;
            background:
                linear-gradient(135deg,
                    var(--primary-dark),
                    var(--primary));
        }

        .reset-btn {
            border: 1px solid var(--line);
            color: var(--muted);
            background: #fff;
        }

        .entries-dropdown {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .entries-toggle {
            min-height: 31px;
            min-width: 104px;
            padding: 0 34px 0 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #fff;
            cursor: pointer;
            font: inherit;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
        }

        .entries-dropdown::after {
            content: "";
            position: absolute;
            right: 12px;
            top: 50%;
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 5px solid var(--muted);
            transform: translateY(-40%);
            pointer-events: none;
        }

        .entries-menu {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            z-index: 10;
            display: none;
            min-width: 136px;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 18px 40px rgba(23, 32, 51, .16);
        }

        .entries-dropdown.is-open .entries-menu {
            display: grid;
        }

        .entries-option {
            min-height: 36px;
            padding: 0 12px;
            border: 0;
            color: var(--ink);
            background: #fff;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            text-align: left;
        }

        .entries-option:hover,
        .entries-option:focus {
            color: #fff;
            background:
                linear-gradient(135deg,
                    var(--primary-dark),
                    var(--primary));
            outline: none;
        }

        .entries-option.is-selected {
            font-weight: 700;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1880px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
            font-size: 13px;
            text-align: left;
            vertical-align: middle;
            white-space: nowrap;
        }

        th {
            color: #fff;
            background:
                linear-gradient(135deg,
                    var(--primary-dark),
                    var(--primary));
            font-size: 13px;
            font-weight: 800;
        }

        tbody tr:hover {
            background: rgba(15, 118, 110, .045);
        }

        .sort-link {
            display: inline-flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 6px;
            color: #fff;
            text-decoration: none;
            white-space: nowrap;
        }

        .sort-mark {
            position: relative;
            width: 10px;
            height: 14px;
            flex: 0 0 10px;
            opacity: .72;
        }

        .sort-mark::before,
        .sort-mark::after {
            content: "";
            position: absolute;
            left: 50%;
            width: 0;
            height: 0;
            border-left: 3px solid transparent;
            border-right: 3px solid transparent;
            transform: translateX(-50%);
        }

        .sort-mark::before {
            top: 2px;
            border-bottom: 4px solid rgba(255, 255, 255, .58);
        }

        .sort-mark::after {
            bottom: 2px;
            border-top: 4px solid rgba(255, 255, 255, .58);
        }

        .sort-link.is-active .sort-mark {
            opacity: 1;
        }

        .sort-link.is-active .sort-mark.asc::before {
            border-bottom-color: #fff;
        }

        .sort-link.is-active .sort-mark.desc::after {
            border-top-color: #fff;
        }

        .text-strong {
            font-weight: 700;
        }

        .number-cell {
            text-align: right;
        }

        .date-cell {
            min-width: 120px;
            white-space: nowrap;
        }

        .muted-text {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
        }

        .table-actions {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .mini-btn {
            min-height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
            border: 1px solid transparent;
            border-radius: 8px;
            color: #fff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            cursor: pointer;
            font-family: inherit;
            font-size: 11px;
            font-weight: 800;
            text-decoration: none;
        }

        .mini-btn.secondary {
            color: var(--primary-dark);
            border-color: rgba(15, 118, 110, .22);
            background: rgba(15, 118, 110, .08);
        }

        .detail-row[hidden] {
            display: none;
        }

        .detail-row td {
            padding: 0;
            background: #f8fbff;
        }

        .detail-panel {
            display: grid;
            gap: 10px;
            padding: 14px 16px 16px;
            border-top: 1px solid rgba(15, 118, 110, .18);
            border-bottom: 1px solid rgba(15, 118, 110, .18);
            background: linear-gradient(135deg, rgba(15, 118, 110, .07), #fff);
        }

        .detail-title {
            color: var(--primary-dark);
            font-size: 13px;
            font-weight: 800;
        }

        .detail-table-wrap {
            overflow-x: auto;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
        }

        .detail-table {
            min-width: 1320px;
        }

        .detail-table th {
            font-size: 11px;
        }

        .detail-table td {
            padding: 9px 10px;
            background: #fff;
            font-size: 12px;
        }

        .preview-modal {
            position: fixed;
            inset: 0;
            z-index: 95;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: rgba(15, 23, 42, .58);
        }

        .preview-modal.is-open {
            display: flex;
        }

        .preview-window {
            width: min(1180px, 96vw);
            max-height: 92vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 12px;
            background: #f8fbff;
            box-shadow: 0 26px 80px rgba(15, 23, 42, .30);
        }

        .preview-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            color: #fff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .preview-title {
            font-size: 18px;
            font-weight: 800;
        }

        .preview-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .preview-action-btn,
        .preview-close-btn {
            min-height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 8px;
            color: #fff;
            background: rgba(255, 255, 255, .14);
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
        }

        .preview-body {
            overflow: auto;
            padding: 18px;
        }

        .invoice-preview {
            width: min(960px, 100%);
            min-height: 640px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            box-shadow: 0 12px 34px rgba(23, 32, 51, .12);
        }

        .invoice-company {
            display: grid;
            gap: 4px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--ink);
            text-align: center;
        }

        .invoice-company h2 {
            margin: 0;
            color: var(--primary-dark);
            font-size: 24px;
        }

        .invoice-meta-strip {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px solid var(--ink);
            font-size: 12px;
            font-weight: 800;
        }

        .invoice-title {
            padding: 8px 0;
            border-bottom: 1px solid var(--ink);
            color: var(--primary-dark);
            font-size: 15px;
            font-weight: 900;
            text-align: center;
            text-transform: uppercase;
        }

        .invoice-parties {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            padding: 12px 0;
        }

        .invoice-block {
            display: grid;
            gap: 5px;
            font-size: 12px;
        }

        .invoice-block strong {
            color: var(--primary-dark);
        }

        .invoice-preview table {
            width: 100%;
            min-width: 0;
            border-collapse: collapse;
        }

        .invoice-preview th,
        .invoice-preview td {
            padding: 7px 6px;
            border: 1px solid #1f2937;
            font-size: 11px;
            white-space: normal;
        }

        .invoice-preview th {
            color: #fff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            text-align: center;
        }

        .invoice-preview .number-cell {
            text-align: right;
            white-space: nowrap;
        }

        .invoice-totals {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 280px;
            gap: 16px;
            padding-top: 14px;
        }

        .invoice-total-box {
            border: 1px solid var(--line);
            border-radius: 8px;
            overflow: hidden;
        }

        .invoice-total-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 10px;
            border-bottom: 1px solid var(--line);
            font-size: 12px;
            font-weight: 800;
        }

        .invoice-total-row:last-child {
            border-bottom: 0;
            color: var(--primary-dark);
            background: rgba(15, 118, 110, .08);
        }

        .invoice-note {
            align-self: end;
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
        }

        .empty-state,
        .pagination-bar {
            padding: 16px 18px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        .empty-state {
            text-align: center;
        }

        .pagination-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .pagination-links {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .page-link,
        .page-current {
            min-width: 28px;
            min-height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 8px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .page-link {
            border: 1px solid var(--line);
            color: var(--primary-dark);
            background: #fff;
        }

        .page-link.muted {
            color: var(--muted);
            background: #f6f8fb;
        }

        .page-current {
            color: #fff;
            background: var(--primary);
        }

        @media (max-width:760px) {

            .site-header-inner {
                grid-template-columns: 1fr;
                gap: 8px;
                padding: 10px;
            }

            .header-title {
                font-size: 17px;
            }

            .header-actions {
                justify-self: center;
            }

            .dayfuel-register-workspace.app-shell-with-sidebar {
                width: 100%;
                min-height: calc(100vh - 64px);
                display: block;
                margin: 0;
                border-radius: 0;
            }

            .page-title,
            .toolbar,
            .pagination-bar {
                align-items: stretch;
                flex-direction: column;
            }

            .search-form {
                width: 100%;
                grid-template-columns: 1fr;
            }

            .toolbar-actions {
                width: 100%;
                display: grid;
                grid-template-columns: 1fr;
            }

            .entries-dropdown,
            .entries-toggle {
                width: 100%;
            }

            h1 {
                font-size: 22px;
            }
        }

        @media (max-width: 1024px) {

            .search-form {
                grid-template-columns: 1fr 1fr;
                flex-wrap: wrap;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 760px) {

            .search-form {
                grid-template-columns: 1fr !important;
            }

            .date-filter-text {
                display: none;
            }

            .search-btn,
            .reset-btn,
            .new-btn {
                width: 100%;
            }

            .toolbar-actions {
                width: 100%;
            }

            .entries-dropdown {
                width: 100%;
            }

            .entries-toggle {
                width: 100%;
            }

            .pagination-bar {
                text-align: center;
            }
        }

        @media (max-width: 480px) {

            h1 {
                font-size: 18px;
            }

            .page-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .record-count {
                align-self: flex-start;
            }

            th,
            td {
                font-size: 11px;
                padding: 8px;
            }

            .site-logo span {
                font-size: 16px;
            }

            .header-title {
                font-size: 14px;
                text-align: center;
            }

            .back-link,
            .logout-btn {
                font-size: 10px;
                padding: 6px 10px;
            }
        }
    </style>

    @include('partials.theme')

</head>

<body>

    @php

    $columns = [
    'sr_no' => 'ID',
    'invoice_no' => 'Invoice No.',
    'date' => 'Invoice Date',
    'ref_no' => 'Ref No.',
    'party' => 'Party Name',
    'vehicle_no' => 'Vehicle No.',
    'driver' => 'Driver',
    'transporter' => 'Transport',
    'total_amount' => 'Total Amount',
    'total_cgst_amount' => 'Total CGST Amt.',
    'total_sgst_amount' => 'Total SGST Amt.',
    'total_igst_amount' => 'Total IGST Amt.',
    'amount' => 'Net Amount',
    ];

    $sortUrl = function (string $column) use ($sort, $direction, $search, $perPage) {
    return route('RegisterPurchaseFilter', [
    'search' => $search,
    'from_date' => request('from_date'),
    'to_date' => request('to_date'),
    'sort' => $column,
    'direction' => $sort === $column && $direction === 'asc' ? 'desc' : 'asc',
    'per_page' => $perPage,
    ]);
    };

    $sortMark = fn (string $column) => $sort === $column ? $direction : '';
    $companyName = $companyInformation->company_name ?? 'FuelTracker';
    $companyOffice = $companyInformation->registered_office ?? '';
    $companyPhone = $companyInformation->phone_no ?? '';
    $companyMobile = $companyInformation->mobile_no ?? '';
    $companyEmail = $companyInformation->email_id ?? '';
    $companyGstNo = $companyInformation->gst_no ?? '';
    $purchasePreviewEntries = $entries->getCollection()->mapWithKeys(function ($entry) {
        $items = collect($entry->purchase_items ?? [])->values();

        return [(string) $entry->Ref_no => [
            'ref_no' => (string) ($entry->Ref_no ?? ''),
            'date' => $entry->date ? \Carbon\Carbon::parse($entry->date)->format('d-m-Y') : '-',
            'invoice_no' => (string) ($entry->invoice_no ?? ''),
            'interstate' => (string) ($entry->interstate ?? 'No'),
            'party' => (string) ($entry->perticulars ?? ''),
            'postal_address' => (string) ($entry->{'postal address'} ?? data_get($items->first(), 'postal address', '')),
            'location' => (string) ($entry->location ?? data_get($items->first(), 'location', '')),
            'subtotal' => (float) ($entry->amount ?? 0),
            'discount' => (float) ($entry->discountinrs ?? 0),
            'taxable' => (float) ($entry->taxable_amount ?? 0),
            'tax' => (float) ($entry->total_tax_amount ?? 0),
            'total' => (float) ($entry->total_amount ?? 0),
            'pdf_url' => route('RegisterPurchaseFilter.reference.pdf', array_merge(request()->query(), ['refNo' => $entry->Ref_no])),
            'excel_url' => route('RegisterPurchaseFilter.reference.excel', array_merge(request()->query(), ['refNo' => $entry->Ref_no])),
            'items' => $items->map(fn ($item) => [
                'item_name' => (string) ($item->item_name ?? ''),
                'quantity' => (float) ($item->quantity ?? 0),
                'rate' => (float) ($item->rate ?? 0),
                'discount_percent' => (float) ($item->{'discount%'} ?? 0),
                'taxable_amount' => (float) ($item->taxable_amount ?? 0),
                'cgst' => (float) ($item->cgst ?? 0),
                'sgst' => (float) ($item->sgst ?? 0),
                'igst' => (float) ($item->igst ?? 0),
                'total_tax_amount' => (float) ($item->total_tax_amount ?? 0),
                'total_amount' => (float) ($item->total_amount ?? 0),
            ])->all(),
        ]];
    });

    @endphp

    <header class="site-header">

        <div class="site-header-inner">

            <a
                href="{{ url('/dashboard') }}"
                class="site-logo"
                aria-label="FuelTracker dashboard">

                <span class="site-logo-icon" aria-hidden="true">

                    <img
                        src="{{ asset('images/fueltracker-logo.jpeg') }}"
                        alt=""
                        class="app-logo-image">

                </span>

                <span>FuelTracker</span>

            </a>

            <div class="header-title">
                Purchase Register
            </div>

            <div class="header-actions">

                <a
                    href="{{ url('/dashboard') }}"
                    class="back-link">

                    Dashboard

                </a>

                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="logout-btn">

                        Logout

                    </button>

                </form>

            </div>

        </div>

    </header>

    <div
        class="app-shell-with-sidebar dayfuel-register-workspace"
        id="dashboardPage">

        @include('partials.fueltracker-menu')

        <main class="dayfuel-register-page">

            <div class="list-shell">

                <section
                    class="page-title"
                    aria-labelledby="purchaseRegisterTitle">

                    <div>

                        <p class="eyebrow">
                            Registers
                        </p>

                        <h1 id="purchaseRegisterTitle">
                            Purchase Register
                        </h1>

                    </div>

                    <span class="record-count">

                        {{ $entries->total() }}

                        {{ $entries->total() === 1 ? 'record' : 'records' }}

                    </span>

                </section>

                <section class="list-panel">

                    @if (session('success'))

                    <div class="form-alert success">

                        {{ session('success') }}

                    </div>

                    @endif

                    @if (session('error'))

                    <div class="form-alert error">

                        {{ session('error') }}

                    </div>

                    @endif

                    <div class="toolbar">

                        <form
                            class="search-form"
                            method="GET"
                            action="{{ route('RegisterPurchaseFilter') }}">

                            <input
                                type="hidden"
                                name="sort"
                                value="{{ $sort }}">

                            <input
                                type="hidden"
                                name="direction"
                                value="{{ $direction }}">

                            <input
                                class="search-input"
                                type="search"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Search sr no, ref no, party, item...">

                            <span class="date-filter-text">
                                From
                            </span>

                            <input
                                class="date-input"
                                type="date"
                                name="from_date"
                                value="{{ request('from_date') }}"
                                aria-label="From date">

                            <span class="date-filter-text">
                                To
                            </span>

                            <input
                                class="date-input"
                                type="date"
                                name="to_date"
                                value="{{ request('to_date') }}"
                                aria-label="To date">

                            <button
                                type="submit"
                                class="search-btn">

                                Search

                            </button>

                            <a
                                href="{{ route('RegisterPurchaseFilter') }}"
                                class="reset-btn">

                                Clear

                            </a>

                            <div class="entries-dropdown">

                                <input
                                    type="hidden"
                                    name="per_page"
                                    value="{{ $perPage }}">

                                <button
                                    class="entries-toggle"
                                    type="button"
                                    aria-haspopup="listbox"
                                    aria-expanded="false">

                                    {{ $perPage }} Entries

                                </button>

                                <div
                                    class="entries-menu"
                                    role="listbox">

                                    @foreach ($perPageOptions as $option)

                                    <button
                                        class="entries-option {{ $perPage === $option ? 'is-selected' : '' }}"
                                        type="button"
                                        role="option"
                                        aria-selected="{{ $perPage === $option ? 'true' : 'false' }}"
                                        data-per-page="{{ $option }}">

                                        {{ $option }} Entries

                                    </button>

                                    @endforeach

                                </div>

                            </div>

                        </form>

                        <div class="toolbar-actions">

                            @if ($entries->count())

                            <a
                                href="{{ route('RegisterPurchaseFilter.pdf', request()->query()) }}"
                                class="new-btn"
                                target="_blank"
                                rel="noopener"
                                data-themed-export>

                                PDF

                            </a>

                            <a
                                href="{{ route('RegisterPurchaseFilter.excel', request()->query()) }}"
                                class="new-btn"
                                data-themed-export>

                                Excel

                            </a>

                            @endif

                        </div>

                    </div>

                    @if ($entries->count())

                    <div class="table-wrap">

                        <table>

                            <thead>

                                <tr>

                                    @foreach ($columns as $column => $label)

                                    <th class="{{ in_array($column, ['total_amount', 'total_cgst_amount', 'total_sgst_amount', 'total_igst_amount', 'amount'], true) ? 'number-cell' : '' }}">

                                        <a
                                            class="sort-link {{ $sort === $column ? 'is-active' : '' }}"
                                            href="{{ $sortUrl($column) }}">

                                            <span>{{ $label }}</span>

                                            <span
                                                class="sort-mark {{ $sortMark($column) }}"
                                                aria-hidden="true"></span>

                                        </a>

                                    </th>

                                    @endforeach

                                    <th>
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($entries as $entry)

                                <tr>

                                    <td>
                                        {{ $entry->id ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $entry->invoice_no ?: '-' }}
                                    </td>

                                    <td class="date-cell">
                                        {{ $entry->date ? \Carbon\Carbon::parse($entry->date)->format('d M Y') : '-' }}
                                    </td>

                                    <td>
                                        {{ $entry->Ref_no ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $entry->perticulars ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $entry->vehicle_no ?: '-' }}
                                    </td>

                                    <td>
                                        {{ $entry->driver ?: '-' }}
                                    </td>

                                    <td>
                                        {{ $entry->transporter ?: '-' }}
                                    </td>

                                    <td class="number-cell">
                                        {{ is_numeric($entry->total_amount) ? number_format($entry->total_amount, 2) : '-' }}
                                    </td>

                                    <td class="number-cell">
                                        {{ is_numeric($entry->total_cgst_amount ?? null) ? number_format($entry->total_cgst_amount, 2) : '-' }}
                                    </td>

                                    <td class="number-cell">
                                        {{ is_numeric($entry->total_sgst_amount ?? null) ? number_format($entry->total_sgst_amount, 2) : '-' }}
                                    </td>

                                    <td class="number-cell">
                                        {{ is_numeric($entry->total_igst_amount ?? null) ? number_format($entry->total_igst_amount, 2) : '-' }}
                                    </td>

                                    <td class="number-cell">
                                        {{ is_numeric($entry->amount) ? number_format($entry->amount, 2) : '-' }}
                                    </td>

                                    <td>
                                        <div class="table-actions">
                                            <button
                                                type="button"
                                                class="mini-btn secondary"
                                                data-purchase-preview="{{ $entry->Ref_no }}">
                                                Preview
                                            </button>
                                        </div>
                                    </td>

                                </tr>

                                <tr
                                    class="detail-row"
                                    data-purchase-detail="{{ $entry->Ref_no }}"
                                    hidden>
                                    <td colspan="{{ count($columns) + 1 }}">
                                        <div class="detail-panel">
                                            <div class="detail-title">
                                                Ref {{ $entry->Ref_no }} Details
                                            </div>

                                            <div class="detail-table-wrap">
                                                <table class="detail-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Ref No</th>
                                                            <th>Invoice No</th>
                                                            <th>Party</th>
                                                            <th>Postal Address</th>
                                                            <th>Location</th>
                                                            <th>Interstate</th>
                                                            <th>Item</th>
                                                            <th>Vehicle No</th>
                                                            <th>Transporter</th>
                                                            <th>Driver</th>
                                                            <th class="number-cell">Qty.</th>
                                                            <th class="number-cell">Rate</th>
                                                            <th class="number-cell">Amount</th>
                                                            <th class="number-cell">Discount %</th>
                                                            <th class="number-cell">Discount</th>
                                                            <th class="number-cell">Taxable Amt.</th>
                                                            <th class="number-cell">CGST %</th>
                                                            <th class="number-cell">SGST %</th>
                                                            <th class="number-cell">IGST %</th>
                                                            <th class="number-cell">Total Tax</th>
                                                            <th class="number-cell">Total Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach (($entry->purchase_items ?? collect()) as $purchaseItem)
                                                            <tr>
                                                                <td class="date-cell">
                                                                    {{ $purchaseItem->date ? \Carbon\Carbon::parse($purchaseItem->date)->format('d M Y') : '-' }}
                                                                </td>
                                                                <td>{{ $purchaseItem->Ref_no ?? '-' }}</td>
                                                                <td>{{ $purchaseItem->invoice_no ?: '-' }}</td>
                                                                <td>{{ $purchaseItem->perticulars ?: '-' }}</td>
                                                                <td>{{ $purchaseItem->{'postal address'} ?: '-' }}</td>
                                                                <td>{{ $purchaseItem->location ?: '-' }}</td>
                                                                <td>{{ $purchaseItem->interstate ?: '-' }}</td>
                                                                <td>{{ $purchaseItem->item_name ?: '-' }}</td>
                                                                <td>{{ $purchaseItem->vehicle_no ?: '-' }}</td>
                                                                <td>{{ $purchaseItem->transporter ?: '-' }}</td>
                                                                <td>{{ $purchaseItem->driver ?: '-' }}</td>
                                                                <td class="number-cell">{{ is_numeric($purchaseItem->quantity) ? number_format((float) $purchaseItem->quantity, 3) : '-' }}</td>
                                                                <td class="number-cell">{{ is_numeric($purchaseItem->rate) ? number_format((float) $purchaseItem->rate, 2) : '-' }}</td>
                                                                <td class="number-cell">{{ is_numeric($purchaseItem->amount) ? number_format((float) $purchaseItem->amount, 2) : '-' }}</td>
                                                                <td class="number-cell">{{ is_numeric($purchaseItem->{'discount%'}) ? number_format((float) $purchaseItem->{'discount%'}, 2) : '-' }}</td>
                                                                <td class="number-cell">{{ is_numeric($purchaseItem->discountinrs) ? number_format((float) $purchaseItem->discountinrs, 2) : '-' }}</td>
                                                                <td class="number-cell">{{ is_numeric($purchaseItem->taxable_amount) ? number_format((float) $purchaseItem->taxable_amount, 2) : '-' }}</td>
                                                                <td class="number-cell">{{ is_numeric($purchaseItem->cgst) ? number_format((float) $purchaseItem->cgst, 2) : '-' }}</td>
                                                                <td class="number-cell">{{ is_numeric($purchaseItem->sgst) ? number_format((float) $purchaseItem->sgst, 2) : '-' }}</td>
                                                                <td class="number-cell">{{ is_numeric($purchaseItem->igst) ? number_format((float) $purchaseItem->igst, 2) : '-' }}</td>
                                                                <td class="number-cell">{{ is_numeric($purchaseItem->total_tax_amount) ? number_format((float) $purchaseItem->total_tax_amount, 2) : '-' }}</td>
                                                                <td class="number-cell">{{ is_numeric($purchaseItem->total_amount) ? number_format((float) $purchaseItem->total_amount, 2) : '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    @else

                    <div class="empty-state">

                        No purchase records found
                        {{ $search ? ' for "' . $search . '"' : '' }}.

                    </div>

                    @endif

                    <div class="pagination-bar">

                        <div>

                            @if ($entries->total())

                            Showing
                            {{ $entries->firstItem() }}
                            to
                            {{ $entries->lastItem() }}
                            of
                            {{ $entries->total() }}

                            @else

                            Showing 0 records

                            @endif

                        </div>

                        @include('partials.compact-pagination', ['paginator' => $entries])

                    </div>

                </section>

            </div>

        </main>

    </div>

    <div class="preview-modal" id="purchaseRegisterPreviewModal" role="dialog" aria-modal="true" aria-labelledby="purchaseRegisterPreviewTitle" aria-hidden="true">
        <div class="preview-window">
            <div class="preview-head">
                <div class="preview-title" id="purchaseRegisterPreviewTitle">Purchase Invoice Preview</div>
                <div class="preview-actions">
                    <a class="preview-action-btn" id="purchaseRegisterPreviewPdf" href="#" target="_blank" rel="noopener" data-themed-export>PDF</a>
                    <a class="preview-action-btn" id="purchaseRegisterPreviewExcel" href="#" data-themed-export>Excel</a>
                    <button type="button" class="preview-action-btn" id="purchaseRegisterPreviewPrint">Print</button>
                    <button type="button" class="preview-close-btn" id="purchaseRegisterPreviewClose" aria-label="Close preview">&times;</button>
                </div>
            </div>

            <div class="preview-body">
                <section class="invoice-preview" id="purchaseRegisterPreviewPrintArea">
                    <div class="invoice-company">
                        <h2>{{ $companyName }}</h2>
                        @if ($companyOffice)
                            <div>{{ $companyOffice }}</div>
                        @endif
                        @if ($companyPhone || $companyMobile || $companyEmail)
                            <div>
                                @if ($companyPhone || $companyMobile)
                                    Phone: {{ $companyPhone ?: $companyMobile }}
                                @endif
                                @if (($companyPhone || $companyMobile) && $companyEmail)
                                    |
                                @endif
                                @if ($companyEmail)
                                    Email: {{ $companyEmail }}
                                @endif
                            </div>
                        @endif
                        @if ($companyGstNo)
                            <div>GSTIN: {{ $companyGstNo }}</div>
                        @endif
                    </div>

                    <div class="invoice-meta-strip">
                        <span id="previewMetaRef">Ref No: -</span>
                        <span id="previewMetaDate">Date: -</span>
                        <span>Original</span>
                    </div>

                    <div class="invoice-title">Purchase Invoice</div>

                    <div class="invoice-parties">
                        <div class="invoice-block">
                            <strong>Invoice Details</strong>
                            <span id="previewDetailRef">Ref No: -</span>
                            <span id="previewDetailInvoice">Invoice No: -</span>
                            <span id="previewDetailDate">Date: -</span>
                            <span id="previewDetailInterstate">Interstate: No</span>
                            <span id="previewDetailItems">Total Items: 0</span>
                        </div>
                        <div class="invoice-block">
                            <strong>Supplier</strong>
                            <span id="previewSupplierName">-</span>
                            <span id="previewSupplierAddress">-</span>
                            <span id="previewSupplierLocation">-</span>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Product No.</th>
                                <th>Particulars</th>
                                <th class="number-cell">Qty</th>
                                <th class="number-cell">Rate</th>
                                <th class="number-cell">Disc %</th>
                                <th class="number-cell">Taxable Amt</th>
                                <th class="number-cell">CGST %</th>
                                <th class="number-cell">SGST %</th>
                                <th class="number-cell">IGST %</th>
                                <th class="number-cell">Total Tax</th>
                                <th class="number-cell">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody id="previewItemsBody"></tbody>
                    </table>

                    <div class="invoice-totals">
                        <div class="invoice-note">This preview is generated from the purchase register.</div>
                        <div class="invoice-total-box">
                            <div class="invoice-total-row">
                                <span>Subtotal</span>
                                <span id="previewSubtotal">Rs 0.00</span>
                            </div>
                            <div class="invoice-total-row">
                                <span>Discount</span>
                                <span id="previewDiscount">Rs 0.00</span>
                            </div>
                            <div class="invoice-total-row">
                                <span>Taxable Amount</span>
                                <span id="previewTaxable">Rs 0.00</span>
                            </div>
                            <div class="invoice-total-row">
                                <span>Total Tax</span>
                                <span id="previewTax">Rs 0.00</span>
                            </div>
                            <div class="invoice-total-row">
                                <span>Total Purchase Amount</span>
                                <span id="previewTotal">Rs 0.00</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        const purchasePreviewEntries = @json($purchasePreviewEntries);
        const purchaseRegisterPreviewModal = document.getElementById('purchaseRegisterPreviewModal');
        const purchaseRegisterPreviewClose = document.getElementById('purchaseRegisterPreviewClose');
        const purchaseRegisterPreviewPrint = document.getElementById('purchaseRegisterPreviewPrint');
        const purchaseRegisterPreviewPdf = document.getElementById('purchaseRegisterPreviewPdf');
        const purchaseRegisterPreviewExcel = document.getElementById('purchaseRegisterPreviewExcel');
        const purchaseRegisterPreviewPrintArea = document.getElementById('purchaseRegisterPreviewPrintArea');

        const formatPreviewMoney = (value) => Number.parseFloat(value || '0').toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        const setPreviewText = (id, value) => {
            const element = document.getElementById(id);

            if (element) {
                element.textContent = value;
            }
        };

        const escapePreviewHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        }[char]));

        const openPurchaseRegisterPreview = (refNo) => {
            const entry = purchasePreviewEntries[String(refNo)];

            if (!entry) {
                return;
            }

            setPreviewText('previewMetaRef', `Ref No: ${entry.ref_no || '-'}`);
            setPreviewText('previewMetaDate', `Date: ${entry.date || '-'}`);
            setPreviewText('previewDetailRef', `Ref No: ${entry.ref_no || '-'}`);
            setPreviewText('previewDetailInvoice', `Invoice No: ${entry.invoice_no || '-'}`);
            setPreviewText('previewDetailDate', `Date: ${entry.date || '-'}`);
            setPreviewText('previewDetailInterstate', `Interstate: ${entry.interstate || 'No'}`);
            setPreviewText('previewDetailItems', `Total Items: ${(entry.items || []).length}`);
            setPreviewText('previewSupplierName', entry.party || '-');
            setPreviewText('previewSupplierAddress', entry.postal_address || '-');
            setPreviewText('previewSupplierLocation', entry.location || '-');
            setPreviewText('previewSubtotal', `Rs ${formatPreviewMoney(entry.subtotal)}`);
            setPreviewText('previewDiscount', `Rs ${formatPreviewMoney(entry.discount)}`);
            setPreviewText('previewTaxable', `Rs ${formatPreviewMoney(entry.taxable)}`);
            setPreviewText('previewTax', `Rs ${formatPreviewMoney(entry.tax)}`);
            setPreviewText('previewTotal', `Rs ${formatPreviewMoney(entry.total)}`);

            if (purchaseRegisterPreviewPdf) {
                purchaseRegisterPreviewPdf.href = entry.pdf_url || '#';
            }

            if (purchaseRegisterPreviewExcel) {
                purchaseRegisterPreviewExcel.href = entry.excel_url || '#';
            }

            const itemRows = (entry.items || []).map((item, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${escapePreviewHtml(item.item_name || '-')}</td>
                    <td>${escapePreviewHtml(item.item_name || '-')}</td>
                    <td class="number-cell">${Number.parseFloat(item.quantity || 0).toFixed(3)}</td>
                    <td class="number-cell">${Number.parseFloat(item.rate || 0).toFixed(2)}</td>
                    <td class="number-cell">${Number.parseFloat(item.discount_percent || 0).toFixed(2)}</td>
                    <td class="number-cell">${formatPreviewMoney(item.taxable_amount)}</td>
                    <td class="number-cell">${Number.parseFloat(item.cgst || 0).toFixed(2)}</td>
                    <td class="number-cell">${Number.parseFloat(item.sgst || 0).toFixed(2)}</td>
                    <td class="number-cell">${Number.parseFloat(item.igst || 0).toFixed(2)}</td>
                    <td class="number-cell">${formatPreviewMoney(item.total_tax_amount)}</td>
                    <td class="number-cell">${formatPreviewMoney(item.total_amount)}</td>
                </tr>
            `).join('');

            document.getElementById('previewItemsBody').innerHTML = itemRows || '<tr><td colspan="12">No purchase items found.</td></tr>';
            purchaseRegisterPreviewModal?.classList.add('is-open');
            purchaseRegisterPreviewModal?.setAttribute('aria-hidden', 'false');
            applyExportThemeLinks();
        };

        const closePurchaseRegisterPreview = () => {
            purchaseRegisterPreviewModal?.classList.remove('is-open');
            purchaseRegisterPreviewModal?.setAttribute('aria-hidden', 'true');
        };

        const printPurchaseRegisterPreview = () => {
            const printWindow = window.open('', '_blank', 'width=1100,height=800');

            if (!printWindow || !purchaseRegisterPreviewPrintArea) {
                window.print();
                return;
            }

            printWindow.document.write(`
                <!doctype html>
                <html>
                    <head>
                        <title>Purchase Invoice Preview</title>
                        <style>
                            body { margin: 0; padding: 20px; font-family: Arial, Helvetica, sans-serif; color: #172033; }
                            .invoice-preview { width: 960px; margin: 0 auto; padding: 20px; border: 1px solid #dce3ee; background: #fff; }
                            .invoice-company { display: grid; gap: 4px; padding-bottom: 12px; border-bottom: 2px solid #172033; text-align: center; }
                            .invoice-company h2 { margin: 0; color: #115e59; font-size: 24px; }
                            .invoice-meta-strip { display: flex; justify-content: space-between; gap: 12px; padding: 8px 0; border-bottom: 1px solid #172033; font-size: 12px; font-weight: 800; }
                            .invoice-title { padding: 8px 0; border-bottom: 1px solid #172033; color: #115e59; font-size: 15px; font-weight: 900; text-align: center; text-transform: uppercase; }
                            .invoice-parties { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; padding: 12px 0; }
                            .invoice-block { display: grid; gap: 5px; font-size: 12px; }
                            table { width: 100%; border-collapse: collapse; }
                            th, td { padding: 7px 6px; border: 1px solid #1f2937; font-size: 11px; }
                            th { color: #fff; background: #115e59; text-align: center; }
                            .number-cell { text-align: right; white-space: nowrap; }
                            .invoice-totals { display: grid; grid-template-columns: minmax(0, 1fr) 280px; gap: 16px; padding-top: 14px; }
                            .invoice-total-box { border: 1px solid #dce3ee; }
                            .invoice-total-row { display: flex; justify-content: space-between; gap: 12px; padding: 8px 10px; border-bottom: 1px solid #dce3ee; font-size: 12px; font-weight: 800; }
                            .invoice-total-row:last-child { border-bottom: 0; color: #115e59; background: #eef8f7; }
                            .invoice-note { align-self: end; color: #657089; font-size: 11px; font-weight: 800; }
                        </style>
                    </head>
                    <body>${purchaseRegisterPreviewPrintArea.outerHTML}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        };

        const applyExportThemeLinks = () => {

            let theme = 'default';

            try {
                theme = localStorage.getItem('fueltracker:theme') || 'default';
            } catch (error) {
                theme = 'default';
            }

            document.querySelectorAll('[data-themed-export]').forEach((link) => {
                const url = new URL(link.href, window.location.origin);
                url.searchParams.set('theme', theme);
                link.href = url.toString();
            });
        };

        applyExportThemeLinks();

        document.querySelectorAll('.form-alert').forEach((alert) => {

            setTimeout(() => {
                alert.classList.add('is-hiding');
                setTimeout(() => alert.remove(), 250);
            }, 4000);
        });

        document.querySelectorAll('.entries-dropdown').forEach((dropdown) => {

            const toggle = dropdown.querySelector('.entries-toggle');
            const input = dropdown.querySelector('input[name="per_page"]');
            const form = dropdown.closest('form');

            toggle.addEventListener('click', () => {
                const isOpen = dropdown.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            dropdown.querySelectorAll('.entries-option').forEach((option) => {
                option.addEventListener('click', () => {
                    input.value = option.dataset.perPage;
                    form.submit();
                });
            });
        });

        document.addEventListener('click', (event) => {

            const previewButton = event.target.closest('[data-purchase-preview]');

            if (previewButton) {
                openPurchaseRegisterPreview(previewButton.dataset.purchasePreview);
                return;
            }

            document.querySelectorAll('.entries-dropdown.is-open').forEach((dropdown) => {
                if (!dropdown.contains(event.target)) {
                    dropdown.classList.remove('is-open');
                    dropdown.querySelector('.entries-toggle').setAttribute('aria-expanded', 'false');
                }
            });
        });

        purchaseRegisterPreviewClose?.addEventListener('click', closePurchaseRegisterPreview);
        purchaseRegisterPreviewPrint?.addEventListener('click', printPurchaseRegisterPreview);
        purchaseRegisterPreviewModal?.addEventListener('click', (event) => {
            if (event.target === purchaseRegisterPreviewModal) {
                closePurchaseRegisterPreview();
            }
        });
    </script>

</body>

</html>
