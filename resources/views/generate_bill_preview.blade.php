<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill {{ $bill->bill_no ?: $bill->id }} Preview | FuelTracker</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">

    <style>
        :root {
            --bg: #f4f7fb;
            --panel: #ffffff;
            --ink: #111827;
            --muted: #4b5563;
            --line: #dce3ee;
            --invoice-line: #111827;
            --print-grid-height: auto;
            --primary: #0f766e;
            --primary-dark: #115e59;
            --primary-shine: #2dd4bf;
            --accent: #f59e0b;
            --theme-glow: rgba(15, 118, 110, .22);
            --theme-bg-end: #eef5f3;
            --theme-brand-start: rgba(8, 47, 73, .98);
            --theme-brand-end: rgba(15, 118, 110, .96);
            --theme-print: none;
            --soft-line: #dce3ee;
            --shadow: 0 22px 70px rgba(23, 32, 51, .16);
        }

        * { box-sizing: border-box; }

        @page {
            size: A4;
            margin: 8mm;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            background:
                var(--theme-print),
                radial-gradient(circle at top left, var(--theme-glow), transparent 32rem),
                linear-gradient(135deg, #f8fbff 0%, var(--bg) 55%, var(--theme-bg-end) 100%);
            background-size: 44px 44px, auto, auto;
            font-family: Arial, Helvetica, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 10;
            width: 100%;
            color: #ffffff;
            background:
                var(--theme-print),
                linear-gradient(160deg, rgba(255, 255, 255, .2) 0%, transparent 28%, transparent 70%, rgba(255, 255, 255, .12) 100%),
                linear-gradient(135deg, var(--theme-brand-start), var(--theme-brand-end)),
                url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.12' stroke-width='2'%3E%3Cpath d='M22 116c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 78c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 40c20-18 40-18 60 0s40 18 60 0'/%3E%3C/g%3E%3C/svg%3E");
            background-size: 44px 44px, auto, auto, 160px 160px;
            box-shadow: 0 10px 28px rgba(23, 32, 51, .16);
        }

        .site-header-inner {
            width: 100%;
            min-height: 64px;
            display: grid;
            grid-template-columns: minmax(220px, 1fr) auto minmax(220px, 1fr);
            align-items: center;
            gap: 18px;
            margin: 0 auto;
            padding: 0 8px;
        }

        .site-logo {
            min-width: 0;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #ffffff;
            font-size: 21px;
            font-weight: 700;
            text-decoration: none;
        }

        .site-logo-icon {
            width: 38px;
            height: 38px;
            flex: 0 0 auto;
            display: grid;
            place-items: center;
            overflow: hidden;
            padding: 2px;
            border-radius: 999px;
            background: #ffffff;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .18);
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
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            line-height: 1.2;
            white-space: nowrap;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .toolbar-btn,
        .logout-btn {
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            border: 1px solid rgba(255, 255, 255, .28);
            border-radius: 8px;
            color: #ffffff;
            background: rgba(255, 255, 255, .13);
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
            transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
        }

        .toolbar-btn:hover,
        .toolbar-btn:focus,
        .logout-btn:hover,
        .logout-btn:focus {
            background: rgba(255, 255, 255, .22);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .28);
            transform: translateY(-1px);
            outline: none;
        }

        .preview-shell {
            width: min(100% - 28px, 1220px);
            margin: 18px auto 32px;
        }

        .preview-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 92px);
            max-height: calc(100vh - 92px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
            overflow: hidden;
        }

        .preview-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
        }

        .preview-content {
            min-width: 0;
            max-height: calc(100vh - 120px);
            overflow: auto;
            padding: 14px;
        }

        .preview-workspace .preview-shell {
            width: 100%;
            margin: 0;
        }

        .bill-summary-strip {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 14px;
        }

        .summary-tile {
            min-height: 70px;
            padding: 12px 14px;
            border: 1px solid rgba(220, 227, 238, .86);
            border-radius: 12px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, .98), rgba(255, 255, 255, .88)),
                var(--panel);
            box-shadow: 0 14px 40px rgba(23, 32, 51, .08);
        }

        .summary-label {
            margin: 0 0 6px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .summary-value {
            margin: 0;
            color: var(--ink);
            font-size: 15px;
            font-weight: 900;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .modern-invoice {
            overflow: hidden;
            border: 1px solid rgba(220, 227, 238, .9);
            border-radius: 16px;
            background: #ffffff;
            box-shadow: var(--shadow);
        }

        .modern-invoice-head {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 24px;
            padding: 24px 28px;
            color: #ffffff;
            background:
                linear-gradient(160deg, rgba(255, 255, 255, .18) 0%, transparent 32%, rgba(255, 255, 255, .12) 100%),
                linear-gradient(135deg, var(--primary-dark), var(--primary) 62%, var(--primary-shine));
        }

        .invoice-kicker {
            margin: 0 0 8px;
            color: rgba(255, 255, 255, .82);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .modern-company-name {
            margin: 0;
            font-size: 30px;
            line-height: 1.08;
            font-weight: 900;
        }

        .modern-company-meta {
            max-width: 720px;
            margin: 10px 0 0;
            color: rgba(255, 255, 255, .88);
            font-size: 12px;
            font-weight: 700;
            line-height: 1.55;
        }

        .modern-invoice-badge {
            min-width: 190px;
            align-self: start;
            padding: 14px 16px;
            border: 1px solid rgba(255, 255, 255, .28);
            border-radius: 12px;
            background: rgba(255, 255, 255, .14);
            text-align: right;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .22);
        }

        .modern-invoice-badge span {
            display: block;
            color: rgba(255, 255, 255, .82);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .modern-invoice-badge strong {
            display: block;
            margin-top: 6px;
            font-size: 28px;
            line-height: 1;
        }

        .modern-info-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(0, .75fr);
            gap: 16px;
            padding: 18px 28px 0;
        }

        .modern-info-panel {
            min-width: 0;
            padding: 16px;
            border: 1px solid var(--soft-line);
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(15, 118, 110, .055), rgba(255, 255, 255, .98));
        }

        .modern-section-label {
            margin: 0 0 8px;
            color: var(--primary-dark);
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .modern-party-name {
            margin: 0;
            color: var(--ink);
            font-size: 20px;
            font-weight: 900;
            line-height: 1.25;
        }

        .modern-muted-line {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            line-height: 1.45;
        }

        .modern-meta-list {
            display: grid;
            gap: 9px;
            margin: 0;
        }

        .modern-meta-row {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 14px;
            padding-bottom: 8px;
            border-bottom: 1px dashed rgba(101, 112, 137, .28);
        }

        .modern-meta-row:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .modern-meta-row dt {
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .modern-meta-row dd {
            margin: 0;
            color: var(--ink);
            font-size: 13px;
            font-weight: 900;
            text-align: right;
        }

        .modern-table-wrap {
            margin: 18px 28px 0;
            overflow-x: auto;
            border: 1px solid var(--soft-line);
            border-radius: 12px;
        }

        .modern-items-table {
            width: 100%;
            min-width: 860px;
            border-collapse: collapse;
        }

        .modern-items-table th,
        .modern-items-table td {
            padding: 12px 12px;
            border-bottom: 1px solid var(--soft-line);
            color: var(--ink);
            font-size: 13px;
            text-align: left;
            vertical-align: top;
        }

        .modern-items-table th {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .modern-items-table tbody tr:nth-child(even) {
            background: rgba(15, 118, 110, .035);
        }

        .modern-items-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .modern-number {
            text-align: right !important;
            white-space: nowrap;
        }

        .modern-total-panel {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 320px;
            gap: 18px;
            padding: 18px 28px 24px;
        }

        .modern-note-box,
        .modern-total-box {
            padding: 16px;
            border: 1px solid var(--soft-line);
            border-radius: 12px;
            background: #fbfcfe;
        }

        .modern-note-box {
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            line-height: 1.55;
        }

        .modern-note-box strong {
            color: var(--ink);
        }

        .modern-total-line {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px dashed rgba(101, 112, 137, .28);
            color: var(--muted);
            font-size: 12px;
            font-weight: 800;
        }

        .modern-grand-total {
            margin-top: 10px;
            padding: 14px;
            border-radius: 10px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .modern-grand-total span {
            display: block;
            color: rgba(255, 255, 255, .82);
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .modern-grand-total strong {
            display: block;
            margin-top: 5px;
            font-size: 24px;
            line-height: 1;
        }

        .modern-signature {
            margin-top: 18px;
            color: var(--ink);
            font-size: 12px;
            font-weight: 900;
            text-align: right;
        }

        .invoice-stage {
            width: 100%;
            overflow-x: auto;
            padding: 18px 14px 26px;
            border: 1px solid rgba(220, 227, 238, .8);
            border-radius: 14px;
            background:
                linear-gradient(135deg, rgba(15, 118, 110, .08), transparent 32%),
                rgba(255, 255, 255, .58);
            box-shadow: var(--shadow);
        }

        .print-invoice-stage {
            display: none;
        }

        .invoice-sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 10mm 12mm;
            background: #ffffff;
            border-radius: 6px;
            box-shadow: 0 30px 70px rgba(23, 32, 51, .18);
        }

        .invoice-box {
            min-height: 277mm;
            border: 1.5px solid var(--invoice-line);
            font-size: 11px;
        }

        .top-line,
        .meta-line {
            width: 100%;
            border-collapse: collapse;
        }

        .top-line td,
        .meta-line td {
            padding: 5px 7px;
            vertical-align: top;
            font-size: 11px;
            font-weight: 800;
        }

        .invoice-title {
            text-align: center;
        }

        .company-name {
            margin: 3px 0 1px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 26px;
            line-height: 1.05;
            font-weight: 900;
            text-align: center;
            text-transform: uppercase;
        }

        .company-address,
        .company-contact {
            margin: 0;
            font-size: 12px;
            line-height: 1.35;
            font-weight: 800;
            text-align: center;
        }

        .meta-line {
            border-top: 1.5px solid var(--invoice-line);
            border-bottom: 1.5px solid var(--invoice-line);
        }

        .party-block {
            min-height: 70px;
            padding: 6px 7px;
            border-bottom: 1.5px solid var(--invoice-line);
            font-size: 12px;
            font-weight: 800;
        }

        .party-state {
            margin-top: 20px;
            text-align: center;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .items-table th,
        .items-table td {
            padding: 4px 4px;
            border-right: 1.5px solid var(--invoice-line);
            font-size: 10px;
            line-height: 1.25;
            vertical-align: top;
        }

        .items-table th {
            border-bottom: 1.5px solid var(--invoice-line);
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .items-table th:last-child,
        .items-table td:last-child {
            border-right: 0;
        }

        .items-table tbody td {
            height: 478px;
        }

        .number-cell {
            text-align: right;
            white-space: nowrap;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 1.5px solid var(--invoice-line);
        }

        .summary-table td {
            padding: 5px 7px;
            font-size: 11px;
            font-weight: 800;
            vertical-align: top;
        }

        .amount-row td {
            border-bottom: 1.5px solid var(--invoice-line);
        }

        .sign-cell {
            text-align: right;
        }

        .small-note {
            font-size: 10px;
            line-height: 1.45;
        }

        .brand-mark {
            font-weight: 900;
        }

        @media (max-width: 900px) {
            .site-header-inner {
                grid-template-columns: 1fr;
                align-items: stretch;
                gap: 10px;
                padding: 12px;
            }

            .header-title {
                justify-self: start;
                font-size: 17px;
            }

            .header-actions {
                justify-content: flex-start;
            }

            .preview-workspace.app-shell-with-sidebar {
                width: 100%;
                min-height: calc(100vh - 68px);
                max-height: none;
                display: block;
                margin: 0;
                border-radius: 0;
                overflow: visible;
            }

            .bill-summary-strip {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .modern-invoice-head,
            .modern-info-grid,
            .modern-total-panel {
                grid-template-columns: 1fr;
            }

            .modern-invoice-badge {
                width: 100%;
                text-align: left;
            }

            .invoice-stage {
                padding: 0 12px 18px;
            }

            .invoice-sheet {
                margin: 0;
            }
        }

        @media (max-width: 560px) {
            .preview-shell {
                width: 100%;
                margin: 0;
            }

            .preview-content {
                padding: 0;
                max-height: none;
                overflow: visible;
            }

            .bill-summary-strip {
                grid-template-columns: 1fr;
                padding: 12px;
                margin-bottom: 0;
            }

            .invoice-stage {
                border-radius: 0;
                border-left: 0;
                border-right: 0;
            }

            .toolbar-btn {
                flex: 1 1 120px;
            }

            .modern-invoice {
                border-radius: 0;
                border-left: 0;
                border-right: 0;
            }

            .modern-invoice-head,
            .modern-info-grid,
            .modern-table-wrap,
            .modern-total-panel {
                margin-left: 0;
                margin-right: 0;
            }

            .modern-invoice-head,
            .modern-info-grid,
            .modern-total-panel {
                padding-left: 14px;
                padding-right: 14px;
            }
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 8mm;
            }

            html,
            body {
                width: auto;
                min-height: 0;
            }

            body {
                background: #ffffff;
            }

            .site-header {
                display: none;
            }

            .preview-workspace.app-shell-with-sidebar {
                display: block;
                width: auto;
                min-height: auto;
                margin: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .preview-workspace .sidebar {
                display: none;
            }

            .preview-content {
                padding: 0;
            }

            .preview-shell {
                width: auto;
                margin: 0;
            }

            .bill-summary-strip {
                display: none;
            }

            .modern-invoice {
                display: none;
            }

            .print-invoice-stage {
                display: block;
            }

            .invoice-sheet {
                width: auto;
                min-height: auto;
                margin: 0;
                padding: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .invoice-box {
                min-height: auto;
                border: 1.4px solid #111827;
                font-size: 11px;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .top-line td,
            .meta-line td {
                padding: 5px 6px;
                font-size: 11px;
            }

            .invoice-title {
                color: #0f766e;
                font-weight: 900;
            }

            .company-name {
                margin: 2px 0 1px;
                color: #0f172a;
                font-size: 27px;
                line-height: 1;
            }

            .company-address,
            .company-contact {
                font-size: 11.5px;
                line-height: 1.25;
            }

            .meta-line,
            .party-block,
            .summary-table,
            .amount-row td {
                border-color: #111827;
            }

            .party-block {
                min-height: 62px;
                padding: 5px 6px;
                font-size: 12px;
            }

            .party-state {
                margin-top: 16px;
            }

            .items-table th,
            .items-table td {
                padding: 4px 5px;
                font-size: 10px;
            }

            .items-table th {
                color: #ffffff;
                background: var(--primary);
                font-size: 9px;
            }

            .items-table tbody td {
                height: min(var(--print-grid-height), 118mm);
            }

            .summary-table td {
                padding: 5px 6px;
                font-size: 11px;
            }

            .small-note {
                font-size: 10px;
                line-height: 1.35;
            }

            .brand-mark {
                color: #0f766e;
            }
        }

        @if (! empty($isPdf))
            body { background: #ffffff; }
            .site-header { display: none; }
            .preview-shell { width: auto; margin: 0; }
            .bill-summary-strip { display: none; }
            .modern-invoice { display: none; }
            .invoice-stage { width: auto; margin: 0; padding: 0; border: 0; border-radius: 0; background: transparent; box-shadow: none; }
            .print-invoice-stage { display: block; }
            .invoice-sheet { width: auto; min-height: auto; margin: 0; padding: 0; border-radius: 0; box-shadow: none; }
            .invoice-box { min-height: auto; }
        @endif
    </style>

    @if (empty($isPdf))
        @include('partials.theme')
        <style>
            html,
            body {
                overflow: hidden;
            }

            @media (max-width: 900px), print {
                html,
                body {
                    overflow: auto;
                }
            }
        </style>
    @endif
</head>

<body>
    @if (empty($isPdf))
        <header class="site-header">
            <div class="site-header-inner">
                <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                    <span class="site-logo-icon" aria-hidden="true">
                        <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                    </span>
                    <span>FuelTracker</span>
                </a>

                <div class="header-title">Bill {{ $bill->bill_no ?: $bill->id }} Preview</div>

                <div class="header-actions">
                    <a class="toolbar-btn" href="{{ url('/dashboard') }}">Dashboard</a>
                    <a class="toolbar-btn" href="{{ route('generate-bill.index') }}">Generate Bill</a>
                    <a class="toolbar-btn" href="{{ route('generate-bill.list') }}">Saved Bills</a>
                    <a class="toolbar-btn" href="{{ route('generate-bill.pdf', $bill) }}" target="_blank" rel="noopener">PDF</a>
                    <button class="toolbar-btn" type="button" id="printBillBtn">Print</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>
        </header>
    @endif

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
        $firstItem = $bill->items->first();
    @endphp

    @if (empty($isPdf))
        <div class="app-shell-with-sidebar preview-workspace" id="dashboardPage">
            @include('partials.fueltracker-menu')
            <main class="preview-shell preview-content">
    @else
    <main class="preview-shell" style="--print-grid-height: {{ max(62, 150 - ($bill->items->count() * 12)) }}mm;">
    @endif
        <section class="bill-summary-strip" aria-label="Bill summary">
            <div class="summary-tile">
                <p class="summary-label">Party</p>
                <p class="summary-value">{{ $bill->party ?: '-' }}</p>
            </div>
            <div class="summary-tile">
                <p class="summary-label">Bill Period</p>
                <p class="summary-value">{{ $dateFrom }} to {{ $dateTo }}</p>
            </div>
            <div class="summary-tile">
                <p class="summary-label">Total Slips</p>
                <p class="summary-value">{{ $bill->total_slips }}</p>
            </div>
            <div class="summary-tile">
                <p class="summary-label">Amount</p>
                <p class="summary-value">{{ number_format((float) $bill->total_amount, 2) }}</p>
            </div>
        </section>

        <section class="modern-invoice" aria-label="Invoice preview">
            <div class="modern-invoice-head">
                <div>
                    <p class="invoice-kicker">Tax Invoice</p>
                    <h2 class="modern-company-name">{{ $companyName }}</h2>
                    <p class="modern-company-meta">
                        @if ($gstNo) GST No.: {{ $gstNo }} @endif
                        @if ($gstNo && $office) &nbsp; | &nbsp; @endif
                        @if ($office) {{ $office }} @endif
                        @if ($email || $mobile || $phone)
                            <br>
                            @if ($email) Email: {{ $email }} @endif
                            @if ($email && ($mobile || $phone)) &nbsp; | &nbsp; @endif
                            @if ($mobile || $phone) Phone: {{ $mobile ?: $phone }} @endif
                        @endif
                    </p>
                </div>
                <div class="modern-invoice-badge">
                    <span>Invoice No.</span>
                    <strong>{{ $bill->bill_no ?: $bill->id }}</strong>
                    <span style="margin-top: 10px;">{{ $invoiceDate }}</span>
                </div>
            </div>

            <div class="modern-info-grid">
                <div class="modern-info-panel">
                    <p class="modern-section-label">Billed To</p>
                    <p class="modern-party-name">{{ $bill->party ?: '-' }}</p>
                    <p class="modern-muted-line">State: MADHYA PRADESH &nbsp; | &nbsp; Code: 23</p>
                </div>
                <div class="modern-info-panel">
                    <p class="modern-section-label">Bill Details</p>
                    <dl class="modern-meta-list">
                        <div class="modern-meta-row">
                            <dt>Period</dt>
                            <dd>{{ $dateFrom }} to {{ $dateTo }}</dd>
                        </div>
                        <div class="modern-meta-row">
                            <dt>Total Slips</dt>
                            <dd>{{ $bill->total_slips }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="modern-table-wrap">
                <table class="modern-items-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Vehicle No.</th>
                            <th>Slip No.</th>
                            <th>Item/Particulars</th>
                            <th>HSN Code</th>
                            <th class="modern-number">Qty</th>
                            <th class="modern-number">Rate</th>
                            <th class="modern-number">Amount</th>
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
                                <td class="modern-number">{{ number_format((float) $item->qty, 2) }}</td>
                                <td class="modern-number">{{ number_format((float) $item->rate, 2) }}</td>
                                <td class="modern-number">{{ number_format((float) $item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="modern-total-panel">
                <div class="modern-note-box">
                    <p class="modern-section-label">Amount In Words</p>
                    <strong>Rs. {{ $amountInWords }}</strong>
                    <p class="modern-muted-line">
                        Terms: Interest will be charged if Bill is not paid within 7 days.
                    </p>
                    @foreach ($itemTotals as $itemTotal)
                        <div>{{ $itemTotal['name'] }} - Qty: {{ number_format((float) $itemTotal['qty'], 2) }} | Amount: {{ number_format((float) $itemTotal['amount'], 2) }}</div>
                    @endforeach
                    <div>Total Slip: {{ $bill->total_slips }}</div>
                    <div><strong>(SAVE FUEL)</strong></div>
                </div>
                <div class="modern-total-box">
                    <div class="modern-total-line">
                        <span>Total Slips</span>
                        <strong>{{ $bill->total_slips }}</strong>
                    </div>
                    <div class="modern-total-line">
                        <span>Invoice Date</span>
                        <strong>{{ $invoiceDate }}</strong>
                    </div>
                    <div class="modern-grand-total">
                        <span>Total Amount</span>
                        <strong>{{ number_format((float) $bill->total_amount, 2) }}</strong>
                    </div>
                    <div class="modern-signature">
                        <div>FOR {{ $companyName }}</div>
                        <br><br>
                        <div>(Authorised Signatory)</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="invoice-stage print-invoice-stage">
            <div class="invoice-sheet">
            <div class="invoice-box">
                <table class="top-line">
                    <tr>
                        <td style="width: 32%;">GST NO.: {{ $gstNo ?: '-' }}</td>
                        <td class="invoice-title" style="width: 36%;">INVOICE</td>
                        <td class="number-cell" style="width: 32%;">PH.: {{ $phone ?: ($mobile ?: '-') }}</td>
                    </tr>
                </table>

                <h2 class="company-name">{{ $companyName }}</h2>
                @if ($office)
                    <p class="company-address">{{ $office }}</p>
                @endif
                @if ($email || $mobile)
                    <p class="company-contact">
                        @if ($email) Email : {{ $email }} @endif
                        @if ($email && $mobile) &nbsp; | &nbsp; @endif
                        @if ($mobile) Mobile : {{ $mobile }} @endif
                    </p>
                @endif

                <table class="meta-line">
                    <tr>
                        <td style="width: 28%;">Invoice No. : {{ $bill->bill_no ?: $bill->id }}</td>
                        <td style="width: 44%; text-align: center;">From : {{ $dateFrom }} To : {{ $dateTo }}</td>
                        <td class="number-cell" style="width: 28%;">Invoice Date : {{ $invoiceDate }}</td>
                    </tr>
                </table>

                <div class="party-block">
                    <div>Particulars : {{ $bill->party ?: '-' }}</div>
                    <div class="party-state">State : MADHYA PRADESH&nbsp;&nbsp; Code : 23</div>
                </div>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 11%;">Date</th>
                            <th style="width: 14%;">Vehicle No.</th>
                            <th style="width: 11%;">Slip No.</th>
                            <th style="width: 23%;">Item/Particulars</th>
                            <th style="width: 12%;">HSN Code</th>
                            <th style="width: 9%;">Qty</th>
                            <th style="width: 9%;">Rate</th>
                            <th style="width: 11%;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                @foreach ($bill->items as $item)
                                    <div>{{ optional($item->bill_date)->format('d/m/Y') ?: '-' }}</div>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($bill->items as $item)
                                    <div>{{ $item->vehicle_no ?: '-' }}</div>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($bill->items as $item)
                                    <div>{{ $item->slip_no ?: '-' }}</div>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($bill->items as $item)
                                    <div>{{ $item->item_name ?: '-' }}</div>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($bill->items as $item)
                                    <div>{{ $item->hsn_code ?: '-' }}</div>
                                @endforeach
                            </td>
                            <td class="number-cell">
                                @foreach ($bill->items as $item)
                                    <div>{{ number_format((float) $item->qty, 2) }}</div>
                                @endforeach
                            </td>
                            <td class="number-cell">
                                @foreach ($bill->items as $item)
                                    <div>{{ number_format((float) $item->rate, 2) }}</div>
                                @endforeach
                            </td>
                            <td class="number-cell">
                                @foreach ($bill->items as $item)
                                    <div>{{ number_format((float) $item->amount, 2) }}</div>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="summary-table">
                    <tr class="amount-row">
                        <td style="width: 70%;">Rs. {{ $amountInWords }}</td>
                        <td class="number-cell" style="width: 15%;">Total Amount :</td>
                        <td class="number-cell" style="width: 15%;">{{ number_format((float) $bill->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="small-note">
                            @foreach ($itemTotals as $itemTotal)
                                <div>{{ $itemTotal['name'] }} - Qty : {{ number_format((float) $itemTotal['qty'], 2) }} | Amount : {{ number_format((float) $itemTotal['amount'], 2) }}</div>
                            @endforeach
                            <div>Total Slip : {{ $bill->total_slips }}</div>
                            <div>Terms : Interest will be charged if Bill is not paid within 7 days</div>
                            <div class="brand-mark">(SAVE FUEL)</div>
                        </td>
                        <td colspan="2" class="sign-cell">
                            <div>FOR {{ $companyName }}</div>
                            <br><br>
                            <div>(Authorised Signatory)</div>
                        </td>
                    </tr>
                </table>
            </div>
            </div>
        </section>

    @if (empty($isPdf))
            </main>
        </div>
    @else
        </main>
    @endif

    @if (empty($isPdf))
        <script>
            document.getElementById('printBillBtn')?.addEventListener('click', () => {
                document.body.classList.add('is-printing-bill');
                window.print();
            });

            window.addEventListener('afterprint', () => {
                document.body.classList.remove('is-printing-bill');
            });
        </script>
    @endif
</body>

</html>
