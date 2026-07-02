<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cash Sales | FuelTracker</title>
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

        * { box-sizing: border-box; }

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

        .cash-sales-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 88px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
        }

        .cash-sales-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
        }

        .cash-sales-page {
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

        .form-panel,
        .list-panel {
            overflow: hidden;
        }

        .panel-head,
        .table-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 13px 14px;
            border-bottom: 1px solid var(--line);
            background: #fbfcfe;
        }

        .panel-head h2,
        .toolbar-title {
            margin: 0;
            color: var(--ink);
            font-size: 18px;
            font-weight: 800;
            line-height: 1.25;
        }

        .toolbar-total {
            color: var(--primary-dark);
            font-size: 12px;
            font-weight: 800;
        }

        .cash-form {
            display: grid;
            gap: 12px;
            padding: 14px;
        }

        .top-fields,
        .form-grid {
            display: grid;
            gap: 12px;
            align-items: end;
        }

        .top-fields {
            grid-template-columns: 150px 150px 170px minmax(180px, 1fr);
        }

        .form-grid {
            grid-template-columns: repeat(4, minmax(150px, 1fr));
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

        .item-dropdown {
            position: relative;
        }

        .item-dropdown-value {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .item-dropdown-button {
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

        .item-dropdown-button:hover,
        .item-dropdown-button:focus {
            border-color: rgba(15, 118, 110, 0.52);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
            outline: none;
        }

        .item-dropdown-text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .item-dropdown-arrow {
            width: 9px;
            height: 9px;
            flex: 0 0 auto;
            border-right: 2px solid currentColor;
            border-bottom: 2px solid currentColor;
            transform: rotate(45deg) translateY(-2px);
        }

        .item-dropdown-menu {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            z-index: 30;
            display: none;
            max-height: 240px;
            overflow-y: auto;
            margin: 0;
            padding: 6px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(23, 32, 51, 0.16);
            list-style: none;
        }

        .item-dropdown.is-open .item-dropdown-menu {
            display: block;
        }

        .item-dropdown-search-wrap {
            position: sticky;
            top: -6px;
            z-index: 1;
            padding: 0 0 6px;
            background: #ffffff;
        }

        .item-dropdown-search {
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

        .item-dropdown-search:focus {
            border-color: rgba(15, 118, 110, 0.52);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
        }

        .item-dropdown-option {
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

        .item-dropdown-option:hover,
        .item-dropdown-option:focus,
        .item-dropdown-option.is-selected {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            outline: none;
        }

        .item-dropdown-option:disabled {
            color: var(--muted);
            background: #ffffff;
            cursor: default;
        }

        .item-dropdown-empty {
            display: none;
            padding: 9px 10px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        .item-dropdown-empty.is-visible {
            display: block;
        }

        .number-input,
        .number-cell {
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

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 940px;
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

        .summary-card .summary-category {
            color: var(--ink);
            font-size: 13px;
            font-weight: 800;
            text-align: left;
        }

        .summary-card strong {
            color: var(--ink);
            font-size: 13px;
            text-align: right;
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
            .cash-sales-workspace.app-shell-with-sidebar {
                width: auto;
                margin: 10px;
            }

            .cash-sales-page {
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
        $cashsales = collect($cashsales ?? []);
        $totalAmount = $cashsales->sum(fn ($sale) => (float) ($sale->amount ?? 0));
        $nextRefNo = old('ref_no', $nextRefNo ?? 1);
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
            <div class="header-title">Cash Sales</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar cash-sales-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="cash-sales-page">
            <section class="page-title" aria-labelledby="cashSalesTitle">
                <div>
                    <p class="eyebrow">Transactions</p>
                    <h1 id="cashSalesTitle">Cash Sales</h1>
                </div>
                <span class="record-count">{{ $cashsales->count() }} {{ $cashsales->count() === 1 ? 'entry' : 'entries' }}</span>
            </section>

            <div class="content-grid">
                <section class="panel form-panel" aria-labelledby="cashEntryTitle">
                    <div class="panel-head">
                        <h2 id="cashEntryTitle">Cash Sale Entry</h2>
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

                    <form class="cash-form" id="cashSaleForm" method="POST" action="{{ route('cashsales.store') }}" data-store-url="{{ route('cashsales.store') }}" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" id="cashSaleFormMethod" value="PUT" disabled>

                        <div class="top-fields">
                            <div class="field">
                                <label for="refNo">Ref. No.</label>
                                <input id="refNo" type="text" name="ref_no" value="{{ $nextRefNo }}" readonly>
                            </div>

                            <div class="field">
                                <label for="slipNo">Slip No.</label>
                                <input id="slipNo" type="text" name="slip_no" value="{{ old('slip_no') }}">
                            </div>

                            <div class="field">
                                <label for="saleDate">Date</label>
                                <input id="saleDate" type="date" name="date" value="{{ $today }}">
                            </div>

                            <div class="field wide">
                                <label for="itemName">Item</label>
                                @php
                                    $selectedProduct = old('item_name', 'DIESEL');
                                @endphp
                                <div class="item-dropdown" id="productDropdown">
                                    <input type="text" class="item-dropdown-value" id="itemName" name="item_name" value="{{ $selectedProduct }}">
                                    <button type="button" class="item-dropdown-button" id="productDropdownButton" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="item-dropdown-text" id="productDropdownText">{{ $selectedProduct ?: 'Select Item' }}</span>
                                        <span class="item-dropdown-arrow" aria-hidden="true"></span>
                                    </button>
                                    <ul class="item-dropdown-menu" role="listbox" aria-label="Product list">
                                        <li class="item-dropdown-search-wrap">
                                            <input type="search" class="item-dropdown-search" id="productSearch" placeholder="Search item" autocomplete="off">
                                        </li>
                                        @forelse (($products ?? collect()) as $product)
                                            @php
                                                $productName = $product->Product_Name;
                                            @endphp
                                            <li>
                                                <button type="button" class="item-dropdown-option {{ $selectedProduct === $productName ? 'is-selected' : '' }}" data-value="{{ $productName }}" role="option" aria-selected="{{ $selectedProduct === $productName ? 'true' : 'false' }}">
                                                    {{ $productName }}
                                                </button>
                                            </li>
                                        @empty
                                            <li><button type="button" class="item-dropdown-option" disabled>No products found</button></li>
                                        @endforelse
                                        <li class="item-dropdown-empty" id="productDropdownEmpty">No matching items</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="form-grid">
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
                                <button class="action-btn clear-btn" type="reset">Clear</button>
                                <button class="action-btn cancel-edit-btn" type="button" id="cancelEditButton" hidden>Cancel Update</button>
                            </div>
                            <div class="primary-actions">
                                <button class="action-btn save-btn" type="submit" id="saveButton">Save</button>
                            </div>
                        </div>
                    </form>
                </section>

                <section class="panel list-panel" aria-labelledby="cashListTitle">
                    <div class="table-toolbar">
                        <div class="toolbar-title" id="cashListTitle">Cash Sales List</div>
                        <div class="toolbar-actions">
                            @if ($cashsales->isNotEmpty())
                                <div class="export-actions" aria-label="Cash sales export actions">
                                    <a href="{{ route('cashsales.pdf', ['date' => $today]) }}" class="export-btn" target="_blank" rel="noopener" data-themed-export>PDF</a>
                                    <a href="{{ route('cashsales.excel', ['date' => $today]) }}" class="export-btn" data-themed-export>Excel</a>
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
                                    <th>Item</th>
                                    <th class="number-cell">Qty</th>
                                    <th class="number-cell">Rate</th>
                                    <th class="number-cell">Amount</th>
                                    <th>Narration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cashsales as $sale)
                                    <tr>
                                        <td>{{ $sale->slip_no }}</td>
                                        <td>{{ $sale->item_name }}</td>
                                        <td class="number-cell">{{ number_format((float) $sale->quantity, 2) }}</td>
                                        <td class="number-cell">{{ number_format((float) $sale->rate, 2) }}</td>
                                        <td class="number-cell">{{ number_format((float) $sale->amount, 2) }}</td>
                                        <td>{{ $sale->Narration }}</td>
                                        <td>
                                            <div class="row-actions">
                                            <button
                                                type="button"
                                                class="update-btn"
                                                data-edit-sale
                                                data-update-url="{{ route('cashsales.update', $sale) }}"
                                                data-ref-no="{{ $sale->ref_no }}"
                                                data-date="{{ $sale->date }}"
                                                data-slip-no="{{ $sale->slip_no }}"
                                                data-item="{{ $sale->item_name }}"
                                                data-quantity="{{ $sale->quantity }}"
                                                data-rate="{{ $sale->rate }}"
                                                data-narration="{{ $sale->Narration }}"
                                            >
                                                Update
                                            </button>
                                            <form method="POST" action="{{ route('cashsales.destroy', $sale) }}" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-btn" data-delete-sale="Slip {{ $sale->slip_no }}">Delete</button>
                                            </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="empty-state">No cash sales entries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if (($categorySummaries ?? collect())->isNotEmpty())
                        <div class="summary-grid" aria-label="Category wise cash sales summary">
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
        const cashSaleForm = document.getElementById('cashSaleForm');
        const formMethodInput = document.getElementById('cashSaleFormMethod');
        const saveButton = document.getElementById('saveButton');
        const cancelEditButton = document.getElementById('cancelEditButton');
        const refNoInput = document.getElementById('refNo');
        const slipNoInput = document.getElementById('slipNo');
        const saleDateInput = document.getElementById('saleDate');
        const quantityInput = document.getElementById('quantity');
        const rateInput = document.getElementById('rate');
        const amountInput = document.getElementById('amount');
        const narrationInput = document.getElementById('narration');
        const productDropdown = document.getElementById('productDropdown');
        const productInput = document.getElementById('itemName');
        const productButton = document.getElementById('productDropdownButton');
        const productText = document.getElementById('productDropdownText');
        const productSearch = document.getElementById('productSearch');
        const productOptions = Array.from(document.querySelectorAll('#productDropdown .item-dropdown-option:not(:disabled)'));
        const productEmpty = document.getElementById('productDropdownEmpty');
        const latestRates = @json($latestRates ?? []);
        const formAlerts = document.querySelectorAll('.form-alert');
        const editButtons = document.querySelectorAll('[data-edit-sale]');
        const cashSalesListUrl = @json(route('cashsales'));
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

        const setDropdownValue = (value) => {
            productInput.value = value || '';
            productText.textContent = value || 'Select Item';

            productOptions.forEach((item) => {
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

        const closeProductDropdown = () => {
            productDropdown?.classList.remove('is-open');
            productButton?.setAttribute('aria-expanded', 'false');
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

        productButton?.addEventListener('click', () => {
            const isOpen = productDropdown.classList.toggle('is-open');
            productButton.setAttribute('aria-expanded', String(isOpen));

            if (isOpen && productSearch) {
                productSearch.value = '';
                filterProductOptions('');
                setTimeout(() => productSearch.focus(), 0);
            }
        });

        productSearch?.addEventListener('input', () => {
            filterProductOptions(productSearch.value);
        });

        productOptions.forEach((option) => {
            option.addEventListener('click', () => {
                setDropdownValue(option.dataset.value);
                applyLatestRate(option.dataset.value);
                closeProductDropdown();
                productButton.focus();
            });
        });

        const setCreateMode = () => {
            cashSaleForm.action = cashSaleForm.dataset.storeUrl;
            formMethodInput.disabled = true;
            saveButton.textContent = 'Save';
            cancelEditButton.hidden = true;
        };

        const setUpdateMode = (button) => {
            cashSaleForm.action = button.dataset.updateUrl;
            formMethodInput.disabled = false;
            saveButton.textContent = 'Update';
            cancelEditButton.hidden = false;

            refNoInput.value = button.dataset.refNo || button.dataset.slipNo || '';
            slipNoInput.value = button.dataset.slipNo || '';
            saleDateInput.value = button.dataset.date || '';
            quantityInput.value = toNumber(button.dataset.quantity).toFixed(2);
            rateInput.value = toNumber(button.dataset.rate).toFixed(2);
            narrationInput.value = button.dataset.narration || '';
            setDropdownValue(button.dataset.item || '');
            calculateAmount();
            closeProductDropdown();
            cashSaleForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
            slipNoInput.focus();
        };

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

            const url = new URL(cashSalesListUrl, window.location.origin);
            url.searchParams.set('date', saleDateInput.value);
            window.location.href = url.toString();
        });

        cancelEditButton.addEventListener('click', () => {
            cashSaleForm.reset();
            setTimeout(() => {
                setCreateMode();
                calculateAmount();
                setDropdownValue(productInput.value);
            }, 0);
        });

        document.addEventListener('click', (event) => {
            if (productDropdown && !productDropdown.contains(event.target)) {
                closeProductDropdown();
            }

            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && productDropdown?.classList.contains('is-open')) {
                closeProductDropdown();
                productButton?.focus();
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

        cashSaleForm.addEventListener('reset', () => {
            setTimeout(() => {
                setCreateMode();
                calculateAmount();
                setDropdownValue(productInput.value);
                closeProductDropdown();
            }, 0);
        });

        cashSaleForm.addEventListener('submit', () => {
            calculateAmount();
            saveButton.disabled = true;
            saveButton.textContent = 'Saving...';
        });

        calculateAmount();
        applyExportThemeLinks();
    </script>
</body>
</html>
