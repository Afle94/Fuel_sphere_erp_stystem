<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stock In - Out Analysis | FuelTracker</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <style>
        :root { --bg:#f4f7fb; --panel:#fff; --ink:#172033; --muted:#657089; --line:#dce3ee; --primary:#0f766e; --primary-dark:#115e59; --primary-shine:#2dd4bf; --accent:#f59e0b; --theme-glow:rgba(15,118,110,.22); --shadow:0 16px 48px rgba(23,32,51,.10); }
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
        .stock-report-workspace.app-shell-with-sidebar { width:calc(100vw - 24px); min-height:calc(100vh - 88px); grid-template-columns:300px minmax(0,1fr); margin:12px; border-radius:12px; }
        .stock-report-workspace.app-shell-with-sidebar.menu-collapsed { grid-template-columns:64px minmax(0,1fr); }
        .stock-report-page { min-width:0; padding:14px; }
        .report-shell { display:grid; gap:12px; }
        .page-title,.list-panel { border:1px solid rgba(220,227,238,.86); border-radius:12px; background:var(--panel); box-shadow:var(--shadow); }
        .page-title { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:18px; }
        .eyebrow { margin:0 0 5px; color:var(--primary); font-size:10px; font-weight:700; text-transform:uppercase; }
        h1 { margin:0; font-size:30px; line-height:1.2; letter-spacing:0; }
        .record-count { flex:0 0 auto; padding:6px 10px; border-radius:999px; color:var(--primary-dark); background:rgba(15,118,110,.09); font-size:11px; font-weight:700; }
        .list-panel { overflow:hidden; }
        .toolbar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:10px 12px; border-bottom:1px solid var(--line); }
        .toolbar-actions { display:inline-flex; align-items:center; gap:8px; flex-wrap:wrap; }
        .search-form { width:min(100%,680px); display:grid; grid-template-columns:minmax(180px,1fr) 142px 142px 66px; align-items:center; gap:8px; }
        .search-input,.date-input { width:100%; min-height:31px; padding:0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fbfcfe; font:inherit; font-size:11px; outline:none; }
        .date-input:focus,.search-input:focus { border-color:rgba(15,118,110,.52); box-shadow:0 0 0 4px rgba(15,118,110,.13); }
        .reset-btn,.new-btn { min-height:31px; display:inline-flex; align-items:center; justify-content:center; padding:0 12px; border-radius:8px; font-size:11px; font-weight:700; text-decoration:none; cursor:pointer; }
        .new-btn { border:1px solid transparent; color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); }
        .reset-btn { border:1px solid var(--line); color:var(--muted); background:#fff; }
        .table-wrap { overflow-x:auto; }
        .stock-table { width:100%; min-width:980px; border-collapse:collapse; }
        .stock-table th,.stock-table td { padding:10px 12px; border-bottom:1px solid var(--line); font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:left; vertical-align:middle; white-space:nowrap; }
        .stock-table th { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); font-size:12px; font-weight:800; }
        .stock-table td { color:var(--ink); font-weight:500; }
        .stock-table tbody tr:last-child td { border-bottom:0; }
        .stock-table tbody tr:hover { background:rgba(15,118,110,.045); }
        .stock-table tfoot td { color:var(--primary-dark); background:#f7fbfa; font-weight:800; }
        .number-cell { text-align:right; font-variant-numeric:tabular-nums; }
        .empty-state { margin:0; padding:34px 16px; color:var(--muted); font-size:14px; font-weight:700; text-align:center; }
        .pagination-bar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:11px 12px; color:var(--muted); font-size:12px; border-top:1px solid var(--line); }
        .total-strip { display:inline-flex; align-items:center; gap:18px; color:var(--primary-dark); font-size:12px; font-weight:800; font-variant-numeric:tabular-nums; flex-wrap:wrap; }
        @media print {
            .site-header,.sidebar,.page-title,.toolbar,.pagination-bar { display:none !important; }
            body { background:#fff; }
            .stock-report-workspace.app-shell-with-sidebar { display:block; width:100%; min-height:auto; margin:0; border:0; box-shadow:none; }
            .stock-report-page { padding:0; }
            .list-panel { border:0; box-shadow:none; }
            .table-wrap { overflow:visible; }
        }
        @media (max-width:1080px) { .toolbar{align-items:stretch;flex-direction:column}.search-form{width:100%;grid-template-columns:1fr 1fr}.toolbar-actions{justify-content:flex-end} }
        @media (max-width:760px) { .site-header-inner{grid-template-columns:1fr;gap:8px;padding:10px}.header-title{font-size:17px}.header-actions{justify-self:center}.stock-report-workspace.app-shell-with-sidebar{width:100%;min-height:calc(100vh - 64px);display:block;margin:0;border-radius:0}.stock-report-page{padding:12px}.page-title,.pagination-bar{align-items:stretch;flex-direction:column}.search-form{grid-template-columns:1fr}.toolbar-actions{width:100%;display:grid;grid-template-columns:1fr}.reset-btn,.new-btn{width:100%}h1{font-size:22px} }
    </style>
    @include('partials.theme')
</head>
<body>
    @php
        $formatQty = fn ($value) => number_format((float) $value, 2);
        $formatMoney = fn ($value) => number_format((float) $value, 2);
    @endphp

    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>
            <div class="header-title">Stock Report</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar stock-report-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="stock-report-page">
            <div class="report-shell">
                <section class="page-title" aria-labelledby="stockReportTitle">
                    <div>
                        <p class="eyebrow">Reports</p>
                        <h1 id="stockReportTitle">Stock In - Out Analysis</h1>
                    </div>
                    <span class="record-count">{{ $rows->count() }} {{ $rows->count() === 1 ? 'product' : 'products' }}</span>
                </section>

                <section class="list-panel">
                    <div class="toolbar">
                        <form class="search-form" method="GET" action="{{ route('stock-report.index') }}">
                            <input class="search-input" type="search" name="search" value="{{ $search }}" placeholder="Search product">
                            <input class="date-input" type="date" name="from_date" value="{{ $fromDate }}" aria-label="From date">
                            <input class="date-input" type="date" name="to_date" value="{{ $toDate }}" aria-label="To date">
                            <a href="{{ route('stock-report.index') }}" class="reset-btn">Clear</a>
                        </form>
                        <div class="toolbar-actions">
                            <a class="new-btn" href="{{ route('stock-report.pdf', request()->query()) }}" target="_blank" rel="noopener">PDF</a>
                            <a class="new-btn" href="{{ route('stock-report.excel', request()->query()) }}">Excel</a>
                            <a class="new-btn" href="{{ url('/dashboard') }}">Exit</a>
                        </div>
                    </div>

                    @if ($rows->count())
                        <div class="table-wrap">
                            <table class="stock-table">
                                <thead>
                                    <tr>
                                        <th>Product Particulars</th>
                                        <th class="number-cell">Opening</th>
                                        <th class="number-cell">In</th>
                                        <th class="number-cell">Out</th>
                                        <th class="number-cell">Closing Stock</th>
                                        <th class="number-cell">Purchase Rate</th>
                                        <th class="number-cell">Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rows as $row)
                                        <tr>
                                            <td>{{ $row['product'] }}</td>
                                            <td class="number-cell">{{ $formatQty($row['opening']) }}</td>
                                            <td class="number-cell">{{ $formatQty($row['in']) }}</td>
                                            <td class="number-cell">{{ $formatQty($row['out']) }}</td>
                                            <td class="number-cell">{{ $formatQty($row['closing']) }}</td>
                                            <td class="number-cell">{{ $formatMoney($row['purchase_rate']) }}</td>
                                            <td class="number-cell">{{ $formatMoney($row['value']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Total</td>
                                        <td class="number-cell">{{ $formatQty($totals['opening']) }}</td>
                                        <td class="number-cell">{{ $formatQty($totals['in']) }}</td>
                                        <td class="number-cell">{{ $formatQty($totals['out']) }}</td>
                                        <td class="number-cell">{{ $formatQty($totals['closing']) }}</td>
                                        <td></td>
                                        <td class="number-cell">{{ $formatMoney($totals['value']) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">No stock records found for selected date range.</div>
                    @endif

                    <div class="pagination-bar">
                        <span>Showing {{ $rows->count() ? '1' : '0' }} to {{ $rows->count() }} of {{ $rows->count() }}</span>
                        <div class="total-strip">
                            <span>Opening {{ $formatQty($totals['opening']) }}</span>
                            <span>In {{ $formatQty($totals['in']) }}</span>
                            <span>Out {{ $formatQty($totals['out']) }}</span>
                            <span>Closing {{ $formatQty($totals['closing']) }}</span>
                            <span>Value {{ $formatMoney($totals['value']) }}</span>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
