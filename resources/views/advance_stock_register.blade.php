<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Advance Stock Register | FuelTracker</title>
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
        .stock-register-workspace.app-shell-with-sidebar { width:calc(100vw - 24px); min-height:calc(100vh - 88px); grid-template-columns:300px minmax(0,1fr); margin:12px; border-radius:12px; }
        .stock-register-workspace.app-shell-with-sidebar.menu-collapsed { grid-template-columns:64px minmax(0,1fr); }
        .stock-register-page { min-width:0; padding:14px; }
        .list-shell { display:grid; gap:12px; }
        .page-title,.list-panel,.summary-card { border:1px solid rgba(220,227,238,.86); border-radius:12px; background:var(--panel); box-shadow:var(--shadow); }
        .page-title { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:18px; }
        .eyebrow { margin:0 0 5px; color:var(--primary); font-size:10px; font-weight:700; text-transform:uppercase; }
        h1 { margin:0; font-size:30px; line-height:1.2; letter-spacing:0; }
        .record-count { flex:0 0 auto; padding:6px 10px; border-radius:999px; color:var(--primary-dark); background:rgba(15,118,110,.09); font-size:11px; font-weight:700; }
        .summary-grid { display:grid; grid-template-columns:repeat(4,minmax(140px,1fr)); gap:10px; }
        .summary-card { padding:14px; }
        .summary-label { margin:0 0 6px; color:var(--muted); font-size:10px; font-weight:800; text-transform:uppercase; }
        .summary-value { margin:0; color:var(--ink); font-size:22px; font-weight:800; }
        .list-panel { overflow:hidden; }
        .toolbar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:10px 12px; border-bottom:1px solid var(--line); }
        .search-form { width:min(100%,1030px); display:grid; grid-template-columns:minmax(160px,1fr) auto 132px auto 132px 74px 66px 116px; align-items:center; gap:8px; }
        .search-input,.date-input { width:100%; min-height:31px; padding:0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fbfcfe; font:inherit; font-size:11px; outline:none; }
        .date-filter-text { font-size:11px; font-weight:700; color:var(--primary-dark); white-space:nowrap; }
        .search-input:focus,.date-input:focus { border-color:rgba(15,118,110,.52); background:#fff; box-shadow:0 0 0 4px rgba(15,118,110,.13); }
        .search-btn,.reset-btn,.new-btn { min-height:31px; display:inline-flex; align-items:center; justify-content:center; padding:0 12px; border-radius:8px; font-size:11px; font-weight:700; text-decoration:none; cursor:pointer; }
        .search-btn,.new-btn { border:1px solid transparent; color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); }
        .reset-btn { border:1px solid var(--line); color:var(--muted); background:#fff; }
        .entries-dropdown { position:relative; display:inline-flex; align-items:center; }
        .entries-toggle { min-height:31px; min-width:104px; padding:0 34px 0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fff; cursor:pointer; font:inherit; font-size:11px; font-weight:700; text-align:left; }
        .entries-dropdown::after { content:""; position:absolute; right:12px; top:50%; width:0; height:0; border-left:4px solid transparent; border-right:4px solid transparent; border-top:5px solid var(--muted); transform:translateY(-40%); pointer-events:none; }
        .entries-menu { position:absolute; top:calc(100% + 6px); left:0; z-index:10; display:none; min-width:136px; overflow:hidden; border:1px solid var(--line); border-radius:10px; background:#fff; box-shadow:0 18px 40px rgba(23,32,51,.16); }
        .entries-dropdown.is-open .entries-menu { display:grid; }
        .entries-option { min-height:36px; padding:0 12px; border:0; color:var(--ink); background:#fff; cursor:pointer; font:inherit; font-size:12px; text-align:left; }
        .entries-option:hover,.entries-option:focus { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); outline:none; }
        .entries-option.is-selected { font-weight:700; }
        .export-actions { display:flex; align-items:center; justify-content:flex-end; gap:8px; flex-wrap:wrap; }
        .table-wrap { overflow:auto; max-height:calc(100vh - 340px); }
        table { width:100%; min-width:1220px; border-collapse:collapse; }
        th,td { padding:10px 12px; border-bottom:1px solid var(--line); font-size:13px; text-align:left; vertical-align:middle; white-space:nowrap; }
        th { position:sticky; top:0; z-index:1; color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); font-size:12px; font-weight:800; }
        tbody tr:hover { background:rgba(15,118,110,.045); }
        tfoot td { color:var(--primary-dark); background:#f7fbfa; font-weight:800; }
        .text-strong { font-weight:700; }
        .number-cell { text-align:right; font-variant-numeric:tabular-nums; }
        .muted-cell { color:var(--muted); }
        .empty-state,.pagination-bar { padding:16px 18px; color:var(--muted); font-size:13px; font-weight:700; }
        .empty-state { text-align:center; }
        .pagination-bar { display:flex; align-items:center; justify-content:space-between; gap:12px; border-top:1px solid var(--line); }
        .page-link,.page-current { min-width:28px; min-height:28px; display:inline-flex; align-items:center; justify-content:center; padding:0 8px; border-radius:8px; font-size:12px; font-weight:700; text-decoration:none; }
        .page-link { border:1px solid var(--line); color:var(--primary-dark); background:#fff; }
        .page-link.muted { color:var(--muted); background:#f6f8fb; }
        .page-current { color:#fff; background:var(--primary); }
        .product-modal { position:fixed; inset:0; z-index:80; display:none; align-items:center; justify-content:center; padding:18px; background:rgba(15,23,42,.42); }
        .product-modal.is-open { display:flex; }
        .product-dialog { width:min(100%,560px); max-height:min(680px,calc(100vh - 36px)); display:grid; grid-template-rows:auto minmax(0,1fr); overflow:hidden; border:1px solid rgba(220,227,238,.92); border-radius:12px; background:#fff; box-shadow:0 28px 80px rgba(15,23,42,.28); }
        .product-dialog-head { display:flex; align-items:center; justify-content:space-between; gap:14px; padding:16px 18px; border-bottom:1px solid var(--line); }
        .product-dialog-title { margin:0; color:var(--ink); font-size:20px; line-height:1.2; }
        .product-dialog-subtitle { margin:4px 0 0; color:var(--muted); font-size:12px; font-weight:700; }
        .product-dialog-close { width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center; border:1px solid var(--line); border-radius:8px; color:var(--primary-dark); background:#fff; cursor:pointer; font-size:22px; line-height:1; }
        .product-dialog-close:hover,.product-dialog-close:focus { border-color:var(--primary); box-shadow:0 0 0 4px rgba(15,118,110,.12); outline:none; }
        .product-list { overflow:auto; margin:0; padding:12px; list-style:none; }
        .product-select-btn { width:100%; min-height:42px; display:flex; align-items:center; justify-content:space-between; gap:12px; padding:0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fbfcfe; cursor:pointer; font:inherit; font-size:14px; font-weight:700; text-align:left; }
        .product-select-btn:hover,.product-select-btn:focus { border-color:var(--primary); color:var(--primary-dark); box-shadow:0 0 0 4px rgba(15,118,110,.1); outline:none; }
        .product-list li { display:block; }
        .product-list li + li { margin-top:8px; }
        .product-empty { padding:18px; color:var(--muted); font-size:13px; font-weight:700; text-align:center; }
        .fy-dip-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; padding:18px; }
        .fy-dip-field { display:grid; gap:7px; }
        .fy-dip-field label { color:var(--primary-dark); font-size:12px; font-weight:800; text-transform:uppercase; }
        .fy-dip-value { min-height:38px; width:100%; padding:0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fbfcfe; font:inherit; font-size:16px; font-weight:800; }
        .fy-dip-value:focus { border-color:var(--primary); box-shadow:0 0 0 4px rgba(15,118,110,.12); outline:none; }
        .fy-dip-value[readonly] { color:var(--muted); background:#f4f7fb; }
        .fy-dip-note { grid-column:1 / -1; margin:0; color:var(--muted); font-size:12px; font-weight:700; line-height:1.5; }
        .fy-dip-actions { grid-column:1 / -1; display:flex; justify-content:flex-end; gap:10px; }
        .fy-dip-status { grid-column:1 / -1; margin:-6px 0 0; color:var(--muted); font-size:12px; font-weight:700; }
        .stock-row { cursor:pointer; }
        .stock-row:hover { background:rgba(15,118,110,.06); }
        @media (max-width:1024px) { .summary-grid{grid-template-columns:repeat(2,minmax(140px,1fr))}.toolbar{align-items:stretch;flex-direction:column}.search-form{width:100%;grid-template-columns:1fr 1fr}.new-btn{width:max-content} }
        @media (max-width:760px) { .site-header-inner{grid-template-columns:1fr;gap:8px;padding:10px}.header-title{font-size:17px}.header-actions{justify-self:center}.stock-register-workspace.app-shell-with-sidebar{width:100%;min-height:calc(100vh - 64px);display:block;margin:0;border-radius:0}.page-title,.pagination-bar{align-items:stretch;flex-direction:column}.summary-grid,.search-form{grid-template-columns:1fr}.date-filter-text{display:none}.search-btn,.reset-btn,.new-btn,.entries-dropdown,.entries-toggle{width:100%}h1{font-size:22px}.table-wrap{max-height:none} }
    </style>
    @include('partials.theme')
</head>
<body>
    @php
        $formatNumber = fn ($value) => $value === null ? '-' : number_format(round((float) $value), 0);
    @endphp

    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>
            <div class="header-title">Advance Stock Register</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar stock-register-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="stock-register-page">
            <div class="list-shell">
                <section class="page-title" aria-labelledby="stockRegisterTitle">
                    <div>
                        <p class="eyebrow">Reports</p>
                        <h1 id="stockRegisterTitle">Advance Stock Register</h1>
                    </div>
                    <span class="record-count">{{ $entries->total() }} {{ $entries->total() === 1 ? 'record' : 'records' }}</span>
                </section>

                <section class="summary-grid" aria-label="Stock summary">
                    <div class="summary-card">
                        <p class="summary-label">Opening Stock</p>
                        <p class="summary-value">{{ $selectedProduct !== '' ? $formatNumber($totals['opening_stock']) : '-' }}</p>
                    </div>
                    <div class="summary-card">
                        <p class="summary-label">Receipts</p>
                        <p class="summary-value">{{ $selectedProduct !== '' ? $formatNumber($totals['receipt']) : '-' }}</p>
                    </div>
                    <div class="summary-card">
                        <p class="summary-label">Net Sales</p>
                        <p class="summary-value">{{ $selectedProduct !== '' ? $formatNumber($totals['net_sales_by_meters']) : '-' }}</p>
                    </div>
                    <div class="summary-card">
                        <p class="summary-label">Variation</p>
                        <p class="summary-value">{{ $selectedProduct !== '' ? $formatNumber($totals['daily_variation']) : '-' }}</p>
                    </div>
                </section>

                <section class="list-panel">
                    @if (session('success'))
                        <div class="empty-state">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="empty-state">{{ session('error') }}</div>
                    @endif

                    <div class="toolbar">
                        <form class="search-form" method="GET" action="{{ route('advance-stock-register.index') }}">
                            <input type="hidden" name="filter" value="1">
                            <input class="search-input" type="search" name="search" value="{{ $search }}" placeholder="Search item">
                            <span class="date-filter-text">From</span>
                            <input class="date-input" type="date" name="from_date" value="{{ $fromDate ?? '' }}" aria-label="From date">
                            <span class="date-filter-text">To</span>
                            <input class="date-input" type="date" name="to_date" value="{{ $toDate ?? '' }}" aria-label="To date">
                            <button type="submit" class="search-btn">Go</button>
                            <a href="{{ route('advance-stock-register.index') }}" class="reset-btn">Clear</a>
                            <div class="entries-dropdown">
                                <input type="hidden" name="per_page" value="{{ $perPage }}">
                                <button class="entries-toggle" type="button" aria-haspopup="listbox" aria-expanded="false">
                                    {{ $perPage }} Entries
                                </button>
                                <div class="entries-menu" role="listbox">
                                    @foreach ($perPageOptions as $option)
                                        <button class="entries-option {{ $perPage === $option ? 'is-selected' : '' }}" type="button" role="option" aria-selected="{{ $perPage === $option ? 'true' : 'false' }}" data-per-page="{{ $option }}">
                                            {{ $option }} Entries
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                        <div class="export-actions">
                            @if ($selectedProduct !== '' && $entries->total())
                                <a href="{{ route('advance-stock-register.pdf', request()->query()) }}" class="new-btn" target="_blank" rel="noopener" data-themed-export>PDF</a>
                                <a href="{{ route('advance-stock-register.excel', request()->query()) }}" class="new-btn" data-themed-export>Excel</a>
                            @endif
                            <button type="button" class="new-btn" id="fyOpeningDipBtn" {{ $selectedProduct === '' ? 'disabled' : '' }}>Enter F.Y. Opening Dip</button>
                        </div>
                    </div>

                    @if ($entries->count())
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th class="number-cell">Opening Stock</th>
                                        <th class="number-cell">Receipt</th>
                                        <th class="number-cell">Total Stock</th>
                                        <th class="number-cell">Sales By Meters</th>
                                        <th class="number-cell">Pump Test</th>
                                        <th class="number-cell">Net Sales By Meters</th>
                                        <th class="number-cell">Cumulative Sales</th>
                                        <th class="number-cell">Sales By Dip</th>
                                        <th class="number-cell">Variation Daily</th>
                                        <th class="number-cell">Variation Cumm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($entries as $entry)
                                        <tr class="stock-row" data-dip-date="{{ \Carbon\Carbon::parse($entry['date'])->format('d/m/Y') }}" data-dip-save-date="{{ \Carbon\Carbon::parse($entry['date'])->toDateString() }}" data-dip-depth="{{ $entry['dip_depth'] ?? '' }}" data-dip-liter="{{ $entry['dip_liter'] ?? '' }}">
                                            <td>{{ \Carbon\Carbon::parse($entry['date'])->format('d M Y') }}</td>
                                            <td class="number-cell">{{ $formatNumber($entry['opening_stock']) }}</td>
                                            <td class="number-cell">{{ $formatNumber($entry['receipt']) }}</td>
                                            <td class="number-cell">{{ $formatNumber($entry['total_stock']) }}</td>
                                            <td class="number-cell">{{ $formatNumber($entry['sales_by_meters']) }}</td>
                                            <td class="number-cell">{{ $formatNumber($entry['pump_test']) }}</td>
                                            <td class="number-cell">{{ $formatNumber($entry['net_sales_by_meters']) }}</td>
                                            <td class="number-cell">{{ $formatNumber($entry['cumulative_sales']) }}</td>
                                            <td class="number-cell {{ $entry['sales_by_dip'] === null ? 'muted-cell' : '' }}">{{ $formatNumber($entry['sales_by_dip']) }}</td>
                                            <td class="number-cell {{ $entry['daily_variation'] === null ? 'muted-cell' : '' }}">{{ $formatNumber($entry['daily_variation']) }}</td>
                                            <td class="number-cell">{{ $formatNumber($entry['cumulative_variation']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Total</td>
                                        <td class="number-cell">{{ $formatNumber($totals['opening_stock']) }}</td>
                                        <td class="number-cell">{{ $formatNumber($totals['receipt']) }}</td>
                                        <td class="number-cell">{{ $formatNumber($totals['total_stock']) }}</td>
                                        <td class="number-cell">{{ $formatNumber($totals['sales_by_meters']) }}</td>
                                        <td class="number-cell">{{ $formatNumber($totals['pump_test']) }}</td>
                                        <td class="number-cell">{{ $formatNumber($totals['net_sales_by_meters']) }}</td>
                                        <td class="number-cell"></td>
                                        <td class="number-cell">{{ $formatNumber($totals['sales_by_dip']) }}</td>
                                        <td class="number-cell">{{ $formatNumber($totals['daily_variation']) }}</td>
                                        <td class="number-cell">{{ $formatNumber($totals['cumulative_variation']) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @elseif ($hasFilter && $selectedProduct !== '')
                        <div class="empty-state">No stock records found{{ $search ? ' for "' . $search . '"' : '' }}.</div>
                    @elseif ($hasFilter)
                        <div class="empty-state">Select a product from the popup to view stock records.</div>
                    @else
                        <div class="empty-state">Select date range and click Go to view stock records.</div>
                    @endif

                    <div class="pagination-bar">
                        <div>
                            @if ($entries->total())
                                Showing {{ $entries->firstItem() }} to {{ $entries->lastItem() }} of {{ $entries->total() }}
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

    @if ($hasFilter && $selectedProduct === '')
        <div class="product-modal" id="productPopup" role="dialog" aria-modal="true" aria-labelledby="productPopupTitle">
            <div class="product-dialog">
                <div class="product-dialog-head">
                    <div>
                        <h2 class="product-dialog-title" id="productPopupTitle">Products</h2>
                        <p class="product-dialog-subtitle">{{ $products->count() }} product{{ $products->count() === 1 ? '' : 's' }}</p>
                    </div>
                    <button type="button" class="product-dialog-close" data-product-popup-close aria-label="Close products popup">&times;</button>
                </div>

                @if ($products->count())
                    <ul class="product-list">
                        @foreach ($products as $product)
                            <li>
                                <form method="GET" action="{{ route('advance-stock-register.index') }}">
                                    <input type="hidden" name="filter" value="1">
                                    <input type="hidden" name="from_date" value="{{ $fromDate ?? '' }}">
                                    <input type="hidden" name="to_date" value="{{ $toDate ?? '' }}">
                                    <input type="hidden" name="search" value="{{ $search }}">
                                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                                    <input type="hidden" name="product" value="{{ $product }}">
                                    <button type="submit" class="product-select-btn">
                                        <span>{{ $product }}</span>
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="product-empty">No products found.</div>
                @endif
            </div>
        </div>
    @endif

    @if ($selectedProduct !== '')
        <div class="product-modal" id="fyOpeningDipPopup" role="dialog" aria-modal="true" aria-labelledby="fyOpeningDipTitle">
            <div class="product-dialog">
                <div class="product-dialog-head">
                    <div>
                        <h2 class="product-dialog-title" id="fyOpeningDipTitle">{{ $selectedProduct }}</h2>
                        <p class="product-dialog-subtitle">F.Y. opening dip</p>
                    </div>
                    <button type="button" class="product-dialog-close" data-fy-dip-close aria-label="Close opening dip popup">&times;</button>
                </div>

                @if ($fyOpeningDip)
                    <form class="fy-dip-grid" method="POST" action="{{ route('advance-stock-register.opening-dip.store') }}">
                        @csrf
                        <input type="hidden" name="product" value="{{ $selectedProduct }}">
                        <input type="hidden" name="from_date" value="{{ $fromDate ?? '' }}">
                        <input type="hidden" name="to_date" value="{{ $toDate ?? '' }}">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                        <div class="fy-dip-field">
                            <label for="fyOpeningDepth">Enter Dip</label>
                            <input class="fy-dip-value" id="fyOpeningDepth" name="enter_depth" type="number" min="0" step="0.01" value="{{ $fyOpeningDip['depth'] }}" required>
                        </div>
                        <div class="fy-dip-field">
                            <label for="fyOpeningLiter">Liter</label>
                            <input class="fy-dip-value" id="fyOpeningLiter" name="liter" type="number" min="0" step="1" value="{{ $fyOpeningDip['liter'] }}" required>
                        </div>
                        <p class="fy-dip-status" id="fyDipStatus"></p>
                        <p class="fy-dip-note">
                            Initial entry date: {{ $fyOpeningDip['date'] }}.
                            This value is used only in Advance Stock Register and will not change Dip Chart.
                        </p>
                        <div class="fy-dip-actions">
                            <button type="button" class="reset-btn" data-fy-dip-close>Cancel</button>
                            <button type="submit" class="new-btn">Accept</button>
                        </div>
                    </form>
                @else
                    <div class="product-empty">No opening dip entry found for this product.</div>
                @endif
            </div>
        </div>

        <div class="product-modal" id="rowDipPopup" role="dialog" aria-modal="true" aria-labelledby="rowDipTitle">
            <div class="product-dialog">
                <div class="product-dialog-head">
                    <div>
                        <h2 class="product-dialog-title" id="rowDipTitle">{{ $selectedProduct }}</h2>
                        <p class="product-dialog-subtitle">Date wise dip entry</p>
                    </div>
                    <button type="button" class="product-dialog-close" data-row-dip-close aria-label="Close dip popup">&times;</button>
                </div>
                <form class="fy-dip-grid" id="rowDipForm">
                    <div class="fy-dip-field" style="grid-column:1 / -1;">
                        <label for="rowDipDate">Date</label>
                        <input class="fy-dip-value" id="rowDipDate" type="text" readonly>
                        <input id="rowDipSaveDate" type="hidden">
                    </div>
                    <div class="fy-dip-field">
                        <label for="rowDipDepth">Enter Dip</label>
                        <input class="fy-dip-value" id="rowDipDepth" type="number" min="0" step="0.01" required>
                    </div>
                    <div class="fy-dip-field">
                        <label for="rowDipLiter">Liter</label>
                        <input class="fy-dip-value" id="rowDipLiter" type="number" min="0" step="1" readonly>
                    </div>
                    <p class="fy-dip-note" id="rowDipNote"></p>
                    <div class="fy-dip-actions">
                        <button type="submit" class="new-btn" id="rowDipAccept">Accept</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        const productPopup = document.getElementById('productPopup');

        if (productPopup) {
            requestAnimationFrame(() => productPopup.classList.add('is-open'));

            const closeProductPopup = () => productPopup.classList.remove('is-open');

            productPopup.querySelectorAll('[data-product-popup-close]').forEach((button) => {
                button.addEventListener('click', closeProductPopup);
            });

            productPopup.addEventListener('click', (event) => {
                if (event.target === productPopup) {
                    closeProductPopup();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeProductPopup();
                }
            });
        }

        const fyOpeningDipBtn = document.getElementById('fyOpeningDipBtn');
        const fyOpeningDipPopup = document.getElementById('fyOpeningDipPopup');
        const dipParameterLookup = @json($dipParameterLookup ?? []);
        const selectedAdvanceProduct = @json($selectedProduct);
        const dailyDipStoreUrl = @json(route('day-fuel.dip-entry.store'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        const dipLookupKey = (value) => {
            const normalized = String(value || '').trim();

            if (!normalized) {
                return '';
            }

            const number = Number.parseFloat(normalized);

            if (Number.isFinite(number)) {
                return String(Number(number.toFixed(4)));
            }

            return normalized.toLowerCase();
        };

        const dipItemLookup = (item) => {
            const selected = String(item || '').trim();

            if (!selected) {
                return {};
            }

            if (dipParameterLookup?.[selected]) {
                return dipParameterLookup[selected];
            }

            const normalizedSelected = selected.toLowerCase();
            const matchedKey = Object.keys(dipParameterLookup || {}).find((key) => key.trim().toLowerCase() === normalizedSelected);

            return matchedKey ? dipParameterLookup[matchedKey] : {};
        };

        const wholeNumber = (value) => String(Math.round(Number.parseFloat(value || 0)));

        if (fyOpeningDipBtn && fyOpeningDipPopup) {
            const closeFyDipPopup = () => fyOpeningDipPopup.classList.remove('is-open');
            const fyOpeningDepth = document.getElementById('fyOpeningDepth');
            const fyOpeningLiter = document.getElementById('fyOpeningLiter');
            const fyDipStatus = document.getElementById('fyDipStatus');

            const updateFyOpeningLiter = () => {
                const depthKey = dipLookupKey(fyOpeningDepth?.value);
                const itemRows = dipItemLookup(selectedAdvanceProduct);
                let match = depthKey ? itemRows?.[depthKey] : null;
                let calculated = false;

                if (!match && depthKey && Number.isFinite(Number.parseFloat(depthKey))) {
                    const depthNumber = Number.parseFloat(depthKey);
                    const lowerDepth = Math.floor(depthNumber);
                    const upperDepth = Math.ceil(depthNumber);
                    const lowerLiter = itemRows?.[dipLookupKey(lowerDepth)];
                    const upperLiter = itemRows?.[dipLookupKey(upperDepth)];

                    if (
                        lowerDepth !== upperDepth
                        && lowerLiter !== undefined
                        && upperLiter !== undefined
                        && Number.isFinite(Number.parseFloat(lowerLiter))
                        && Number.isFinite(Number.parseFloat(upperLiter))
                    ) {
                        const ratio = depthNumber - lowerDepth;
                        match = Number.parseFloat(lowerLiter) + ((Number.parseFloat(upperLiter) - Number.parseFloat(lowerLiter)) * ratio);
                        calculated = true;
                    }
                }

                if (fyOpeningLiter && match !== null && match !== undefined) {
                    fyOpeningLiter.value = wholeNumber(match);
                }

                if (fyDipStatus) {
                    fyDipStatus.textContent = match
                        ? (calculated ? 'Liter calculated from nearest dip chart values.' : 'Liter loaded from dip chart.')
                        : (depthKey ? 'No liter value found for this dip.' : '');
                }
            };

            fyOpeningDipBtn.addEventListener('click', () => {
                fyOpeningDipPopup.classList.add('is-open');
                updateFyOpeningLiter();
            });

            fyOpeningDepth?.addEventListener('input', updateFyOpeningLiter);

            fyOpeningDipPopup.querySelectorAll('[data-fy-dip-close]').forEach((button) => {
                button.addEventListener('click', closeFyDipPopup);
            });

            fyOpeningDipPopup.addEventListener('click', (event) => {
                if (event.target === fyOpeningDipPopup) {
                    closeFyDipPopup();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeFyDipPopup();
                }
            });
        }

        const rowDipPopup = document.getElementById('rowDipPopup');

        if (rowDipPopup) {
            const rowDipForm = document.getElementById('rowDipForm');
            const rowDipDate = document.getElementById('rowDipDate');
            const rowDipSaveDate = document.getElementById('rowDipSaveDate');
            const rowDipDepth = document.getElementById('rowDipDepth');
            const rowDipLiter = document.getElementById('rowDipLiter');
            const rowDipNote = document.getElementById('rowDipNote');
            const rowDipAccept = document.getElementById('rowDipAccept');
            const closeRowDipPopup = () => rowDipPopup.classList.remove('is-open');
            const updateRowDipLiter = () => {
                const depthKey = dipLookupKey(rowDipDepth?.value);
                const itemRows = dipItemLookup(selectedAdvanceProduct);
                let match = depthKey ? itemRows?.[depthKey] : null;
                let calculated = false;

                if (!match && depthKey && Number.isFinite(Number.parseFloat(depthKey))) {
                    const depthNumber = Number.parseFloat(depthKey);
                    const lowerDepth = Math.floor(depthNumber);
                    const upperDepth = Math.ceil(depthNumber);
                    const lowerLiter = itemRows?.[dipLookupKey(lowerDepth)];
                    const upperLiter = itemRows?.[dipLookupKey(upperDepth)];

                    if (
                        lowerDepth !== upperDepth
                        && lowerLiter !== undefined
                        && upperLiter !== undefined
                        && Number.isFinite(Number.parseFloat(lowerLiter))
                        && Number.isFinite(Number.parseFloat(upperLiter))
                    ) {
                        const ratio = depthNumber - lowerDepth;
                        match = Number.parseFloat(lowerLiter) + ((Number.parseFloat(upperLiter) - Number.parseFloat(lowerLiter)) * ratio);
                        calculated = true;
                    }
                }

                if (rowDipLiter) {
                    rowDipLiter.value = match !== null && match !== undefined ? wholeNumber(match) : '';
                }

                if (rowDipNote) {
                    rowDipNote.textContent = match
                        ? (calculated ? 'Liter calculated from nearest dip chart values.' : 'Liter loaded from dip chart.')
                        : (depthKey ? 'No liter value found for this dip.' : '');
                }
            };

            const saveRowDip = async () => {
                const enterDip = rowDipDepth?.value || '';
                const liter = rowDipLiter?.value || '';

                if (!rowDipSaveDate?.value || !enterDip || !liter) {
                    if (rowDipNote) {
                        rowDipNote.textContent = !liter ? 'No liter value found for this dip.' : 'Please enter dip value.';
                    }

                    return;
                }

                if (rowDipAccept) {
                    rowDipAccept.disabled = true;
                    rowDipAccept.textContent = 'Saving';
                }

                try {
                    const response = await fetch(dailyDipStoreUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            date: rowDipSaveDate.value,
                            item: selectedAdvanceProduct,
                            enter_depth: enterDip,
                            liter,
                        }),
                    });
                    const savedDip = await response.json();

                    if (!response.ok) {
                        const firstError = savedDip.errors
                            ? Object.values(savedDip.errors).flat()[0]
                            : null;

                        throw new Error(firstError || savedDip.message || 'Dip entry could not be saved.');
                    }

                    if (rowDipNote) {
                        rowDipNote.textContent = 'Dip saved. Updating table...';
                    }

                    window.location.reload();
                } catch (error) {
                    if (rowDipNote) {
                        rowDipNote.textContent = error.message || 'Dip entry could not be saved.';
                    }
                } finally {
                    if (rowDipAccept) {
                        rowDipAccept.disabled = false;
                        rowDipAccept.textContent = 'Accept';
                    }
                }
            };

            document.querySelectorAll('.stock-row').forEach((row) => {
                row.addEventListener('dblclick', () => {
                    rowDipDate.value = row.dataset.dipDate || '';
                    rowDipSaveDate.value = row.dataset.dipSaveDate || '';
                    rowDipDepth.value = row.dataset.dipDepth || '';
                    rowDipLiter.value = row.dataset.dipLiter ? wholeNumber(row.dataset.dipLiter) : '';
                    rowDipNote.textContent = row.dataset.dipLiter ? '' : 'No dip entry found for this date.';
                    rowDipPopup.classList.add('is-open');
                    updateRowDipLiter();
                    rowDipDepth.focus();
                });
            });

            rowDipDepth?.addEventListener('input', updateRowDipLiter);

            rowDipForm?.addEventListener('submit', (event) => {
                event.preventDefault();
                saveRowDip();
            });

            rowDipPopup.querySelectorAll('[data-row-dip-close]').forEach((button) => {
                button.addEventListener('click', closeRowDipPopup);
            });

            rowDipPopup.addEventListener('click', (event) => {
                if (event.target === rowDipPopup) {
                    closeRowDipPopup();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeRowDipPopup();
                }
            });
        }

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
