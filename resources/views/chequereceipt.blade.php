<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cheque Receipt | FuelTracker</title>
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

        .cheque-receipt-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 88px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
        }

        .cheque-receipt-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
        }

        .cheque-receipt-page {
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

        .toolbar-actions,
        .export-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .toolbar-total {
            color: var(--primary-dark);
            font-size: 12px;
            font-weight: 800;
        }

        .cheque-form {
            display: grid;
            gap: 12px;
            padding: 14px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 130px 150px repeat(3, minmax(150px, 1fr));
            gap: 12px;
            align-items: end;
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
        .primary-actions,
        .row-actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .action-btn,
        .export-btn,
        .update-btn,
        .delete-btn,
        .modal-no-btn,
        .modal-yes-btn {
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            border-radius: 8px;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
        }

        .save-btn,
        .export-btn {
            border: 1px solid transparent;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .clear-btn,
        .cancel-edit-btn,
        .modal-no-btn {
            border: 1px solid var(--line);
            color: var(--muted);
            background: #ffffff;
        }

        .update-btn {
            min-height: 30px;
            padding: 0 12px;
            border: 1px solid rgba(15, 118, 110, 0.24);
            color: var(--primary-dark);
            background: rgba(15, 118, 110, 0.08);
        }

        .delete-btn,
        .modal-yes-btn {
            min-height: 30px;
            padding: 0 12px;
            border: 1px solid rgba(180, 35, 24, 0.16);
            color: #b42318;
            background: #fff1f0;
        }

        .update-btn:hover {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .delete-btn:hover,
        .modal-yes-btn {
            color: #ffffff;
            background: #b42318;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 900px;
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

            .form-grid {
                grid-template-columns: 1fr 1fr;
            }

            .field.wide {
                grid-column: span 2;
            }
        }

        @media (max-width: 640px) {
            .cheque-receipt-workspace.app-shell-with-sidebar {
                width: auto;
                margin: 10px;
            }

            .cheque-receipt-page {
                padding: 10px;
            }

            .page-title,
            .panel-head,
            .table-toolbar,
            .form-actions {
                align-items: flex-start;
                flex-direction: column;
            }

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
        $chequereceipts = collect($chequereceipts ?? $chequereceipt ?? []);
        $totalAmount = $chequereceipts->sum(fn ($receipt) => (float) ($receipt->amount ?? 0));
        $today = old('date', $selectedDate ?? now()->toDateString());
        $creditAccounts = collect($credit ?? $Credit ?? $creditAccounts ?? $CreditAccounts ?? []);
        $debitAccounts = collect($debit ?? $Debit ?? $debitAccounts ?? $DebitAccounts ?? []);
        $selectedCredit = old('credit', '');
        $selectedDebit = old('debit', '');
        $nextSlipNo = old('slip_no', $nextSlipNo ?? $next_slip_no ?? '');
        $storeUrl = \Illuminate\Support\Facades\Route::has('chequereceipt.store') ? route('chequereceipt.store') : route('chequereceipt');
        $listUrl = \Illuminate\Support\Facades\Route::has('chequereceipt') ? route('chequereceipt') : url('/chequereceipt');
        $hasUpdateRoute = \Illuminate\Support\Facades\Route::has('chequereceipt.update');
        $hasDestroyRoute = \Illuminate\Support\Facades\Route::has('chequereceipt.destroy');
        $hasPdfRoute = \Illuminate\Support\Facades\Route::has('chequereceipt.pdf');
        $hasExcelRoute = \Illuminate\Support\Facades\Route::has('chequereceipt.excel');
    @endphp

    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>
            <div class="header-title">Cheque Receipt</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar cheque-receipt-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="cheque-receipt-page">
            <section class="page-title" aria-labelledby="chequereceiptTitle">
                <div>
                    <p class="eyebrow">Transactions</p>
                    <h1 id="chequereceiptTitle">Cheque Receipt</h1>
                </div>
                <span class="record-count">{{ $chequereceipts->count() }} {{ $chequereceipts->count() === 1 ? 'entry' : 'entries' }}</span>
            </section>

            <div class="content-grid">
                <section class="panel form-panel" aria-labelledby="chequereceiptEntryTitle">
                    <div class="panel-head">
                        <h2 id="chequereceiptEntryTitle">Cheque Receipt Entry</h2>
                    </div>

                    @if (session('success'))
                        <div class="form-alert success" id="success-message">{{ session('success') }}</div>
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

                    <form class="cheque-form" id="chequereceiptForm" method="POST" action="{{ $storeUrl }}" data-store-url="{{ $storeUrl }}" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" id="chequereceiptFormMethod" value="PUT" disabled>

                        <div class="form-grid">
                            <div class="field">
                                <label for="receiptDate">Date</label>
                                <input id="receiptDate" type="date" name="date" value="{{ old('date', $today) }}">
                            </div>

                            <div class="field">
                                <label for="slipNo">Slip No.</label>
                                <input id="slipNo" type="text" name="slip_no" value="{{ old('slip_no', $nextSlipNo) }}" readonly>
                            </div>

                            <div class="field">
                                <label for="credit">Credit</label>
                                <div class="party-dropdown" id="creditDropdown" data-cheque-dropdown>
                                    <input type="text" class="party-dropdown-value" id="credit" name="credit" value="{{ $selectedCredit }}" autocomplete="off">
                                    <button type="button" class="party-dropdown-button" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="party-dropdown-text" data-cheque-dropdown-text>{{ $selectedCredit ?: 'Select credit' }}</span>
                                        <span class="party-dropdown-arrow" aria-hidden="true"></span>
                                    </button>
                                    <ul class="party-dropdown-menu" role="listbox" aria-label="Credit account list">
                                        <li class="party-dropdown-search-wrap">
                                            <input type="search" class="party-dropdown-search" placeholder="Search credit" autocomplete="off">
                                        </li>
                                        @forelse ($creditAccounts as $account)
                                            @php $accountName = is_object($account) ? ($account->account_perticular ?? '') : $account; @endphp
                                            <li><button type="button" class="party-dropdown-option {{ $selectedCredit === $accountName ? 'is-selected' : '' }}" data-value="{{ $accountName }}" role="option" aria-selected="{{ $selectedCredit === $accountName ? 'true' : 'false' }}">{{ $accountName }}</button></li>
                                        @empty
                                            <li><button type="button" class="party-dropdown-option" disabled>No credit accounts found</button></li>
                                        @endforelse
                                        @if ($selectedCredit && !$creditAccounts->contains(fn ($account) => (is_object($account) ? ($account->account_perticular ?? '') : $account) === $selectedCredit))
                                            <li><button type="button" class="party-dropdown-option is-selected" data-value="{{ $selectedCredit }}" role="option" aria-selected="true">{{ $selectedCredit }}</button></li>
                                        @endif
                                        <li class="party-dropdown-empty">No matching credit</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="field">
                                <label for="debit">Debit</label>
                                <div class="party-dropdown" id="debitDropdown" data-cheque-dropdown>
                                    <input type="text" class="party-dropdown-value" id="debit" name="debit" value="{{ $selectedDebit }}" autocomplete="off">
                                    <button type="button" class="party-dropdown-button" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="party-dropdown-text" data-cheque-dropdown-text>{{ $selectedDebit ?: 'Select debit' }}</span>
                                        <span class="party-dropdown-arrow" aria-hidden="true"></span>
                                    </button>
                                    <ul class="party-dropdown-menu" role="listbox" aria-label="Debit account list">
                                        <li class="party-dropdown-search-wrap">
                                            <input type="search" class="party-dropdown-search" placeholder="Search debit" autocomplete="off">
                                        </li>
                                        @forelse ($debitAccounts as $account)
                                            @php $accountName = is_object($account) ? ($account->account_perticular ?? '') : $account; @endphp
                                            <li><button type="button" class="party-dropdown-option {{ $selectedDebit === $accountName ? 'is-selected' : '' }}" data-value="{{ $accountName }}" role="option" aria-selected="{{ $selectedDebit === $accountName ? 'true' : 'false' }}">{{ $accountName }}</button></li>
                                        @empty
                                            <li><button type="button" class="party-dropdown-option" disabled>No debit accounts found</button></li>
                                        @endforelse
                                        @if ($selectedDebit && !$debitAccounts->contains(fn ($account) => (is_object($account) ? ($account->account_perticular ?? '') : $account) === $selectedDebit))
                                            <li><button type="button" class="party-dropdown-option is-selected" data-value="{{ $selectedDebit }}" role="option" aria-selected="true">{{ $selectedDebit }}</button></li>
                                        @endif
                                        <li class="party-dropdown-empty">No matching debit</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="field">
                                <label for="amount">Amount</label>
                                <input class="number-input" id="amount" type="number" name="amount" step="0.01" value="{{ old('amount', '0.00') }}">
                            </div>

                            <div class="field">
                                <label for="chequeNo">Cheque No.</label>
                                <input id="chequeNo" type="text" name="cheque_no" value="{{ old('cheque_no') }}" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="6 digit cheque no.">
                            </div>

                            <div class="field">
                                <label for="chequeDate">Cheque Date</label>
                                <input id="chequeDate" type="date" name="datet" value="{{ old('datet') }}">
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

                <section class="panel list-panel" aria-labelledby="chequereceiptListTitle">
                    <div class="table-toolbar">
                        <div class="toolbar-title" id="chequereceiptListTitle">Cheque Receipt List</div>
                        <div class="toolbar-actions">
                            @if ($chequereceipts->isNotEmpty() && ($hasPdfRoute || $hasExcelRoute))
                                <div class="export-actions" aria-label="Cheque Receipt export actions">
                                    @if ($hasPdfRoute)
                                        <a href="{{ route('chequereceipt.pdf', ['date' => $today]) }}" class="export-btn" target="_blank" rel="noopener" data-themed-export>PDF</a>
                                    @endif
                                    @if ($hasExcelRoute)
                                        <a href="{{ route('chequereceipt.excel', ['date' => $today]) }}" class="export-btn" data-themed-export>Excel</a>
                                    @endif
                                </div>
                            @endif
                            <div class="toolbar-total">Total: {{ number_format($totalAmount, 2) }}</div>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Slip No</th>
                                    <th>Cheque No</th>
                                    <th>Cheque Date</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                    <th class="number-cell">Amount</th>
                                    <th>Narration</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($chequereceipts as $receipt)
                                    <tr>
                                        <td>{{ $receipt->slip_no }}</td>
                                        <td>{{ $receipt->cheque_no }}</td>
                                        <td>{{ $receipt->datet }}</td>
                                        <td>{{ $receipt->credit }}</td>
                                        <td>{{ $receipt->debit }}</td>
                                        <td class="number-cell">{{ number_format((float) $receipt->amount, 2) }}</td>
                                        <td>{{ $receipt->narration }}</td>
                                        <td>{{ $receipt->date }}</td>
                                        <td>
                                            <div class="row-actions">
                                                @if ($hasUpdateRoute)
                                                    <button
                                                        type="button"
                                                        class="update-btn"
                                                        data-edit-cheque
                                                        data-update-url="{{ route('chequereceipt.update', $receipt) }}"
                                                        data-date="{{ $receipt->date }}"
                                                        data-slip-no="{{ $receipt->slip_no }}"
                                                        data-cheque-no="{{ $receipt->cheque_no }}"
                                                        data-cheque-date="{{ $receipt->datet }}"
                                                        data-credit="{{ $receipt->credit }}"
                                                        data-debit="{{ $receipt->debit }}"
                                                        data-amount="{{ $receipt->amount }}"
                                                        data-narration="{{ $receipt->narration }}"
                                                    >
                                                        Update
                                                    </button>
                                                @endif

                                                @if ($hasDestroyRoute)
                                                    <form method="POST" action="{{ route('chequereceipt.destroy', $receipt) }}" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="delete-btn" data-delete-cheque="Slip {{ $receipt->slip_no }}">Delete</button>
                                                    </form>
                                                @endif

                                                @unless ($hasUpdateRoute || $hasDestroyRoute)
                                                    <span class="toolbar-total">No actions</span>
                                                @endunless
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="empty-state">No Cheque Receipt entries found.</td>
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
            <p class="delete-dialog-body">Are you sure you want to delete <strong id="deleteChequeName">this entry</strong>? This action cannot be undone.</p>
            <div class="delete-dialog-actions">
                <button type="button" class="modal-no-btn" id="deleteCancelBtn">No</button>
                <button type="button" class="modal-yes-btn" id="deleteConfirmBtn">Yes</button>
            </div>
        </div>
    </div>

    <script>
        const chequereceiptForm = document.getElementById('chequereceiptForm');
        const formMethodInput = document.getElementById('chequereceiptFormMethod');
        const saveButton = document.getElementById('saveButton');
        const cancelEditButton = document.getElementById('cancelEditButton');
        const receiptDateInput = document.getElementById('receiptDate');
        const slipNoInput = document.getElementById('slipNo');
        const creditInput = document.getElementById('credit');
        const debitInput = document.getElementById('debit');
        const amountInput = document.getElementById('amount');
        const chequeNoInput = document.getElementById('chequeNo');
        const chequeDateInput = document.getElementById('chequeDate');
        const narrationInput = document.getElementById('narration');
        const editButtons = document.querySelectorAll('[data-edit-cheque]');
        const chequereceiptListUrl = @json($listUrl);
        const deleteModal = document.getElementById('deleteConfirmModal');
        const deleteChequeName = document.getElementById('deleteChequeName');
        const deleteCancelBtn = document.getElementById('deleteCancelBtn');
        const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');
        const dropdowns = Array.from(document.querySelectorAll('[data-cheque-dropdown]'));
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

        const setDropdownValue = (dropdown, value) => {
            const input = dropdown.querySelector('.party-dropdown-value');
            const text = dropdown.querySelector('[data-cheque-dropdown-text]');
            const fallback = input.id === 'credit' ? 'Select credit' : 'Select debit';

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

            if (!document.querySelector('[data-cheque-dropdown].is-open')) {
                chequereceiptForm.closest('.form-panel')?.classList.remove('has-open-dropdown');
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
                chequereceiptForm.closest('.form-panel')?.classList.toggle('has-open-dropdown', isOpen);

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
            chequereceiptForm.action = chequereceiptForm.dataset.storeUrl;
            formMethodInput.disabled = true;
            saveButton.textContent = 'Save';
            cancelEditButton.hidden = true;
        };

        const setUpdateMode = (button) => {
            chequereceiptForm.action = button.dataset.updateUrl;
            formMethodInput.disabled = false;
            saveButton.textContent = 'Update';
            cancelEditButton.hidden = false;

            receiptDateInput.value = button.dataset.date || '';
            slipNoInput.value = button.dataset.slipNo || '';
            setDropdownValue(document.getElementById('creditDropdown'), button.dataset.credit || creditInput.value);
            setDropdownValue(document.getElementById('debitDropdown'), button.dataset.debit || debitInput.value);
            amountInput.value = Number(button.dataset.amount || 0).toFixed(2);
            chequeNoInput.value = button.dataset.chequeNo || '';
            chequeDateInput.value = button.dataset.chequeDate || '';
            narrationInput.value = button.dataset.narration || '';
            closeAllDropdowns();
            chequereceiptForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
            slipNoInput.focus();
        };

        editButtons.forEach((button) => {
            button.addEventListener('click', () => setUpdateMode(button));
        });

        document.querySelectorAll('.delete-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                pendingDeleteForm = form;
                deleteChequeName.textContent = form.querySelector('[data-delete-cheque]')?.dataset.deleteCheque || 'this entry';
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

        receiptDateInput.addEventListener('change', () => {
            if (!receiptDateInput.value) {
                return;
            }

            const url = new URL(chequereceiptListUrl, window.location.origin);
            url.searchParams.set('date', receiptDateInput.value);
            window.location.href = url.toString();
        });

        cancelEditButton.addEventListener('click', () => {
            chequereceiptForm.reset();
            setTimeout(() => {
                setCreateMode();
                setDropdownValue(document.getElementById('creditDropdown'), creditInput.value);
                setDropdownValue(document.getElementById('debitDropdown'), debitInput.value);
                closeAllDropdowns();
            }, 0);
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('[data-cheque-dropdown]')) {
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

        chequereceiptForm.addEventListener('reset', () => {
            setTimeout(() => {
                setCreateMode();
                setDropdownValue(document.getElementById('creditDropdown'), creditInput.value);
                setDropdownValue(document.getElementById('debitDropdown'), debitInput.value);
                closeAllDropdowns();
            }, 0);
        });

        chequereceiptForm.addEventListener('submit', () => {
            saveButton.disabled = true;
            saveButton.textContent = 'Saving...';
        });

        setCreateMode();
        applyExportThemeLinks();

        setTimeout(() => {
            const message = document.getElementById('success-message');

            if (message) {
                message.style.display = 'none';
            }
        }, 3000);
    </script>
</body>
</html>

