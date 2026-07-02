<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Day Book Register | FuelTracker</title>
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
        .dayfuel-register-workspace.app-shell-with-sidebar { width:calc(100vw - 24px); min-height:calc(100vh - 88px); grid-template-columns:300px minmax(0,1fr); margin:12px; border-radius:12px; }
        .dayfuel-register-workspace.app-shell-with-sidebar.menu-collapsed { grid-template-columns:64px minmax(0,1fr); }
        .dayfuel-register-page { min-width:0; padding:14px; }
        .list-shell { display:grid; gap:12px; }
        .page-title,.list-panel,.cash-banner-box,.daybook-center-workspace { border:1px solid rgba(220,227,238,.86); border-radius:12px; background:var(--panel); box-shadow:var(--shadow); }
        .page-title { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:18px; }
        .eyebrow { margin:0 0 5px; color:var(--primary); font-size:10px; font-weight:700; text-transform:uppercase; }
        h1 { margin:0; font-size:30px; line-height:1.2; letter-spacing:0; }
        .list-panel { overflow:hidden; }
        .toolbar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:10px 12px; border-bottom:1px solid var(--line); }
        .toolbar-actions { display:flex; align-items:center; justify-content:flex-end; gap:8px; flex-wrap:wrap; }
        .search-form-daybook { display:flex; align-items:center; gap:8px; }
        .date-input { min-height:31px; padding:0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fbfcfe; font:inherit; font-size:11px; font-weight:700; outline:none; }
        .date-input:focus { border-color:rgba(15,118,110,.52); background:#fff; box-shadow:0 0 0 4px rgba(15,118,110,.13); }
        .date-filter-label { font-size:11px; font-weight:700; color:var(--primary-dark); white-space:nowrap; }
        .search-btn,.new-btn { min-height:31px; display:inline-flex; align-items:center; justify-content:center; padding:0 12px; border-radius:8px; border:1px solid transparent; color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); font-size:11px; font-weight:700; text-decoration:none; cursor:pointer; }
        .daybook-container { padding:14px; display:grid; gap:12px; }
        .cash-banner-box { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:14px; box-shadow:none; }
        .cash-banner-box span { color:var(--primary-dark); font-size:12px; font-weight:800; text-transform:uppercase; }
        .closing-box { background:#f7fbfa; }
        .display-input-field { width:180px; min-height:34px; padding:0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fbfcfe; font:inherit; font-size:13px; font-weight:800; text-align:right; font-variant-numeric:tabular-nums; outline:none; }
        .display-input-field[readonly] { cursor:default; }
        .daybook-center-workspace { display:grid; grid-template-columns:1.2fr 1fr; gap:18px; padding:16px; box-shadow:none; }
        .modules-vertical-stack { display:grid; gap:8px; align-content:start; }
        .module-row-item { display:grid; grid-template-columns:minmax(160px,1fr) 180px; align-items:center; gap:12px; min-height:42px; padding:5px 0; border-bottom:1px solid var(--line); }
        .module-row-item:last-child { border-bottom:0; }
        .module-label-text { color:var(--ink); font-size:13px; font-weight:700; }
        .matrix-table-wrap { overflow:auto; }
        .matrix-subgrid-table { width:100%; min-width:360px; border-collapse:collapse; background:#fff; }
        .matrix-subgrid-table th,.matrix-subgrid-table td { padding:10px 12px; border-bottom:1px solid var(--line); font-size:13px; text-align:left; vertical-align:middle; white-space:nowrap; }
        .matrix-subgrid-table th { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); font-size:12px; font-weight:800; }
        .matrix-subgrid-table tbody tr:hover { background:rgba(15,118,110,.045); }
        .text-strong { font-weight:700; }
        .number-cell { text-align:right; font-variant-numeric:tabular-nums; }

        @media (max-width:940px) { .daybook-center-workspace{grid-template-columns:1fr} }
        @media (max-width:760px) { .site-header-inner{grid-template-columns:1fr;gap:8px;padding:10px}.header-title{font-size:17px}.header-actions{justify-self:center}.dayfuel-register-workspace.app-shell-with-sidebar{width:100%;min-height:calc(100vh - 64px);display:block;margin:0;border-radius:0}.page-title,.toolbar,.cash-banner-box{align-items:stretch;flex-direction:column}h1{font-size:22px}.search-form-daybook,.toolbar-actions,.new-btn,.display-input-field{width:100%}.date-filter-label{display:none}.module-row-item{grid-template-columns:1fr}.matrix-subgrid-table{min-width:320px} }
    </style>

    @include('partials.theme')
</head>

<body>

    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo">
                <span class="site-logo-icon">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" class="app-logo-image" alt="">
                </span>
                <span>FuelTracker</span>
            </a>
            <div class="header-title">Day Book Register ({{ \Carbon\Carbon::parse($targetDate)->format('d-m-Y') }})</div>
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
                        <h1>Day Book Statement: {{ \Carbon\Carbon::parse($targetDate)->format('d-m-Y') }}</h1>
                    </div>
                </section>

                <section class="list-panel">
                    
                    <div class="toolbar">
                        <form class="search-form-daybook" method="GET" action="{{ route('RegisterDayBook') }}">
                            <input type="hidden" name="theme" value="{{ request('theme', 'default') }}">
                            <span class="date-filter-label">Statement Date:</span>
                            <input class="date-input" type="date" name="date" value="{{ $targetDate }}" onchange="this.form.submit()">
                        </form>

                        <div class="toolbar-actions">
                            <a href="{{ route('Day_Book_Register_pdf.pdf', request()->query()) }}" class="new-btn" target="_blank" data-themed-export>PDF</a>
                            <a href="{{ route('daybook.excel', request()->query()) }}" class="new-btn" data-themed-export>Excel</a>
                        </div>
                    </div>

                    <div class="daybook-container">
                        
                        <div class="cash-banner-box opening-box">
                            <span>Opening Cash</span>
                            <input type="text" readonly class="display-input-field" value="{{ number_format($dayBookData['Opening Cash'], 2, '.', '') }}">
                        </div>

                        <div class="daybook-center-workspace">
                            
                            <div class="modules-vertical-stack">
                                
                                <div class="module-row-item">
                                    <span class="module-label-text">Day Fuel Sale</span>
                                    <input type="text" readonly class="display-input-field" value="{{ number_format($dayBookData['Day Fuel Sale'], 2, '.', '') }}">
                                </div>

                                <div class="module-row-item">
                                    <span class="module-label-text">Credit Sales</span>
                                    <input type="text" readonly class="display-input-field" value="{{ number_format($dayBookData['Credit Sales'], 2, '.', '') }}">
                                </div>

                                <div class="module-row-item">
                                    <span class="module-label-text">Cash Sales</span>
                                    <input type="text" readonly class="display-input-field" value="{{ number_format($dayBookData['Cash Sales'], 2, '.', '') }}">
                                </div>

                                <div class="module-row-item">
                                    <span class="module-label-text">Cash Receipt</span>
                                    <input type="text" readonly class="display-input-field" value="{{ number_format($dayBookData['Cash Receipt'], 2, '.', '') }}">
                                </div>

                                <div class="module-row-item">
                                    <span class="module-label-text">Cheque Receipt</span>
                                    <input type="text" readonly class="display-input-field" value="{{ number_format($dayBookData['Cheque Receipt'], 2, '.', '') }}">
                                </div>

                                <div class="module-row-item">
                                    <span class="module-label-text">Cash Payment</span>
                                    <input type="text" readonly class="display-input-field" value="{{ number_format($dayBookData['Cash Payment'], 2, '.', '') }}">
                                </div>

                                <div class="module-row-item">
                                    <span class="module-label-text">Cheque Payment</span>
                                    <input type="text" readonly class="display-input-field" value="{{ number_format($dayBookData['Cheque Payment'], 2, '.', '') }}">
                                </div>

                                <div class="module-row-item">
                                    <span class="module-label-text">Purchase</span>
                                    <input type="text" readonly class="display-input-field" value="{{ number_format($dayBookData['Purchase'], 2, '.', '') }}">
                                </div>

                            </div>

                            <div class="matrix-table-wrap">
                                <table class="matrix-subgrid-table">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $rowCount = 0; @endphp
                                        @foreach($dayBookData['ItemsMatrix'] as $itemName => $itemData)
                                            @php $rowCount++; @endphp
                                            <tr>
                                                <td class="text-strong">{{ $itemName }}</td>
                                                <td class="number-cell">{{ number_format($itemData['quantity'], 2) }}</td>
                                                <td class="number-cell">{{ number_format($itemData['amount'], 2) }}</td>
                                            </tr>
                                        @endforeach

                                        @for($i = $rowCount; $i < 5; $i++)
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="cash-banner-box closing-box">
                            <span>Closing Cash</span>
                            <input type="text" readonly class="display-input-field" value="{{ number_format($dayBookData['Closing Cash'], 2, '.', '') }}">
                        </div>

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

        document.querySelectorAll('.menu-toggle').forEach((toggle) => {
            const menuShell = toggle.closest('.dayfuel-register-workspace');
            if (!menuShell) return;
            toggle.addEventListener('click', () => {
                const isCollapsed = menuShell.classList.toggle('menu-collapsed');
                toggle.setAttribute('aria-expanded', String(!isCollapsed));
                toggle.setAttribute('aria-label', isCollapsed ? 'Show menu' : 'Hide menu');
            });
        });
    </script>
</body>
</html>
