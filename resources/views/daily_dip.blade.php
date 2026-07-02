<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dip Chart | FuelTracker</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <style>
        :root { --bg:#f4f7fb; --panel:#ffffff; --ink:#172033; --muted:#657089; --line:#dce3ee; --primary:#0f766e; --primary-dark:#115e59; --shadow:0 16px 48px rgba(23,32,51,.10); }
        * { box-sizing:border-box; }
        body { margin:0; min-height:100vh; font-family:Arial, Helvetica, sans-serif; color:var(--ink); background:linear-gradient(135deg,#f8fbff 0%,var(--bg) 55%,#eef5f3 100%); }
        .site-header { position:sticky; top:0; z-index:20; width:100%; background:linear-gradient(135deg,rgba(8,47,73,.98),rgba(15,118,110,.98)); box-shadow:0 10px 30px rgba(23,32,51,.12); }
        .site-header-inner { min-height:64px; display:grid; grid-template-columns:minmax(220px,1fr) auto minmax(220px,1fr); align-items:center; gap:18px; padding:0 8px; }
        .site-logo { display:inline-flex; align-items:center; gap:10px; color:#fff; font-size:21px; font-weight:700; text-decoration:none; }
        .site-logo-icon { display:grid; width:38px; height:38px; place-items:center; overflow:hidden; padding:2px; border-radius:999px; background:#fff; box-shadow:0 10px 28px rgba(0,0,0,.18); }
        .app-logo-image { width:100%; height:100%; border-radius:inherit; object-fit:cover; }
        .header-title { justify-self:center; color:#fff; font-size:20px; font-weight:700; white-space:nowrap; }
        .header-actions { display:flex; align-items:center; justify-self:end; gap:10px; }
        .back-link,.logout-btn { min-height:30px; display:inline-flex; align-items:center; justify-content:center; padding:0 14px; border:1px solid rgba(255,255,255,.24); border-radius:8px; color:#fff; background:rgba(255,255,255,.12); cursor:pointer; font:inherit; font-size:12px; font-weight:700; text-decoration:none; }
        .dip-chart-workspace.app-shell-with-sidebar { width:calc(100vw - 24px); min-height:calc(100vh - 88px); grid-template-columns:300px minmax(0,1fr); margin:12px; border-radius:12px; }
        .dip-chart-workspace.app-shell-with-sidebar.menu-collapsed { grid-template-columns:64px minmax(0,1fr); }
        .dip-chart-page { min-width:0; padding:14px; }
        .page-shell { display:grid; gap:12px; }
        .page-title,.list-panel { border:1px solid rgba(220,227,238,.86); border-radius:12px; background:var(--panel); box-shadow:var(--shadow); }
        .page-title { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:18px; }
        .eyebrow { margin:0 0 5px; color:var(--primary); font-size:10px; font-weight:700; text-transform:uppercase; }
        h1 { margin:0; font-size:30px; line-height:1.2; }
        .record-count { flex:0 0 auto; padding:6px 10px; border-radius:999px; color:var(--primary-dark); background:rgba(15,118,110,.09); font-size:11px; font-weight:700; }
        .toolbar { display:flex; align-items:center; justify-content:space-between; gap:10px; padding:12px; border-bottom:1px solid var(--line); }
        .toolbar-actions { display:inline-flex; align-items:center; gap:8px; flex:0 0 auto; }
        .date-form { display:flex; align-items:end; gap:8px; flex-wrap:wrap; }
        .field { display:grid; gap:6px; }
        .field label { color:var(--muted); font-size:12px; font-weight:800; }
        .date-input,.search-input { min-height:34px; padding:0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fbfcfe; font:inherit; font-size:13px; outline:none; }
        .date-input:focus,.search-input:focus { border-color:rgba(15,118,110,.52); background:#fff; box-shadow:0 0 0 4px rgba(15,118,110,.13); }
        .dip-item-dropdown { position:relative; min-width:190px; }
        .dip-item-value { position:absolute; opacity:0; pointer-events:none; }
        .dip-item-toggle { width:100%; min-height:34px; display:flex; align-items:center; justify-content:space-between; gap:10px; padding:0 34px 0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fbfcfe; cursor:pointer; font:inherit; font-size:13px; font-weight:700; text-align:left; outline:none; }
        .dip-item-toggle:hover,.dip-item-toggle:focus { border-color:rgba(15,118,110,.52); background:#fff; box-shadow:0 0 0 4px rgba(15,118,110,.13); }
        .dip-item-text { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .dip-item-arrow { position:absolute; right:12px; top:50%; width:0; height:0; border-left:4px solid transparent; border-right:4px solid transparent; border-top:5px solid var(--muted); transform:translateY(-40%); pointer-events:none; }
        .dip-item-menu { position:absolute; top:calc(100% + 5px); left:0; right:0; z-index:30; display:none; max-height:220px; overflow-y:auto; margin:0; padding:0; border:1px solid rgba(15,118,110,.35); border-radius:8px; background:#fff; box-shadow:0 16px 34px rgba(23,32,51,.18); list-style:none; }
        .dip-item-dropdown.is-open .dip-item-menu { display:grid; }
        .dip-item-option { width:100%; min-height:32px; padding:0 12px; border:0; color:var(--ink); background:#fff; cursor:pointer; font:inherit; font-size:12px; text-align:left; }
        .dip-item-option:hover,.dip-item-option:focus,.dip-item-option.is-selected { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); outline:none; }
        .search-input { width:min(100%,240px); }
        .primary-btn,.clear-btn { min-height:34px; display:inline-flex; align-items:center; justify-content:center; padding:0 14px; border-radius:8px; font:inherit; font-size:12px; font-weight:800; text-decoration:none; cursor:pointer; }
        .primary-btn { border:1px solid transparent; color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); }
        .clear-btn { border:1px solid var(--line); color:var(--muted); background:#fff; }
        .table-wrap { overflow:auto; }
        .dip-table { width:auto; min-width:520px; max-width:760px; border-collapse:collapse; table-layout:auto; }
        th,td { padding:11px 12px; border-bottom:1px solid var(--line); font-size:13px; text-align:left; vertical-align:middle; }
        th { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); font-weight:800; white-space:nowrap; }
        .sr-column { width:62px; }
        .date-column { width:150px; }
        .depth-column,
        .liter-column { width:120px; }
        tbody tr:hover { background:rgba(15,118,110,.05); }
        .number-cell { text-align:right; font-variant-numeric:tabular-nums; }
        .text-strong { font-weight:700; }
        .empty-state { padding:34px 16px; color:var(--muted); font-size:14px; font-weight:700; text-align:center; }
        .pagination-bar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:11px 12px; color:var(--muted); font-size:12px; }
        .pagination-links { display:flex; align-items:center; gap:6px; }
        .page-link,.page-current { min-width:28px; min-height:28px; display:inline-flex; align-items:center; justify-content:center; padding:0 8px; border-radius:8px; font-size:12px; font-weight:700; text-decoration:none; }
        .page-link { border:1px solid var(--line); color:var(--muted); background:#fff; }
        .page-current { color:#fff; background:var(--primary); }
        .page-link.muted { opacity:.55; }
        @media (max-width:760px) {
            .site-header-inner { grid-template-columns:1fr; gap:8px; padding:10px; }
            .header-title,.header-actions { justify-self:center; }
            .dip-chart-workspace.app-shell-with-sidebar { width:100%; min-height:calc(100vh - 64px); display:block; margin:0; border-radius:0; }
            .page-title,.toolbar,.pagination-bar { align-items:stretch; flex-direction:column; }
            .date-form { align-items:stretch; flex-direction:column; }
            .toolbar-actions { width:100%; display:grid; grid-template-columns:1fr; }
            .date-input,.dip-item-dropdown,.search-input,.primary-btn,.clear-btn { width:100%; }
        }
    </style>
    @include('partials.theme')
    <style>
        html[data-theme] .dip-chart-page .dip-item-toggle {
            border-color: color-mix(in srgb, var(--primary) 28%, var(--line)) !important;
            color: var(--ink) !important;
            background:
                linear-gradient(135deg, var(--theme-glow), rgba(255, 255, 255, .96)),
                #ffffff !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .7) !important;
        }

        html[data-theme] .dip-chart-page .dip-item-toggle:hover,
        html[data-theme] .dip-chart-page .dip-item-toggle:focus {
            border-color: var(--primary) !important;
            background: #ffffff !important;
            box-shadow: 0 0 0 4px var(--theme-glow) !important;
            outline: none !important;
        }

        html[data-theme] .dip-chart-page .dip-item-arrow {
            border-top-color: var(--primary) !important;
        }

        html[data-theme] .dip-chart-page .dip-item-menu {
            border-color: color-mix(in srgb, var(--primary) 35%, var(--line)) !important;
            box-shadow: 0 18px 38px var(--theme-glow) !important;
            scrollbar-color: var(--primary) rgba(220, 227, 238, .72) !important;
        }

        html[data-theme] .dip-chart-page .dip-item-option:hover,
        html[data-theme] .dip-chart-page .dip-item-option:focus,
        html[data-theme] .dip-chart-page .dip-item-option.is-selected {
            color: #ffffff !important;
            background:
                linear-gradient(160deg, rgba(255, 255, 255, .34) 0%, rgba(255, 255, 255, .08) 28%, transparent 48%),
                linear-gradient(135deg, var(--primary-dark), var(--primary) 58%, var(--primary-shine)) !important;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, .32),
                0 10px 22px var(--theme-glow) !important;
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true"><img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image"></span>
                <span>FuelTracker</span>
            </a>
            <div class="header-title">Dip Chart</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-btn">Logout</button></form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar dip-chart-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')
        <main class="dip-chart-page">
            <div class="page-shell">
                <section class="page-title" aria-labelledby="dipChartTitle">
                    <div>
                        <p class="eyebrow">Transactions</p>
                        <h1 id="dipChartTitle">Dip Chart</h1>
                    </div>
                    <span class="record-count">{{ method_exists($entries, 'total') ? $entries->total() : $entries->count() }} records</span>
                </section>

                <section class="list-panel">
                    <div class="toolbar">
                        <form class="date-form" method="GET" action="{{ route('daily-dip.index') }}">
                            <div class="field">
                                <label for="fromDate">From Date</label>
                                <input class="date-input" id="fromDate" name="from_date" type="date" value="{{ $selectedFromDate }}">
                            </div>
                            <div class="field">
                                <label for="toDate">To Date</label>
                                <input class="date-input" id="toDate" name="to_date" type="date" value="{{ $selectedToDate }}">
                            </div>
                            <div class="field">
                                <label for="item">Item</label>
                                <div class="dip-item-dropdown" id="dipItemDropdown">
                                    <input type="hidden" class="dip-item-value" id="item" name="item" value="{{ $selectedItem }}">
                                    <button type="button" class="dip-item-toggle" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="dip-item-text">{{ $selectedItem ?: 'Select Item' }}</span>
                                        <span class="dip-item-arrow" aria-hidden="true"></span>
                                    </button>
                                    <ul class="dip-item-menu" role="listbox" aria-label="Item list">
                                        @foreach ($items as $item)
                                            <li>
                                                <button type="button" class="dip-item-option {{ $selectedItem === $item ? 'is-selected' : '' }}" data-value="{{ $item }}" role="option" aria-selected="{{ $selectedItem === $item ? 'true' : 'false' }}">
                                                    {{ $item }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="field">
                                <label for="search">Search</label>
                                <input class="search-input" id="search" name="search" type="search" value="{{ $search }}" placeholder="Search depth or liter">
                            </div>
                            <button type="submit" class="primary-btn">Search</button>
                            <a href="{{ route('daily-dip.index') }}" class="clear-btn">Clear</a>
                        </form>
                        @if ($tableReady && $entries->count() && ! empty($selectedItem))
                            <div class="toolbar-actions">
                                <a href="{{ route('daily-dip.pdf', request()->query()) }}" class="primary-btn" target="_blank" rel="noopener" data-themed-export>PDF</a>
                                <a href="{{ route('daily-dip.excel', request()->query()) }}" class="primary-btn" data-themed-export>Excel</a>
                            </div>
                        @endif
                    </div>

                    <div class="table-wrap">
                        <table class="dip-table">
                            <thead>
                                <tr>
                                    <th class="number-cell sr-column">Sr.</th>
                                    <th class="date-column">Date</th>
                                    <th class="number-cell depth-column">Enter Depth</th>
                                    <th class="number-cell liter-column">Liter</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($tableReady && $entries->count())
                                    @foreach ($entries as $entry)
                                        <tr>
                                            <td class="number-cell text-strong">{{ method_exists($entries, 'firstItem') ? $entries->firstItem() + $loop->index : $loop->iteration }}</td>
                                            <td>{{ $entry->date ? \Carbon\Carbon::parse($entry->date)->format('d/m/Y') : '' }}</td>
                                            <td class="number-cell">{{ rtrim(rtrim(number_format((float) $entry->{$depthColumn}, 2, '.', ''), '0'), '.') }}</td>
                                            <td class="number-cell">{{ (int) (float) $entry->{$literColumn} }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="empty-state" colspan="4">
                                            @if (! empty($requiresItemSelection))
                                                Select an item to view dip chart records.
                                            @else
                                                {{ $tableReady ? 'No dip chart records found.' : 'Daily dip table is not ready.' }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if (method_exists($entries, 'hasPages') && $entries->hasPages())
                        <div class="pagination-bar">
                            <span>Showing {{ $entries->firstItem() }} to {{ $entries->lastItem() }} of {{ $entries->total() }} records</span>
                            <div class="pagination-links">
                                @if ($entries->onFirstPage())
                                    <span class="page-link muted">Prev</span>
                                @else
                                    <a class="page-link" href="{{ $entries->previousPageUrl() }}">Prev</a>
                                @endif

                                <span class="page-current">{{ $entries->currentPage() }}</span>

                                @if ($entries->hasMorePages())
                                    <a class="page-link" href="{{ $entries->nextPageUrl() }}">Next</a>
                                @else
                                    <span class="page-link muted">Next</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </section>
            </div>
        </main>
    </div>
    <script>
        @if ($selectedItem !== '')
            window.history.replaceState({}, '', "{{ route('daily-dip.index') }}");
        @endif

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

        const dipItemDropdown = document.getElementById('dipItemDropdown');

        if (dipItemDropdown) {
            const dipItemInput = dipItemDropdown.querySelector('.dip-item-value');
            const dipItemToggle = dipItemDropdown.querySelector('.dip-item-toggle');
            const dipItemText = dipItemDropdown.querySelector('.dip-item-text');
            const dipItemForm = dipItemDropdown.closest('form');

            dipItemToggle.addEventListener('click', () => {
                const isOpen = dipItemDropdown.classList.toggle('is-open');
                dipItemToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            dipItemDropdown.querySelectorAll('.dip-item-option').forEach((option) => {
                option.addEventListener('click', () => {
                    dipItemInput.value = option.dataset.value || '';
                    dipItemText.textContent = option.textContent.trim() || 'Select Item';
                    dipItemForm.submit();
                });
            });

            document.addEventListener('click', (event) => {
                if (!dipItemDropdown.contains(event.target)) {
                    dipItemDropdown.classList.remove('is-open');
                    dipItemToggle.setAttribute('aria-expanded', 'false');
                }
            });
        }
    </script>
</body>
</html>
