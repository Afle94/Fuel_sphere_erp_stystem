<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Wise Sales Register | FuelTracker</title>
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
                radial-gradient(circle at top left, rgba(15, 118, 110, .16), transparent 32rem),
                linear-gradient(135deg, #f8fbff 0%, var(--bg) 55%, #eef5f3 100%);
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 30;
            width: 100%;
            background: linear-gradient(135deg, rgba(8, 47, 73, .98), rgba(15, 118, 110, .98));
            box-shadow: 0 10px 30px rgba(23, 32, 51, .12);
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
            transition: background .2s ease, transform .2s ease;
            font-family: inherit;
        }

        .back-link:hover,
        .logout-btn:hover {
            background: rgba(255, 255, 255, .2);
            transform: translateY(-1px);
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
            transition: opacity .25s ease, transform .25s ease;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(23, 32, 51, 0.28);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .modal-overlay.is-active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-window {
            background: #ffffff;
            width: min(92%, 520px);
            border-radius: 16px;
            box-shadow: 0 24px 70px rgba(23, 32, 51, 0.24);
            overflow: visible;
            transform: scale(0.95);
            transition: transform 0.25s ease;
            border: 1px solid var(--line);
        }

        .modal-overlay.is-active .modal-window {
            transform: scale(1);
        }

        .modal-header {
            padding: 18px 24px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-close-btn {
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 22px;
            cursor: pointer;
            opacity: 0.8;
        }

        .modal-close-btn:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 24px;
            position: relative;
            background: #ffffff;
            border-radius: 0 0 16px 16px;
        }

        .popup-form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
        }

        .popup-input {
            width: 100%;
            min-height: 40px;
            padding: 0 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background: #fbfcfe;
        }

        .popup-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.12);
            background: #fff;
        }

        .date-picker {
            position: relative;
        }

        .date-picker-input {
            cursor: pointer;
            background: #ffffff;
        }

        .date-picker-panel {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            z-index: 6000;
            display: none;
            width: 270px;
            padding: 10px;
            border: 1px solid rgba(15, 118, 110, .34);
            border-radius: 8px;
            color: var(--ink);
            background: #ffffff;
            box-shadow: 0 18px 36px rgba(23, 32, 51, .20);
        }

        .date-picker.is-open .date-picker-panel {
            display: block;
        }

        .calendar-header {
            display: grid;
            grid-template-columns: 34px 1fr 34px;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .calendar-month {
            color: var(--primary-dark);
            font-size: 13px;
            font-weight: 800;
            text-align: center;
        }

        .calendar-nav {
            width: 32px;
            height: 30px;
            border: 1px solid rgba(15, 118, 110, .25);
            border-radius: 8px;
            color: var(--primary-dark);
            background: rgba(15, 118, 110, .08);
            cursor: pointer;
            font-size: 16px;
            font-weight: 800;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 3px;
        }

        .calendar-weekday,
        .calendar-day {
            min-height: 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
        }

        .calendar-weekday {
            color: var(--muted);
        }

        .calendar-day {
            border: 0;
            color: var(--ink);
            background: #ffffff;
            cursor: pointer;
        }

        .calendar-day:hover {
            color: var(--primary-dark);
            background: rgba(15, 118, 110, .08);
        }

        .calendar-day.is-selected {
            color: var(--primary-dark);
            background: rgba(15, 118, 110, .18);
        }

        .calendar-day.is-muted {
            color: #9aa4b6;
        }

        .modal-footer-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
        }

        .active-filter-bar {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .summary-badge {
            font-weight: 700;
            padding: 6px 14px;
            background: #f0fdf4;
            color: #166534;
            border-radius: 6px;
            border: 1px solid rgba(22, 101, 52, 0.15);
            font-size: 12px;
        }

        .change-interval-btn {
            background: #ffffff;
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .change-interval-btn:hover {
            background: rgba(15, 118, 110, 0.05);
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
            white-space: nowrap;
        }

        .search-btn,
        .new-btn {
            border: 1px solid transparent;
            color: #fff;
            background: var(--primary);
        }

        .reset-btn {
            border: 1px solid var(--line);
            color: var(--muted);
            background: #fff;
        }

        .toolbar-actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex: 0 0 auto;
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
            right: 14px;
            top: 50%;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 5px solid var(--muted);
            transform: translateY(-50%);
            pointer-events: none;
        }

        .entries-menu {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            z-index: 10;
            display: none;
            min-width: 100%;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fff;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .entries-dropdown.is-open .entries-menu {
            display: grid;
        }

        .entries-option {
            min-height: 36px;
            padding: 0 14px;
            border: 0;
            background: #fff;
            text-align: left;
            cursor: pointer;
            font-size: 12px;
            color: var(--ink);
        }

        .entries-option:hover {
            color: #fff;
            background: var(--primary);
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            min-width: 1000px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
            font-size: 13px;
            text-align: left;
            white-space: nowrap;
            vertical-align: middle;
        }

        th {
            color: #fff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            font-weight: 700;
        }

        tbody tr:hover {
            background: rgba(15, 118, 110, .045);
        }

        .text-strong {
            font-weight: 700;
        }

        .number-cell {
            text-align: right;
        }

        .dayfuel-register-page .list-panel {
            overflow: hidden;
            max-width: 100%;
        }

        .dayfuel-register-page .toolbar {
            position: relative;
            z-index: 40;
        }

        .dayfuel-register-page .entries-menu {
            z-index: 80;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: var(--muted);
            font-size: 14px;
            font-weight: 700;
        }

        .open-filter-prompt-btn {
            margin-top: 14px;
            min-height: 38px;
            background: var(--primary);
            color: #ffffff;
            border: none;
            padding: 0 20px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }

        .pagination-bar {
            padding: 16px 18px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .date-input {
            min-height: 31px;
            padding: 0 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #fbfcfe;
            font: inherit;
            font-size: 11px;
            outline: none;
            width: 100%;
        }

        .search-input {
            min-height: 31px;
            padding: 0 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #fbfcfe;
            font: inherit;
            font-size: 11px;
            outline: none;
            width: 100%;
        }

        .search-form {
            width: min(100%, 1050px);
            display: grid;
            grid-template-columns: minmax(180px, 1fr) auto 132px auto 132px 74px 66px 116px;
            align-items: center;
            gap: 8px;
        }

        .date-filter-text {
            font-size: 12px;
            font-weight: 700;
            color: var(--primary-dark);
            white-space: nowrap;
        }

        @media (max-width: 940px) {
            .dayfuel-register-workspace.app-shell-with-sidebar {
                grid-template-columns: 1fr;
                margin: 0;
                width: 100%;
                border-radius: 0;
            }
        }

        @media (max-width: 760px) {
            .site-header-inner {
                grid-template-columns: 1fr;
                text-align: center;
                padding: 12px;
            }

            .header-title {
                font-size: 18px;
            }

            .header-actions {
                justify-self: center;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
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

            .pagination-bar {
                flex-direction: column;
                text-align: center;
            }

            .search-btn,
            .reset-btn,
            .new-btn {
                width: 100%;
            }

            h1 {
                font-size: 22px;
            }
        }
    </style>
    @include('partials.theme')
</head>

<body>
    @php
        $defaultFromDate = request('from_date', now()->toDateString());
        $defaultToDate = request('to_date', now()->toDateString());
    @endphp

    <div class="modal-overlay" id="dateFilterModal">
        <div class="modal-window">
            <div class="modal-header">
                <h3>Select Date Interval</h3>
                <button type="button" class="modal-close-btn" id="closeModalBtn">&times;</button>
            </div>
            <form method="GET" action="{{ route('RegisterProductWiseSales') }}" id="dateRangeForm">
                <div class="modal-body">
                    <div class="popup-form-grid">
                        
                        <div class="form-group">
                            <label>From Date</label>
                            <div class="date-picker">
                                <input type="hidden" name="from_date" id="modal_from_date" value="{{ $defaultFromDate }}">
                                <input class="popup-input date-picker-input" type="text" value="{{ \Carbon\Carbon::parse($defaultFromDate)->format('d-m-Y') }}" placeholder="DD-MM-YYYY" readonly>
                                <div class="date-picker-panel"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>To Date</label>
                            <div class="date-picker">
                                <input type="hidden" name="to_date" id="modal_to_date" value="{{ $defaultToDate }}">
                                <input class="popup-input date-picker-input" type="text" value="{{ \Carbon\Carbon::parse($defaultToDate)->format('d-m-Y') }}" placeholder="DD-MM-YYYY" readonly>
                                <div class="date-picker-panel"></div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                    </div>
                    <div class="modal-footer-actions">
                        <button type="button" class="reset-btn" id="modalClearBtn" style="min-height:40px; padding:0 20px;">Reset</button>
                        <button type="submit" class="search-btn" style="min-height:40px; padding:0 24px;">Generate Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo">
                <span class="site-logo-icon">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>
            <div class="header-title">Product Wise Sales Register</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar dayfuel-register-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="dayfuel-register-page">
            <div class="list-shell">
                <section class="page-title">
                    <div>
                        <p class="eyebrow">Registers</p>
                        <h1>Product Wise Sales Register</h1>
                    </div>
                    <span class="record-count">
                        {{ $items->total() }} {{ $items->total() === 1 ? 'record' : 'records' }}
                    </span>
                </section>

                @if (session('error'))
                <div class="form-alert error">
                    {{ session('error') }}
                </div>
                @endif

                <section class="list-panel">
                    <div class="toolbar">
                        
                        <form class="search-form" method="GET" action="{{ route('RegisterProductWiseSales') }}">
                            <input type="search" class="search-input" name="search" value="{{ $search }}" placeholder="Search item name...">
                            
                            <span class="date-filter-text">From</span>
                            <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                            <input class="date-input auto-date-mask" type="text" placeholder="DD-MM-YYYY" maxlength="10" 
                                   value="{{ request('from_date') ? \Carbon\Carbon::parse(request('from_date'))->format('d-m-Y') : '' }}" autocomplete="off">

                            <span class="date-filter-text">To</span>
                            <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                            <input class="date-input auto-date-mask" type="text" placeholder="DD-MM-YYYY" maxlength="10" 
                                   value="{{ request('to_date') ? \Carbon\Carbon::parse(request('to_date'))->format('d-m-Y') : '' }}" autocomplete="off">
                            
                            <button type="submit" class="search-btn">Search</button>
                            <a href="{{ route('RegisterProductWiseSales') }}" class="reset-btn">Clear</a>

                            <div class="entries-dropdown">
                                <input type="hidden" name="per_page" value="{{ $perPage }}">
                                <button class="entries-toggle" type="button" aria-haspopup="listbox" aria-expanded="false">
                                    {{ $perPage }} Entries
                                </button>
                                <div class="entries-menu" role="listbox">
                                    @foreach ($perPageOptions as $option)
                                    <button class="entries-option {{ $perPage === $option ? 'is-selected' : '' }}" type="button" role="option" aria-selected="{{ $perPage === $option ? 'true' : 'false' }}" data-per-page="{{ $option }}">{{ $option }} Entries</button>
                                    @endforeach
                                </div>
                            </div>
                        </form>

                        <div class="toolbar-actions">
                            @if ($items->count())
                            <a href="{{ route('Product_Wise_Sales_Register_pdf.pdf', request()->query()) }}" class="new-btn" style="background: #be123c;" target="_blank" data-themed-export>PDF</a>
                            <a href="{{ route('productwisesales.excel', request()->query()) }}" class="new-btn" style="background: #166534;" data-themed-export>Excel</a>
                            @endif
                        </div>
                    </div>

                    <div style="padding: 10px 14px; background: #f8fafc; border-bottom: 1px solid var(--line);">
                        <div class="active-filter-bar">
                            @if(request('from_date') && request('to_date'))
                                <span class="summary-badge">Interval Scope: {{ \Carbon\Carbon::parse(request('from_date'))->format('d-m-Y') }} to {{ \Carbon\Carbon::parse(request('to_date'))->format('d-m-Y') }}</span>
                                <button type="button" class="change-interval-btn" id="openFilterBtn">Change Interval</button>
                            @else
                                <span class="summary-badge" style="background:#f1f5f9; color:#475569; border-color:#e2e8f0;">Select Date Range First</span>
                                <button type="button" class="change-interval-btn" id="openFilterBtn" style="background:var(--primary); color:#fff;">Select Dates</button>
                            @endif
                        </div>
                    </div>

                    @if ($items->count())
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 80px;">Sr.</th>
                                    <th>Item Name</th>
                                    <th class="number-cell">Quantity</th>
                                    <th class="number-cell">Amount (Sales)</th>
                                    <th class="number-cell">Contribution (%)</th>
                                    <th class="number-cell">Cumulative (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                <tr>
                                    <td>{{ $items->firstItem() + $loop->index }}</td>
                                    <td class="text-strong" style="color: var(--primary-dark);">{{ $item->item_name }}</td>
                                    <td class="number-cell">{{ number_format($item->total_quantity, 2) }}</td>
                                    <td class="number-cell">{{ number_format($item->total_amount, 2) }}</td>
                                    <td class="number-cell" style="color: #2563eb; font-weight: 700;">{{ number_format($item->contribution_pct, 2) }}%</td>
                                    <td class="number-cell">
                                        <span style="padding: 3px 8px; background: #f8fafc; border-radius: 4px; border: 1px solid var(--line); font-weight: 600;">
                                            {{ number_format($item->cumulative_pct, 2) }}%
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                                <tr style="background: #f8fafc; font-weight: 800; border-top: 2px solid var(--primary);">
                                    <td colspan="3" class="text-strong">Total Summary Matrix:</td>
                                    <td class="number-cell" style="color: var(--primary-dark); font-size: 14px;">{{ number_format($totalSalesAmount, 2) }}</td>
                                    <td class="number-cell">100.00%</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state">
                        <p style="margin:0;">{{ $hasDateRange ? 'No records found for the selected parameters.' : 'Please select From and To date to view the register.' }}</p>
                        <button type="button" class="open-filter-prompt-btn" id="promptFilterBtn">Open Date Interval Selector</button>
                    </div>
                    @endif

                    <div class="pagination-bar">
                        <div>
                            {{ $items->total() ? "Showing {$items->firstItem()} to {$items->lastItem()} of {$items->total()}" : "Showing 0 records" }}
                        </div>
                        @include('partials.compact-pagination', ['paginator' => $items])
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        document.querySelectorAll('.auto-date-mask').forEach(input => {
            input.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, ''); 
                let formattedValue = '';

                if (value.length > 0) { formattedValue = value.substring(0, 2); }
                if (value.length > 2) { formattedValue += '-' + value.substring(2, 4); }
                if (value.length > 4) { formattedValue += '-' + value.substring(4, 8); }
                
                e.target.value = formattedValue;
                
                const hiddenInput = e.target.previousElementSibling;
                if (formattedValue.length === 10) {
                    const parts = formattedValue.split('-');
                    hiddenInput.value = `${parts[2]}-${parts[1]}-${parts[0]}`;
                } else {
                    hiddenInput.value = '';
                }
            });
        });

        const modal = document.getElementById('dateFilterModal');
        const openFilterBtn = document.getElementById('openFilterBtn');
        const promptFilterBtn = document.getElementById('promptFilterBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const modalClearBtn = document.getElementById('modalClearBtn');
        const dateRangeForm = document.getElementById('dateRangeForm');
        const hasDateRange = @json($hasDateRange);

        if (!hasDateRange) {
            modal.classList.add('is-active');
        }

        if (openFilterBtn) openFilterBtn.addEventListener('click', () => modal.classList.add('is-active'));
        if (promptFilterBtn) promptFilterBtn.addEventListener('click', () => modal.classList.add('is-active'));
        if (closeModalBtn) closeModalBtn.addEventListener('click', () => modal.classList.remove('is-active'));
        
        modalClearBtn.addEventListener('click', () => { window.location.href = "{{ route('RegisterProductWiseSales') }}"; });
        modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.remove('is-active'); });

        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const weekDays = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
        const padDate = (value) => String(value).padStart(2, '0');
        const toIsoDate = (date) => `${date.getFullYear()}-${padDate(date.getMonth() + 1)}-${padDate(date.getDate())}`;
        const toDisplayDate = (isoDate) => {
            const parts = String(isoDate || '').split('-');
            return parts.length === 3 ? `${parts[2]}-${parts[1]}-${parts[0]}` : '';
        };
        const fromIsoDate = (isoDate) => {
            const parts = String(isoDate || '').split('-').map(Number);
            return parts.length === 3 ? new Date(parts[0], parts[1] - 1, parts[2]) : new Date();
        };
        const closeDatePickers = (except = null) => {
            document.querySelectorAll('.date-picker.is-open').forEach((picker) => {
                if (picker !== except) picker.classList.remove('is-open');
            });
        };
        const renderCalendar = (picker, viewDate) => {
            const hiddenInput = picker.querySelector('input[type="hidden"]');
            const displayInput = picker.querySelector('.date-picker-input');
            const panel = picker.querySelector('.date-picker-panel');
            const selectedIso = hiddenInput.value;
            const year = viewDate.getFullYear();
            const month = viewDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const startDate = new Date(year, month, 1 - firstDay.getDay());

            let html = `
                <div class="calendar-header">
                    <button type="button" class="calendar-nav" data-calendar-nav="-1">&lsaquo;</button>
                    <div class="calendar-month">${monthNames[month]} ${year}</div>
                    <button type="button" class="calendar-nav" data-calendar-nav="1">&rsaquo;</button>
                </div>
                <div class="calendar-grid">
                    ${weekDays.map((day) => `<span class="calendar-weekday">${day}</span>`).join('')}
            `;

            for (let index = 0; index < 42; index += 1) {
                const day = new Date(startDate);
                day.setDate(startDate.getDate() + index);
                const isoDate = toIsoDate(day);
                const classes = [
                    'calendar-day',
                    day.getMonth() !== month ? 'is-muted' : '',
                    isoDate === selectedIso ? 'is-selected' : ''
                ].filter(Boolean).join(' ');

                html += `<button type="button" class="${classes}" data-calendar-date="${isoDate}">${day.getDate()}</button>`;
            }

            html += '</div>';
            panel.innerHTML = html;

            panel.querySelectorAll('[data-calendar-nav]').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.stopPropagation();
                    viewDate.setMonth(viewDate.getMonth() + Number(button.dataset.calendarNav));
                    renderCalendar(picker, viewDate);
                });
            });

            panel.querySelectorAll('[data-calendar-date]').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.stopPropagation();
                    hiddenInput.value = button.dataset.calendarDate;
                    displayInput.value = toDisplayDate(button.dataset.calendarDate);
                    picker.classList.remove('is-open');
                });
            });
        };

        document.querySelectorAll('.date-picker').forEach((picker) => {
            const hiddenInput = picker.querySelector('input[type="hidden"]');
            const displayInput = picker.querySelector('.date-picker-input');
            let viewDate = fromIsoDate(hiddenInput.value);

            displayInput.addEventListener('click', (event) => {
                event.stopPropagation();
                closeDatePickers(picker);
                viewDate = fromIsoDate(hiddenInput.value);
                renderCalendar(picker, viewDate);
                picker.classList.add('is-open');
            });
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('.date-picker')) closeDatePickers();
        });

        dateRangeForm.addEventListener('submit', (event) => {
            const fromDate = document.getElementById('modal_from_date');
            const toDate = document.getElementById('modal_to_date');

            if (!fromDate.value || !toDate.value) {
                event.preventDefault();
                const picker = (!fromDate.value ? fromDate : toDate).closest('.date-picker');
                closeDatePickers(picker);
                renderCalendar(picker, fromIsoDate(picker.querySelector('input[type="hidden"]').value));
                picker.classList.add('is-open');
            }
        });

        document.querySelectorAll('.entries-dropdown').forEach((dropdown) => {
            const toggle = dropdown.querySelector('.entries-toggle');
            const input = dropdown.querySelector('input[name="per_page"]');
            const form = dropdown.closest('form');
            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
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
        document.addEventListener('click', () => {
            document.querySelectorAll('.entries-dropdown.is-open').forEach((d) => {
                d.classList.remove('is-open');
                d.querySelector('.entries-toggle').setAttribute('aria-expanded', 'false');
            });
        });

        const applyExportThemeLinks = () => {
            let theme = 'default';
            try { theme = localStorage.getItem('fueltracker:theme') || 'default'; } catch (e) {}
            document.querySelectorAll('[data-themed-export]').forEach((link) => {
                const url = new URL(link.href, window.location.origin);
                url.searchParams.set('theme', theme);
                link.href = url.toString();
            });
        };
        applyExportThemeLinks();
    </script>
</body>
</html>
