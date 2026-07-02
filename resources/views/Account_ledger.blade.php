<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Account Ledger | FuelTracker</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <style>
        :root { --bg:#f4f7fb; --panel:#fff; --ink:#172033; --muted:#657089; --line:#dce3ee; --primary:#0f766e; --primary-dark:#115e59; --shadow:0 16px 48px rgba(23,32,51,.10); }
        * { box-sizing:border-box; }
        body { margin:0; min-height:100vh; font-family:Arial, Helvetica, sans-serif; color:var(--ink); background:radial-gradient(circle at top left, rgba(15,118,110,.16), transparent 32rem), linear-gradient(135deg,#f8fbff 0%,var(--bg) 55%,#eef5f3 100%); }
        .site-header { position:sticky; top:0; z-index:20; width:100%; background:linear-gradient(135deg,rgba(8,47,73,.98),rgba(15,118,110,.98)); box-shadow:0 10px 30px rgba(23,32,51,.12); }
        .site-header-inner { width:100%; min-height:64px; display:grid; grid-template-columns:minmax(220px,1fr) auto minmax(220px,1fr); align-items:center; gap:18px; margin:0 auto; padding:0 8px; }
        .site-logo { display:inline-flex; align-items:center; gap:10px; color:#fff; font-size:21px; font-weight:700; text-decoration:none; }
        .site-logo-icon { display:grid; width:38px; height:38px; place-items:center; border-radius:999px; background:#fff; box-shadow:0 10px 28px rgba(0,0,0,.18); overflow:hidden; padding:2px; }
        .app-logo-image { display:block; width:100%; height:100%; border-radius:inherit; object-fit:cover; }
        .header-title { justify-self:center; color:#fff; font-size:20px; font-weight:700; white-space:nowrap; }
        .header-actions { display:flex; align-items:center; justify-self:end; gap:10px; }
        .back-link,.logout-btn { min-height:30px; display:inline-flex; align-items:center; justify-content:center; padding:0 14px; border:1px solid rgba(255,255,255,.24); border-radius:8px; color:#fff; background:rgba(255,255,255,.12); cursor:pointer; font-size:12px; font-weight:700; text-decoration:none; transition:background .2s ease, transform .2s ease; }
        .back-link:hover,.logout-btn:hover { background:rgba(255,255,255,.2); transform:translateY(-1px); }
        .logout-btn { font-family:inherit; }
        .account-ledger-workspace.app-shell-with-sidebar { width:calc(100vw - 24px); min-height:calc(100vh - 88px); grid-template-columns:300px minmax(0,1fr); margin:12px; border-radius:12px; }
        .account-ledger-workspace.app-shell-with-sidebar.menu-collapsed { grid-template-columns:64px minmax(0,1fr); }
        .account-ledger-page { min-width:0; padding:14px; }
        .list-shell { display:grid; gap:12px; }
        .page-title,.list-panel { border:1px solid rgba(220,227,238,.86); border-radius:12px; background:var(--panel); box-shadow:var(--shadow); }
        .page-title { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:18px; }
        .eyebrow { margin:0 0 5px; color:var(--primary); font-size:10px; font-weight:700; text-transform:uppercase; }
        h1 { margin:0; font-size:30px; line-height:1.2; }
        .record-count { flex:0 0 auto; padding:6px 10px; border-radius:999px; color:var(--primary-dark); background:rgba(15,118,110,.09); font-size:11px; font-weight:700; }
        .list-panel { overflow:hidden; }
        .toolbar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:10px 12px; border-bottom:1px solid var(--line); }
        .toolbar-actions { width:100%; display:flex; align-items:center; justify-content:space-between; gap:10px; }
        .toolbar-left,.toolbar-right { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
        .ledger-summary { display:grid; grid-template-columns:1.4fr 1fr 1fr 1fr; gap:10px; padding:12px; border-bottom:1px solid var(--line); background:#fbfcfe; }
        .ledger-summary-item { min-width:0; display:grid; gap:4px; }
        .ledger-summary-label { color:var(--muted); font-size:10px; font-weight:800; text-transform:uppercase; }
        .ledger-summary-value { color:var(--ink); font-size:13px; font-weight:800; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .search-form { display:grid; grid-template-columns:1fr 1fr 1fr; align-items:end; gap:10px; }
        .filter-field { display:grid; gap:5px; min-width:0; }
        .filter-label { color:var(--muted); font-size:10px; font-weight:800; text-transform:uppercase; }
        .search-input,.select-input,.date-input { width:100%; min-height:31px; padding:0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fbfcfe; font:inherit; font-size:11px; outline:none; }
        .search-input:focus,.select-input:focus,.date-input:focus { border-color:rgba(15,118,110,.52); background:#fff; box-shadow:0 0 0 4px rgba(15,118,110,.13); }
        .search-btn,.reset-btn,.process-btn,.export-btn { min-height:31px; display:inline-flex; align-items:center; justify-content:center; padding:0 12px; border-radius:8px; font-size:11px; font-weight:700; text-decoration:none; cursor:pointer; }
        .search-btn { border:1px solid transparent; color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); }
        .reset-btn { border:1px solid var(--line); color:var(--muted); background:#fff; }
        .process-btn { border:1px solid transparent; color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); }
        .export-btn { border:1px solid var(--line); color:var(--primary-dark); background:#fff; }
        .export-btn:hover,.export-btn:focus { border-color:rgba(15,118,110,.38); background:rgba(15,118,110,.08); outline:none; }
        .entries-dropdown { position:relative; display:inline-flex; align-items:center; }
        .entries-toggle { min-height:31px; min-width:104px; padding:0 34px 0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fff; cursor:pointer; font:inherit; font-size:11px; font-weight:700; text-align:left; }
        .entries-dropdown::after { content:""; position:absolute; right:12px; top:50%; width:0; height:0; border-left:4px solid transparent; border-right:4px solid transparent; border-top:5px solid var(--muted); transform:translateY(-40%); pointer-events:none; }
        .entries-menu { position:absolute; top:calc(100% + 6px); left:0; z-index:10; display:none; min-width:136px; overflow:hidden; border:1px solid var(--line); border-radius:10px; background:#fff; box-shadow:0 18px 40px rgba(23,32,51,.16); }
        .entries-dropdown.is-open .entries-menu { display:grid; }
        .entries-option { min-height:36px; padding:0 12px; border:0; color:var(--ink); background:#fff; cursor:pointer; font:inherit; font-size:12px; text-align:left; }
        .entries-option:hover,.entries-option:focus,.entries-option.is-selected { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); outline:none; }
        .table-wrap { overflow-x:auto; }
        table { width:100%; min-width:980px; border-collapse:collapse; }
        th,td { padding:10px 12px; border-bottom:1px solid var(--line); font-size:13px; text-align:left; vertical-align:middle; white-space:nowrap; }
        th { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); font-size:13px; font-weight:800; }
        tbody tr:hover { background:rgba(15,118,110,.045); }
        tfoot td { border-top:2px solid var(--primary); border-bottom:0; background:#f8fbff; font-size:13px; font-weight:800; }
        .sort-link { display:inline-flex; align-items:center; gap:6px; color:#fff; text-decoration:none; white-space:nowrap; }
        .sort-mark { position:relative; width:10px; height:14px; flex:0 0 10px; opacity:.72; }
        .sort-mark::before,.sort-mark::after { content:""; position:absolute; left:50%; width:0; height:0; border-left:3px solid transparent; border-right:3px solid transparent; transform:translateX(-50%); }
        .sort-mark::before { top:2px; border-bottom:4px solid rgba(255,255,255,.58); }
        .sort-mark::after { bottom:2px; border-top:4px solid rgba(255,255,255,.58); }
        .sort-link.is-active .sort-mark { opacity:1; }
        .sort-link.is-active .sort-mark.asc::before { border-bottom-color:#fff; }
        .sort-link.is-active .sort-mark.desc::after { border-top-color:#fff; }
        .text-strong { font-weight:700; }
        .number-cell { text-align:right; }
        .debit-cell { color:#067647; }
        .credit-cell { color:#b42318; }
        .empty-state,.pagination-bar { padding:16px 18px; color:var(--muted); font-size:13px; font-weight:700; }
        .empty-state { text-align:center; }
        .pagination-bar { display:flex; align-items:center; justify-content:space-between; gap:12px; }
        .pagination-links { display:inline-flex; align-items:center; gap:6px; flex-wrap:wrap; }
        .page-link,.page-current { min-width:28px; min-height:28px; display:inline-flex; align-items:center; justify-content:center; padding:0 8px; border-radius:8px; font-size:12px; font-weight:700; text-decoration:none; }
        .page-link { border:1px solid var(--line); color:var(--primary-dark); background:#fff; }
        .page-link.muted { color:var(--muted); background:#f6f8fb; }
        .page-current { color:#fff; background:var(--primary); }
        .ledger-modal { position:fixed; inset:0; z-index:50; display:none; align-items:center; justify-content:center; padding:18px; background:rgba(23,32,51,.48); }
        .ledger-modal.is-open { display:flex; }
        .ledger-modal-panel { width:min(560px,100%); border:1px solid var(--line); border-radius:12px; background:#fff; box-shadow:0 24px 70px rgba(23,32,51,.24); overflow:hidden; }
        .ledger-modal-header { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:14px 16px; border-bottom:1px solid var(--line); }
        .ledger-modal-title { margin:0; font-size:18px; }
        .modal-close { width:31px; height:31px; border:1px solid var(--line); border-radius:8px; color:var(--muted); background:#fff; cursor:pointer; font-size:18px; line-height:1; }
        .ledger-modal-body { display:grid; gap:12px; padding:16px; }
        .ledger-modal-actions { display:flex; justify-content:flex-end; gap:8px; padding:0 16px 16px; }
        .ledger-dropdown { position:relative; }
        .ledger-dropdown-value { position:absolute; inset:0; width:100%; height:100%; opacity:0; pointer-events:none; }
        .ledger-dropdown-button { width:100%; min-height:31px; display:flex; align-items:center; justify-content:space-between; gap:8px; padding:0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:linear-gradient(135deg,rgba(15,118,110,.12),rgba(15,118,110,.04)), #fbfcfe; cursor:pointer; font:inherit; font-size:11px; text-align:left; }
        .ledger-dropdown-button:focus { border-color:rgba(15,118,110,.52); background:#fff; box-shadow:0 0 0 4px rgba(15,118,110,.13); outline:none; }
        .ledger-dropdown-text { min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .ledger-dropdown-arrow { width:0; height:0; border-left:4px solid transparent; border-right:4px solid transparent; border-top:5px solid var(--muted); }
        .ledger-dropdown-menu { position:absolute; top:calc(100% + 6px); left:0; right:0; z-index:60; display:none; max-height:260px; margin:0; padding:6px; overflow:auto; border:1px solid var(--line); border-radius:10px; background:#fff; box-shadow:0 18px 40px rgba(23,32,51,.18); list-style:none; }
        .ledger-dropdown.is-open .ledger-dropdown-menu { display:block; }
        .ledger-dropdown-search-wrap { padding:4px; }
        .ledger-dropdown-search { width:100%; min-height:31px; padding:0 10px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fbfcfe; font:inherit; font-size:11px; outline:none; }
        .ledger-dropdown-option { width:100%; min-height:32px; padding:0 10px; border:0; border-radius:7px; color:var(--ink); background:#fff; cursor:pointer; font:inherit; font-size:11px; text-align:left; }
        .ledger-dropdown-option:hover,.ledger-dropdown-option:focus,.ledger-dropdown-option.is-selected { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); outline:none; }
        .ledger-dropdown-empty { display:none; padding:9px 10px; color:var(--muted); font-size:11px; font-weight:700; }
        .ledger-dropdown-empty.is-visible { display:block; }
        @media (max-width:980px) { .search-form{grid-template-columns:1fr 1fr 1fr} }
        @media (max-width:760px) { .site-header-inner{grid-template-columns:1fr;gap:8px;padding:10px}.header-title{font-size:17px}.header-actions{justify-self:center}.account-ledger-workspace.app-shell-with-sidebar{width:100%;min-height:calc(100vh - 64px);display:block;margin:0;border-radius:0}.page-title,.toolbar,.toolbar-actions,.pagination-bar{align-items:stretch;flex-direction:column}.toolbar-left,.toolbar-right{align-items:stretch;flex-direction:column}.ledger-summary{grid-template-columns:1fr}.search-form{width:100%;grid-template-columns:1fr}.entries-dropdown,.entries-toggle,.process-btn,.export-btn{width:100%}h1{font-size:22px} }
    </style>
    @include('partials.theme')
</head>
<body>
    @php
        $columns = [
            'TRANDATE' => 'Date',
            'ACNO' => 'Particular',
            'vehicle_no' => 'Vehicle No',
            'debit' => 'Debit',
            'credit' => 'Credit',
            'balance' => 'Balance',
        ];

        $sortUrl = function ($column) use ($sort, $direction, $search, $perPage, $accountParticular) {
            return route('accounts.ledger', [
                'search' => $search,
                'account_particular' => $accountParticular,
                'from_date' => request('from_date'),
                'to_date' => request('to_date'),
                'sort' => $column,
                'direction' => ($sort === $column && $direction === 'asc') ? 'desc' : 'asc',
                'per_page' => $perPage,
            ]);
        };

        $sortMark = fn ($column) => $sort === $column ? $direction : '';
    @endphp

    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true"><img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image"></span>
                <span>FuelTracker</span>
            </a>
            <div class="header-title">Account Ledger</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-btn">Logout</button></form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar account-ledger-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')
        <main class="account-ledger-page">
            <div class="list-shell">
                <section class="page-title" aria-labelledby="accountLedgerTitle">
                    <div>
                        <p class="eyebrow">Reports</p>
                        <h1 id="accountLedgerTitle">Account Ledger</h1>
                    </div>
                    <span class="record-count">{{ $transactions->total() }} {{ $transactions->total() === 1 ? 'record' : 'records' }}</span>
                </section>

                <section class="list-panel">
                    <div class="toolbar">
                        <form class="toolbar-actions" method="GET" action="{{ route('accounts.ledger') }}">
                            <input type="hidden" name="sort" value="{{ $sort }}">
                            <input type="hidden" name="direction" value="{{ $direction }}">
                            <input type="hidden" name="search" value="{{ $search }}">
                            <input type="hidden" name="account_particular" value="{{ $accountParticular }}">
                            <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                            <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                            <div class="toolbar-left">
                                <button type="button" class="process-btn" id="openLedgerProcess">Process</button>
                                @if ($hasLedgerFilters && $transactions->total())
                                    <a href="{{ route('accounts.ledger.pdf', request()->query()) }}" class="export-btn" target="_blank" rel="noopener" data-themed-export>PDF</a>
                                    <a href="{{ route('accounts.ledger.excel', request()->query()) }}" class="export-btn" data-themed-export>Excel</a>
                                @endif
                            </div>
                            <div class="toolbar-right">
                                <div class="entries-dropdown">
                                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                                    <button class="entries-toggle" type="button" aria-haspopup="listbox" aria-expanded="false">{{ $perPage }} Entries</button>
                                    <div class="entries-menu" role="listbox">
                                        @foreach ($perPageOptions as $option)
                                            <button class="entries-option {{ $perPage === $option ? 'is-selected' : '' }}" type="button" role="option" aria-selected="{{ $perPage === $option ? 'true' : 'false' }}" data-per-page="{{ $option }}">{{ $option }} Entries</button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if ($hasLedgerFilters)
                        <div class="ledger-summary">
                            <div class="ledger-summary-item">
                                <span class="ledger-summary-label">Particular</span>
                                <span class="ledger-summary-value">{{ $accountParticular }}</span>
                            </div>
                            <div class="ledger-summary-item">
                                <span class="ledger-summary-label">Under Group</span>
                                <span class="ledger-summary-value">{{ $selectedAccount?->under_group ?: '-' }}</span>
                            </div>
                            <div class="ledger-summary-item">
                                <span class="ledger-summary-label">From Date</span>
                                <span class="ledger-summary-value">{{ \Carbon\Carbon::parse(request('from_date'))->format('d M Y') }}</span>
                            </div>
                            <div class="ledger-summary-item">
                                <span class="ledger-summary-label">To Date</span>
                                <span class="ledger-summary-value">{{ \Carbon\Carbon::parse(request('to_date'))->format('d M Y') }}</span>
                            </div>
                        </div>
                    @endif

                    @if ($hasLedgerFilters && $transactions->count())
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        @foreach ($columns as $column => $label)
                                            <th class="{{ in_array($column, ['debit', 'credit', 'balance'], true) ? 'number-cell' : '' }}">
                                                <a class="sort-link {{ $sort === $column ? 'is-active' : '' }}" href="{{ $sortUrl($column) }}">
                                                    <span>{{ $label }}</span>
                                                    <span class="sort-mark {{ $sortMark($column) }}" aria-hidden="true"></span>
                                                </a>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $tx)
                                        <tr>
                                            <td>{{ $tx->TRANDATE ? \Carbon\Carbon::parse($tx->TRANDATE)->format('d M Y') : '-' }}</td>
                                            <td class="text-strong">{{ $tx->particular_label ?? '-' }}</td>
                                            <td>{{ $tx->vehicle_no_label ?? '-' }}</td>
                                            <td class="number-cell debit-cell">{{ $tx->debit > 0 ? number_format($tx->debit, 2) : '-' }}</td>
                                            <td class="number-cell credit-cell">{{ $tx->credit > 0 ? number_format($tx->credit, 2) : '-' }}</td>
                                            <td class="number-cell text-strong">{{ $tx->balance_label }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-strong">Total</td>
                                        <td class="number-cell debit-cell">{{ number_format($ledgerTotals['debit'] ?? 0, 2) }}</td>
                                        <td class="number-cell credit-cell">{{ number_format($ledgerTotals['credit'] ?? 0, 2) }}</td>
                                        <td class="number-cell text-strong">{{ $ledgerTotals['balance_label'] ?? '0.00 Dr' }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-strong">Closing Balance</td>
                                        <td class="number-cell text-strong">{{ $ledgerTotals['closing_balance_label'] ?? '0.00 Dr' }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            @if ($hasLedgerFilters)
                                No ledger records found for "{{ $accountParticular }}" between {{ \Carbon\Carbon::parse(request('from_date'))->format('d M Y') }} and {{ \Carbon\Carbon::parse(request('to_date'))->format('d M Y') }}{{ $search ? ' matching "' . $search . '"' : '' }}.
                            @else
                                Select particular, from date, and to date to view ledger records.
                            @endif
                        </div>
                    @endif

                    @if ($hasLedgerFilters)
                        <div class="pagination-bar">
                            <div>
                                @if ($transactions->total())
                                    Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }}
                                @else
                                    Showing 0 records
                                @endif
                            </div>
                            @include('partials.compact-pagination', ['paginator' => $transactions])
                        </div>
                    @endif
                </section>
            </div>
        </main>
    </div>

    <div class="ledger-modal" id="ledgerProcessModal" aria-hidden="true">
        <div class="ledger-modal-panel" role="dialog" aria-modal="true" aria-labelledby="ledgerProcessTitle">
            <div class="ledger-modal-header">
                <h2 class="ledger-modal-title" id="ledgerProcessTitle">Process Ledger</h2>
                <button type="button" class="modal-close" id="closeLedgerProcess" aria-label="Close">&times;</button>
            </div>
            <form method="GET" action="{{ route('accounts.ledger') }}">
                <input type="hidden" name="sort" value="{{ $sort }}">
                <input type="hidden" name="direction" value="{{ $direction }}">
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                <div class="ledger-modal-body">
                    <label class="filter-field">
                        <span class="filter-label">Particular</span>
                        <div class="ledger-dropdown" id="ledgerParticularDropdown">
                            <input type="text" class="ledger-dropdown-value" id="ledgerParticularValue" name="account_particular" value="{{ $accountParticular }}" required>
                            <button type="button" class="ledger-dropdown-button" aria-haspopup="listbox" aria-expanded="false">
                                <span class="ledger-dropdown-text" id="ledgerParticularText">{{ $accountParticular ?: 'Select Account' }}</span>
                                <span class="ledger-dropdown-arrow" aria-hidden="true"></span>
                            </button>
                            <ul class="ledger-dropdown-menu" role="listbox" aria-label="Particular list">
                                <li class="ledger-dropdown-search-wrap">
                                    <input type="search" class="ledger-dropdown-search" placeholder="Search particular" autocomplete="off">
                                </li>
                                @foreach ($accountNames as $accountName)
                                    <li>
                                        <button type="button" class="ledger-dropdown-option {{ $accountParticular === $accountName ? 'is-selected' : '' }}" data-value="{{ $accountName }}" role="option" aria-selected="{{ $accountParticular === $accountName ? 'true' : 'false' }}">{{ $accountName }}</button>
                                    </li>
                                @endforeach
                                <li class="ledger-dropdown-empty">No matching particular</li>
                            </ul>
                        </div>
                    </label>
                    <label class="filter-field">
                        <span class="filter-label">From Date</span>
                        <input class="date-input" type="date" name="from_date" value="{{ request('from_date') }}" required>
                    </label>
                    <label class="filter-field">
                        <span class="filter-label">To Date</span>
                        <input class="date-input" type="date" name="to_date" value="{{ request('to_date') }}" required>
                    </label>
                </div>
                <div class="ledger-modal-actions">
                    <a href="{{ route('accounts.ledger') }}" class="reset-btn">Clear</a>
                    <button type="submit" class="search-btn">Process</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const ledgerProcessModal = document.getElementById('ledgerProcessModal');
        const openLedgerProcess = document.getElementById('openLedgerProcess');
        const closeLedgerProcess = document.getElementById('closeLedgerProcess');

        const setLedgerProcessModal = (isOpen) => {
            ledgerProcessModal.classList.toggle('is-open', isOpen);
            ledgerProcessModal.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        };

        openLedgerProcess.addEventListener('click', () => setLedgerProcessModal(true));
        closeLedgerProcess.addEventListener('click', () => setLedgerProcessModal(false));
        ledgerProcessModal.addEventListener('click', (event) => {
            if (event.target === ledgerProcessModal) {
                setLedgerProcessModal(false);
            }
        });

        const ledgerParticularDropdown = document.getElementById('ledgerParticularDropdown');
        const ledgerParticularButton = ledgerParticularDropdown.querySelector('.ledger-dropdown-button');
        const ledgerParticularValue = document.getElementById('ledgerParticularValue');
        const ledgerParticularText = document.getElementById('ledgerParticularText');
        const ledgerParticularSearch = ledgerParticularDropdown.querySelector('.ledger-dropdown-search');
        const ledgerParticularOptions = Array.from(ledgerParticularDropdown.querySelectorAll('.ledger-dropdown-option'));
        const ledgerParticularEmpty = ledgerParticularDropdown.querySelector('.ledger-dropdown-empty');

        const closeLedgerDropdown = () => {
            ledgerParticularDropdown.classList.remove('is-open');
            ledgerParticularButton.setAttribute('aria-expanded', 'false');
        };

        const filterLedgerOptions = () => {
            const query = ledgerParticularSearch.value.trim().toLowerCase();
            let visibleCount = 0;

            ledgerParticularOptions.forEach((option) => {
                const isVisible = option.dataset.value.toLowerCase().includes(query);
                option.closest('li').hidden = !isVisible;
                visibleCount += isVisible ? 1 : 0;
            });

            ledgerParticularEmpty.classList.toggle('is-visible', visibleCount === 0);
        };

        ledgerParticularButton.addEventListener('click', () => {
            const isOpen = !ledgerParticularDropdown.classList.contains('is-open');
            ledgerParticularDropdown.classList.toggle('is-open', isOpen);
            ledgerParticularButton.setAttribute('aria-expanded', String(isOpen));

            if (isOpen) {
                ledgerParticularSearch.value = '';
                filterLedgerOptions();
                setTimeout(() => ledgerParticularSearch.focus(), 0);
            }
        });

        ledgerParticularSearch.addEventListener('input', filterLedgerOptions);

        ledgerParticularOptions.forEach((option) => {
            option.addEventListener('click', () => {
                ledgerParticularValue.value = option.dataset.value || '';
                ledgerParticularText.textContent = ledgerParticularValue.value || 'Select Account';
                ledgerParticularOptions.forEach((item) => {
                    const isSelected = item === option;
                    item.classList.toggle('is-selected', isSelected);
                    item.setAttribute('aria-selected', String(isSelected));
                });
                closeLedgerDropdown();
                ledgerParticularButton.focus();
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                setLedgerProcessModal(false);
                closeLedgerDropdown();
            }
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
            if (!ledgerParticularDropdown.contains(event.target)) {
                closeLedgerDropdown();
            }

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
