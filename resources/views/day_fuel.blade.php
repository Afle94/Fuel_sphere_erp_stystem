<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Day Fuel Sales | FuelTracker</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <style>
        :root {
            --bg:#f4f7fb;
            --panel:#ffffff;
            --ink:#172033;
            --muted:#657089;
            --line:#dce3ee;
            --primary:#0f766e;
            --primary-dark:#115e59;
            --shadow:0 16px 48px rgba(23,32,51,.10);
        }

        * { box-sizing:border-box; }

        body {
            margin:0;
            min-height:100vh;
            font-family:Arial, Helvetica, sans-serif;
            color:var(--ink);
            background:linear-gradient(135deg,#f8fbff 0%,var(--bg) 55%,#eef5f3 100%);
        }

        .site-header {
            position:sticky;
            top:0;
            z-index:20;
            width:100%;
            background:linear-gradient(135deg,rgba(8,47,73,.98),rgba(15,118,110,.98));
            box-shadow:0 10px 30px rgba(23,32,51,.12);
        }

        .site-header-inner {
            width:100%;
            min-height:64px;
            display:grid;
            grid-template-columns:minmax(220px,1fr) auto minmax(220px,1fr);
            align-items:center;
            gap:18px;
            padding:0 12px;
        }

        .site-logo {
            display:inline-flex;
            align-items:center;
            gap:10px;
            color:#fff;
            font-size:21px;
            font-weight:700;
            text-decoration:none;
        }

        .site-logo-icon {
            display:grid;
            width:38px;
            height:38px;
            place-items:center;
            border-radius:999px;
            background:#fff;
            overflow:hidden;
            padding:2px;
        }

        .app-logo-image {
            display:block;
            width:100%;
            height:100%;
            border-radius:inherit;
            object-fit:cover;
        }

        .header-title {
            justify-self:center;
            color:#fff;
            font-size:20px;
            font-weight:700;
            white-space:nowrap;
        }

        .header-actions {
            display:flex;
            align-items:center;
            justify-self:end;
            gap:10px;
        }

        .back-link,
        .logout-btn {
            min-height:30px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0 14px;
            border:1px solid rgba(255,255,255,.24);
            border-radius:8px;
            color:#fff;
            background:rgba(255,255,255,.12);
            cursor:pointer;
            font-size:12px;
            font-weight:700;
            text-decoration:none;
        }

        .logout-btn { font-family:inherit; }

        .day-fuel-workspace.app-shell-with-sidebar {
            width:calc(100vw - 24px);
            min-height:calc(100vh - 88px);
            grid-template-columns:300px minmax(0,1fr);
            margin:12px;
            border-radius:12px;
        }

        .day-fuel-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns:64px minmax(0,1fr);
        }

        .day-fuel-page {
            width:100%;
            min-width:0;
            margin:0;
            padding:14px;
        }

        .page-title,
        .list-panel {
            border:1px solid rgba(220,227,238,.86);
            border-radius:12px;
            background:var(--panel);
            box-shadow:var(--shadow);
        }

        .page-title {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
            margin-bottom:12px;
            padding:18px;
        }

        .eyebrow {
            margin:0 0 5px;
            color:var(--primary);
            font-size:10px;
            font-weight:700;
            text-transform:uppercase;
        }

        h1 {
            margin:0;
            font-size:30px;
            line-height:1.2;
        }

        .record-count {
            flex:0 0 auto;
            padding:6px 10px;
            border-radius:999px;
            color:var(--primary-dark);
            background:rgba(15,118,110,.09);
            font-size:11px;
            font-weight:700;
        }

        .list-panel { overflow:hidden; }
        .date-toolbar {
            display:flex;
            align-items:flex-end;
            justify-content:space-between;
            gap:12px;
            padding:12px;
            border-bottom:1px solid var(--line);
            background:#fbfcfe;
        }

        .date-form {
            display:flex;
            align-items:flex-end;
            gap:10px;
            flex-wrap:wrap;
        }

        .date-field {
            display:grid;
            gap:5px;
        }

        .date-label {
            color:var(--muted);
            font-size:11px;
            font-weight:700;
        }

        .date-input {
            min-height:34px;
            padding:0 10px;
            border:1px solid var(--line);
            border-radius:8px;
            color:var(--ink);
            background:#fff;
            font:inherit;
            font-size:13px;
            outline:none;
        }

        .date-input:focus {
            border-color:rgba(15,118,110,.52);
            box-shadow:0 0 0 4px rgba(15,118,110,.13);
        }

        .filter-btn,
        .dip-entry-btn,
        .clear-btn {
            min-height:34px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0 14px;
            border-radius:8px;
            font-size:12px;
            font-weight:700;
            text-decoration:none;
            cursor:pointer;
        }

        .filter-btn,
        .dip-entry-btn {
            border:1px solid transparent;
            color:#fff;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
        }

        .dip-entry-btn {
            gap:8px;
            box-shadow:0 10px 22px rgba(15,118,110,.12);
        }

        .dip-entry-btn:hover,
        .dip-entry-btn:focus {
            background:linear-gradient(135deg,var(--primary),var(--primary-dark));
            box-shadow:0 12px 26px rgba(15,118,110,.18);
            outline:none;
        }

        .dip-entry-btn.is-hidden { display:none; }

        .clear-btn {
            border:1px solid var(--line);
            color:var(--muted);
            background:#fff;
        }

        .selected-date {
            color:var(--primary-dark);
            font-size:12px;
            font-weight:700;
        }

        .toolbar-side {
            display:inline-flex;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
            justify-content:flex-end;
        }

        .export-actions {
            display:inline-flex;
            align-items:center;
            gap:8px;
        }

        .export-btn {
            min-height:34px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0 14px;
            border:1px solid transparent;
            border-radius:8px;
            color:#fff;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
            font-size:12px;
            font-weight:700;
            text-decoration:none;
            cursor:pointer;
        }

        .form-alert {
            margin:12px;
            padding:10px 12px;
            border-radius:10px;
            font-size:13px;
            font-weight:700;
        }

        .form-alert.error {
            color:#b42318;
            background:#fff1f0;
            border:1px solid rgba(180,35,24,.22);
        }

        .form-alert.is-hiding {
            opacity:0;
            transform:translateY(-4px);
            transition:opacity .25s ease, transform .25s ease;
        }

        .previous-entry-panel {
            display:flex;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
            padding:12px;
            border-bottom:1px solid var(--line);
            background:#fff;
        }

        .previous-entry-label {
            color:var(--muted);
            font-size:12px;
            font-weight:700;
        }

        .previous-entry-message {
            padding:7px 10px;
            border:1px solid rgba(180,35,24,.22);
            border-radius:8px;
            color:#b42318;
            background:#fff1f0;
            font-size:12px;
            font-weight:700;
        }

        .nozzle-chip {
            display:inline-flex;
            align-items:center;
            min-height:28px;
            padding:0 10px;
            border-radius:999px;
            color:var(--primary-dark);
            background:rgba(15,118,110,.09);
            font-size:12px;
            font-weight:700;
        }

        .table-wrap { overflow-x:auto; }

        .entry-layout {
            display:grid;
            grid-template-columns:minmax(0,1fr);
            align-items:start;
            gap:12px;
            padding:12px;
        }

        .entry-layout.is-form-open {
            grid-template-columns:minmax(0,1fr) 320px;
        }

        .entry-form-panel {
            position:sticky;
            top:84px;
            display:none;
            border:1px solid var(--line);
            border-radius:10px;
            background:#fff;
            box-shadow:0 12px 30px rgba(23,32,51,.10);
            overflow:hidden;
        }

        .entry-form-panel.is-open { display:block; }

        .entry-form-head {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            padding:12px;
            color:#fff;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
            font-size:14px;
            font-weight:800;
        }

        .entry-form-close {
            width:28px;
            height:28px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border:1px solid rgba(255,255,255,.28);
            border-radius:8px;
            color:#fff;
            background:rgba(255,255,255,.14);
            cursor:pointer;
            font:inherit;
            font-size:20px;
            line-height:1;
        }

        .entry-form-close:hover {
            background:rgba(255,255,255,.24);
        }

        .entry-form {
            display:grid;
            gap:10px;
            padding:12px;
        }

        .entry-field {
            display:grid;
            gap:5px;
        }

        .entry-field label {
            color:var(--muted);
            font-size:11px;
            font-weight:700;
        }

        .entry-input {
            width:100%;
            min-height:34px;
            padding:0 10px;
            border:1px solid var(--line);
            border-radius:8px;
            color:var(--ink);
            background:#fff;
            font:inherit;
            font-size:13px;
            outline:none;
        }

        .entry-input:focus {
            border-color:rgba(15,118,110,.52);
            box-shadow:0 0 0 4px rgba(15,118,110,.13);
        }

        .entry-input[readonly] {
            color:var(--muted);
            background:#f4f7fb;
        }

        .entry-actions {
            display:flex;
            justify-content:flex-end;
            gap:8px;
            margin-top:2px;
        }

        .entry-save,
        .entry-cancel {
            min-height:32px;
            padding:0 12px;
            border-radius:8px;
            cursor:pointer;
            font:inherit;
            font-size:12px;
            font-weight:700;
        }

        .entry-save {
            border:0;
            color:#fff;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
        }

        .entry-cancel {
            border:1px solid var(--line);
            color:var(--muted);
            background:#fff;
        }

        table {
            width:100%;
            min-width:1080px;
            border-collapse:collapse;
        }

        th,
        td {
            padding:10px 12px;
            border-bottom:1px solid var(--line);
            font-size:13px;
            text-align:left;
            vertical-align:middle;
            white-space:nowrap;
        }

        th {
            color:#fff;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
            font-weight:800;
        }

        tbody tr[data-entry-row] {
            cursor:pointer;
            transition:background .16s ease, box-shadow .16s ease;
        }

        tbody tr[data-entry-row]:hover {
            background:color-mix(in srgb,var(--primary) 10%,#fff);
        }

        tbody tr[data-entry-row].is-selected {
            background:color-mix(in srgb,var(--primary) 16%,#fff);
            box-shadow:inset 4px 0 0 var(--primary);
        }

        .text-strong { font-weight:700; }
        .number-cell { text-align:right; }
        .empty-state {
            padding:34px 16px;
            color:var(--muted);
            font-size:14px;
            font-weight:700;
            text-align:center;
        }

        .pagination-bar {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            padding:11px 12px;
            color:var(--muted);
            font-size:12px;
        }

        .pagination-links {
            display:flex;
            align-items:center;
            justify-content:flex-end;
            gap:6px;
            flex-wrap:wrap;
        }

        .page-link,
        .page-current {
            min-width:28px;
            min-height:28px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0 8px;
            border-radius:8px;
            font-size:12px;
            font-weight:700;
            text-decoration:none;
        }

        .page-link {
            border:1px solid var(--line);
            color:var(--muted);
            background:#fff;
        }

        .page-link.muted {
            opacity:.55;
        }

        .page-current {
            color:#fff;
            background:var(--primary);
        }

        .entry-alert-backdrop {
            position:fixed;
            inset:0;
            z-index:60;
            display:none;
            align-items:center;
            justify-content:center;
            padding:18px;
            background:rgba(15,23,42,.42);
        }

        .entry-alert-backdrop.is-open { display:flex; }

        .entry-alert-dialog {
            width:min(420px,100%);
            border:1px solid rgba(220,227,238,.92);
            border-radius:12px;
            background:#fff;
            box-shadow:0 24px 70px rgba(15,23,42,.24);
            overflow:hidden;
        }

        .entry-alert-head {
            padding:14px 16px;
            color:#fff;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
            font-size:15px;
            font-weight:800;
        }

        .dip-entry-dialog {
            width:min(560px,100%);
        }

        .dip-entry-head {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }

        .dip-entry-close {
            width:32px;
            height:32px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border:1px solid rgba(255,255,255,.28);
            border-radius:8px;
            color:#fff;
            background:rgba(255,255,255,.12);
            cursor:pointer;
            font:inherit;
            font-size:20px;
            line-height:1;
        }

        .dip-entry-close:hover,
        .dip-entry-close:focus {
            background:rgba(255,255,255,.2);
            outline:none;
        }

        .dip-entry-body {
            padding:14px;
        }

        .dip-item-list {
            max-height:360px;
            display:grid;
            gap:8px;
            overflow:auto;
            padding-right:4px;
            scrollbar-width:thin;
            scrollbar-color:var(--primary) rgba(220,227,238,.72);
        }

        .dip-item-option {
            min-height:42px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            padding:0 12px;
            border:1px solid var(--line);
            border-radius:9px;
            color:var(--ink);
            background:linear-gradient(135deg,rgba(15,118,110,.06),#fff);
            cursor:pointer;
            font:inherit;
            font-size:14px;
            font-weight:800;
            text-align:left;
        }

        .dip-item-option:hover,
        .dip-item-option:focus,
        .dip-item-option.is-selected {
            border-color:rgba(15,118,110,.42);
            color:#fff;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
            outline:none;
        }

        .dip-item-pill {
            flex:0 0 auto;
            padding:4px 8px;
            border-radius:999px;
            color:var(--primary-dark);
            background:rgba(15,118,110,.1);
            font-size:10px;
            font-weight:800;
            text-transform:uppercase;
        }

        .dip-item-option:hover .dip-item-pill,
        .dip-item-option:focus .dip-item-pill,
        .dip-item-option.is-selected .dip-item-pill {
            color:var(--primary-dark);
            background:#fff;
        }

        .dip-entry-empty {
            padding:28px 16px;
            border:1px dashed rgba(15,118,110,.3);
            border-radius:10px;
            color:var(--muted);
            background:rgba(15,118,110,.05);
            font-size:14px;
            font-weight:700;
            text-align:center;
        }

        .dip-value-dialog {
            width:min(520px,100%);
        }

        .dip-value-body {
            display:grid;
            gap:16px;
            padding:18px;
        }

        .dip-value-meta {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            padding:10px 12px;
            border:1px solid rgba(15,118,110,.18);
            border-radius:10px;
            color:var(--primary-dark);
            background:rgba(15,118,110,.07);
            font-size:12px;
            font-weight:800;
        }

        .dip-value-grid {
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:12px;
        }

        .dip-value-field {
            display:grid;
            gap:7px;
        }

        .dip-value-field label {
            color:var(--muted);
            font-size:12px;
            font-weight:800;
            text-transform:uppercase;
        }

        .dip-value-input {
            min-height:40px;
            width:100%;
            padding:0 12px;
            border:1px solid var(--line);
            border-radius:9px;
            color:var(--ink);
            background:#fff;
            font:inherit;
            font-size:14px;
            font-weight:800;
            outline:none;
        }

        .dip-value-input:focus {
            border-color:rgba(15,118,110,.52);
            box-shadow:0 0 0 4px rgba(15,118,110,.13);
        }

        .dip-value-input[readonly] {
            color:var(--primary-dark);
            background:#f6fbfa;
        }

        .dip-value-message {
            min-height:18px;
            color:var(--muted);
            font-size:12px;
            font-weight:700;
        }

        .dip-value-actions {
            display:flex;
            justify-content:flex-end;
            gap:10px;
            padding-top:4px;
        }

        .dip-accept-btn {
            min-height:38px;
            padding:0 18px;
            border:0;
            border-radius:9px;
            color:#fff;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
            cursor:pointer;
            font:inherit;
            font-size:13px;
            font-weight:800;
        }

        .dip-accept-btn:hover,
        .dip-accept-btn:focus {
            background:linear-gradient(135deg,var(--primary),var(--primary-dark));
            outline:none;
        }

        .entry-alert-body {
            padding:18px 16px;
            color:var(--ink);
            font-size:14px;
            line-height:1.5;
        }

        .entry-alert-actions {
            display:flex;
            justify-content:flex-end;
            padding:0 16px 16px;
        }

        .entry-alert-close {
            min-height:34px;
            padding:0 16px;
            border:0;
            border-radius:8px;
            color:#fff;
            background:linear-gradient(135deg,var(--primary-dark),var(--primary));
            cursor:pointer;
            font:inherit;
            font-size:12px;
            font-weight:800;
        }

        @media (max-width:760px) {
            .site-header-inner {
                grid-template-columns:1fr;
                gap:8px;
                padding:10px;
            }

            .header-title,
            .header-actions {
                justify-self:center;
            }

            .page-title {
                align-items:flex-start;
                flex-direction:column;
            }

            .date-toolbar {
                align-items:flex-start;
                flex-direction:column;
            }

            .toolbar-side {
                align-items:flex-start;
                justify-content:flex-start;
            }

            .entry-layout {
                grid-template-columns:1fr;
            }

            .entry-layout.is-form-open {
                grid-template-columns:1fr;
            }

            .entry-form-panel {
                position:static;
            }
        }
    </style>
    @include('partials.theme')
</head>
<body>
    @php
        $dayFuels = $dayFuels ?? collect();
        $starterRows = $starterRows ?? collect();
        $selectedDate = $selectedDate ?? request('date', now()->toDateString());
        $previousNozzleNames = $previousNozzleNames ?? collect();
        $previousEntryMessage = $previousEntryMessage ?? null;
        $showPreviousEntryPopup = $showPreviousEntryPopup ?? false;
        $dipParameterItems = $dipParameterItems ?? collect();
        $dipParameterLookup = $dipParameterLookup ?? [];
        $latestRates = $latestRates ?? collect();
        $latestRateLookup = collect($latestRates)
            ->mapWithKeys(fn ($rate, $item) => [strtolower(preg_replace('/\s+/', ' ', trim((string) $item))) => $rate]);
        $rateForItem = fn ($item) => $latestRateLookup[strtolower(preg_replace('/\s+/', ' ', trim((string) $item)))] ?? '';
        $previousDateFormatted = $previousDateFormatted ?? \Carbon\Carbon::parse($selectedDate)->subDay()->format('d-m-Y');
        $hasSavedDayFuelEntry = $dayFuels->count() > 0 || $starterRows->contains(fn ($row) => isset($row->id));
        $totalRecords = method_exists($dayFuels, 'total') && $dayFuels->total()
            ? $dayFuels->total()
            : $starterRows->count();
    @endphp

    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>
            <div class="header-title">Day Fuel Sales</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar day-fuel-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')
        <main class="day-fuel-page">
            <section class="page-title" aria-labelledby="dayFuelListTitle">
                <div>
                    <p class="eyebrow">Daily Entry</p>
                    <h1 id="dayFuelListTitle">Day Fuel Sales</h1>
                </div>
                <span class="record-count">{{ $totalRecords }} {{ $totalRecords === 1 ? 'record' : 'records' }}</span>
            </section>

            <section class="list-panel">
                @if (session('error'))
                    <div class="form-alert error">{{ session('error') }}</div>
                @endif

                <div class="date-toolbar">
                    <form class="date-form" method="GET" action="{{ route('day-fuel.list') }}">
                        <label class="date-field">
                            <span class="date-label">Date</span>
                            <input class="date-input" type="date" name="date" value="{{ $selectedDate }}">
                        </label>
                        <a href="{{ route('day-fuel.list') }}" class="clear-btn">Clear</a>
                    </form>
                    <div class="toolbar-side">
                        <div class="export-actions">
                            <a href="#" class="dip-entry-btn {{ $hasSavedDayFuelEntry ? '' : 'is-hidden' }}" id="dipEntryBtn" aria-haspopup="dialog">Dip Entry</a>
                            <a href="{{ route('day-fuel.pdf', ['date' => $selectedDate]) }}" class="export-btn" target="_blank" rel="noopener" data-themed-export>PDF</a>
                            <a href="{{ route('day-fuel.excel', ['date' => $selectedDate]) }}" class="export-btn" data-themed-export>Excel</a>
                        </div>
                        <div class="selected-date">
                            Selected Date: {{ $selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('d M Y') : 'All' }}
                        </div>
                    </div>
                </div>

                <div class="previous-entry-panel">
                    <span class="previous-entry-label">Previous Date: {{ $previousDateFormatted }}</span>
                    @unless ($previousEntryMessage)
                        @foreach ($previousNozzleNames as $nozzleName)
                            <span class="nozzle-chip">{{ $nozzleName }}</span>
                        @endforeach
                    @endunless
                </div>

                <div class="entry-layout">
                    <div>
                        <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Nozzle Name</th>
                                    <th>Item</th>
                                    <th class="number-cell">Opening Reading</th>
                                    <th class="number-cell">Closing Reading</th>
                                    <th class="number-cell">Test</th>
                                    <th class="number-cell">Quantity</th>
                                    <th class="number-cell">Rate</th>
                                    <th class="number-cell">Amount</th>
                                </tr>
                            </thead>
                            <tbody id="dayFuelRows">
                                @if ($dayFuels->count())
                                    @foreach ($dayFuels as $dayFuel)
                                        <tr
                                            data-entry-row
                                            data-nozzle-id="{{ $dayFuel->nozzle_id ?? $dayFuel->Nozzel_id }}"
                                            data-nozzle-name="{{ optional($dayFuel->Nozzle)->Nozzle_Name ?? '-' }}"
                                            data-item="{{ $dayFuel->items ?? optional($dayFuel->Nozzle)->Item ?? '-' }}"
                                            data-opening="{{ $dayFuel->open ?? '0.00' }}"
                                            data-latest-rate="{{ $rateForItem($dayFuel->items ?? optional($dayFuel->Nozzle)->Item ?? '') }}"
                                            data-has-entry="1"
                                        >
                                        <td>{{ method_exists($dayFuels, 'firstItem') ? $dayFuels->firstItem() + $loop->index : $loop->iteration }}</td>
                                        <td class="text-strong">{{ optional($dayFuel->Nozzle)->Nozzle_Name ?? '-' }}</td>
                                        <td>{{ $dayFuel->items ?? optional($dayFuel->Nozzle)->Item ?? '-' }}</td>
                                        <td class="number-cell" data-cell="opening">{{ $dayFuel->open ?? '-' }}</td>
                                        <td class="number-cell" data-cell="closing">{{ $dayFuel->close ?? '-' }}</td>
                                        <td class="number-cell" data-cell="test">{{ $dayFuel->Test ?? '-' }}</td>
                                        <td class="number-cell" data-cell="quantity">{{ $dayFuel->Quantity ?? '-' }}</td>
                                        <td class="number-cell" data-cell="rate">{{ is_numeric($dayFuel->rate) ? number_format($dayFuel->rate, 2) : '-' }}</td>
                                        <td class="number-cell" data-cell="amount">{{ is_numeric($dayFuel->Amount) ? number_format($dayFuel->Amount, 2) : '-' }}</td>
                                    </tr>
                                    @endforeach
                                @elseif ($starterRows->count())
                                    @foreach ($starterRows as $starterRow)
                                        <tr
                                            data-entry-row
                                            data-nozzle-id="{{ $starterRow->nozzle_id ?? $starterRow->Nozzel_id }}"
                                            data-nozzle-name="{{ optional($starterRow->Nozzle)->Nozzle_Name ?? '-' }}"
                                            data-item="{{ $starterRow->items ?? optional($starterRow->Nozzle)->Item ?? '-' }}"
                                            data-opening="{{ $starterRow->open ?? '0.00' }}"
                                            data-latest-rate="{{ $rateForItem($starterRow->items ?? optional($starterRow->Nozzle)->Item ?? '') }}"
                                            data-has-entry="{{ isset($starterRow->id) ? '1' : '0' }}"
                                        >
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-strong">{{ optional($starterRow->Nozzle)->Nozzle_Name ?? '-' }}</td>
                                        <td>{{ $starterRow->items ?? optional($starterRow->Nozzle)->Item ?? '-' }}</td>
                                        <td class="number-cell" data-cell="opening">{{ is_numeric($starterRow->open) ? number_format($starterRow->open, 2) : '0.00' }}</td>
                                        <td class="number-cell" data-cell="closing">{{ is_numeric($starterRow->close) ? number_format($starterRow->close, 2) : '-' }}</td>
                                        <td class="number-cell" data-cell="test">{{ is_numeric($starterRow->Test) ? number_format($starterRow->Test, 2) : '-' }}</td>
                                        <td class="number-cell" data-cell="quantity">{{ is_numeric($starterRow->Quantity) ? number_format($starterRow->Quantity, 2) : '-' }}</td>
                                        <td class="number-cell" data-cell="rate">{{ is_numeric($starterRow->rate) ? number_format($starterRow->rate, 2) : '-' }}</td>
                                        <td class="number-cell" data-cell="amount">{{ is_numeric($starterRow->Amount) ? number_format($starterRow->Amount, 2) : '-' }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" class="empty-state">No day fuel records found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        </div>
                    </div>

                    <aside class="entry-form-panel" id="entryFormPanel" aria-live="polite">
                        <div class="entry-form-head">
                            <span>Day Fuel Entry</span>
                            <button type="button" class="entry-form-close" id="entryFormClose" aria-label="Close day fuel entry form">&times;</button>
                        </div>
                        <form class="entry-form" id="entryForm">
                            <div class="entry-field">
                                <label for="entryNozzleName">Nozzle Name</label>
                                <input class="entry-input" id="entryNozzleName" type="text" readonly>
                            </div>
                            <div class="entry-field">
                                <label for="entryItem">Item</label>
                                <input class="entry-input" id="entryItem" type="text" readonly>
                            </div>
                            <div class="entry-field">
                                <label for="entryOpening">Opening Reading</label>
                                <input class="entry-input" id="entryOpening" type="number" step="0.01" value="0.00" readonly>
                            </div>
                            <div class="entry-field">
                                <label for="entryClosing">Closing Reading</label>
                                <input class="entry-input" id="entryClosing" type="number" step="0.01" min="0">
                            </div>
                            <div class="entry-field">
                                <label for="entryQuantity">Quantity</label>
                                <input class="entry-input" id="entryQuantity" type="number" step="0.01" value="0.00" readonly>
                            </div>
                            <div class="entry-field">
                                <label for="entryTest">Test</label>
                                <input class="entry-input" id="entryTest" type="number" step="0.01" min="0">
                            </div>
                            <div class="entry-field">
                                <label for="entryRate">Rate</label>
                                <input class="entry-input" id="entryRate" type="number" step="0.01" min="0">
                            </div>
                            <div class="entry-field">
                                <label for="entryAmount">Amount</label>
                                <input class="entry-input" id="entryAmount" type="number" step="0.01" value="0.00" readonly>
                            </div>
                            <div class="entry-actions">
                                <button type="button" class="entry-cancel" id="entryCancel">Cancel</button>
                                <button type="submit" class="entry-save">Save</button>
                            </div>
                        </form>
                    </aside>
                </div>

                <div class="pagination-bar">
                    <div>
                        @if ($totalRecords)
                            @if (method_exists($dayFuels, 'firstItem') && $dayFuels->total())
                                Showing {{ $dayFuels->firstItem() }} to {{ $dayFuels->lastItem() }} of {{ $dayFuels->total() }}
                            @else
                                Showing {{ $starterRows->count() }} {{ $starterRows->count() === 1 ? 'record' : 'records' }}
                            @endif
                        @else
                            Showing 0 records
                        @endif
                    </div>

                    @if (method_exists($dayFuels, 'links'))
                        @include('partials.compact-pagination', ['paginator' => $dayFuels])
                    @endif
                </div>
            </section>
        </main>
    </div>

    @if ($previousEntryMessage)
        <div class="entry-alert-backdrop" id="previousEntryAlert" role="dialog" aria-modal="true" aria-labelledby="previousEntryAlertTitle">
            <div class="entry-alert-dialog">
                <div class="entry-alert-head" id="previousEntryAlertTitle">Previous Day Entry Missing</div>
                <div class="entry-alert-body">{{ $previousEntryMessage }}</div>
                <div class="entry-alert-actions">
                    <button type="button" class="entry-alert-close" id="previousEntryAlertClose">OK</button>
                </div>
            </div>
        </div>
    @endif

    <div class="entry-alert-backdrop" id="dipEntryModal" role="dialog" aria-modal="true" aria-labelledby="dipEntryTitle" aria-hidden="true">
        <div class="entry-alert-dialog dip-entry-dialog">
            <div class="entry-alert-head dip-entry-head">
                <span id="dipEntryTitle">Dip Entry Items</span>
                <button type="button" class="dip-entry-close" id="dipEntryClose" aria-label="Close dip entry items">&times;</button>
            </div>
            <div class="dip-entry-body">
                @if ($dipParameterItems->count())
                    <div class="dip-item-list">
                        @foreach ($dipParameterItems as $dipItem)
                            <button type="button" class="dip-item-option" data-dip-item="{{ $dipItem }}">
                                <span>{{ $dipItem }}</span>
                                <span class="dip-item-pill">Item</span>
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="dip-entry-empty">No dip parameter items found.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="entry-alert-backdrop" id="dipValueModal" role="dialog" aria-modal="true" aria-labelledby="dipValueTitle" aria-hidden="true">
        <div class="entry-alert-dialog dip-value-dialog">
            <div class="entry-alert-head dip-entry-head">
                <span id="dipValueTitle">Enter Depth</span>
                <button type="button" class="dip-entry-close" id="dipValueClose" aria-label="Close dip entry">&times;</button>
            </div>
            <div class="dip-value-body">
                <div class="dip-value-meta">
                    <span>Date: {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</span>
                    <span id="dipValueItem">Item</span>
                </div>
                <div class="dip-value-grid">
                    <div class="dip-value-field">
                        <label for="dipDepthInput">Enter Depth</label>
                        <input class="dip-value-input" id="dipDepthInput" type="number" step="0.01" min="0" placeholder="0.00">
                    </div>
                    <div class="dip-value-field">
                        <label for="dipLiterInput">Liter</label>
                        <input class="dip-value-input" id="dipLiterInput" type="text" value="0" readonly>
                    </div>
                </div>
                <div class="dip-value-message" id="dipValueMessage"></div>
                <div class="dip-value-actions">
                    <button type="button" class="clear-btn" id="dipValueBack">Back</button>
                    <button type="button" class="dip-accept-btn" id="dipValueAccept">Accept</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const rows = Array.from(document.querySelectorAll('[data-entry-row]'));
        const dateInput = document.querySelector('.date-input');
        const entryLayout = document.querySelector('.entry-layout');
        const formPanel = document.getElementById('entryFormPanel');
        const entryForm = document.getElementById('entryForm');
        const entryCancel = document.getElementById('entryCancel');
        const entryFormClose = document.getElementById('entryFormClose');
        const entrySave = entryForm.querySelector('.entry-save');
        const dipEntryBtn = document.getElementById('dipEntryBtn');
        const nozzleInput = document.getElementById('entryNozzleName');
        const itemInput = document.getElementById('entryItem');
        const openingInput = document.getElementById('entryOpening');
        const closingInput = document.getElementById('entryClosing');
        const quantityInput = document.getElementById('entryQuantity');
        const testInput = document.getElementById('entryTest');
        const rateInput = document.getElementById('entryRate');
        const amountInput = document.getElementById('entryAmount');
        const selectedDate = @json($selectedDate);
        const dipParameterLookup = @json($dipParameterLookup);
        const dailyDipLookup = @json($dailyDipLookup);
        const storeUrl = @json(route('day-fuel.store'));
        const dailyDipStoreUrl = @json(route('day-fuel.dip-entry.store'));
        const defaultListUrl = @json(route('day-fuel.list'));
        const hasDateFilter = @json(request()->filled('date'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const previousEntryAlert = document.getElementById('previousEntryAlert');
        const previousEntryAlertClose = document.getElementById('previousEntryAlertClose');
        const dipEntryModal = document.getElementById('dipEntryModal');
        const dipEntryClose = document.getElementById('dipEntryClose');
        const dipValueModal = document.getElementById('dipValueModal');
        const dipValueClose = document.getElementById('dipValueClose');
        const dipValueBack = document.getElementById('dipValueBack');
        const dipValueAccept = document.getElementById('dipValueAccept');
        const dipValueItem = document.getElementById('dipValueItem');
        const dipDepthInput = document.getElementById('dipDepthInput');
        const dipLiterInput = document.getElementById('dipLiterInput');
        const dipValueMessage = document.getElementById('dipValueMessage');
        let selectedRow = null;
        let selectedIndex = -1;
        let selectedDipItem = '';

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

        document.querySelectorAll('.form-alert').forEach((alert) => {
            setTimeout(() => {
                alert.classList.add('is-hiding');
                setTimeout(() => alert.remove(), 250);
            }, 4000);
        });

        const datePickerPopupKey = 'dayFuelPickedDateForMissingPreviousEntry';

        dateInput?.addEventListener('change', () => {
            if (dateInput.value) {
                sessionStorage.setItem(datePickerPopupKey, dateInput.value);
            }

            dateInput.form?.submit();
        });

        if (previousEntryAlert) {
            const pickedDate = sessionStorage.getItem(datePickerPopupKey);

            if (pickedDate === selectedDate) {
                previousEntryAlert.classList.add('is-open');
            }

            sessionStorage.removeItem(datePickerPopupKey);
        }

        const browserToday = () => {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        };

        const refreshWhenDefaultDateChanges = () => {
            if (!hasDateFilter && selectedDate !== browserToday()) {
                window.location.href = defaultListUrl;
            }
        };

        refreshWhenDefaultDateChanges();
        window.setInterval(refreshWhenDefaultDateChanges, 60000);

        const toNumber = (value) => {
            const number = Number.parseFloat(value);
            return Number.isFinite(number) ? number : 0;
        };

        const money = (value) => toNumber(value).toFixed(2);
        const wholeNumber = (value) => {
            const number = Number.parseFloat(value);

            return Number.isFinite(number) ? String(Math.trunc(number)) : '0';
        };

        const calculateEntry = () => {
            const opening = toNumber(openingInput.value);
            const closing = toNumber(closingInput.value);
            const test = toNumber(testInput.value);
            const rate = toNumber(rateInput.value);
            const quantity = Math.max(closing - opening - test, 0);

            quantityInput.value = money(quantity);
            amountInput.value = money(quantity * rate);
        };

        const openEntryForm = (row, index) => {
            rows.forEach((item) => item.classList.remove('is-selected'));
            row.classList.add('is-selected');

            selectedRow = row;
            selectedIndex = index;

            nozzleInput.value = row.dataset.nozzleName || '';
            itemInput.value = row.dataset.item || '';
            openingInput.value = money(row.dataset.opening || row.querySelector('[data-cell="opening"]')?.textContent || 0);
            const hasSavedEntry = row.dataset.hasEntry === '1';

            closingInput.value = hasSavedEntry && row.querySelector('[data-cell="closing"]')?.textContent?.trim() !== '-'
                ? money(row.querySelector('[data-cell="closing"]').textContent)
                : '';
            testInput.value = hasSavedEntry && row.querySelector('[data-cell="test"]')?.textContent?.trim() !== '-'
                ? money(row.querySelector('[data-cell="test"]').textContent)
                : '';
            rateInput.value = hasSavedEntry && row.querySelector('[data-cell="rate"]')?.textContent?.trim() !== '-'
                ? money(row.querySelector('[data-cell="rate"]').textContent)
                : money(row.dataset.latestRate || row.querySelector('[data-cell="rate"]')?.textContent || 0);
            quantityInput.value = hasSavedEntry && row.querySelector('[data-cell="quantity"]')?.textContent?.trim() !== '-'
                ? money(row.querySelector('[data-cell="quantity"]').textContent)
                : '0.00';
            amountInput.value = hasSavedEntry && row.querySelector('[data-cell="amount"]')?.textContent?.trim() !== '-'
                ? money(row.querySelector('[data-cell="amount"]').textContent)
                : '0.00';

            formPanel.classList.add('is-open');
            entryLayout?.classList.add('is-form-open');
            closingInput.focus();
        };

        const closeEntryForm = () => {
            formPanel.classList.remove('is-open');
            entryLayout?.classList.remove('is-form-open');
            selectedRow = null;
            selectedIndex = -1;
            rows.forEach((item) => item.classList.remove('is-selected'));
        };

        const setRowValues = (values) => {
            if (!selectedRow) {
                return;
            }

            selectedRow.querySelector('[data-cell="opening"]').textContent = values.opening;
            selectedRow.querySelector('[data-cell="closing"]').textContent = values.closing;
            selectedRow.querySelector('[data-cell="test"]').textContent = values.test;
            selectedRow.querySelector('[data-cell="quantity"]').textContent = values.quantity;
            selectedRow.querySelector('[data-cell="rate"]').textContent = values.rate;
            selectedRow.querySelector('[data-cell="amount"]').textContent = values.amount;
            selectedRow.dataset.opening = values.opening;
            selectedRow.dataset.hasEntry = '1';
        };

        const saveEntry = async () => {
            if (!selectedRow) {
                return;
            }

            calculateEntry();
            entrySave.disabled = true;
            entrySave.textContent = 'Saving';

            try {
                const response = await fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        date: selectedDate,
                        nozzle_id: selectedRow.dataset.nozzleId,
                        open: openingInput.value,
                        close: closingInput.value,
                        Test: testInput.value || 0,
                        rate: rateInput.value || 0,
                    }),
                });

                const savedEntry = await response.json();

                if (!response.ok) {
                    const firstError = savedEntry.errors
                        ? Object.values(savedEntry.errors).flat()[0]
                        : null;

                    throw new Error(firstError || savedEntry.message || 'Entry could not be saved.');
                }

                setRowValues(savedEntry);
                selectedRow.dataset.item = savedEntry.item || selectedRow.dataset.item || '';
                itemInput.value = selectedRow.dataset.item;
                dipEntryBtn?.classList.remove('is-hidden');
                closeEntryForm();
            } catch (error) {
                alert(error.message || 'Entry could not be saved.');
            } finally {
                entrySave.disabled = false;
                entrySave.textContent = 'Save';
            }
        };

        rows.forEach((row, index) => {
            row.addEventListener('click', () => openEntryForm(row, index));
        });

        [closingInput, testInput, rateInput].forEach((input) => {
            input.addEventListener('input', calculateEntry);
        });

        entryForm.addEventListener('submit', (event) => {
            event.preventDefault();
            saveEntry();
        });

        entryCancel.addEventListener('click', closeEntryForm);
        entryFormClose.addEventListener('click', closeEntryForm);

        previousEntryAlertClose?.addEventListener('click', () => {
            previousEntryAlert?.classList.remove('is-open');
        });

        previousEntryAlert?.addEventListener('click', (event) => {
            if (event.target === previousEntryAlert) {
                previousEntryAlert.classList.remove('is-open');
            }
        });

        const openDipEntryModal = () => {
            dipEntryModal?.classList.add('is-open');
            dipEntryModal?.setAttribute('aria-hidden', 'false');
            dipEntryClose?.focus();
        };

        const closeDipEntryModal = () => {
            dipEntryModal?.classList.remove('is-open');
            dipEntryModal?.setAttribute('aria-hidden', 'true');
        };

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

        const savedDailyDipForItem = (item) => {
            const selected = String(item || '').trim();

            if (!selected) {
                return null;
            }

            if (dailyDipLookup?.[selected]) {
                return dailyDipLookup[selected];
            }

            const normalizedSelected = selected.toLowerCase();
            const matchedKey = Object.keys(dailyDipLookup || {}).find((key) => key.trim().toLowerCase() === normalizedSelected);

            return matchedKey ? dailyDipLookup[matchedKey] : null;
        };

        const updateDipLiter = () => {
            const depthKey = dipLookupKey(dipDepthInput?.value);
            const itemRows = dipItemLookup(selectedDipItem);
            let match = depthKey ? itemRows?.[depthKey] : null;
            let interpolated = false;

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
                    match = wholeNumber((Number.parseFloat(lowerLiter) + Number.parseFloat(upperLiter)) / 2);
                    interpolated = true;
                }
            }

            if (dipLiterInput) {
                dipLiterInput.value = match ? wholeNumber(match) : '0';
            }

            if (dipValueMessage) {
                dipValueMessage.textContent = match
                    ? (interpolated ? 'Liter calculated from nearest depth values.' : '')
                    : (depthKey ? 'No liter value found for this depth.' : 'Enter depth to fetch liter value.');
            }
        };

        const openDipValueModal = (item) => {
            selectedDipItem = item || '';
            closeDipEntryModal();

            if (dipValueItem) {
                dipValueItem.textContent = selectedDipItem || 'Item';
            }

            if (dipDepthInput) {
                dipDepthInput.value = savedDailyDipForItem(selectedDipItem)?.enter_depth || '';
            }

            if (dipLiterInput) {
                dipLiterInput.value = savedDailyDipForItem(selectedDipItem)?.liter || '0';
            }

            if (dipValueMessage) {
                dipValueMessage.textContent = savedDailyDipForItem(selectedDipItem)
                    ? 'Saved dip entry loaded.'
                    : 'Enter depth to fetch liter value.';
            }

            dipValueModal?.classList.add('is-open');
            dipValueModal?.setAttribute('aria-hidden', 'false');
            dipDepthInput?.focus();
        };

        const closeDipValueModal = () => {
            dipValueModal?.classList.remove('is-open');
            dipValueModal?.setAttribute('aria-hidden', 'true');
        };

        const saveDailyDip = async () => {
            const enterDip = dipDepthInput?.value || '';
            const liter = wholeNumber(dipLiterInput?.value || '0');

            if (!selectedDipItem || !enterDip) {
                if (dipValueMessage) {
                    dipValueMessage.textContent = 'Please enter depth value.';
                }

                return;
            }

            if (dipValueAccept) {
                dipValueAccept.disabled = true;
                dipValueAccept.textContent = 'Saving';
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
                        date: selectedDate,
                        item: selectedDipItem,
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

                if (dipValueMessage) {
                    dipValueMessage.textContent = savedDip.message || 'Dip entry saved successfully.';
                }

                dailyDipLookup[selectedDipItem] = {
                    enter_depth: savedDip.enter_depth,
                    liter: savedDip.liter,
                };

                setTimeout(closeDipValueModal, 500);
            } catch (error) {
                if (dipValueMessage) {
                    dipValueMessage.textContent = error.message || 'Dip entry could not be saved.';
                }
            } finally {
                if (dipValueAccept) {
                    dipValueAccept.disabled = false;
                    dipValueAccept.textContent = 'Accept';
                }
            }
        };

        dipEntryBtn?.addEventListener('click', (event) => {
            event.preventDefault();
            openDipEntryModal();
        });

        dipEntryClose?.addEventListener('click', closeDipEntryModal);

        dipEntryModal?.addEventListener('click', (event) => {
            if (event.target === dipEntryModal) {
                closeDipEntryModal();
            }
        });

        document.querySelectorAll('[data-dip-item]').forEach((button) => {
            button.addEventListener('click', () => {
                document.querySelectorAll('[data-dip-item]').forEach((item) => item.classList.remove('is-selected'));
                button.classList.add('is-selected');
                openDipValueModal(button.dataset.dipItem || button.textContent.trim());
            });
        });

        dipDepthInput?.addEventListener('input', updateDipLiter);
        dipValueClose?.addEventListener('click', closeDipValueModal);
        dipValueAccept?.addEventListener('click', saveDailyDip);

        dipValueBack?.addEventListener('click', () => {
            closeDipValueModal();
            openDipEntryModal();
        });

        dipValueModal?.addEventListener('click', (event) => {
            if (event.target === dipValueModal) {
                closeDipValueModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && dipValueModal?.classList.contains('is-open')) {
                closeDipValueModal();
                return;
            }

            if (event.key === 'Escape' && dipEntryModal?.classList.contains('is-open')) {
                closeDipEntryModal();
                return;
            }

            if (event.key === 'Escape' && previousEntryAlert?.classList.contains('is-open')) {
                previousEntryAlert.classList.remove('is-open');
                return;
            }

            if (!rows.length) {
                return;
            }

            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                const direction = event.key === 'ArrowDown' ? 1 : -1;
                const nextIndex = selectedIndex < 0
                    ? 0
                    : Math.min(Math.max(selectedIndex + direction, 0), rows.length - 1);

                openEntryForm(rows[nextIndex], nextIndex);
            }

            if (event.key === 'Escape' && formPanel.classList.contains('is-open')) {
                closeEntryForm();
            }
        });
    </script>
</body>
</html>
