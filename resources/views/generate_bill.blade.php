<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Generate Bill | FuelTracker</title>
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
            --accent: #f59e0b;
            --danger: #b42318;
            --shadow: 0 16px 48px rgba(23, 32, 51, .10);
        }

        * { box-sizing: border-box; }

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
            z-index: 20;
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
            padding: 0 8px;
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
            overflow: hidden;
            padding: 2px;
            border-radius: 999px;
            background: #ffffff;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .18);
        }

        .app-logo-image {
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
            border: 1px solid rgba(255, 255, 255, .24);
            border-radius: 8px;
            color: #ffffff;
            background: rgba(255, 255, 255, .12);
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: background .2s ease, transform .2s ease;
        }

        .back-link:hover,
        .logout-btn:hover {
            background: rgba(255, 255, 255, .2);
            transform: translateY(-1px);
        }

        .bill-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 88px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
        }

        .bill-page {
            min-width: 0;
            padding: 14px;
        }

        .page-shell {
            display: grid;
            gap: 12px;
        }

        .page-title,
        .form-panel {
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
            padding: 16px 18px;
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

        .status-pill {
            flex: 0 0 auto;
            padding: 6px 10px;
            border-radius: 999px;
            color: var(--primary-dark);
            background: rgba(15, 118, 110, .09);
            font-size: 11px;
            font-weight: 700;
        }

        .page-title-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
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
            color: var(--danger);
            background: #fff1f0;
            border: 1px solid rgba(180, 35, 24, .22);
        }

        .bill-form {
            display: grid;
            gap: 14px;
            padding: 18px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .field {
            display: grid;
            gap: 6px;
        }

        .field.wide {
            grid-column: span 2;
        }

        .field label {
            color: var(--primary-dark);
            font-size: 13px;
            font-weight: 800;
        }

        .form-input {
            width: 100%;
            min-height: 38px;
            padding: 0 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #fbfcfe;
            font: inherit;
            font-size: 14px;
            outline: none;
        }

        .form-input:focus {
            border-color: rgba(15, 118, 110, .52);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, .13);
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
            min-height: 38px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 0 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #ffffff;
            cursor: pointer;
            font: inherit;
            font-size: 14px;
            text-align: left;
            outline: none;
        }

        .party-dropdown-button:hover,
        .party-dropdown-button:focus {
            border-color: rgba(15, 118, 110, .52);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, .13);
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
            box-shadow: 0 18px 40px rgba(23, 32, 51, .16);
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
            border-color: rgba(15, 118, 110, .52);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, .12);
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

        .bill-table-wrap {
            overflow: auto;
            border: 1px solid var(--line);
            border-radius: 12px;
        }

        .is-hidden {
            display: none !important;
        }

        .bill-table {
            width: 100%;
            min-width: 920px;
            border-collapse: collapse;
        }

        .bill-table th,
        .bill-table td {
            padding: 8px;
            border-bottom: 1px solid var(--line);
            text-align: left;
            vertical-align: middle;
        }

        .bill-table th {
            color: var(--primary-dark);
            background: #ecfdfb;
            font-size: 13px;
            font-weight: 800;
        }

        .bill-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .bill-table .form-input {
            min-height: 34px;
            padding: 0 9px;
            font-size: 13px;
        }

        .amount-input,
        .number-input {
            text-align: right;
        }

        .bill-summary {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto auto;
            gap: 12px;
            align-items: end;
        }

        .item-summary-panel {
            width: min(100%, 560px);
            display: grid;
            gap: 8px;
        }

        .item-summary-row {
            display: grid;
            grid-template-columns: minmax(160px, 1.2fr) minmax(120px, .7fr) minmax(130px, .8fr);
            gap: 12px;
        }

        .item-summary-box {
            min-height: 34px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #ffffff;
            font-size: 13px;
            font-weight: 800;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .8);
        }

        .item-summary-name {
            justify-content: flex-start;
            text-transform: uppercase;
        }

        html[data-theme] .item-summary-box {
            border-color: color-mix(in srgb, var(--primary) 24%, var(--line));
            background:
                linear-gradient(135deg, color-mix(in srgb, var(--primary) 8%, transparent), rgba(255, 255, 255, .96)),
                #ffffff;
        }

        .total-box {
            display: grid;
            gap: 6px;
            min-width: 180px;
        }

        .total-box label {
            color: var(--primary-dark);
            font-size: 13px;
            font-weight: 800;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .vehicle-action-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            align-items: end;
        }

        .primary-btn,
        .secondary-btn {
            min-height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            border-radius: 8px;
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
        }

        .primary-btn {
            border: 1px solid transparent;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .secondary-btn {
            border: 1px solid rgba(15, 118, 110, .22);
            color: var(--primary-dark);
            background: rgba(15, 118, 110, .08);
        }

        .theme-modal {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: rgba(23, 32, 51, .38);
        }

        .theme-modal.is-visible {
            display: flex;
        }

        .theme-modal-panel {
            width: min(100%, 420px);
            overflow: hidden;
            border: 1px solid rgba(220, 227, 238, .92);
            border-radius: 12px;
            background: var(--panel);
            box-shadow: 0 24px 70px rgba(23, 32, 51, .22);
        }

        .theme-modal-header {
            padding: 14px 16px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            font-size: 16px;
            font-weight: 800;
        }

        .theme-modal-body {
            padding: 16px;
            color: var(--ink);
            font-size: 14px;
            font-weight: 700;
            line-height: 1.55;
        }

        .theme-modal-actions {
            display: flex;
            justify-content: flex-end;
            padding: 0 16px 16px;
        }

        @media (max-width: 1000px) {
            .form-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 900px) {
            .site-header-inner {
                grid-template-columns: 1fr;
                gap: 8px;
                padding: 10px;
            }

            .header-actions {
                justify-self: center;
            }

            .bill-workspace.app-shell-with-sidebar {
                width: 100%;
                min-height: calc(100vh - 64px);
                display: block;
                margin: 0;
                border-radius: 0;
            }

            .field.wide,
            .form-grid {
                grid-template-columns: 1fr;
                grid-column: auto;
            }

            .bill-summary {
                grid-template-columns: 1fr;
            }

            .item-summary-row {
                grid-template-columns: 1fr;
                gap: 6px;
            }

            .actions {
                flex-direction: column;
            }

            .vehicle-action-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @include('partials.theme')
</head>

<body>
    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>

            <div class="header-title">Generate Bill</div>

            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar bill-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="bill-page">
            <div class="page-shell">
                <section class="page-title" aria-labelledby="generateBillTitle">
                    <div>
                        <p class="eyebrow">Transactions</p>
                        <h1 id="generateBillTitle">Generate Bill</h1>
                    </div>
                    <div class="page-title-actions">
                        <a class="secondary-btn" href="{{ route('generate-bill.list') }}">View All List</a>
                        <span class="status-pill">New Bill</span>
                    </div>
                </section>

                <section class="form-panel">
                    @if (session('success'))
                        <div class="form-alert success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="form-alert error">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('generate-bill.store') }}" class="bill-form" id="billForm">
                        @csrf

                        <div class="form-grid">
                            <div class="field">
                                <label for="billNo">Bill No</label>
                                <input class="form-input" id="billNo" name="bill_no" type="text" value="{{ old('bill_no', $nextBillNo) }}" maxlength="50">
                            </div>

                            <div class="field">
                                <label for="billDate">Bill Date</label>
                                <input class="form-input" id="billDate" name="bill_date" type="date" value="{{ old('bill_date', now()->toDateString()) }}" required>
                            </div>

                            <div class="field">
                                <label for="dateFrom">Date From</label>
                                <input class="form-input" id="dateFrom" name="date_from" type="date" value="{{ old('date_from') }}" required>
                            </div>

                            <div class="field">
                                <label for="dateTo">Date To</label>
                                <input class="form-input" id="dateTo" name="date_to" type="date" value="{{ old('date_to') }}" required>
                            </div>

                            <div class="field wide">
                                <label for="party">Party</label>
                                <div class="party-dropdown" id="partyDropdown">
                                    <input type="text" class="party-dropdown-value" id="party" name="party" value="{{ old('party') }}" autocomplete="off">
                                    <button type="button" class="party-dropdown-button" id="partyDropdownButton" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="party-dropdown-text" id="partyDropdownText">{{ old('party') ?: 'Select Party' }}</span>
                                        <span class="party-dropdown-arrow" aria-hidden="true"></span>
                                    </button>
                                    <ul class="party-dropdown-menu" role="listbox" aria-label="Party list">
                                        <li class="party-dropdown-search-wrap">
                                            <input type="search" class="party-dropdown-search" id="partySearch" placeholder="Search party" autocomplete="off">
                                        </li>
                                    @foreach (($creditSaleParties ?? collect()) as $partyName)
                                        <li>
                                            <button type="button" class="party-dropdown-option {{ old('party') === $partyName ? 'is-selected' : '' }}" data-value="{{ $partyName }}" role="option" aria-selected="{{ old('party') === $partyName ? 'true' : 'false' }}">
                                                {{ $partyName }}
                                            </button>
                                        </li>
                                    @endforeach
                                        <li class="party-dropdown-empty" id="partyDropdownEmpty">No matching parties</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="field wide">
                                <label for="vehicleNo">Vehicle No</label>
                                <div class="vehicle-action-row">
                                    <div class="party-dropdown" id="vehicleDropdown">
                                        <input type="text" class="party-dropdown-value" id="vehicleNo" name="vehicle_no" value="{{ old('vehicle_no') }}" data-selected="{{ old('vehicle_no') }}" autocomplete="off">
                                        <button type="button" class="party-dropdown-button" id="vehicleDropdownButton" aria-haspopup="listbox" aria-expanded="false">
                                            <span class="party-dropdown-text" id="vehicleDropdownText">{{ old('vehicle_no') ?: 'Select Party First' }}</span>
                                            <span class="party-dropdown-arrow" aria-hidden="true"></span>
                                        </button>
                                        <ul class="party-dropdown-menu" role="listbox" aria-label="Vehicle list">
                                            <li class="party-dropdown-search-wrap">
                                                <input type="search" class="party-dropdown-search" id="vehicleSearch" placeholder="Search vehicle" autocomplete="off">
                                            </li>
                                            <li class="party-dropdown-empty is-visible" id="vehicleDropdownEmpty">Select party first</li>
                                        </ul>
                                    </div>
                                    <button type="button" class="primary-btn" id="loadBillRowsBtn">Generate Bill</button>
                                </div>
                            </div>
                        </div>

                        <div class="bill-table-wrap is-hidden" id="billTableWrap">
                            <table class="bill-table">
                                <thead>
                                    <tr>
                                        <th style="width: 140px;">Date</th>
                                        <th>Vehicle No</th>
                                        <th>Slip No</th>
                                        <th>Item Name</th>
                                        <th>HSN Code</th>
                                        <th style="width: 110px;">Qty</th>
                                        <th style="width: 110px;">Rate</th>
                                        <th style="width: 130px;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="billItemsBody"></tbody>
                            </table>
                        </div>

                        <div class="bill-summary is-hidden" id="billSummary">
                            <span></span>

                            <div class="total-box">
                                <label for="totalSlips">Total No. Of Slips</label>
                                <input class="form-input number-input" id="totalSlips" type="text" value="0" readonly>
                            </div>

                            <div class="total-box">
                                <label for="totalAmount">Total</label>
                                <input class="form-input amount-input" id="totalAmount" type="text" value="0.00" readonly>
                            </div>
                        </div>

                        <div class="item-summary-panel is-hidden" id="itemSummaryPanel" aria-label="Item wise bill summary"></div>

                        <div class="actions is-hidden" id="billActions">
                            <button type="submit" class="primary-btn">Save</button>
                        </div>
                    </form>
                </section>

            </div>
        </main>
    </div>

    <template id="billRowTemplate">
        <tr>
            <td><input class="form-input" data-name="bill_date" type="date" readonly></td>
            <td><input class="form-input" data-name="vehicle_no" type="text" maxlength="255" readonly></td>
            <td><input class="form-input" data-name="slip_no" type="text" maxlength="80" readonly></td>
            <td><input class="form-input" data-name="item_name" type="text" maxlength="255" readonly></td>
            <td><input class="form-input" data-name="hsn_code" type="text" maxlength="80" readonly></td>
            <td><input class="form-input number-input" data-name="qty" type="number" min="0" step="0.001" readonly></td>
            <td><input class="form-input number-input" data-name="rate" type="number" min="0" step="0.01" readonly></td>
            <td><input class="form-input amount-input" data-name="amount" type="text" value="0.00" readonly></td>
        </tr>
    </template>

    <div class="theme-modal" id="noEntryModal" role="dialog" aria-modal="true" aria-labelledby="noEntryModalTitle" aria-hidden="true">
        <div class="theme-modal-panel">
            <div class="theme-modal-header" id="noEntryModalTitle">No Entry Found</div>
            <div class="theme-modal-body" id="noEntryModalMessage">There is no entry in particular party.</div>
            <div class="theme-modal-actions">
                <button type="button" class="primary-btn" id="noEntryModalClose">OK</button>
            </div>
        </div>
    </div>

    <script>
        const body = document.getElementById('billItemsBody');
        const template = document.getElementById('billRowTemplate');
        const billTableWrap = document.getElementById('billTableWrap');
        const billSummary = document.getElementById('billSummary');
        const itemSummaryPanel = document.getElementById('itemSummaryPanel');
        const billActions = document.getElementById('billActions');
        const loadBillRowsBtn = document.getElementById('loadBillRowsBtn');
        const totalSlips = document.getElementById('totalSlips');
        const totalAmount = document.getElementById('totalAmount');
        const dateFromInput = document.getElementById('dateFrom');
        const dateToInput = document.getElementById('dateTo');
        const partyInput = document.getElementById('party');
        const partyDropdown = document.getElementById('partyDropdown');
        const partyButton = document.getElementById('partyDropdownButton');
        const partyText = document.getElementById('partyDropdownText');
        const partySearch = document.getElementById('partySearch');
        const partyOptions = Array.from(document.querySelectorAll('#partyDropdown .party-dropdown-option:not(:disabled)'));
        const partyEmpty = document.getElementById('partyDropdownEmpty');
        const vehicleInput = document.getElementById('vehicleNo');
        const vehicleDropdown = document.getElementById('vehicleDropdown');
        const vehicleButton = document.getElementById('vehicleDropdownButton');
        const vehicleText = document.getElementById('vehicleDropdownText');
        const vehicleSearch = document.getElementById('vehicleSearch');
        const vehicleEmpty = document.getElementById('vehicleDropdownEmpty');
        const creditSaleVehiclesByParty = @json($creditSaleVehiclesByParty ?? []);
        const unbilledCreditSalesByPartyVehicle = @json($unbilledCreditSalesByPartyVehicle ?? []);
        const billedCreditSalesByPartyVehicle = @json($billedCreditSalesByPartyVehicle ?? []);
        const productCategories = @json($productCategories ?? []);
        const billPreviewUrl = @json(route('generate-bill.preview'));
        const noEntryModal = document.getElementById('noEntryModal');
        const noEntryModalTitle = document.getElementById('noEntryModalTitle');
        const noEntryModalMessage = document.getElementById('noEntryModalMessage');
        const noEntryModalClose = document.getElementById('noEntryModalClose');
        let lastNoEntryAlertKey = '';

        const showNoEntryModal = (title = 'No Entry Found', message = 'There is no entry in particular party.') => {
            if (noEntryModalTitle) {
                noEntryModalTitle.textContent = title;
            }

            if (noEntryModalMessage) {
                noEntryModalMessage.textContent = message;
            }

            noEntryModal?.classList.add('is-visible');
            noEntryModal?.setAttribute('aria-hidden', 'false');
            noEntryModalClose?.focus();
        };

        const hideNoEntryModal = () => {
            noEntryModal?.classList.remove('is-visible');
            noEntryModal?.setAttribute('aria-hidden', 'true');
        };

        const resetBillRows = () => {
            body.innerHTML = '';
            totalSlips.value = '0';
            totalAmount.value = '0.00';
            itemSummaryPanel.innerHTML = '';
            billTableWrap?.classList.add('is-hidden');
            billSummary?.classList.add('is-hidden');
            itemSummaryPanel?.classList.add('is-hidden');
            billActions?.classList.add('is-hidden');
        };

        const isDateInSelectedRange = (dateValue) => {
            const dateFrom = dateFromInput?.value || '';
            const dateTo = dateToInput?.value || '';

            if (!dateFrom || !dateTo || !dateValue) {
                return false;
            }

            return dateValue >= dateFrom && dateValue <= dateTo;
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

        let vehicleOptions = [];

        const setVehicleValue = (value, placeholder = 'Select Party First') => {
            vehicleInput.value = value || '';
            vehicleInput.dataset.selected = value || '';
            vehicleText.textContent = value || placeholder;

            vehicleOptions.forEach((item) => {
                const isSelected = item.dataset.value === value;
                item.classList.toggle('is-selected', isSelected);
                item.setAttribute('aria-selected', String(isSelected));
            });
        };

        const renderVehicleOptions = () => {
            const partyName = partyInput?.value || '';
            const selectedVehicle = vehicleInput?.dataset.selected || vehicleInput?.value || '';
            const vehicles = creditSaleVehiclesByParty[partyName] || [];
            const menu = vehicleDropdown?.querySelector('.party-dropdown-menu');

            menu?.querySelectorAll('li[data-vehicle-option]').forEach((item) => item.remove());
            vehicleOptions = [];

            if (partyName && vehicles.length > 0) {
                const allItem = document.createElement('li');
                const allOption = document.createElement('button');

                allItem.dataset.vehicleOption = 'true';
                allOption.type = 'button';
                allOption.className = 'party-dropdown-option';
                allOption.dataset.value = '';
                allOption.setAttribute('role', 'option');
                allOption.setAttribute('aria-selected', 'false');
                allOption.textContent = 'All Vehicles';
                allOption.addEventListener('click', () => selectVehicle(allOption));

                allItem.appendChild(allOption);
                menu?.insertBefore(allItem, vehicleEmpty);
                vehicleOptions.push(allOption);
            }

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

            vehicleEmpty.textContent = partyName ? 'No Vehicle Found' : 'Select party first';
            vehicleEmpty.classList.toggle('is-visible', !partyName || vehicles.length === 0);
            setVehicleValue(
                vehicles.includes(selectedVehicle) ? selectedVehicle : '',
                partyName ? (vehicles.length > 0 ? 'All Vehicles' : 'No Vehicle Found') : 'Select Party First'
            );
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
            vehicleInput.dataset.selected = '';
            renderVehicleOptions();
            resetBillRows();
            closePartyDropdown();
            vehicleButton?.focus();
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
                const isVisible = option.textContent.toLowerCase().includes(searchText);
                option.closest('li').hidden = !isVisible;
                visibleCount += isVisible ? 1 : 0;
            });

            vehicleEmpty.textContent = partyInput.value ? 'No matching vehicles' : 'Select party first';
            vehicleEmpty?.classList.toggle('is-visible', visibleCount === 0);
        };

        const selectVehicle = (option) => {
            setVehicleValue(option.dataset.value, partyInput.value ? 'All Vehicles' : 'Select Party First');
            resetBillRows();
            closeVehicleDropdown();
            vehicleButton?.focus();
        };

        const updateNames = () => {
            body.querySelectorAll('tr').forEach((row, index) => {
                row.querySelectorAll('[data-name]').forEach((input) => {
                    input.name = `items[${index}][${input.dataset.name}]`;
                });
            });
        };

        const updateTotals = () => {
            let slips = 0;
            let total = 0;
            const itemTotals = new Map();

            body.querySelectorAll('tr').forEach((row) => {
                const filled = Array.from(row.querySelectorAll('[data-name]:not([data-name="amount"])'))
                    .some((input) => input.value.trim() !== '');
                const itemName = row.querySelector('[data-name="item_name"]').value.trim();
                const qty = parseFloat(row.querySelector('[data-name="qty"]').value || '0');
                const rate = parseFloat(row.querySelector('[data-name="rate"]').value || '0');
                const amount = qty * rate;

                row.querySelector('[data-name="amount"]').value = amount.toFixed(2);

                if (filled) {
                    slips += 1;
                    total += amount;

                    if (itemName) {
                        const key = (productCategories[itemName] || itemName).toUpperCase();
                        const current = itemTotals.get(key) || { qty: 0, amount: 0 };
                        current.qty += qty;
                        current.amount += amount;
                        itemTotals.set(key, current);
                    }
                }
            });

            totalSlips.value = String(slips);
            totalAmount.value = total.toFixed(2);
            renderItemSummary(itemTotals);
        };

        const renderItemSummary = (itemTotals) => {
            itemSummaryPanel.innerHTML = '';

            if (!itemTotals || itemTotals.size === 0) {
                itemSummaryPanel?.classList.add('is-hidden');
                return;
            }

            itemTotals.forEach((values, itemName) => {
                const row = document.createElement('div');
                const name = document.createElement('div');
                const qty = document.createElement('div');
                const amount = document.createElement('div');

                row.className = 'item-summary-row';
                name.className = 'item-summary-box item-summary-name';
                qty.className = 'item-summary-box';
                amount.className = 'item-summary-box';

                name.textContent = itemName;
                qty.textContent = values.qty.toFixed(2);
                amount.textContent = values.amount.toFixed(2);

                row.append(name, qty, amount);
                itemSummaryPanel.appendChild(row);
            });

            itemSummaryPanel?.classList.remove('is-hidden');
        };

        const fillRow = (row, values = {}) => {
            Object.entries(values).forEach(([name, value]) => {
                const input = row.querySelector(`[data-name="${name}"]`);

                if (input) {
                    input.value = value ?? '';
                }
            });
        };

        const addRow = (values = {}) => {
            const row = template.content.firstElementChild.cloneNode(true);

            fillRow(row, values);
            body.appendChild(row);
            updateNames();
            updateTotals();

            return row;
        };

        const renderBillRowsForSelection = async (showPopup = false) => {
            const partyName = partyInput?.value || '';
            const vehicleNo = vehicleInput?.value || '';
            const dateFrom = dateFromInput?.value || '';
            const dateTo = dateToInput?.value || '';

            if (showPopup && partyName && (!dateFrom || !dateTo)) {
                resetBillRows();
                showNoEntryModal('Date Range Required', 'Please choose date range first.');
                return;
            }

            if (showPopup && !partyName) {
                resetBillRows();
                showNoEntryModal('Party Required', 'Please choose party first.');
                return;
            }

            if (showPopup) {
                resetBillRows();

                const params = new URLSearchParams({
                    date_from: dateFrom,
                    date_to: dateTo,
                    party: partyName,
                });

                if (vehicleNo) {
                    params.set('vehicle_no', vehicleNo);
                }

                try {
                    const response = await fetch(`${billPreviewUrl}?${params.toString()}`, {
                        headers: {
                            Accept: 'application/json',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Bill entries could not be loaded.');
                    }

                    const result = await response.json();

                    if (result.status === 'billed') {
                        const billNumbers = result.bill_numbers || [];
                        showNoEntryModal(
                            'Bill Already Generated',
                            `This party / vehicle is already billed. Bill No: ${billNumbers.join(', ')}`
                        );
                        return;
                    }

                    if (result.status !== 'ok' || !Array.isArray(result.rows) || result.rows.length === 0) {
                        showNoEntryModal();
                        return;
                    }

                    body.innerHTML = '';
                    result.rows.forEach((sale) => addRow(sale));
                    billTableWrap?.classList.remove('is-hidden');
                    billSummary?.classList.remove('is-hidden');
                    billActions?.classList.remove('is-hidden');
                    return;
                } catch (error) {
                    showNoEntryModal('Unable To Load Entries', error.message || 'Bill entries could not be loaded.');
                    return;
                }
            }

            const partyCreditSales = unbilledCreditSalesByPartyVehicle[partyName] || {};
            const allCreditSales = vehicleNo
                ? partyCreditSales[vehicleNo] || []
                : Object.values(partyCreditSales).flat();
            const creditSales = allCreditSales.filter((sale) => isDateInSelectedRange(sale.bill_date));
            const partyBilledCreditSales = billedCreditSalesByPartyVehicle[partyName] || {};
            const allBilledCreditSales = vehicleNo
                ? partyBilledCreditSales[vehicleNo] || []
                : Object.values(partyBilledCreditSales).flat();
            const billedCreditSales = allBilledCreditSales.filter((sale) => isDateInSelectedRange(sale.bill_date));

            body.innerHTML = '';

            if (creditSales.length === 0) {
                resetBillRows();

                if (showPopup && dateFrom && dateTo && partyName) {
                    const alertKey = `${dateFrom}|${dateTo}|${partyName}|${vehicleNo}`;

                    if (lastNoEntryAlertKey !== alertKey) {
                        lastNoEntryAlertKey = alertKey;
                        if (billedCreditSales.length > 0) {
                            const billNumbers = [...new Set(billedCreditSales.map((sale) => sale.bill_no).filter(Boolean))];
                            showNoEntryModal(
                                'Bill Already Generated',
                                `This party / vehicle is already billed. Bill No: ${billNumbers.join(', ')}`
                            );
                        } else {
                            showNoEntryModal();
                        }
                    }
                }

                return;
            }

            lastNoEntryAlertKey = '';
            creditSales.forEach((sale) => addRow(sale));
            billTableWrap?.classList.remove('is-hidden');
            billSummary?.classList.remove('is-hidden');
            billActions?.classList.remove('is-hidden');
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

        document.addEventListener('click', (event) => {
            if (partyDropdown && !partyDropdown.contains(event.target)) {
                closePartyDropdown();
            }

            if (vehicleDropdown && !vehicleDropdown.contains(event.target)) {
                closeVehicleDropdown();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && partyDropdown?.classList.contains('is-open')) {
                closePartyDropdown();
                partyButton?.focus();
            }

            if (event.key === 'Escape' && vehicleDropdown?.classList.contains('is-open')) {
                closeVehicleDropdown();
                vehicleButton?.focus();
            }
        });

        dateFromInput?.addEventListener('change', resetBillRows);
        dateToInput?.addEventListener('change', resetBillRows);
        loadBillRowsBtn?.addEventListener('click', () => renderBillRowsForSelection(true));
        noEntryModalClose?.addEventListener('click', hideNoEntryModal);
        noEntryModal?.addEventListener('click', (event) => {
            if (event.target === noEntryModal) {
                hideNoEntryModal();
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && noEntryModal?.classList.contains('is-visible')) {
                hideNoEntryModal();
            }
        });

        renderVehicleOptions();
        resetBillRows();
    </script>
</body>

</html>
