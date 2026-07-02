<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Of Item Date Wise Rates | FuelTracker</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <style>
        :root { --bg:#f4f7fb; --panel:#fff; --ink:#172033; --muted:#657089; --line:#dce3ee; --primary:#0f766e; --primary-dark:#115e59; --danger:#b42318; --shadow:0 16px 48px rgba(23,32,51,.10); }
        * { box-sizing:border-box; }
        body { margin:0; min-height:100vh; font-family:Arial, Helvetica, sans-serif; color:var(--ink); background:radial-gradient(circle at top left, rgba(15,118,110,.16), transparent 32rem), linear-gradient(135deg,#f8fbff 0%,var(--bg) 55%,#eef5f3 100%); }
        .site-header { position:sticky; top:0; z-index:20; background:linear-gradient(135deg,rgba(8,47,73,.98),rgba(15,118,110,.98)); box-shadow:0 10px 30px rgba(23,32,51,.12); }
        .site-header-inner { min-height:64px; display:grid; grid-template-columns:minmax(220px,1fr) auto minmax(220px,1fr); align-items:center; gap:18px; padding:0 8px; }
        .site-logo { display:inline-flex; align-items:center; gap:10px; color:#fff; font-size:21px; font-weight:700; text-decoration:none; }
        .site-logo-icon { width:38px; height:38px; display:grid; place-items:center; overflow:hidden; padding:2px; border-radius:999px; background:#fff; box-shadow:0 10px 28px rgba(0,0,0,.18); }
        .app-logo-image { width:100%; height:100%; border-radius:inherit; object-fit:cover; }
        .header-title { justify-self:center; color:#fff; font-size:20px; font-weight:700; white-space:nowrap; }
        .header-actions { display:flex; align-items:center; justify-self:end; gap:10px; }
        .back-link,.logout-btn,.new-btn { min-height:30px; display:inline-flex; align-items:center; justify-content:center; padding:0 14px; border:1px solid rgba(255,255,255,.24); border-radius:8px; color:#fff; background:rgba(255,255,255,.12); cursor:pointer; font-size:12px; font-weight:700; text-decoration:none; }
        .logout-btn { font-family:inherit; }
        .rate-list-workspace.app-shell-with-sidebar { width:calc(100vw - 24px); min-height:calc(100vh - 88px); grid-template-columns:300px minmax(0,1fr); margin:12px; border-radius:12px; }
        .rate-list-workspace.app-shell-with-sidebar.menu-collapsed { grid-template-columns:64px minmax(0,1fr); }
        .rate-list-page { min-width:0; padding:14px; }
        .list-shell { display:grid; gap:12px; }
        .page-title,.list-panel { border:1px solid rgba(220,227,238,.86); border-radius:12px; background:var(--panel); box-shadow:var(--shadow); }
        .page-title { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:18px; }
        .eyebrow { margin:0 0 5px; color:var(--primary); font-size:10px; font-weight:700; text-transform:uppercase; }
        h1 { margin:0; font-size:30px; line-height:1.2; }
        .record-count { padding:6px 10px; border-radius:999px; color:var(--primary-dark); background:rgba(15,118,110,.09); font-size:11px; font-weight:700; }
        .list-panel { overflow:hidden; }
        .form-alert { margin:12px; padding:10px 12px; border-radius:12px; font-size:14px; font-weight:700; }
        .form-alert.success { color:#067647; background:#ecfdf3; border:1px solid rgba(6,118,71,.22); }
        .toolbar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:10px 12px; border-bottom:1px solid var(--line); }
        .toolbar-actions { display:inline-flex; align-items:center; gap:8px; }
        .search-form { width:min(100%,650px); display:grid; grid-template-columns:minmax(160px,1fr) 74px 66px 116px; align-items:center; gap:8px; }
        .search-input { width:100%; min-height:31px; padding:0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fbfcfe; font:inherit; font-size:11px; outline:none; }
        .search-btn,.reset-btn,.new-btn { min-height:31px; display:inline-flex; align-items:center; justify-content:center; padding:0 12px; border-radius:8px; font-size:11px; font-weight:700; text-decoration:none; cursor:pointer; }
        .search-btn,.new-btn { border:1px solid transparent; color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); }
        .reset-btn { border:1px solid var(--line); color:var(--muted); background:#fff; }
        .entries-dropdown { position:relative; display:inline-flex; align-items:center; }
        .entries-toggle { min-height:31px; min-width:104px; padding:0 34px 0 12px; border:1px solid var(--line); border-radius:8px; color:var(--ink); background:#fff; cursor:pointer; font:inherit; font-size:11px; font-weight:700; text-align:left; }
        .entries-dropdown::after { content:""; position:absolute; right:12px; top:50%; border-left:4px solid transparent; border-right:4px solid transparent; border-top:5px solid var(--muted); transform:translateY(-40%); pointer-events:none; }
        .entries-menu { position:absolute; top:calc(100% + 6px); left:0; z-index:10; display:none; min-width:136px; overflow:hidden; border:1px solid var(--line); border-radius:10px; background:#fff; box-shadow:0 18px 40px rgba(23,32,51,.16); }
        .entries-dropdown.is-open .entries-menu { display:grid; }
        .entries-option { min-height:36px; padding:0 12px; border:0; color:var(--ink); background:#fff; cursor:pointer; font:inherit; font-size:12px; text-align:left; }
        .entries-option:hover,.entries-option.is-selected:hover { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); }
        .entries-option.is-selected { font-weight:700; }
        .table-wrap { overflow-x:auto; }
        table { width:100%; min-width:760px; border-collapse:collapse; }
        th,td { padding:10px 12px; border-bottom:1px solid var(--line); font-size:13px; text-align:left; vertical-align:middle; }
        th { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); font-weight:800; }
        .sort-link { display:inline-flex; align-items:center; gap:6px; color:#fff; text-decoration:none; white-space:nowrap; }
        .sort-mark { position:relative; width:10px; height:14px; opacity:.72; }
        .sort-mark::before,.sort-mark::after { content:""; position:absolute; left:50%; border-left:3px solid transparent; border-right:3px solid transparent; transform:translateX(-50%); }
        .sort-mark::before { top:2px; border-bottom:4px solid rgba(255,255,255,.58); }
        .sort-mark::after { bottom:2px; border-top:4px solid rgba(255,255,255,.58); }
        .sort-link.is-active .sort-mark.asc::before { border-bottom-color:#fff; }
        .sort-link.is-active .sort-mark.desc::after { border-top-color:#fff; }
        .text-strong { font-weight:700; }
        .actions { display:flex; align-items:center; gap:8px; }
        .action-btn { min-height:28px; display:inline-flex; align-items:center; justify-content:center; padding:0 10px; border:0; border-radius:8px; cursor:pointer; font-size:11px; font-weight:700; text-decoration:none; }
        .edit-btn { color:#075985; background:#e0f2fe; }
        .delete-btn { color:var(--danger); background:#fff1f0; font-family:inherit; }
        .empty-state { padding:34px 16px; color:var(--muted); font-size:14px; font-weight:700; text-align:center; }
        .pagination-bar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:11px 12px; color:var(--muted); font-size:12px; }
        .pagination-links { display:flex; align-items:center; gap:6px; flex-wrap:wrap; }
        .page-link,.page-current { min-width:28px; min-height:28px; display:inline-flex; align-items:center; justify-content:center; padding:0 8px; border-radius:8px; font-size:12px; font-weight:700; text-decoration:none; }
        .page-link { border:1px solid var(--line); color:var(--muted); background:#fff; }
        .page-link.muted { opacity:.55; }
        .page-current { color:#fff; background:var(--primary); }
        .delete-modal { position:fixed; inset:0; z-index:80; display:none; align-items:center; justify-content:center; padding:18px; background:rgba(15,23,42,.48); }
        .delete-modal.is-open { display:flex; }
        .delete-dialog { width:min(100%,420px); border-radius:14px; background:#fff; box-shadow:0 24px 70px rgba(15,23,42,.28); overflow:hidden; }
        .delete-dialog-head { display:flex; align-items:center; gap:12px; padding:18px; border-bottom:1px solid var(--line); }
        .delete-dialog-icon { width:34px; height:34px; display:grid; place-items:center; border-radius:999px; color:#fff; background:var(--danger); font-weight:800; }
        .delete-dialog-title { margin:0; font-size:20px; }
        .delete-dialog-body { padding:16px 18px; color:var(--muted); line-height:1.5; }
        .delete-dialog-actions { display:flex; justify-content:flex-end; gap:10px; padding:14px 18px 18px; }
        .modal-no-btn,.modal-yes-btn { min-height:34px; padding:0 18px; border-radius:9px; cursor:pointer; font:inherit; font-size:13px; font-weight:700; }
        .modal-no-btn { border:1px solid var(--line); color:var(--muted); background:#fff; }
        .modal-yes-btn { border:0; color:#fff; background:var(--danger); }
        @media (max-width:760px) { .site-header-inner{grid-template-columns:1fr;gap:8px;padding:10px}.header-title{font-size:17px}.header-actions{justify-self:center}.toolbar{align-items:stretch;flex-direction:column}.search-form{width:100%;grid-template-columns:1fr}.toolbar-actions{width:100%;display:grid;grid-template-columns:1fr}.entries-dropdown,.entries-toggle{width:100%}.page-title{align-items:flex-start;flex-direction:column}.pagination-bar{align-items:flex-start;flex-direction:column} }
    </style>
    @include('partials.theme')
</head>
<body>
    @php
        $columns = ['id' => 'Sr.', 'rate_date' => 'Date', 'product_name' => 'Item Name', 'rate' => 'Rate', 'created_at' => 'Created'];
        $sortUrl = function ($column) use ($sort, $direction, $search, $perPage) {
            return route('item-date-rates.list', [
                'search' => $search,
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
            <div class="header-title">List Of Item Date Wise Rates</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-btn">Logout</button></form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar rate-list-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')
        <main class="rate-list-page">
            <div class="list-shell">
                <section class="page-title" aria-labelledby="itemRateListTitle">
                    <div>
                        <p class="eyebrow">Masters</p>
                        <h1 id="itemRateListTitle">List Of Item Date Wise Rates</h1>
                    </div>
                    <span class="record-count">{{ $itemDateRates->total() }} {{ $itemDateRates->total() === 1 ? 'record' : 'records' }}</span>
                </section>

                <section class="list-panel">
                    @if (session('success')) <div class="form-alert success">{{ session('success') }}</div> @endif

                    <div class="toolbar">
                        <form class="search-form" method="GET" action="{{ route('item-date-rates.list') }}">
                            <input type="hidden" name="sort" value="{{ $sort }}">
                            <input type="hidden" name="direction" value="{{ $direction }}">
                            <input class="search-input" type="search" name="search" value="{{ $search }}" placeholder="Search date, item or rate">
                            <button type="submit" class="search-btn">Search</button>
                            <a href="{{ route('item-date-rates.list') }}" class="reset-btn">Clear</a>
                            <div class="entries-dropdown">
                                <input type="hidden" name="per_page" value="{{ $perPage }}">
                                <button class="entries-toggle" type="button" aria-haspopup="listbox" aria-expanded="false">{{ $perPage }} Entries</button>
                                <div class="entries-menu" role="listbox">
                                    @foreach ($perPageOptions as $option)
                                        <button class="entries-option {{ $perPage === $option ? 'is-selected' : '' }}" type="button" role="option" aria-selected="{{ $perPage === $option ? 'true' : 'false' }}" data-per-page="{{ $option }}">{{ $option }} Entries</button>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                        <div class="toolbar-actions">
                            @if ($itemDateRates->count())
                                <a href="{{ route('item-date-rates.pdf') }}" class="new-btn" target="_blank" rel="noopener" data-themed-export>PDF</a>
                                <a href="{{ route('item-date-rates.excel') }}" class="new-btn" data-themed-export>Excel</a>
                            @endif
                            <a href="{{ route('item-date-rates') }}" class="new-btn">New Rate</a>
                        </div>
                    </div>

                    @if ($itemDateRates->count())
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        @foreach ($columns as $column => $label)
                                            <th><a class="sort-link {{ $sort === $column ? 'is-active' : '' }}" href="{{ $sortUrl($column) }}"><span>{{ $label }}</span><span class="sort-mark {{ $sortMark($column) }}" aria-hidden="true"></span></a></th>
                                        @endforeach
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($itemDateRates as $itemDateRate)
                                        <tr>
                                            <td>{{ $itemDateRate->id }}</td>
                                            <td>{{ optional($itemDateRate->rate_date)->format('d M Y') ?: '-' }}</td>
                                            <td class="text-strong">{{ $itemDateRate->product->Product_Name ?? '-' }}</td>
                                            <td>{{ number_format((float) $itemDateRate->rate, 2) }}</td>
                                            <td>{{ optional($itemDateRate->created_at)->format('d M Y') ?: '-' }}</td>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('item-date-rates.edit', $itemDateRate->id) }}" class="action-btn edit-btn">Edit</a>
                                                    <form class="delete-form" method="POST" action="{{ route('item-date-rates.destroy', $itemDateRate->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-btn delete-btn" data-delete-rate="{{ $itemDateRate->product->Product_Name ?? 'this rate' }}">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">No item date wise rates found{{ $search ? ' for "' . $search . '"' : '' }}.</div>
                    @endif

                    <div class="pagination-bar">
                        <div>
                            @if ($itemDateRates->total())
                                Showing {{ $itemDateRates->firstItem() }} to {{ $itemDateRates->lastItem() }} of {{ $itemDateRates->total() }}
                            @else
                                Showing 0 records
                            @endif
                        </div>
                        @include('partials.compact-pagination', ['paginator' => $itemDateRates])
                    </div>
                </section>
            </div>
        </main>
    </div>

    <div class="delete-modal" id="deleteConfirmModal" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle" aria-hidden="true">
        <div class="delete-dialog">
            <div class="delete-dialog-head">
                <span class="delete-dialog-icon" aria-hidden="true">!</span>
                <h2 class="delete-dialog-title" id="deleteModalTitle">Do you want to delete?</h2>
            </div>
            <div class="delete-dialog-body">
                <p>Are you sure you want to delete <strong id="deleteRateName">this rate</strong>? This action cannot be undone.</p>
            </div>
            <div class="delete-dialog-actions">
                <button type="button" class="modal-no-btn" id="deleteCancelBtn">No</button>
                <button type="button" class="modal-yes-btn" id="deleteConfirmBtn">Yes</button>
            </div>
        </div>
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

        const deleteModal = document.getElementById('deleteConfirmModal');
        const deleteRateName = document.getElementById('deleteRateName');
        const deleteCancelBtn = document.getElementById('deleteCancelBtn');
        const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');
        let pendingDeleteForm = null;

        const closeDeleteModal = () => {
            deleteModal.classList.remove('is-open');
            deleteModal.setAttribute('aria-hidden', 'true');
            pendingDeleteForm = null;
        };

        document.querySelectorAll('.delete-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                const button = form.querySelector('[data-delete-rate]');
                if (form.dataset.confirmed === 'true') {
                    return;
                }
                event.preventDefault();
                pendingDeleteForm = form;
                deleteRateName.textContent = button?.dataset.deleteRate || 'this rate';
                deleteModal.classList.add('is-open');
                deleteModal.setAttribute('aria-hidden', 'false');
                deleteCancelBtn.focus();
            });
        });

        deleteCancelBtn.addEventListener('click', closeDeleteModal);
        deleteConfirmBtn.addEventListener('click', () => {
            if (!pendingDeleteForm) {
                return;
            }
            pendingDeleteForm.dataset.confirmed = 'true';
            pendingDeleteForm.submit();
        });
        deleteModal.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
