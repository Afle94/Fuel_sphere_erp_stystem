<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Density Chart | FuelTracker</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <style>
        :root { --bg:#f4f7fb; --panel:#ffffff; --ink:#172033; --muted:#657089; --line:#dce3ee; --primary:#0f766e; --primary-dark:#115e59; --danger:#b42318; --shadow:0 16px 48px rgba(23,32,51,.10); }
        * { box-sizing:border-box; }
        html,body { height:100%; overflow:hidden; }
        body { margin:0; min-height:100vh; font-family:Arial, Helvetica, sans-serif; color:var(--ink); background:radial-gradient(circle at top left, rgba(15,118,110,.16), transparent 32rem), linear-gradient(135deg,#f8fbff 0%,var(--bg) 55%,#eef5f3 100%); }
        .site-header { position:sticky; top:0; z-index:20; width:100%; background:linear-gradient(135deg,rgba(8,47,73,.98),rgba(15,118,110,.98)); box-shadow:0 10px 30px rgba(23,32,51,.12); }
        .site-header-inner { width:100%; min-height:64px; display:grid; grid-template-columns:minmax(220px,1fr) auto minmax(220px,1fr); align-items:center; gap:18px; padding:0 8px; }
        .site-logo { display:inline-flex; align-items:center; gap:10px; color:#fff; font-size:21px; font-weight:700; text-decoration:none; }
        .site-logo-icon { display:grid; width:38px; height:38px; place-items:center; overflow:hidden; padding:2px; border-radius:999px; background:#fff; box-shadow:0 10px 28px rgba(0,0,0,.18); }
        .app-logo-image { display:block; width:100%; height:100%; border-radius:inherit; object-fit:cover; }
        .header-title { justify-self:center; color:#fff; font-size:20px; font-weight:700; white-space:nowrap; }
        .header-actions { display:flex; align-items:center; justify-self:end; gap:10px; }
        .back-link,.logout-btn { min-height:30px; display:inline-flex; align-items:center; justify-content:center; padding:0 14px; border:1px solid rgba(255,255,255,.24); border-radius:8px; color:#fff; background:rgba(255,255,255,.12); cursor:pointer; font:inherit; font-size:12px; font-weight:700; text-decoration:none; transition:background .2s ease, transform .2s ease; }
        .back-link:hover,.logout-btn:hover { background:rgba(255,255,255,.2); transform:translateY(-1px); }
        .density-list-workspace.app-shell-with-sidebar { width:calc(100vw - 24px); height:calc(100vh - 88px); min-height:0; grid-template-columns:300px minmax(0,1fr); margin:12px; border-radius:12px; }
        .density-list-workspace.app-shell-with-sidebar.menu-collapsed { grid-template-columns:64px minmax(0,1fr); }
        .density-list-page { min-width:0; min-height:0; overflow:hidden; padding:14px; }
        .list-shell { height:100%; min-height:0; display:grid; grid-template-rows:auto auto minmax(0,1fr); gap:12px; }
        .page-title,.list-panel { border:1px solid rgba(220,227,238,.86); border-radius:12px; background:var(--panel); box-shadow:var(--shadow); }
        .page-title { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:18px; }
        .eyebrow { margin:0 0 5px; color:var(--primary); font-size:10px; font-weight:700; text-transform:uppercase; }
        h1 { margin:0; font-size:30px; line-height:1.2; }
        .record-count { flex:0 0 auto; padding:6px 10px; border-radius:999px; color:var(--primary-dark); background:rgba(15,118,110,.09); font-size:11px; font-weight:700; }
        .density-content-grid { height:100%; min-height:0; display:grid; grid-template-columns:minmax(520px,620px) minmax(300px,1fr); gap:12px; overflow:hidden; }
        .list-panel,.import-panel,.format-panel { border:1px solid rgba(220,227,238,.86); border-radius:12px; background:var(--panel); box-shadow:var(--shadow); }
        .list-panel { min-width:0; min-height:0; display:flex; flex-direction:column; overflow:hidden; }
        .import-side { min-width:0; min-height:0; display:grid; grid-template-rows:auto minmax(0,1fr); align-content:stretch; gap:12px; overflow:hidden; }
        .import-panel { padding:16px; }
        .format-panel { min-height:0; overflow:hidden; }
        .panel-title { margin:0 0 12px; font-size:18px; line-height:1.25; font-weight:800; }
        .form-alert { margin:12px; padding:10px 12px; border-radius:12px; font-size:14px; font-weight:700; }
        .form-alert.is-hiding { opacity:0; transform:translateY(-6px); transition:opacity .25s ease, transform .25s ease; }
        .import-panel .form-alert { margin:0 0 12px; }
        .form-alert.success { color:#067647; background:#ecfdf3; border:1px solid rgba(6,118,71,.22); }
        .form-alert.error { color:#b42318; background:#fff1f0; border:1px solid rgba(180,35,24,.22); }
        .toolbar { display:flex; align-items:center; justify-content:space-between; gap:10px; padding:10px 12px; border-bottom:1px solid var(--line); }
        .filter-form { min-width:0; flex:1 1 auto; display:grid; grid-template-columns:72px minmax(116px,170px) 68px 58px; align-items:center; gap:8px; }
        .filter-label { color:var(--muted); font-size:12px; font-weight:800; }
        .fuel-dropdown { position:relative; min-width:0; }
        .fuel-dropdown-input { display:none; }
        .fuel-dropdown-toggle { width:100%; min-height:34px; display:flex; align-items:center; justify-content:space-between; gap:10px; padding:0 11px 0 12px; border:1px solid color-mix(in srgb, var(--primary) 28%, var(--line)); border-radius:8px; color:var(--ink); background:linear-gradient(135deg,rgba(15,118,110,.08),rgba(255,255,255,.98)); cursor:pointer; font:inherit; font-size:12px; font-weight:800; text-align:left; box-shadow:inset 0 1px 0 rgba(255,255,255,.72); }
        .fuel-dropdown-toggle:hover { border-color:var(--primary); box-shadow:0 0 0 3px rgba(15,118,110,.11); }
        .fuel-dropdown-toggle:focus { border-color:var(--primary); box-shadow:0 0 0 4px rgba(15,118,110,.16); outline:none; }
        .fuel-dropdown-text { min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .fuel-dropdown-arrow { width:16px; height:16px; flex:0 0 auto; transition:transform .2s ease; }
        .fuel-dropdown.is-open .fuel-dropdown-arrow { transform:rotate(180deg); }
        .fuel-dropdown-menu { position:absolute; top:calc(100% + 7px); left:0; z-index:30; width:100%; max-height:240px; display:none; overflow:auto; padding:6px; border:1px solid var(--line); border-radius:10px; background:#fff; box-shadow:0 18px 40px rgba(23,32,51,.16); scrollbar-width:thin; scrollbar-color:var(--primary) rgba(220,227,238,.72); }
        .fuel-dropdown.is-open .fuel-dropdown-menu { display:grid; gap:4px; }
        .fuel-option { min-height:34px; display:flex; align-items:center; width:100%; padding:0 10px; border:0; border-radius:8px; color:var(--ink); background:#fff; cursor:pointer; font:inherit; font-size:12px; font-weight:700; text-align:left; }
        .fuel-option:hover,.fuel-option:focus,.fuel-option.is-selected { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); outline:none; }
        .search-btn,.reset-btn { min-height:31px; display:inline-flex; align-items:center; justify-content:center; padding:0 12px; border-radius:8px; font-size:11px; font-weight:700; text-decoration:none; cursor:pointer; }
        .search-btn { border:1px solid transparent; color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); }
        .reset-btn { border:1px solid var(--line); color:var(--muted); background:#fff; }
        .field { display:grid; gap:8px; }
        .field label { color:var(--muted); font-size:12px; font-weight:800; text-transform:uppercase; }
        .file-control { position:absolute; width:1px; height:1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; clip-path:inset(50%); }
        .upload-control { min-height:78px; display:flex; align-items:center; justify-content:space-between; gap:12px; padding:12px; border:1px dashed rgba(15,118,110,.42); border-radius:12px; background:linear-gradient(135deg,rgba(15,118,110,.08),rgba(255,255,255,.96)); cursor:pointer; transition:border-color .2s ease, box-shadow .2s ease, transform .2s ease; }
        .upload-control:hover { border-color:var(--primary); box-shadow:0 12px 28px rgba(15,118,110,.10); transform:translateY(-1px); }
        .file-control:focus + .upload-control { border-color:var(--primary); box-shadow:0 0 0 4px rgba(15,118,110,.13); }
        .upload-info { min-width:0; display:flex; align-items:center; gap:12px; }
        .upload-icon { width:42px; height:42px; flex:0 0 auto; display:grid; place-items:center; border-radius:10px; color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); box-shadow:0 10px 22px rgba(15,118,110,.18); }
        .upload-icon svg { width:22px; height:22px; }
        .upload-copy { min-width:0; display:grid; gap:4px; }
        .upload-title { color:var(--ink); font-size:14px; font-weight:800; }
        .upload-filename { max-width:100%; overflow:hidden; color:var(--muted); font-size:12px; font-weight:700; text-overflow:ellipsis; white-space:nowrap; }
        .upload-button { flex:0 0 auto; min-height:32px; display:inline-flex; align-items:center; justify-content:center; padding:0 12px; border-radius:9px; color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); font-size:12px; font-weight:800; box-shadow:0 10px 22px rgba(15,118,110,.16); }
        .help-text { margin:10px 0 0; color:var(--muted); font-size:13px; line-height:1.55; }
        .import-actions { display:flex; align-items:center; justify-content:flex-end; gap:10px; margin-top:16px; padding-top:14px; border-top:1px solid var(--line); }
        .primary-btn { min-height:36px; display:inline-flex; align-items:center; justify-content:center; padding:0 16px; border:1px solid transparent; border-radius:9px; color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); cursor:pointer; font:inherit; font-size:13px; font-weight:800; }
        .danger-btn { min-height:36px; display:inline-flex; align-items:center; justify-content:center; flex:0 0 auto; padding:0 14px; border:1px solid rgba(180,35,24,.28); border-radius:9px; color:#fff; background:linear-gradient(135deg,#b42318,#d92d20); cursor:pointer; font:inherit; font-size:12px; font-weight:800; }
        .danger-btn:disabled { opacity:.48; cursor:not-allowed; }
        .toolbar-delete-form { flex:0 0 auto; }
        .toolbar-delete-form .danger-btn { min-height:31px; padding:0 12px; border-radius:8px; font-size:11px; }
        .delete-modal { position:fixed; inset:0; z-index:80; display:none; align-items:center; justify-content:center; padding:18px; background:rgba(15,23,42,.48); backdrop-filter:blur(6px); }
        .delete-modal.is-open { display:flex; }
        .delete-dialog { width:min(100%,420px); border:1px solid rgba(220,227,238,.92); border-radius:18px; background:var(--panel); box-shadow:0 24px 70px rgba(15,23,42,.28); overflow:hidden; transform:translateY(8px) scale(.98); animation:modalPop .18s ease forwards; }
        @keyframes modalPop { to { transform:translateY(0) scale(1); } }
        .delete-dialog-head { display:flex; align-items:center; gap:12px; padding:18px 18px 12px; }
        .delete-dialog-icon { width:42px; height:42px; flex:0 0 auto; display:inline-flex; align-items:center; justify-content:center; border-radius:14px; color:var(--danger); background:#fff1f0; font-size:22px; font-weight:800; }
        .delete-dialog-title { margin:0; color:var(--ink); font-size:20px; line-height:1.2; }
        .delete-dialog-body { padding:0 18px 18px; color:var(--muted); font-size:14px; line-height:1.55; }
        .delete-dialog-body strong { color:var(--ink); }
        .delete-dialog-actions { display:flex; justify-content:flex-end; gap:10px; padding:14px 18px 18px; border-top:1px solid var(--line); background:#fbfcfe; }
        .modal-no-btn,.modal-yes-btn { min-height:38px; display:inline-flex; align-items:center; justify-content:center; padding:0 18px; border-radius:12px; cursor:pointer; font:inherit; font-size:13px; font-weight:800; transition:background .2s ease, transform .2s ease, box-shadow .2s ease; }
        .modal-no-btn { border:1px solid var(--line); color:var(--ink); background:#fff; }
        .modal-no-btn:hover,.modal-no-btn:focus { border-color:rgba(15,118,110,.28); color:var(--primary-dark); background:rgba(15,118,110,.08); outline:none; }
        .modal-yes-btn { border:0; color:#fff; background:var(--danger); box-shadow:0 12px 28px rgba(180,35,24,.2); }
        .modal-yes-btn:hover,.modal-yes-btn:focus { background:#912018; transform:translateY(-1px); outline:none; }
        .format-table { width:100%; border-collapse:collapse; }
        .format-table th,.format-table td { padding:11px 12px; border-bottom:1px solid var(--line); font-size:13px; text-align:left; }
        .format-table th { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); font-weight:800; }
        .sample-value { color:var(--muted); font-family:"Courier New", monospace; }
        .table-wrap { flex:1 1 auto; min-height:0; overflow:auto; scrollbar-width:thin; scrollbar-color:var(--primary) rgba(220,227,238,.72); }
        .table-wrap::-webkit-scrollbar { width:10px; height:10px; }
        .table-wrap::-webkit-scrollbar-track { background:rgba(220,227,238,.72); }
        .table-wrap::-webkit-scrollbar-thumb { border-radius:999px; background:var(--primary); }
        .density-table { width:100%; min-width:560px; border-collapse:collapse; table-layout:fixed; }
        col.sno-col { width:76px; }
        col.temp-col { width:130px; }
        col.base-col { width:190px; }
        col.value-col { width:170px; }
        th,td { padding:10px 12px; border-bottom:1px solid var(--line); font-size:13px; text-align:left; vertical-align:middle; }
        th { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); font-size:13px; font-weight:800; white-space:nowrap; }
        thead th { position:sticky; top:0; z-index:5; }
        .sort-link { display:inline-flex; align-items:center; justify-content:flex-end; gap:6px; width:100%; color:#fff; text-decoration:none; }
        .sort-mark { width:10px; height:14px; position:relative; flex:0 0 10px; opacity:.7; }
        .sort-mark::before,.sort-mark::after { content:""; position:absolute; left:50%; width:0; height:0; border-left:3px solid transparent; border-right:3px solid transparent; transform:translateX(-50%); }
        .sort-mark::before { top:2px; border-bottom:4px solid rgba(255,255,255,.62); }
        .sort-mark::after { bottom:2px; border-top:4px solid rgba(255,255,255,.62); }
        .sort-link.is-active .sort-mark { opacity:1; }
        .sort-link.is-active .sort-mark.asc::before { border-bottom-color:#fff; }
        .sort-link.is-active .sort-mark.desc::after { border-top-color:#fff; }
        tbody tr:hover { background:rgba(15,118,110,.05); }
        .number-cell { text-align:right; font-variant-numeric:tabular-nums; }
        .text-strong { font-weight:700; }
        .empty-state { padding:34px 16px; color:var(--muted); font-size:14px; font-weight:700; text-align:center; }
        .pagination-bar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:11px 12px; color:var(--muted); font-size:12px; }
        .pagination-links { display:flex; align-items:center; gap:6px; flex-wrap:nowrap; }
        .page-link,.page-current { min-width:28px; min-height:28px; display:inline-flex; align-items:center; justify-content:center; padding:0 8px; border-radius:8px; font-size:12px; font-weight:700; text-decoration:none; }
        .page-link { border:1px solid var(--line); color:var(--muted); background:#fff; }
        .page-current { color:#fff; background:var(--primary); }
        .page-link.muted { opacity:.55; }
        @media (max-width:760px) {
            .site-header-inner { grid-template-columns:1fr; gap:8px; padding:10px; }
            .header-title { font-size:17px; }
            .header-actions { justify-self:center; }
            .density-list-workspace.app-shell-with-sidebar { width:100%; height:calc(100vh - 64px); display:block; margin:0; border-radius:0; }
            .density-content-grid { grid-template-columns:1fr; overflow:auto; }
            .toolbar,.page-title,.pagination-bar,.import-actions,.upload-control { align-items:stretch; flex-direction:column; }
            .filter-form { width:100%; grid-template-columns:1fr; }
            .toolbar-delete-form,.toolbar-delete-form .danger-btn { width:100%; }
            .fuel-dropdown-menu { position:static; margin-top:7px; }
            .upload-button { width:100%; }
        }
    </style>
    @include('partials.theme')
</head>
<body>
    @php
        $sortUrl = function ($column) use ($selectedFuelType, $sort, $direction) {
            return route('density.chart', [
                'filter' => $selectedFuelType !== '' ? '1' : null,
                'fuel_type' => $selectedFuelType,
                'sort' => $column,
                'direction' => ($sort === $column && $direction === 'asc') ? 'desc' : 'asc',
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
            <div class="header-title">Density Chart</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-btn">Logout</button></form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar density-list-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')
        <main class="density-list-page">
            <div class="list-shell">
                <section class="page-title" aria-labelledby="densityListTitle">
                    <div>
                        <p class="eyebrow">Reports</p>
                        <h1 id="densityListTitle">Density Chart</h1>
                    </div>
                    <span class="record-count">{{ $data->total() }} {{ $data->total() === 1 ? 'record' : 'records' }}</span>
                </section>

                @if (session('success')) <div class="form-alert success">{{ session('success') }}</div> @endif
                @if (session('error')) <div class="form-alert error">{{ session('error') }}</div> @endif
                @if ($errors->any()) <div class="form-alert error">{{ $errors->first() }}</div> @endif

                <div class="density-content-grid">
                    <section class="list-panel">
                        <div class="toolbar">
                            <form class="filter-form" method="GET" action="{{ route('density.chart') }}">
                                <input type="hidden" name="filter" value="1">
                                <label class="filter-label" for="fuel_type">Fuel Type</label>
                                <div class="fuel-dropdown" data-fuel-dropdown>
                                    <input class="fuel-dropdown-input" id="fuel_type" name="fuel_type" type="hidden" value="{{ $selectedFuelType }}">
                                    <button class="fuel-dropdown-toggle" type="button" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="fuel-dropdown-text" data-fuel-dropdown-text>{{ $selectedFuelType ?: 'Select Fuel Type' }}</span>
                                        <svg class="fuel-dropdown-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                    <div class="fuel-dropdown-menu" role="listbox">
                                        @foreach ($fuelTypes as $fuelType)
                                            <button class="fuel-option {{ $selectedFuelType === $fuelType ? 'is-selected' : '' }}" type="button" role="option" data-value="{{ $fuelType }}">{{ $fuelType }}</button>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="submit" class="search-btn">Search</button>
                                <a href="{{ route('density.chart') }}" class="reset-btn">Clear</a>
                            </form>
                            <form class="toolbar-delete-form" method="POST" action="{{ route('density.destroy-all') }}" data-delete-all-density data-record-count="{{ $totalDensityRecords }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="danger-btn" {{ $totalDensityRecords === 0 ? 'disabled' : '' }}>Delete All</button>
                            </form>
                        </div>

                    <div class="table-wrap">
                        <table class="density-table">
                            <colgroup>
                                <col class="sno-col">
                                <col class="temp-col">
                                <col class="base-col">
                                <col class="value-col">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="number-cell">
                                        <a class="sort-link {{ $sort === 'id' ? 'is-active' : '' }}" href="{{ $sortUrl('id') }}">
                                            S.No.
                                            <span class="sort-mark {{ $sortMark('id') }}" aria-hidden="true"></span>
                                        </a>
                                    </th>
                                    <th class="number-cell">
                                        <a class="sort-link {{ $sort === 'temperature' ? 'is-active' : '' }}" href="{{ $sortUrl('temperature') }}">
                                            Temp
                                            <span class="sort-mark {{ $sortMark('temperature') }}" aria-hidden="true"></span>
                                        </a>
                                    </th>
                                    <th class="number-cell">
                                        <a class="sort-link {{ $sort === 'base_dens' ? 'is-active' : '' }}" href="{{ $sortUrl('base_dens') }}">
                                            Base Density
                                            <span class="sort-mark {{ $sortMark('base_dens') }}" aria-hidden="true"></span>
                                        </a>
                                    </th>
                                    <th class="number-cell">
                                        <a class="sort-link {{ $sort === 'chart_val' ? 'is-active' : '' }}" href="{{ $sortUrl('chart_val') }}">
                                            Value
                                            <span class="sort-mark {{ $sortMark('chart_val') }}" aria-hidden="true"></span>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($data->count())
                                    @foreach ($data as $density)
                                        <tr>
                                            <td class="number-cell text-strong">{{ $data->firstItem() + $loop->index }}</td>
                                            <td class="number-cell">{{ number_format((float) $density->temperature, 2) }}</td>
                                            <td class="number-cell">{{ number_format((float) $density->base_dens, 4) }}</td>
                                            <td class="number-cell">{{ number_format((float) $density->chart_val, 4) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="empty-state" colspan="4">
                                            {{ $selectedFuelType ? 'No density chart records found.' : 'Please select a fuel type to view density chart records.' }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if ($data->hasPages())
                        <div class="pagination-bar">
                            <span>
                                Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} records
                            </span>
                            <div class="pagination-links">
                                @if ($data->onFirstPage())
                                    <span class="page-link muted">Prev</span>
                                @else
                                    <a class="page-link" href="{{ $data->previousPageUrl() }}">Prev</a>
                                @endif

                                @php
                                    $currentPage = $data->currentPage();
                                    $lastPage = $data->lastPage();
                                    $pages = collect([1, $lastPage, $currentPage - 2, $currentPage - 1, $currentPage, $currentPage + 1, $currentPage + 2])
                                        ->filter(fn ($page) => $page >= 1 && $page <= $lastPage)
                                        ->unique()
                                        ->sort()
                                        ->values();
                                    $previousPage = null;
                                @endphp

                                @foreach ($pages as $page)
                                    @if ($previousPage && $page > $previousPage + 1)
                                        <span class="page-link muted">...</span>
                                    @endif

                                    @if ($page === $currentPage)
                                        <span class="page-current">{{ $page }}</span>
                                    @else
                                        <a class="page-link" href="{{ $data->url($page) }}">{{ $page }}</a>
                                    @endif

                                    @php $previousPage = $page; @endphp
                                @endforeach

                                @if ($data->hasMorePages())
                                    <a class="page-link" href="{{ $data->nextPageUrl() }}">Next</a>
                                @else
                                    <span class="page-link muted">Next</span>
                                @endif
                            </div>
                        </div>
                    @endif
                    </section>

                    <aside class="import-side" aria-label="Density chart import">
                        <section class="import-panel">
                            <h2 class="panel-title">Import Density Chart</h2>
                            <form method="POST" action="{{ route('density.import.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="field">
                                    <label for="density_file">Excel / CSV File</label>
                                    <input class="file-control" id="density_file" name="density_file" type="file" required>
                                    <label class="upload-control" for="density_file">
                                        <span class="upload-info">
                                            <span class="upload-icon" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none">
                                                    <path d="M12 15V4m0 0 4 4m-4-4-4 4M5 15v3a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </span>
                                            <span class="upload-copy">
                                                <span class="upload-title">Choose density chart file</span>
                                                <span class="upload-filename" id="densityFileName">No file selected</span>
                                            </span>
                                        </span>
                                        <span class="upload-button">Browse</span>
                                    </label>
                                </div>
                                <p class="help-text">Duplicate rows with the same Fuel Type, Temp, and Base Density will be updated with the new Value.</p>
                                <div class="import-actions">
                                    <button type="reset" class="reset-btn">Clear</button>
                                    <button type="submit" class="primary-btn">Import File</button>
                                </div>
                            </form>
                        </section>

                        <section class="format-panel" aria-labelledby="formatTitle">
                            <table class="format-table">
                                <thead>
                                    <tr>
                                        <th id="formatTitle">Required Column</th>
                                        <th>Sample</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Fuel Type</td>
                                        <td class="sample-value">HSD</td>
                                    </tr>
                                    <tr>
                                        <td>Temp</td>
                                        <td class="sample-value">15.00</td>
                                    </tr>
                                    <tr>
                                        <td>Base Density</td>
                                        <td class="sample-value">0.8300</td>
                                    </tr>
                                    <tr>
                                        <td>Value</td>
                                        <td class="sample-value">0.0012</td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>
                    </aside>
                </div>
            </div>
        </main>
    </div>

    <div class="delete-modal" id="deleteConfirmModal" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle" aria-hidden="true">
        <div class="delete-dialog">
            <div class="delete-dialog-head">
                <span class="delete-dialog-icon" aria-hidden="true">!</span>
                <div>
                    <h2 class="delete-dialog-title" id="deleteModalTitle">Do you want to delete?</h2>
                </div>
            </div>
            <div class="delete-dialog-body">
                <p>
                    Are you sure you want to delete <strong id="deleteDensityName">all density chart records</strong>? This action cannot be undone.
                </p>
            </div>
            <div class="delete-dialog-actions">
                <button type="button" class="modal-no-btn" id="deleteCancelBtn">No</button>
                <button type="button" class="modal-yes-btn" id="deleteConfirmBtn">Yes</button>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-fuel-dropdown]').forEach((dropdown) => {
            const input = dropdown.querySelector('.fuel-dropdown-input');
            const toggle = dropdown.querySelector('.fuel-dropdown-toggle');
            const text = dropdown.querySelector('[data-fuel-dropdown-text]');
            const options = dropdown.querySelectorAll('.fuel-option');

            const close = () => {
                dropdown.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            };

            toggle.addEventListener('click', () => {
                const isOpen = dropdown.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            options.forEach((option) => {
                option.addEventListener('click', () => {
                    input.value = option.dataset.value || '';
                    text.textContent = option.textContent.trim();
                    options.forEach((item) => item.classList.remove('is-selected'));
                    option.classList.add('is-selected');
                    close();
                });
            });

            document.addEventListener('click', (event) => {
                if (! dropdown.contains(event.target)) {
                    close();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    close();
                }
            });
        });

        const densityFileInput = document.getElementById('density_file');
        const densityFileName = document.getElementById('densityFileName');

        if (densityFileInput && densityFileName) {
            densityFileInput.addEventListener('change', () => {
                densityFileName.textContent = densityFileInput.files.length
                    ? densityFileInput.files[0].name
                    : 'No file selected';
            });
        }

        const deleteModal = document.getElementById('deleteConfirmModal');
        const deleteDensityName = document.getElementById('deleteDensityName');
        const deleteCancelBtn = document.getElementById('deleteCancelBtn');
        const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');
        const deleteAllDensityForm = document.querySelector('[data-delete-all-density]');
        let pendingDeleteForm = null;

        const closeDeleteModal = () => {
            deleteModal.classList.remove('is-open');
            deleteModal.setAttribute('aria-hidden', 'true');
            pendingDeleteForm = null;
        };

        if (deleteAllDensityForm) {
            deleteAllDensityForm.addEventListener('submit', (event) => {
                if (deleteAllDensityForm.dataset.confirmed === 'true') {
                    return;
                }

                event.preventDefault();
                pendingDeleteForm = deleteAllDensityForm;
                deleteDensityName.textContent = `${deleteAllDensityForm.dataset.recordCount || 'all'} density chart records`;
                deleteModal.classList.add('is-open');
                deleteModal.setAttribute('aria-hidden', 'false');
                deleteCancelBtn.focus();
            });
        }

        deleteCancelBtn.addEventListener('click', closeDeleteModal);

        deleteConfirmBtn.addEventListener('click', () => {
            if (! pendingDeleteForm) {
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

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && deleteModal.classList.contains('is-open')) {
                closeDeleteModal();
            }
        });

        document.querySelectorAll('.form-alert').forEach((alert) => {
            setTimeout(() => {
                alert.classList.add('is-hiding');
                setTimeout(() => alert.remove(), 250);
            }, 2000);
        });
    </script>
</body>
</html>
