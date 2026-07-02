<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Card Sales | FuelTracker</title>
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

        .card-sales-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 88px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
        }

        .card-sales-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
        }

        .card-sales-page {
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

        .list-panel {
            overflow: hidden;
        }

        .form-panel {
            position: relative;
            z-index: 10;
            overflow: visible;
        }

        .form-panel.has-open-dropdown {
            z-index: 200;
        }

        .list-panel {
            position: relative;
            z-index: 1;
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

        .card-form {
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
            grid-template-columns: 130px 150px 150px 150px minmax(150px, 1fr);
        }

        .form-grid {
            grid-template-columns: repeat(3, minmax(150px, 1fr));
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

        .field input,
        .field select {
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

        .field input:focus,
        .field select:focus {
            border-color: rgba(15, 118, 110, 0.52);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
        }

        .field input[readonly] {
            color: var(--muted);
            background: #f4f7fb;
        }

        .party-dropdown {
            position: relative;
        }

        .party-dropdown.is-open {
            z-index: 120;
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
            outline: none;
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
            transform: translateY(-2px) rotate(45deg);
        }

        .party-dropdown-menu {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            z-index: 130;
            display: none;
            max-height: min(360px, calc(100vh - 260px));
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
        .cancel-edit-btn {
            border: 1px solid var(--line);
            color: var(--muted);
            background: #ffffff;
        }

        .clear-btn:hover,
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
            .card-sales-workspace.app-shell-with-sidebar {
                width: auto;
                margin: 10px;
            }

            .card-sales-page {
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
            .form-grid {
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
        $cardsales = collect($cardsales ?? []);
        $totalAmount = $cardsales->sum(fn ($sale) => (float) ($sale->Amount ?? 0));
        $today = old('date', $selectedDate ?? now()->toDateString());
        $nextInvoiceNo = old('invoice_no', $nextInvoiceNo ?? 1);
        $selectedCardType = old('Card_type', $card_types[0] ?? '');
        $selectedPerticular = old('perticulars', $Perticulars->first()?->account_perticular ?? '');
    @endphp

    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>
            <div class="header-title">Card Sales</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar card-sales-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="card-sales-page">
            <section class="page-title" aria-labelledby="cardSalesTitle">
                <div>
                    <p class="eyebrow">Transactions</p>
                    <h1 id="cardSalesTitle">Card Sales</h1>
                </div>
                <span class="record-count">{{ $cardsales->count() }} {{ $cardsales->count() === 1 ? 'entry' : 'entries' }}</span>
            </section>

            <div class="content-grid">
                <section class="panel form-panel" aria-labelledby="cardEntryTitle">
                    <div class="panel-head">
                        <h2 id="cardEntryTitle">Card Sale Entry</h2>
                    </div>

                    @if (session('success'))
                        <div class="form-alert success" id = "success-message">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="form-alert error">{{ session('error') }}</div>
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

                    <form class="card-form" id="cardSaleForm" method="POST" action="{{ route('cardsales.store') }}" data-store-url="{{ route('cardsales.store') }}" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" id="cardSaleFormMethod" value="PUT" disabled>

                        <div class="top-fields">
                            <div class="field">
                                <label for="saleDate">Date</label>
                                <input id="saleDate" type="date" name="date" value="{{ old('date', $today) }}">
                            </div>

                            <div class="field">
                                <label for="cardType">Card Type</label>
                                <div class="party-dropdown" id="cardTypeDropdown" data-card-dropdown>
                                    <input type="text" class="party-dropdown-value" id="cardType" name="Card_type" value="{{ $selectedCardType }}" autocomplete="off">
                                    <button type="button" class="party-dropdown-button" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="party-dropdown-text" data-card-dropdown-text>{{ $selectedCardType ?: 'Select card type' }}</span>
                                        <span class="party-dropdown-arrow" aria-hidden="true"></span>
                                    </button>
                                    <ul class="party-dropdown-menu" role="listbox" aria-label="Card type list">
                                        <li class="party-dropdown-search-wrap">
                                            <input type="search" class="party-dropdown-search" placeholder="Search card type" autocomplete="off">
                                        </li>
                                        @foreach ($card_types as $type)
                                            <li><button type="button" class="party-dropdown-option {{ $selectedCardType === $type ? 'is-selected' : '' }}" data-value="{{ $type }}" role="option" aria-selected="{{ $selectedCardType === $type ? 'true' : 'false' }}">{{ $type }}</button></li>
                                        @endforeach
                                        <li class="party-dropdown-empty">No matching card type</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="field">
                                <label for="batchNo">Batch No.</label>
                                <input id="batchNo" type="text" name="Batch_no" maxlength="255" value="{{ old('Batch_no') }}">
                            </div>

                            <div class="field">
                                <label for="invoiceNo">Invoice No.</label>
                                <input id="invoiceNo" type="text" name="invoice_no" value="{{ $nextInvoiceNo }}" readonly>
                            </div>

                            <div class="field">
                                <label for="amount">Amount</label>
                                <input class="number-input" id="amount" type="number" name="Amount" min="0.01" max="99999999.99" step="0.01" value="{{ old('Amount', '0.00') }}">
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="field wide">
                                <label for="perticulars">Perticulars</label>
                                <div class="party-dropdown" id="perticularsDropdown" data-card-dropdown>
                                    <input type="text" class="party-dropdown-value" id="perticulars" name="perticulars" value="{{ $selectedPerticular }}" autocomplete="off">
                                    <button type="button" class="party-dropdown-button" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="party-dropdown-text" data-card-dropdown-text>{{ $selectedPerticular ?: 'Select perticulars' }}</span>
                                        <span class="party-dropdown-arrow" aria-hidden="true"></span>
                                    </button>
                                    <ul class="party-dropdown-menu" role="listbox" aria-label="Perticulars list">
                                        <li class="party-dropdown-search-wrap">
                                            <input type="search" class="party-dropdown-search" placeholder="Search perticulars" autocomplete="off">
                                        </li>
                                        @forelse ($Perticulars as $perticular)
                                            <li><button type="button" class="party-dropdown-option {{ $selectedPerticular === $perticular->account_perticular ? 'is-selected' : '' }}" data-value="{{ $perticular->account_perticular }}" role="option" aria-selected="{{ $selectedPerticular === $perticular->account_perticular ? 'true' : 'false' }}">{{ $perticular->account_perticular }}</button></li>
                                        @empty
                                            <li><button type="button" class="party-dropdown-option" disabled>No perticulars found</button></li>
                                        @endforelse
                                        <li class="party-dropdown-empty">No matching perticulars</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="field wide">
                                <label for="narration">Narration</label>
                                <input id="narration" type="text" name="narration" value="{{ old('narration') }}">
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="secondary-actions">
                                <button class="action-btn clear-btn" type="reset">Clear</button>
                                <button class="action-btn cancel-edit-btn" type="button" id="cancelEditButton" hidden>Cancel Update</button>
                            </div>
                            <div class="primary-actions">
                                <button class="action-btn save-btn" type="submit" id="saveButton">Save</button>
                            </div>
                        </div>
                    </form>
                </section>

                <section class="panel list-panel" aria-labelledby="cardListTitle">
                    <div class="table-toolbar">
                        <div class="toolbar-title" id="cardListTitle">Card Sales List</div>
                        <div class="toolbar-actions">
                            @if ($cardsales->isNotEmpty())
                                <div class="export-actions" aria-label="Card sales export actions">
                                    <a href="{{ route('cardsales.pdf', ['date' => $today]) }}" class="export-btn" target="_blank" rel="noopener" data-themed-export>PDF</a>
                                    <a href="{{ route('cardsales.excel', ['date' => $today]) }}" class="export-btn" data-themed-export>Excel</a>
                                </div>
                            @endif
                            <div class="toolbar-total">Total: {{ number_format($totalAmount, 2) }}</div>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Batch No</th>
                                    <th>invoice no</th>
                                    <th>Card Type</th>
                                    <th>Perticulars</th>
                                    <th class="number-cell">Amount</th>
                                    <th>Narration</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cardsales as $sale)
                                    <tr>
                                        <td>{{ $sale->Batch_no }}</td>
                                        <td>{{ $sale->invoice_no }}</td>
                                        <td>{{ $sale->Card_type }}</td>
                                        <td>{{ $sale->perticulars }}</td>
                                        <td class="number-cell">{{ number_format((float) $sale->Amount, 2) }}</td>
                                        <td>{{ $sale->narration }}</td>
                                        <td>{{ $sale->date }}</td>
                                        <td>
                                            <div class="row-actions">
                                                <button
                                                    type="button"
                                                    class="update-btn"
                                                    data-edit-sale
                                                    data-update-url="{{ route('cardsales.update', $sale) }}"
                                                    data-date="{{ $sale->date }}"
                                                    data-card-type="{{ $sale->Card_type }}"
                                                    data-batch-no="{{ $sale->Batch_no }}"
                                                    data-invoice-no="{{ $sale->invoice_no }}"
                                                    data-amount="{{ $sale->Amount }}"
                                                    data-perticulars="{{ $sale->perticulars }}"
                                                    data-narration="{{ $sale->narration }}"
                                                >
                                                    Update
                                                </button>
                                                <form method="POST" action="{{ route('cardsales.destroy', $sale) }}" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-btn" data-delete-sale="Invoice {{ $sale->invoice_no }}">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="empty-state">No card sales entries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
        const cardSaleForm = document.getElementById('cardSaleForm');
        const formMethodInput = document.getElementById('cardSaleFormMethod');
        const saveButton = document.getElementById('saveButton');
        const cancelEditButton = document.getElementById('cancelEditButton');
        const saleDateInput = document.getElementById('saleDate');
        const cardTypeInput = document.getElementById('cardType');
        const batchNoInput = document.getElementById('batchNo');
        const invoiceNoInput = document.getElementById('invoiceNo');
        const amountInput = document.getElementById('amount');
        const perticularsInput = document.getElementById('perticulars');
        const narrationInput = document.getElementById('narration');
        const editButtons = document.querySelectorAll('[data-edit-sale]');
        const cardSalesListUrl = @json(route('cardsales'));
        const deleteModal = document.getElementById('deleteConfirmModal');
        const deleteSaleName = document.getElementById('deleteSaleName');
        const deleteCancelBtn = document.getElementById('deleteCancelBtn');
        const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');
        let pendingDeleteForm = null;
        const dropdowns = Array.from(document.querySelectorAll('[data-card-dropdown]'));

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

        const setDropdownValue = (dropdown, value) => {
            const input = dropdown.querySelector('.party-dropdown-value');
            const text = dropdown.querySelector('[data-card-dropdown-text]');
            const fallback = text?.textContent || '';

            input.value = value || '';
            text.textContent = value || fallback;

            dropdown.querySelectorAll('.party-dropdown-option:not(:disabled)').forEach((option) => {
                const isSelected = option.dataset.value === value;
                option.classList.toggle('is-selected', isSelected);
                option.setAttribute('aria-selected', String(isSelected));
            });
        };

        const closeDropdown = (dropdown) => {
            dropdown?.classList.remove('is-open');
            dropdown?.querySelector('.party-dropdown-button')?.setAttribute('aria-expanded', 'false');

            if (!document.querySelector('[data-card-dropdown].is-open')) {
                cardSaleForm.closest('.form-panel')?.classList.remove('has-open-dropdown');
            }
        };

        const closeAllDropdowns = (except = null) => {
            dropdowns.forEach((dropdown) => {
                if (dropdown !== except) {
                    closeDropdown(dropdown);
                }
            });
        };

        dropdowns.forEach((dropdown) => {
            const button = dropdown.querySelector('.party-dropdown-button');
            const search = dropdown.querySelector('.party-dropdown-search');
            const options = Array.from(dropdown.querySelectorAll('.party-dropdown-option:not(:disabled)'));
            const empty = dropdown.querySelector('.party-dropdown-empty');

            const filterOptions = () => {
                const query = (search?.value || '').trim().toLowerCase();
                let visibleCount = 0;

                options.forEach((option) => {
                    const isVisible = option.dataset.value.toLowerCase().includes(query);
                    option.closest('li').hidden = !isVisible;
                    visibleCount += isVisible ? 1 : 0;
                });

                empty?.classList.toggle('is-visible', visibleCount === 0);
            };

            button?.addEventListener('click', () => {
                const isOpen = !dropdown.classList.contains('is-open');
                closeAllDropdowns(dropdown);
                dropdown.classList.toggle('is-open', isOpen);
                button.setAttribute('aria-expanded', String(isOpen));
                cardSaleForm.closest('.form-panel')?.classList.toggle('has-open-dropdown', isOpen);

                if (isOpen && search) {
                    search.value = '';
                    filterOptions();
                    setTimeout(() => search.focus(), 0);
                }
            });

            search?.addEventListener('input', filterOptions);

            options.forEach((option) => {
                option.addEventListener('click', () => {
                    setDropdownValue(dropdown, option.dataset.value || '');
                    closeDropdown(dropdown);
                    button?.focus();
                });
            });
        });

        const setCreateMode = () => {
            cardSaleForm.action = cardSaleForm.dataset.storeUrl;
            formMethodInput.disabled = true;
            saveButton.textContent = 'Save';
            cancelEditButton.hidden = true;
        };

        const setUpdateMode = (button) => {
            cardSaleForm.action = button.dataset.updateUrl;
            formMethodInput.disabled = false;
            saveButton.textContent = 'Update';
            cancelEditButton.hidden = false;

            saleDateInput.value = button.dataset.date || '';
            batchNoInput.value = button.dataset.batchNo || '';
            invoiceNoInput.value = button.dataset.invoiceNo || invoiceNoInput.value;
            amountInput.value = Number(button.dataset.amount || 0).toFixed(2);
            narrationInput.value = button.dataset.narration || '';
            setDropdownValue(document.getElementById('cardTypeDropdown'), button.dataset.cardType || cardTypeInput.value);
            setDropdownValue(document.getElementById('perticularsDropdown'), button.dataset.perticulars || perticularsInput.value);
            closeAllDropdowns();
            cardSaleForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
            batchNoInput.focus();
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

        const closeDeleteModal = () => {
            deleteModal?.classList.remove('is-open');
            deleteModal?.setAttribute('aria-hidden', 'true');
            pendingDeleteForm = null;
        };

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

            const url = new URL(cardSalesListUrl, window.location.origin);
            url.searchParams.set('date', saleDateInput.value);
            window.location.href = url.toString();
        });

        cancelEditButton.addEventListener('click', () => {
            cardSaleForm.reset();
            setTimeout(() => {
                setCreateMode();
                setDropdownValue(document.getElementById('cardTypeDropdown'), cardTypeInput.value);
                setDropdownValue(document.getElementById('perticularsDropdown'), perticularsInput.value);
                closeAllDropdowns();
            }, 0);
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('[data-card-dropdown]')) {
                closeAllDropdowns();
            }

            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeAllDropdowns();
            }

            if (event.key === 'Escape' && deleteModal?.classList.contains('is-open')) {
                closeDeleteModal();
            }
        });

        cardSaleForm.addEventListener('reset', () => {
            setTimeout(() => {
                setCreateMode();
                setDropdownValue(document.getElementById('cardTypeDropdown'), cardTypeInput.value);
                setDropdownValue(document.getElementById('perticularsDropdown'), perticularsInput.value);
                closeAllDropdowns();
            }, 0);
        });

        cardSaleForm.addEventListener('submit', () => {
            saveButton.disabled = true;
            saveButton.textContent = 'Saving...';
        });

        setCreateMode();
        applyExportThemeLinks();

        setTimeout(function () {

    let message = document.getElementById('success-message');

    if(message){
        message.style.display = 'none';
    }

}, 3000);
    </script>
</body>
</html>
