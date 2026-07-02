<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>List Of Category | FuelTracker</title>
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
            font-family: Arial, Helvetica, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(15, 118, 110, 0.16), transparent 32rem),
                linear-gradient(135deg, #f8fbff 0%, var(--bg) 55%, #eef5f3 100%);
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 20;
            width: 100%;
            background:
                linear-gradient(135deg, rgba(8, 47, 73, 0.98), rgba(15, 118, 110, 0.98)),
                url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.12' stroke-width='2'%3E%3Cpath d='M22 116c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 78c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 40c20-18 40-18 60 0s40 18 60 0'/%3E%3C/g%3E%3C/svg%3E");
            box-shadow: 0 10px 30px rgba(23, 32, 51, 0.12);
        }

        .site-header-inner {
            width: 100%;
            min-height: 64px;
            display: grid;
            grid-template-columns: minmax(220px, 1fr) auto minmax(220px, 1fr);
            align-items: center;
            gap: 18px;
            margin: 0 auto;
            padding: 0 8px;
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
            color: var(--primary);
            background: #ffffff;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.18);
        }

        .site-logo-icon.has-brand-image {
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
        .logout-btn,
        .primary-btn {
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
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .back-link:hover,
        .logout-btn:hover,
        .primary-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .logout-btn {
            font-family: inherit;
        }

        .category-list-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 88px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
        }

        .category-list-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
        }

        .category-list-page {
            min-width: 0;
            padding: 14px;
        }

        .list-shell {
            display: grid;
            gap: 12px;
        }

        .page-title,
        .list-panel {
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

        .list-panel {
            overflow: hidden;
        }

        .form-alert {
            margin: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            font-size: 14px;
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

        .form-alert.is-hiding {
            opacity: 0;
            transform: translateY(-4px);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
        }

        .toolbar-actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .search-form {
            width: min(100%, 650px);
            display: grid;
            grid-template-columns: minmax(160px, 1fr) 74px 66px 116px;
            align-items: center;
            gap: 8px;
        }

        .search-input {
            width: 100%;
            min-height: 31px;
            padding: 0 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #fbfcfe;
            font: inherit;
            font-size: 11px;
            outline: none;
        }

        .search-input:focus {
            border-color: rgba(15, 118, 110, 0.52);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
        }

        .search-btn,
        .reset-btn,
        .new-btn {
            min-height: 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .search-btn,
        .new-btn {
            border: 1px solid transparent;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .reset-btn {
            border: 1px solid var(--line);
            color: var(--muted);
            background: #ffffff;
        }

        .entries-dropdown {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .entries-toggle {
            min-height: 31px;
            min-width: 104px;
            padding: 0 34px 0 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #ffffff;
            cursor: pointer;
            font: inherit;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
        }

        .entries-dropdown::after {
            content: "";
            position: absolute;
            right: 12px;
            top: 50%;
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 5px solid var(--muted);
            transform: translateY(-40%);
            pointer-events: none;
        }

        .entries-menu {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            z-index: 10;
            display: none;
            min-width: 136px;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(23, 32, 51, 0.16);
        }

        .entries-dropdown.is-open .entries-menu {
            display: grid;
        }

        .entries-option {
            min-height: 36px;
            padding: 0 12px;
            border: 0;
            color: var(--ink);
            background: #ffffff;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            text-align: left;
        }

        .entries-option:hover,
        .entries-option:focus,
        .entries-option.is-selected:hover,
        .entries-option.is-selected:focus {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            outline: none;
        }

        .entries-option.is-selected {
            color: var(--ink);
            background: #ffffff;
            font-weight: 700;
        }

        .entries-toggle:focus {
            border-color: rgba(15, 118, 110, 0.52);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
            outline: none;
        }

        .entries-toggle:hover {
            border-color: rgba(15, 118, 110, 0.42);
            background:
                linear-gradient(135deg, rgba(15, 118, 110, 0.12), rgba(20, 184, 166, 0.07)),
                #ffffff;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 620px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
            font-size: 13px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            font-size: 13px;
            font-weight: 800;
        }

        .sort-link {
            display: inline-flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 6px;
            color: #ffffff;
            text-decoration: none;
            white-space: nowrap;
        }

        .sort-mark {
            position: relative;
            width: 10px;
            height: 14px;
            flex: 0 0 10px;
            opacity: 0.72;
        }

        .sort-mark::before,
        .sort-mark::after {
            content: "";
            position: absolute;
            left: 50%;
            width: 0;
            height: 0;
            border-left: 3px solid transparent;
            border-right: 3px solid transparent;
            transform: translateX(-50%);
        }

        .sort-mark::before {
            top: 2px;
            border-bottom: 4px solid rgba(255, 255, 255, 0.58);
        }

        .sort-mark::after {
            bottom: 2px;
            border-top: 4px solid rgba(255, 255, 255, 0.58);
        }

        .sort-link.is-active .sort-mark {
            opacity: 1;
        }

        .sort-link.is-active .sort-mark.asc::before {
            border-bottom-color: #ffffff;
        }

        .sort-link.is-active .sort-mark.desc::after {
            border-top-color: #ffffff;
        }

        td {
            color: #172033;
            font-weight: 500;
        }

        tbody tr:hover {
            background: rgba(15, 118, 110, 0.045);
        }

        .text-strong {
            color: var(--ink);
            font-weight: 700;
        }

        .actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .action-btn {
            min-height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .edit-btn {
            border: 1px solid rgba(15, 118, 110, 0.2);
            color: var(--primary-dark);
            background: rgba(15, 118, 110, 0.08);
        }

        .delete-btn {
            border: 1px solid rgba(180, 35, 24, 0.2);
            color: var(--danger);
            background: #fff1f0;
        }

        .delete-form {
            margin: 0;
        }

        .delete-modal {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: rgba(15, 23, 42, 0.48);
            backdrop-filter: blur(6px);
        }

        .delete-modal.is-open {
            display: flex;
        }

        .delete-dialog {
            width: min(100%, 420px);
            border: 1px solid rgba(220, 227, 238, 0.92);
            border-radius: 18px;
            background: var(--panel);
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.28);
            overflow: hidden;
            transform: translateY(8px) scale(0.98);
            animation: modalPop 0.18s ease forwards;
        }

        @keyframes modalPop {
            to {
                transform: translateY(0) scale(1);
            }
        }

        .delete-dialog-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 18px 12px;
        }

        .delete-dialog-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: var(--danger);
            background: #fff1f0;
            font-size: 22px;
            font-weight: 800;
        }

        .delete-dialog-title {
            margin: 0;
            color: var(--ink);
            font-size: 20px;
            line-height: 1.2;
        }

        .delete-dialog-body {
            padding: 0 18px 18px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.55;
        }

        .delete-dialog-body strong {
            color: var(--ink);
        }

        .delete-dialog-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 14px 18px 18px;
            border-top: 1px solid var(--line);
            background: #fbfcfe;
        }

        .modal-no-btn,
        .modal-yes-btn {
            min-height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            border-radius: 12px;
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            font-weight: 800;
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .modal-no-btn {
            border: 1px solid var(--line);
            color: var(--ink);
            background: #ffffff;
        }

        .modal-no-btn:hover,
        .modal-no-btn:focus {
            border-color: rgba(15, 118, 110, 0.28);
            color: var(--primary-dark);
            background: rgba(15, 118, 110, 0.08);
            outline: none;
        }

        .modal-yes-btn {
            border: 0;
            color: #ffffff;
            background: var(--danger);
            box-shadow: 0 12px 28px rgba(180, 35, 24, 0.2);
        }

        .modal-yes-btn:hover,
        .modal-yes-btn:focus {
            background: #912018;
            transform: translateY(-1px);
            outline: none;
        }

        .empty-state,
        .pagination-bar {
            padding: 16px 18px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        .pagination-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .pagination-links {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .page-link,
        .page-current {
            min-width: 28px;
            min-height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 8px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .page-link {
            border: 1px solid var(--line);
            color: var(--primary-dark);
            background: #ffffff;
        }

        .page-link.muted {
            color: var(--muted);
            background: #f6f8fb;
        }

        .page-current {
            color: #ffffff;
            background: var(--primary);
        }

        @media (max-width: 720px) {
            .site-header-inner {
                min-height: 54px;
                grid-template-columns: 1fr auto;
                gap: 10px;
                padding: 0 10px;
            }

            .site-logo {
                font-size: 18px;
            }

            .header-title {
                display: none;
            }

            .header-actions {
                gap: 6px;
            }

            .back-link,
            .logout-btn {
                min-height: 32px;
                padding: 0 7px;
                font-size: 12px;
            }

            .category-list-workspace.app-shell-with-sidebar {
                width: 100%;
                min-height: calc(100vh - 54px);
                display: block;
                margin: 0;
                border-radius: 0;
            }

            .category-list-page {
                padding: 12px;
            }

            .page-title,
            .toolbar,
            .pagination-bar {
                align-items: stretch;
                flex-direction: column;
            }

            h1 {
                font-size: 22px;
            }

            .search-form {
                width: 100%;
                grid-template-columns: 1fr;
            }

            .toolbar-actions {
                width: 100%;
                display: grid;
                grid-template-columns: 1fr;
            }

            .entries-dropdown,
            .entries-toggle {
                width: 100%;
            }

            .delete-dialog-actions {
                flex-direction: column-reverse;
            }

            .modal-no-btn,
            .modal-yes-btn {
                width: 100%;
            }
        }
    </style>
    @include('partials.theme')
</head>
<body>
    @php
        $columns = [
            'id' => 'ID',
            'Category_Name' => 'Category Name',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
        ];

        $sortUrl = function (string $column) use ($sort, $direction, $search, $perPage) {
            return route('category.list', [
                'search' => $search,
                'sort' => $column,
                'direction' => $sort === $column && $direction === 'asc' ? 'desc' : 'asc',
                'per_page' => $perPage,
            ]);
        };

        $sortMark = function (string $column) use ($sort, $direction) {
            if ($sort !== $column) {
                return '';
            }

            return $direction === 'asc' ? 'asc' : 'desc';
        };
    @endphp

    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon has-brand-image" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>

            <div class="header-title">List Of Category</div>

            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar category-list-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="category-list-page">
            <div class="list-shell">
                <section class="page-title" aria-labelledby="categoryListTitle">
                    <div>
                        <p class="eyebrow">Masters</p>
                        <h1 id="categoryListTitle">List Of Category</h1>
                    </div>
                    <span class="record-count">{{ $categories->total() }} {{ $categories->total() === 1 ? 'record' : 'records' }}</span>
                </section>

                <section class="list-panel">
                    @if (session('success'))
                        <div class="form-alert success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="form-alert error">{{ session('error') }}</div>
                    @endif

                    <div class="toolbar">
                        <form class="search-form" method="GET" action="{{ route('category.list') }}">
                            <input type="hidden" name="sort" value="{{ $sort }}">
                            <input type="hidden" name="direction" value="{{ $direction }}">
                            <input class="search-input" type="search" name="search" value="{{ $search }}" placeholder="Search category">
                            <button type="submit" class="search-btn">Search</button>
                            <a href="{{ route('category.list') }}" class="reset-btn">Clear</a>
                            <div class="entries-dropdown">
                                <input type="hidden" name="per_page" value="{{ $perPage }}">
                                <button class="entries-toggle" type="button" aria-haspopup="listbox" aria-expanded="false">
                                    {{ $perPage }} Entries
                                </button>
                                <div class="entries-menu" role="listbox">
                                    @foreach ($perPageOptions as $option)
                                        <button
                                            class="entries-option {{ $perPage === $option ? 'is-selected' : '' }}"
                                            type="button"
                                            role="option"
                                            aria-selected="{{ $perPage === $option ? 'true' : 'false' }}"
                                            data-per-page="{{ $option }}"
                                        >
                                            {{ $option }} Entries
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                        <div class="toolbar-actions">
                            @if ($categories->count())
                                <a href="{{ route('category.pdf') }}" class="new-btn" target="_blank" rel="noopener" data-themed-export>PDF</a>
                                <a href="{{ route('category.excel') }}" class="new-btn" data-themed-export>Excel</a>
                            @endif
                            <a href="{{ route('category') }}" class="new-btn">New Category</a>
                        </div>
                    </div>

                    @if ($categories->count())
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        @foreach ($columns as $column => $label)
                                            <th>
                                                <a class="sort-link {{ $sort === $column ? 'is-active' : '' }}" href="{{ $sortUrl($column) }}">
                                                    <span>{{ $label }}</span>
                                                    <span class="sort-mark {{ $sortMark($column) }}" aria-hidden="true"></span>
                                                </a>
                                            </th>
                                        @endforeach
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td class="text-strong">{{ $category->Category_Name }}</td>
                                            <td>{{ optional($category->created_at)->format('d M Y') ?: '-' }}</td>
                                            <td>{{ optional($category->updated_at)->format('d M Y') ?: '-' }}</td>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('category.edit', $category->id) }}" class="action-btn edit-btn">Edit</a>
                                                    <form class="delete-form" method="POST" action="{{ route('category.destroy', $category->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-btn delete-btn" data-delete-category="{{ $category->Category_Name }}">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">No categories found{{ $search ? ' for "' . $search . '"' : '' }}.</div>
                    @endif

                    <div class="pagination-bar">
                        <div>
                            @if ($categories->total())
                                Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }}
                            @else
                                Showing 0 records
                            @endif
                        </div>

                        @include('partials.compact-pagination', ['paginator' => $categories])
                    </div>
                </section>
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
                    Are you sure you want to delete <strong id="deleteCategoryName">this category</strong>? This action cannot be undone.
                </p>
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
        const deleteCategoryName = document.getElementById('deleteCategoryName');
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
                const button = form.querySelector('[data-delete-category]');

                if (form.dataset.confirmed === 'true') {
                    return;
                }

                event.preventDefault();
                pendingDeleteForm = form;
                deleteCategoryName.textContent = button?.dataset.deleteCategory || 'this category';
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

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && deleteModal.classList.contains('is-open')) {
                closeDeleteModal();
            }
        });

        document.querySelectorAll('.form-alert').forEach((alert) => {
            setTimeout(() => {
                alert.classList.add('is-hiding');
                setTimeout(() => alert.remove(), 250);
            }, 4000);
        });
    </script>
</body>
</html>
