<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Credit Sales | FuelTracker</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <style>
        :root {
            --bg: #f4f7fb;
            --panel: #ffffff;
            --ink: #172033;
            --muted: #657089;
            --line: #dce3ee;
            --primary: #0f766e;
            --primary-dark: #115e59;
            --danger: #b42318;
            --shadow: 0 16px 48px rgba(23, 32, 51, 0.10);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--ink);
            background: linear-gradient(135deg, #f8fbff 0%, var(--bg) 55%, #eef5f3 100%);
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 20;
            width: 100%;
            background: linear-gradient(135deg, rgba(8, 47, 73, 0.98), rgba(15, 118, 110, 0.98));
            box-shadow: 0 10px 30px rgba(23, 32, 51, 0.12);
        }

        .site-header-inner {
            width: 100%;
            min-height: 64px;
            display: grid;
            grid-template-columns: minmax(220px, 1fr) auto minmax(220px, 1fr);
            align-items: center;
            gap: 18px;
            padding: 0 12px;
        }

        .site-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #ffffff;
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
            background: #ffffff;
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
            color: #ffffff;
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
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 8px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .credit-sales-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 88px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
        }

        .credit-sales-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
        }

        .credit-sales-page {
            width: 100%;
            min-width: 0;
            margin: 0;
            padding: 14px;
        }

        .page-title,
        .panel {
            border: 1px solid rgba(220, 227, 238, 0.86);
            border-radius: 12px;
            background: var(--panel);
            box-shadow: var(--shadow);
        }

        .page-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 12px;
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
            background: rgba(15, 118, 110, 0.09);
            font-size: 11px;
            font-weight: 700;
        }

        .content-grid {
            display: grid;
            gap: 12px;
        }

        .form-panel {
            position: relative;
            z-index: 10;
            overflow: visible;
        }

        .list-panel {
            position: relative;
            z-index: 1;
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 13px 14px;
            border-bottom: 1px solid var(--line);
            background: #fbfcfe;
        }

        .panel-head h2 {
            margin: 0;
            font-size: 18px;
            line-height: 1.25;
        }

        .party-note {
            color: var(--primary-dark);
            font-size: 12px;
            font-weight: 700;
            text-align: right;
        }

        .credit-form {
            display: grid;
            gap: 12px;
            padding: 14px;
        }

        .top-fields {
            display: grid;
            grid-template-columns: 150px 170px minmax(180px, 1fr);
            gap: 12px;
            align-items: end;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(150px, 1fr));
            gap: 12px;
        }

        .field {
            display: grid;
            gap: 5px;
        }

        .field.wide {
            grid-column: span 2;
        }

        .field label {
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
        }

        .field input {
            width: 100%;
            min-height: 34px;
            padding: 0 10px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #ffffff;
            font: inherit;
            font-size: 13px;
            outline: none;
        }

        .field input:focus {
            border-color: rgba(15, 118, 110, 0.52);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
        }

        .field input[readonly] {
            color: var(--muted);
            background: #f4f7fb;
        }

        .form-alert {
            margin: 14px 14px 0;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
        }

        .form-alert.success {
            color: #067647;
            background: #ecfdf3;
            border: 1px solid rgba(6, 118, 71, 0.22);
        }

        .form-alert.error {
            color: var(--danger);
            background: #fff1f0;
            border: 1px solid rgba(180, 35, 24, 0.22);
        }

        .form-alert ul {
            margin: 6px 0 0 18px;
            padding: 0;
            font-weight: 600;
        }

        .form-alert.is-hiding {
            opacity: 0;
            transform: translateY(-4px);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .party-dropdown {
            position: relative;
        }

        .party-dropdown.is-open {
            z-index: 80;
        }

        .party-dropdown-value {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .party-dropdown-button {
            width: 100%;
            min-height: 34px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 0 10px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #ffffff;
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            text-align: left;
        }

        .party-dropdown-button:hover,
        .party-dropdown-button:focus {
            border-color: rgba(15, 118, 110, 0.52);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
            outline: none;
        }

        .party-dropdown-text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .party-dropdown-arrow {
            width: 9px;
            height: 9px;
            flex: 0 0 auto;
            border-right: 2px solid currentColor;
            border-bottom: 2px solid currentColor;
            transform: rotate(45deg) translateY(-2px);
        }

        .party-dropdown-menu {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            z-index: 90;
            display: none;
            max-height: min(320px, calc(100vh - 260px));
            overflow-y: auto;
            margin: 0;
            padding: 6px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(23, 32, 51, 0.16);
            list-style: none;
        }

        .party-dropdown.is-open .party-dropdown-menu {
            display: block;
        }

        .party-dropdown-search-wrap {
            position: sticky;
            top: -6px;
            z-index: 1;
            padding: 0 0 6px;
            background: #ffffff;
        }

        .party-dropdown-search {
            width: 100%;
            min-height: 34px;
            padding: 0 10px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #fbfcfe;
            font: inherit;
            font-size: 13px;
            outline: none;
        }

        .party-dropdown-search:focus {
            border-color: rgba(15, 118, 110, 0.52);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
        }

        .party-dropdown-option {
            width: 100%;
            min-height: 36px;
            padding: 0 10px;
            border: 0;
            border-radius: 8px;
            color: var(--ink);
            background: #ffffff;
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            text-align: left;
        }

        .party-dropdown-option:hover,
        .party-dropdown-option:focus,
        .party-dropdown-option.is-selected {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            outline: none;
        }

        .party-dropdown-option:disabled {
            color: var(--muted);
            background: #ffffff;
            cursor: default;
        }

        .party-dropdown-empty {
            display: none;
            padding: 9px 10px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        .party-dropdown-empty.is-visible {
            display: block;
        }

        .number-input {
            text-align: right;
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-top: 4px;
        }

        .secondary-actions,
        .primary-actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .action-btn {
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            border-radius: 8px;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .save-btn {
            border: 1px solid transparent;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .clear-btn,
        .view-btn,
        .cancel-edit-btn {
            border: 1px solid var(--line);
            color: var(--muted);
            background: #ffffff;
        }

        .clear-btn:hover,
        .view-btn:hover,
        .cancel-edit-btn:hover {
            color: var(--primary-dark);
            border-color: rgba(15, 118, 110, 0.36);
        }

        .update-btn {
            min-height: 30px;
            padding: 0 12px;
            border: 1px solid rgba(15, 118, 110, 0.24);
            border-radius: 8px;
            color: var(--primary-dark);
            background: rgba(15, 118, 110, 0.08);
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 800;
        }

        .delete-btn {
            min-height: 30px;
            padding: 0 12px;
            border: 1px solid rgba(180, 35, 24, 0.16);
            border-radius: 8px;
            color: #b42318;
            background: #fff1f0;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 800;
        }

        .update-btn:hover {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .delete-btn:hover {
            color: #ffffff;
            background: #b42318;
        }

        .row-actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .export-actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .export-btn {
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            border: 1px solid transparent;
            border-radius: 8px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .export-btn:hover,
        .export-btn:focus {
            outline: none;
        }

        .delete-modal {
            position: fixed;
            inset: 0;
            z-index: 80;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: rgba(15, 23, 42, 0.45);
        }

        .delete-modal.is-open {
            display: flex;
        }

        .delete-dialog {
            width: min(420px, 100%);
            padding: 18px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.24);
        }

        .delete-dialog-title {
            margin: 0 0 8px;
            font-size: 18px;
        }

        .delete-dialog-body {
            margin: 0 0 16px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        .delete-dialog-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .modal-no-btn,
        .modal-yes-btn {
            min-height: 34px;
            padding: 0 14px;
            border-radius: 8px;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 800;
        }

        .modal-no-btn {
            border: 1px solid var(--line);
            color: var(--muted);
            background: #ffffff;
        }

        .modal-yes-btn {
            border: 1px solid transparent;
            color: #ffffff;
            background: #b42318;
        }

        .list-panel {
            overflow: hidden;
        }

        .table-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px;
            border-bottom: 1px solid var(--line);
            background: #fbfcfe;
        }

        .toolbar-title {
            color: var(--ink);
            font-size: 14px;
            font-weight: 800;
        }

        .toolbar-total {
            color: var(--primary-dark);
            font-size: 12px;
            font-weight: 800;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1120px;
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
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            font-weight: 800;
        }

        tbody tr:hover {
            background: color-mix(in srgb, var(--primary) 8%, #ffffff);
        }

        .number-cell {
            text-align: right;
        }

        .empty-state {
            padding: 34px 16px;
            color: var(--muted);
            font-size: 14px;
            font-weight: 700;
            text-align: center;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 8px;
            padding: 12px;
            border-top: 1px solid var(--line);
            background: #fbfcfe;
        }

        .summary-card {
            min-height: 48px;
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(82px, 0.7fr) minmax(96px, 0.8fr);
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #ffffff;
        }

        .summary-card span,
        .summary-card strong {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .summary-card span {
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
        }

        .summary-card strong {
            color: var(--ink);
            font-size: 13px;
            text-align: right;
        }

        .summary-card .summary-category {
            color: var(--ink);
            font-size: 13px;
            font-weight: 800;
            text-align: left;
        }

        .modify-note {
            padding: 10px 12px 12px;
            color: var(--primary-dark);
            font-size: 12px;
            font-weight: 800;
            text-align: center;
        }

        @media (max-width: 980px) {
            .site-header-inner {
                grid-template-columns: 1fr;
                gap: 8px;
                padding: 10px;
            }

            .header-title,
            .header-actions {
                justify-self: center;
            }

            .top-fields,
            .form-grid {
                grid-template-columns: 1fr 1fr;
            }

            .field.wide {
                grid-column: span 2;
            }
        }

        @media (max-width: 640px) {
            .credit-sales-workspace.app-shell-with-sidebar {
                width: auto;
                margin: 10px;
            }

            .credit-sales-page {
                padding: 10px;
            }

            .page-title,
            .panel-head,
            .table-toolbar,
            .form-actions {
                align-items: flex-start;
                flex-direction: column;
            }

            .top-fields,
            .form-grid,
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .field.wide {
                grid-column: span 1;
            }
        }
    </style>
    @include('partials.theme')
</head>
<body>
    @php
        $creditsales = collect($creditsales ?? []);
        $totalAmount = $creditsales->sum(fn ($sale) => (float) ($sale->amount ?? 0));
        $totalQty = $creditsales->sum(fn ($sale) => (float) ($sale->quantity ?? 0));
        $lastRefNo = $creditsales
            ->pluck('ref_no')
            ->filter(fn ($refNo) => is_numeric($refNo))
            ->map(fn ($refNo) => (int) $refNo)
            ->max();
        $nextRefNo = old('ref_no', $lastRefNo ? $lastRefNo + 1 : 1);
        $today = old('date', $selectedDate ?? now()->toDateString());
    @endphp

    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>
            <div class="header-title">Credit Sales</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar credit-sales-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="credit-sales-page">
            <section class="page-title" aria-labelledby="creditSalesTitle">
                <div>
                    <p class="eyebrow">Transactions</p>
                    <h1 id="creditSalesTitle">Credit Sales</h1>
                </div>
                <span class="record-count">{{ $creditsales->count() }} {{ $creditsales->count() === 1 ? 'entry' : 'entries' }}</span>
            </section>

            <div class="content-grid">
                <section class="panel form-panel" aria-labelledby="creditEntryTitle">
                    <div class="panel-head">
                        <h2 id="creditEntryTitle">Credit Sale Entry</h2>
                    </div>

                    @if (session('success'))
                        <div class="form-alert success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="form-alert error">
                            Please fix the highlighted details.
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="credit-form" id="creditSaleForm" method="POST" action="{{ route('creditsales.store') }}" data-store-url="{{ route('creditsales.store') }}" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" id="creditSaleFormMethod" value="PUT" disabled>
                        <div class="top-fields">
                            <div class="field">
                                <label for="refNo">Ref. No.</label>
                                <input id="refNo" type="text" name="ref_no" value="{{ $nextRefNo }}">
                            </div>

                            <div class="field">
                                <label for="saleDate">Date</label>
                                <input id="saleDate" type="date" name="date" value="{{ $today }}">
                            </div>

                            <div class="field">
                                <label for="slipNo">Slip No.</label>
                                <input id="slipNo" type="text" name="slip_no" value="{{ old('slip_no') }}">
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="field wide">
                                <label for="partyName">Party</label>
                                @php
                                    $selectedParty = old('Party_name');
                                @endphp
                                <div class="party-dropdown" id="partyDropdown">
                                    <input type="text" class="party-dropdown-value" id="partyName" name="Party_name" value="{{ $selectedParty }}">
                                    <button type="button" class="party-dropdown-button" id="partyDropdownButton" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="party-dropdown-text" id="partyDropdownText">{{ $selectedParty ?: 'Select Party' }}</span>
                                        <span class="party-dropdown-arrow" aria-hidden="true"></span>
                                    </button>
                                    <ul class="party-dropdown-menu" role="listbox" aria-label="Party list">
                                        <li class="party-dropdown-search-wrap">
                                            <input type="search" class="party-dropdown-search" id="partySearch" placeholder="Search party" autocomplete="off">
                                        </li>
                                        @forelse (($parties ?? collect()) as $party)
                                            @php
                                                $partyName = $party->account_perticular;
                                            @endphp
                                            <li>
                                                <button type="button" class="party-dropdown-option {{ $selectedParty === $partyName ? 'is-selected' : '' }}" data-value="{{ $partyName }}" role="option" aria-selected="{{ $selectedParty === $partyName ? 'true' : 'false' }}">
                                                    {{ $partyName }}
                                                </button>
                                            </li>
                                        @empty
                                            <li><button type="button" class="party-dropdown-option" disabled>No parties found</button></li>
                                        @endforelse
                                        <li class="party-dropdown-empty" id="partyDropdownEmpty">No matching parties</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="field">
                                <label for="vehicleNo">Vehicle No.</label>
                                @php
                                    $selectedVehicle = old('vehicle_no');
                                @endphp
                                <div class="party-dropdown" id="vehicleDropdown">
                                    <input type="text" class="party-dropdown-value" id="vehicleNo" name="vehicle_no" value="{{ $selectedVehicle }}">
                                    <button type="button" class="party-dropdown-button" id="vehicleDropdownButton" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="party-dropdown-text" id="vehicleDropdownText">{{ $selectedVehicle ?: 'Select Vehicle' }}</span>
                                        <span class="party-dropdown-arrow" aria-hidden="true"></span>
                                    </button>
                                    <ul class="party-dropdown-menu" role="listbox" aria-label="Vehicle list">
                                        <li class="party-dropdown-search-wrap">
                                            <input type="search" class="party-dropdown-search" id="vehicleSearch" placeholder="Search vehicle" autocomplete="off">
                                        </li>
                                        <li class="party-dropdown-empty is-visible" id="vehicleDropdownEmpty">Select party first</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="field">
                                <label for="itemName">Item</label>
                                @php
                                    $selectedProduct = old('item_name', 'DIESEL');
                                @endphp
                                <div class="party-dropdown" id="productDropdown">
                                    <input type="text" class="party-dropdown-value" id="itemName" name="item_name" value="{{ $selectedProduct }}">
                                    <button type="button" class="party-dropdown-button" id="productDropdownButton" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="party-dropdown-text" id="productDropdownText">{{ $selectedProduct ?: 'Select Item' }}</span>
                                        <span class="party-dropdown-arrow" aria-hidden="true"></span>
                                    </button>
                                    <ul class="party-dropdown-menu" role="listbox" aria-label="Product list">
                                        <li class="party-dropdown-search-wrap">
                                            <input type="search" class="party-dropdown-search" id="productSearch" placeholder="Search item" autocomplete="off">
                                        </li>
                                        @forelse (($products ?? collect()) as $product)
                                            @php
                                                $productName = $product->Product_Name;
                                            @endphp
                                            <li>
                                                <button type="button" class="party-dropdown-option {{ $selectedProduct === $productName ? 'is-selected' : '' }}" data-value="{{ $productName }}" role="option" aria-selected="{{ $selectedProduct === $productName ? 'true' : 'false' }}">
                                                    {{ $productName }}
                                                </button>
                                            </li>
                                        @empty
                                            <li><button type="button" class="party-dropdown-option" disabled>No products found</button></li>
                                        @endforelse
                                        <li class="party-dropdown-empty" id="productDropdownEmpty">No matching items</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="field">
                                <label for="quantity">Qty</label>
                                <input class="number-input js-calc" id="quantity" type="number" name="quantity" step="0.01" value="{{ old('quantity', '0.00') }}">
                            </div>

                            <div class="field">
                                <label for="rate">Rate</label>
                                <input class="number-input js-calc" id="rate" type="number" name="rate" step="0.01" value="{{ old('rate', ($latestRates[$selectedProduct] ?? '0.00')) }}">
                            </div>

                            <div class="field">
                                <label for="amount">Amount</label>
                                <input class="number-input" id="amount" type="number" name="amount" step="0.01" value="{{ old('amount', '0.00') }}" readonly>
                            </div>

                            <div class="field wide">
                                <label for="narration">Narration</label>
                                <input id="narration" type="text" name="Narration" value="{{ old('Narration') }}">
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="secondary-actions">
                                <button class="action-btn view-btn" type="button">View All Invoices</button>
                                <button class="action-btn clear-btn" type="reset" id="clearButton">Clear</button>
                                <button class="action-btn cancel-edit-btn" type="button" id="cancelEditButton" hidden>Cancel Update</button>
                            </div>
                            <div class="primary-actions">
                                <button class="action-btn save-btn" type="submit" id="saveButton">Save</button>
                            </div>
                        </div>
                    </form>
                </section>

                <section class="panel list-panel" aria-labelledby="creditListTitle">
                    <div class="table-toolbar">
                        <div class="toolbar-title" id="creditListTitle">Credit Sales List</div>
                        <div class="toolbar-actions">
                            @if ($creditsales->isNotEmpty())
                                <div class="export-actions" aria-label="Credit sales export actions">
                                    <a href="{{ route('creditsales.pdf', ['date' => $today]) }}" class="export-btn" target="_blank" rel="noopener" data-themed-export>PDF</a>
                                    <a href="{{ route('creditsales.excel', ['date' => $today]) }}" class="export-btn" data-themed-export>Excel</a>
                                </div>
                            @endif
                            <div class="toolbar-total">Total: {{ number_format($totalAmount, 2) }}</div>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Slip No.</th>
                                    <th>Party</th>
                                    <th>Vehicle No.</th>
                                    <th>Item</th>
                                    <th class="number-cell">Qty</th>
                                    <th class="number-cell">Rate</th>
                                    <th class="number-cell">Amount</th>
                                    <th>Bill No</th>
                                    <th>Narration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($creditsales as $sale)
                                    <tr>
                                        <td>{{ $sale->slip_no }}</td>
                                        <td>{{ $sale->Party_name }}</td>
                                        <td>{{ $sale->vehicle_no }}</td>
                                        <td>{{ $sale->item_name }}</td>
                                        <td class="number-cell">{{ number_format((float) $sale->quantity, 2) }}</td>
                                        <td class="number-cell">{{ number_format((float) $sale->rate, 2) }}</td>
                                        <td class="number-cell">{{ number_format((float) $sale->amount, 2) }}</td>
                                        <td>{{ $sale->bill_no ?: '-' }}</td>
                                        <td>{{ $sale->Narration }}</td>
                                        <td>
                                            @if (empty($sale->bill_no) && empty($sale->is_bill_item_only))
                                                <div class="row-actions">
                                                    <button
                                                        type="button"
                                                        class="update-btn"
                                                        data-edit-sale
                                                        data-update-url="{{ route('creditsales.update', $sale) }}"
                                                        data-ref-no="{{ $sale->ref_no }}"
                                                        data-date="{{ $sale->date }}"
                                                        data-slip-no="{{ $sale->slip_no }}"
                                                        data-party="{{ $sale->Party_name }}"
                                                        data-vehicle-no="{{ $sale->vehicle_no }}"
                                                        data-item="{{ $sale->item_name }}"
                                                        data-quantity="{{ $sale->quantity }}"
                                                        data-rate="{{ $sale->rate }}"
                                                        data-narration="{{ $sale->Narration }}"
                                                    >
                                                        Update
                                                    </button>
                                                    <form method="POST" action="{{ route('creditsales.destroy', $sale) }}" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="delete-btn" data-delete-sale="Slip {{ $sale->slip_no }}">Delete</button>
                                                    </form>
                                                </div>
                                            @else
                                                <strong>Billed</strong>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="empty-state">No credit sales entries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if (($categorySummaries ?? collect())->isNotEmpty())
                        <div class="summary-grid" aria-label="Category wise credit sales summary">
                            @foreach ($categorySummaries as $summary)
                                <div class="summary-card">
                                    <span class="summary-category">{{ $summary->category }}</span>
                                    <strong>{{ number_format($summary->quantity, 2) }}</strong>
                                    <strong>{{ number_format($summary->amount, 2) }}</strong>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </section>
            </div>
        </main>
    </div>

    <div class="delete-modal" id="deleteConfirmModal" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle" aria-hidden="true">
        <div class="delete-dialog">
            <h2 class="delete-dialog-title" id="deleteModalTitle">Do you want to delete?</h2>
            <p class="delete-dialog-body">Are you sure you want to delete <strong id="deleteSaleName">this entry</strong>? This action cannot be undone.</p>
            <div class="delete-dialog-actions">
                <button type="button" class="modal-no-btn" id="deleteCancelBtn">No</button>
                <button type="button" class="modal-yes-btn" id="deleteConfirmBtn">Yes</button>
            </div>
        </div>
    </div>

    <script>
        const creditSaleForm = document.getElementById('creditSaleForm');
        const quantityInput = document.getElementById('quantity');
        const rateInput = document.getElementById('rate');
        const amountInput = document.getElementById('amount');
        const saveButton = document.getElementById('saveButton');
        const formMethodInput = document.getElementById('creditSaleFormMethod');
        const cancelEditButton = document.getElementById('cancelEditButton');
        const refNoInput = document.getElementById('refNo');
        const saleDateInput = document.getElementById('saleDate');
        const slipNoInput = document.getElementById('slipNo');
        const vehicleNoInput = document.getElementById('vehicleNo');
        const vehicleDropdown = document.getElementById('vehicleDropdown');
        const vehicleButton = document.getElementById('vehicleDropdownButton');
        const vehicleText = document.getElementById('vehicleDropdownText');
        const vehicleSearch = document.getElementById('vehicleSearch');
        const vehicleEmpty = document.getElementById('vehicleDropdownEmpty');
        const narrationInput = document.getElementById('narration');
        const partyDropdown = document.getElementById('partyDropdown');
        const partyInput = document.getElementById('partyName');
        const partyButton = document.getElementById('partyDropdownButton');
        const partyText = document.getElementById('partyDropdownText');
        const partySearch = document.getElementById('partySearch');
        const partyOptions = Array.from(document.querySelectorAll('#partyDropdown .party-dropdown-option:not(:disabled)'));
        const partyEmpty = document.getElementById('partyDropdownEmpty');
        const productDropdown = document.getElementById('productDropdown');
        const productInput = document.getElementById('itemName');
        const productButton = document.getElementById('productDropdownButton');
        const productText = document.getElementById('productDropdownText');
        const productSearch = document.getElementById('productSearch');
        const productOptions = Array.from(document.querySelectorAll('#productDropdown .party-dropdown-option:not(:disabled)'));
        const productEmpty = document.getElementById('productDropdownEmpty');
        const latestRates = @json($latestRates ?? []);
        const formAlerts = document.querySelectorAll('.form-alert');
        const editButtons = document.querySelectorAll('[data-edit-sale]');
        const creditSalesListUrl = @json(route('creditsales'));
        const vehiclesByParty = @json($vehiclesByParty ?? []);
        let vehicleOptions = [];
        const deleteModal = document.getElementById('deleteConfirmModal');
        const deleteSaleName = document.getElementById('deleteSaleName');
        const deleteCancelBtn = document.getElementById('deleteCancelBtn');
        const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');
        let pendingDeleteForm = null;

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

        const closeDeleteModal = () => {
            deleteModal?.classList.remove('is-open');
            deleteModal?.setAttribute('aria-hidden', 'true');
            pendingDeleteForm = null;
        };

        const toNumber = (value) => {
            const number = Number.parseFloat(value);
            return Number.isFinite(number) ? number : 0;
        };

        const calculateAmount = () => {
            amountInput.value = (toNumber(quantityInput.value) * toNumber(rateInput.value)).toFixed(2);
        };

        const setDropdownValue = (input, text, options, value, placeholder) => {
            input.value = value || '';
            text.textContent = value || placeholder;

            options.forEach((item) => {
                const isSelected = item.dataset.value === value;
                item.classList.toggle('is-selected', isSelected);
                item.setAttribute('aria-selected', String(isSelected));
            });
        };

        const setVehicleValue = (value) => {
            vehicleNoInput.value = value || '';
            vehicleText.textContent = value || 'Select Vehicle';

            vehicleOptions.forEach((item) => {
                const isSelected = item.dataset.value === value;
                item.classList.toggle('is-selected', isSelected);
                item.setAttribute('aria-selected', String(isSelected));
            });
        };

        const applyLatestRate = (itemName) => {
            if (Object.prototype.hasOwnProperty.call(latestRates, itemName)) {
                rateInput.value = toNumber(latestRates[itemName]).toFixed(2);
                calculateAmount();
            }
        };

        const renderVehicleOptions = (partyName, selectedVehicle = '') => {
            const menu = vehicleDropdown?.querySelector('.party-dropdown-menu');
            const vehicles = vehiclesByParty[partyName] || [];

            menu?.querySelectorAll('li[data-vehicle-option]').forEach((item) => item.remove());
            vehicleOptions = [];

            vehicles.forEach((vehicleNo) => {
                const item = document.createElement('li');
                const option = document.createElement('button');

                item.dataset.vehicleOption = 'true';
                option.type = 'button';
                option.className = 'party-dropdown-option';
                option.dataset.value = vehicleNo;
                option.setAttribute('role', 'option');
                option.setAttribute('aria-selected', 'false');
                option.textContent = vehicleNo;
                option.addEventListener('click', () => selectVehicle(option));

                item.appendChild(option);
                menu?.insertBefore(item, vehicleEmpty);
                vehicleOptions.push(option);
            });

            vehicleEmpty.textContent = partyName ? 'No vehicles found for this party' : 'Select party first';
            vehicleEmpty.classList.toggle('is-visible', vehicles.length === 0);
            setVehicleValue(vehicles.includes(selectedVehicle) ? selectedVehicle : '');
        };

        const closePartyDropdown = () => {
            partyDropdown?.classList.remove('is-open');
            partyButton?.setAttribute('aria-expanded', 'false');
        };

        const openPartyDropdown = () => {
            partyDropdown?.classList.add('is-open');
            partyButton?.setAttribute('aria-expanded', 'true');

            if (partySearch) {
                partySearch.value = '';
                filterPartyOptions('');
                setTimeout(() => partySearch.focus(), 0);
            }
        };

        const filterPartyOptions = (query) => {
            const searchText = query.trim().toLowerCase();
            let visibleCount = 0;

            partyOptions.forEach((option) => {
                const isVisible = option.dataset.value.toLowerCase().includes(searchText);
                option.closest('li').hidden = !isVisible;
                visibleCount += isVisible ? 1 : 0;
            });

            partyEmpty?.classList.toggle('is-visible', visibleCount === 0);
        };

        const selectParty = (option) => {
            setDropdownValue(partyInput, partyText, partyOptions, option.dataset.value, 'Select Party');
            renderVehicleOptions(option.dataset.value, '');
            closePartyDropdown();
            vehicleButton.focus();
        };

        const closeVehicleDropdown = () => {
            vehicleDropdown?.classList.remove('is-open');
            vehicleButton?.setAttribute('aria-expanded', 'false');
        };

        const openVehicleDropdown = () => {
            vehicleDropdown?.classList.add('is-open');
            vehicleButton?.setAttribute('aria-expanded', 'true');

            if (vehicleSearch) {
                vehicleSearch.value = '';
                filterVehicleOptions('');
                setTimeout(() => vehicleSearch.focus(), 0);
            }
        };

        const filterVehicleOptions = (query) => {
            const searchText = query.trim().toLowerCase();
            let visibleCount = 0;

            vehicleOptions.forEach((option) => {
                const isVisible = option.dataset.value.toLowerCase().includes(searchText);
                option.closest('li').hidden = !isVisible;
                visibleCount += isVisible ? 1 : 0;
            });

            vehicleEmpty.textContent = partyInput.value ? 'No matching vehicles' : 'Select party first';
            vehicleEmpty?.classList.toggle('is-visible', visibleCount === 0);
        };

        const selectVehicle = (option) => {
            setVehicleValue(option.dataset.value);
            closeVehicleDropdown();
            vehicleButton.focus();
        };

        const closeProductDropdown = () => {
            productDropdown?.classList.remove('is-open');
            productButton?.setAttribute('aria-expanded', 'false');
        };

        const openProductDropdown = () => {
            productDropdown?.classList.add('is-open');
            productButton?.setAttribute('aria-expanded', 'true');

            if (productSearch) {
                productSearch.value = '';
                filterProductOptions('');
                setTimeout(() => productSearch.focus(), 0);
            }
        };

        const filterProductOptions = (query) => {
            const searchText = query.trim().toLowerCase();
            let visibleCount = 0;

            productOptions.forEach((option) => {
                const isVisible = option.dataset.value.toLowerCase().includes(searchText);
                option.closest('li').hidden = !isVisible;
                visibleCount += isVisible ? 1 : 0;
            });

            productEmpty?.classList.toggle('is-visible', visibleCount === 0);
        };

        const selectProduct = (option) => {
            setDropdownValue(productInput, productText, productOptions, option.dataset.value, 'Select Item');
            applyLatestRate(option.dataset.value);
            closeProductDropdown();
            productButton.focus();
        };

        const setCreateMode = () => {
            creditSaleForm.action = creditSaleForm.dataset.storeUrl;
            formMethodInput.disabled = true;
            saveButton.textContent = 'Save';
            cancelEditButton.hidden = true;
        };

        const setUpdateMode = (button) => {
            creditSaleForm.action = button.dataset.updateUrl;
            formMethodInput.disabled = false;
            saveButton.textContent = 'Update';
            cancelEditButton.hidden = false;

            refNoInput.value = button.dataset.refNo || '';
            saleDateInput.value = button.dataset.date || '';
            slipNoInput.value = button.dataset.slipNo || '';
            quantityInput.value = toNumber(button.dataset.quantity).toFixed(2);
            rateInput.value = toNumber(button.dataset.rate).toFixed(2);
            narrationInput.value = button.dataset.narration || '';

            setDropdownValue(partyInput, partyText, partyOptions, button.dataset.party || '', 'Select Party');
            renderVehicleOptions(button.dataset.party || '', button.dataset.vehicleNo || '');
            setDropdownValue(productInput, productText, productOptions, button.dataset.item || '', 'Select Item');
            calculateAmount();
            closePartyDropdown();
            closeVehicleDropdown();
            closeProductDropdown();
            creditSaleForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
            slipNoInput.focus();
        };

        partyButton?.addEventListener('click', () => {
            if (partyDropdown.classList.contains('is-open')) {
                closePartyDropdown();
                return;
            }

            openPartyDropdown();
        });

        partySearch?.addEventListener('input', () => {
            filterPartyOptions(partySearch.value);
        });

        partyOptions.forEach((option) => {
            option.addEventListener('click', () => selectParty(option));
        });

        vehicleButton?.addEventListener('click', () => {
            if (vehicleDropdown.classList.contains('is-open')) {
                closeVehicleDropdown();
                return;
            }

            openVehicleDropdown();
        });

        vehicleSearch?.addEventListener('input', () => {
            filterVehicleOptions(vehicleSearch.value);
        });

        productButton?.addEventListener('click', () => {
            if (productDropdown.classList.contains('is-open')) {
                closeProductDropdown();
                return;
            }

            openProductDropdown();
        });

        productSearch?.addEventListener('input', () => {
            filterProductOptions(productSearch.value);
        });

        productOptions.forEach((option) => {
            option.addEventListener('click', () => selectProduct(option));
        });

        editButtons.forEach((button) => {
            button.addEventListener('click', () => setUpdateMode(button));
        });

        document.querySelectorAll('.delete-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                pendingDeleteForm = form;
                deleteSaleName.textContent = form.querySelector('[data-delete-sale]')?.dataset.deleteSale || 'this entry';
                deleteModal?.classList.add('is-open');
                deleteModal?.setAttribute('aria-hidden', 'false');
                deleteCancelBtn?.focus();
            });
        });

        deleteCancelBtn?.addEventListener('click', closeDeleteModal);

        deleteConfirmBtn?.addEventListener('click', () => {
            if (pendingDeleteForm) {
                pendingDeleteForm.submit();
            }
        });

        saleDateInput.addEventListener('change', () => {
            if (!saleDateInput.value) {
                return;
            }

            const url = new URL(creditSalesListUrl, window.location.origin);
            url.searchParams.set('date', saleDateInput.value);
            window.location.href = url.toString();
        });

        cancelEditButton.addEventListener('click', () => {
            creditSaleForm.reset();
            setTimeout(() => {
                setCreateMode();
                calculateAmount();
                setDropdownValue(partyInput, partyText, partyOptions, partyInput.value, 'Select Party');
                renderVehicleOptions(partyInput.value, vehicleNoInput.value);
                setDropdownValue(productInput, productText, productOptions, productInput.value, 'Select Item');
            }, 0);
        });

        document.addEventListener('click', (event) => {
            if (partyDropdown && !partyDropdown.contains(event.target)) {
                closePartyDropdown();
            }

            if (productDropdown && !productDropdown.contains(event.target)) {
                closeProductDropdown();
            }

            if (vehicleDropdown && !vehicleDropdown.contains(event.target)) {
                closeVehicleDropdown();
            }

            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && partyDropdown?.classList.contains('is-open')) {
                closePartyDropdown();
                partyButton?.focus();
            }

            if (event.key === 'Escape' && productDropdown?.classList.contains('is-open')) {
                closeProductDropdown();
                productButton?.focus();
            }

            if (event.key === 'Escape' && vehicleDropdown?.classList.contains('is-open')) {
                closeVehicleDropdown();
                vehicleButton?.focus();
            }

            if (event.key === 'Escape' && deleteModal?.classList.contains('is-open')) {
                closeDeleteModal();
            }
        });

        document.querySelectorAll('.js-calc').forEach((input) => {
            input.addEventListener('input', calculateAmount);
            input.addEventListener('blur', () => {
                input.value = toNumber(input.value).toFixed(2);
                calculateAmount();
            });
        });

        formAlerts.forEach((alert) => {
            setTimeout(() => {
                alert.classList.add('is-hiding');
                setTimeout(() => alert.remove(), 260);
            }, 4000);
        });

        creditSaleForm.addEventListener('reset', () => {
            setTimeout(() => {
                setCreateMode();
                calculateAmount();
                setDropdownValue(partyInput, partyText, partyOptions, partyInput.value, 'Select Party');
                renderVehicleOptions(partyInput.value, vehicleNoInput.value);
                closePartyDropdown();
                closeVehicleDropdown();
                setDropdownValue(productInput, productText, productOptions, productInput.value, 'Select Item');
                closeProductDropdown();
            }, 0);
        });

        creditSaleForm.addEventListener('submit', () => {
            calculateAmount();
            saveButton.disabled = true;
            saveButton.textContent = 'Saving...';
        });

        calculateAmount();
        renderVehicleOptions(partyInput.value, vehicleNoInput.value);
        applyExportThemeLinks();
    </script>
</body>
</html>
