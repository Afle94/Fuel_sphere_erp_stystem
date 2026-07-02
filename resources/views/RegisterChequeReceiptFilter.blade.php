<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cheque Receipt Register | FuelTracker</title>
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
            min-width: 1000px;
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

    <style>
        html[data-theme] .chequereceipt-register-page .entries-toggle {
            border-color: color-mix(in srgb, var(--primary) 28%, var(--line)) !important;
            color: var(--ink) !important;
            background:
                linear-gradient(135deg, var(--theme-glow), rgba(255, 255, 255, .96)),
                #ffffff !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .7) !important;
        }

        html[data-theme] .chequereceipt-register-page .entries-toggle:hover,
        html[data-theme] .chequereceipt-register-page .entries-toggle:focus {
            border-color: var(--primary) !important;
            background: #ffffff !important;
            box-shadow: 0 0 0 4px var(--theme-glow) !important;
            outline: none !important;
        }

        html[data-theme] .chequereceipt-register-page .entries-dropdown::after {
            border-top-color: var(--primary) !important;
        }

        html[data-theme] .chequereceipt-register-page .entries-menu {
            border-color: color-mix(in srgb, var(--primary) 35%, var(--line)) !important;
            box-shadow: 0 18px 38px var(--theme-glow) !important;
        }

        html[data-theme] .chequereceipt-register-page .entries-option:hover,
        html[data-theme] .chequereceipt-register-page .entries-option:focus,
        html[data-theme] .chequereceipt-register-page .entries-option.is-selected {
            color: #ffffff !important;
            background:
                linear-gradient(160deg, rgba(255, 255, 255, .34) 0%, rgba(255, 255, 255, .08) 28%, transparent 48%),
                linear-gradient(135deg, var(--primary-dark), var(--primary) 58%, var(--primary-shine)) !important;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, .32),
                0 10px 22px var(--theme-glow) !important;
        }

        .chequereceipt-register-page .list-panel {
            overflow: hidden;
            max-width: 100%;
        }

        .chequereceipt-register-page .toolbar {
            position: relative;
            z-index: 40;
        }

        .chequereceipt-register-page .entries-menu {
            z-index: 80;
        }
    </style>

</head>

<body>

    @php

    $columns = [
        'date'      => 'Date',
        'slip_no'   => 'Ref No',
        'credit'    => 'Credit',
        'debit'     => 'Debit',
        'amount'    => 'Amount',
        'cheque_no' => 'Cheque No.',
        'datet'     => 'Cheque Date',
        'narration' => 'Narration',
    ];

    $sortUrl = function (string $column) use ($sort, $direction, $search, $perPage) {
        return route('RegisterChequeReceiptFilter', [
            'search'    => $search,
            'from_date' => request('from_date'),
            'to_date'   => request('to_date'),
            'sort'      => $column,
            'direction' => $sort === $column && $direction === 'asc' ? 'desc' : 'asc',
            'per_page'  => $perPage,
        ]);
    };

    $sortMark = fn (string $column) => $sort === $column ? $direction : '';
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
                Cheque Receipt Register
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
        class="app-shell-with-sidebar dayfuel-register-workspace chequereceipt-register-workspace"
        id="dashboardPage">

        @include('partials.fueltracker-menu')

        <main class="dayfuel-register-page chequereceipt-register-page">

            <div class="list-shell">

                <section
                    class="page-title"
                    aria-labelledby="chequeReceiptRegisterTitle">

                    <div>

                        <p class="eyebrow">
                            Registers
                        </p>

                        <h1 id="chequeReceiptRegisterTitle">
                            Cheque Receipt Register
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
                            action="{{ route('RegisterChequeReceiptFilter') }}">

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
                                placeholder="Search slip, bank, cheque or narration">

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
                                href="{{ route('RegisterChequeReceiptFilter') }}"
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
                                href="{{ route('RegisterChequeReceipt.pdf', request()->query()) }}"
                                class="new-btn"
                                target="_blank"
                                rel="noopener"
                                data-themed-export>

                                PDF

                            </a>

                            <a
                                href="{{ route('RegisterChequeReceipt.excel', request()->query()) }}"
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

                                    <th class="{{ in_array($column, ['amount'], true) ? 'number-cell' : '' }}">

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

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($entries as $entry)

                                <tr>

                                    <td class="date-cell">
                                        {{ $entry->date ? \Carbon\Carbon::parse($entry->date)->format('d M Y') : '-' }}
                                    </td>

                                    <td>
                                        {{ $entry->slip_no ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $entry->credit ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $entry->debit ?? '-' }}
                                    </td>

                                    <td class="number-cell">
                                        {{ is_numeric($entry->amount) ? number_format($entry->amount, 2) : '-' }}
                                    </td>

                                    <td>
                                        {{ $entry->cheque_no ?? '-' }}
                                    </td>

                                    <td class="date-cell">
                                        {{ $entry->datet ? \Carbon\Carbon::parse($entry->datet)->format('d M Y') : '-' }}
                                    </td>

                                    <td>
                                        {{ $entry->narration ?? '-' }}
                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    @else

                    <div class="empty-state">

                        No cheque receipt records found
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

    <script>
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

            document.querySelectorAll('.entries-dropdown.is-open').forEach((dropdown) => {
                if (!dropdown.contains(event.target)) {
                    dropdown.classList.remove('is-open');
                    dropdown.querySelector('.entries-toggle').setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>

</body>

</html>
