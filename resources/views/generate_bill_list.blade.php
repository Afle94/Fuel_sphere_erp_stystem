<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Saved Bills List | FuelTracker</title>
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
            --shadow: 0 16px 48px rgba(23, 32, 51, .10);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(15, 118, 110, .16), transparent 32rem),
                linear-gradient(135deg, #f8fbff 0%, var(--bg) 55%, #eef5f3 100%);
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 20;
            width: 100%;
            background:
                linear-gradient(135deg, rgba(8, 47, 73, .98), rgba(15, 118, 110, .98)),
                url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.12' stroke-width='2'%3E%3Cpath d='M22 116c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 78c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 40c20-18 40-18 60 0s40 18 60 0'/%3E%3C/g%3E%3C/svg%3E");
            box-shadow: 0 10px 30px rgba(23, 32, 51, .12);
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
            overflow: hidden;
            padding: 2px;
            border-radius: 999px;
            background: #ffffff;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .18);
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
            border: 1px solid rgba(255, 255, 255, .24);
            border-radius: 8px;
            color: #ffffff;
            background: rgba(255, 255, 255, .12);
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: background .2s ease, transform .2s ease;
        }

        .back-link:hover,
        .logout-btn:hover,
        .primary-btn:hover {
            background: rgba(255, 255, 255, .2);
            transform: translateY(-1px);
        }

        .logout-btn {
            font-family: inherit;
        }

        .primary-btn {
            border: 1px solid transparent;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .bill-list-page {
            min-width: 0;
            padding: 14px;
        }

        .bill-list-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 88px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
        }

        .bill-list-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
        }

        .page-shell {
            display: grid;
            gap: 12px;
        }

        .page-title,
        .list-panel {
            border: 1px solid rgba(220, 227, 238, .86);
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
            background: rgba(15, 118, 110, .09);
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

        .form-alert.error {
            color: var(--danger);
            background: #fff1f0;
            border: 1px solid rgba(180, 35, 24, .22);
        }

        .form-alert.is-hiding {
            opacity: 0;
            transform: translateY(-4px);
            transition: opacity .25s ease, transform .25s ease;
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
            border-color: rgba(15, 118, 110, .52);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, .13);
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
            box-shadow: 0 18px 40px rgba(23, 32, 51, .16);
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
            border-color: rgba(15, 118, 110, .52);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, .13);
            outline: none;
        }

        .entries-toggle:hover {
            border-color: rgba(15, 118, 110, .42);
            background:
                linear-gradient(135deg, rgba(15, 118, 110, .12), rgba(20, 184, 166, .07)),
                #ffffff;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .bill-table {
            width: 100%;
            min-width: 880px;
            border-collapse: collapse;
        }

        .bill-table th,
        .bill-table td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
            font-size: 13px;
            text-align: left;
            vertical-align: middle;
        }

        .bill-table th {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            font-weight: 800;
        }

        .bill-table td {
            color: var(--ink);
            font-weight: 500;
        }

        .bill-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .bill-table tbody tr:hover {
            background: rgba(15, 118, 110, .045);
        }

        .bill-link {
            color: var(--primary-dark);
            font-weight: 800;
            text-decoration: none;
        }

        .bill-link:hover {
            text-decoration: underline;
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

        .preview-btn {
            border: 1px solid rgba(15, 118, 110, .2);
            color: var(--primary-dark);
            background: rgba(15, 118, 110, .08);
        }

        .empty-state {
            margin: 0;
            padding: 34px 16px;
            color: var(--muted);
            font-size: 14px;
            font-weight: 700;
            text-align: center;
        }

        .pagination-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 11px 12px;
            color: var(--muted);
            font-size: 12px;
        }

        .pagination-links {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
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
            color: var(--muted);
            background: #ffffff;
        }

        .page-current {
            color: #ffffff;
            background: var(--primary);
        }

        .page-link.muted {
            opacity: .55;
        }

        @media (max-width: 760px) {
            .site-header-inner {
                grid-template-columns: 1fr;
                gap: 8px;
                padding: 10px;
            }

            .header-title {
                font-size: 17px;
            }

            .header-actions {
                justify-self: center;
            }

            .bill-list-workspace.app-shell-with-sidebar {
                width: 100%;
                min-height: calc(100vh - 64px);
                display: block;
                margin: 0;
                border-radius: 0;
            }

            .bill-list-page {
                padding: 12px;
            }

            .page-title {
                align-items: flex-start;
                flex-direction: column;
            }

            .toolbar,
            .pagination-bar {
                align-items: stretch;
                flex-direction: column;
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

            h1 {
                font-size: 22px;
            }
        }
    </style>

    @include('partials.theme')
</head>

<body>
    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>

            <div class="header-title">Saved Bills List</div>

            <div class="header-actions">
                <a href="{{ route('generate-bill.index') }}" class="back-link">Generate Bill</a>
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar bill-list-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="bill-list-page">
            <div class="page-shell">
                <section class="page-title" aria-labelledby="savedBillsTitle">
                    <div>
                        <p class="eyebrow">Transactions</p>
                        <h1 id="savedBillsTitle">Saved Bills List</h1>
                    </div>
                    <span class="record-count">{{ $bills->total() }} {{ $bills->total() === 1 ? 'bill' : 'bills' }}</span>
                </section>

                <section class="list-panel">
                    @if (session('error'))
                        <div class="form-alert error">{{ session('error') }}</div>
                    @endif

                    <div class="toolbar">
                        <form class="search-form" method="GET" action="{{ route('generate-bill.list') }}">
                            <input class="search-input" type="search" name="search" value="{{ $search }}" placeholder="Search bill, party, vehicle, date or amount">
                            <button type="submit" class="search-btn">Search</button>
                            <a href="{{ route('generate-bill.list') }}" class="reset-btn">Clear</a>
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
                            @if ($bills->total())
                                <a href="{{ route('generate-bill.list.pdf', request()->query()) }}" class="new-btn" target="_blank" rel="noopener" data-themed-export>PDF</a>
                                <a href="{{ route('generate-bill.list.excel', request()->query()) }}" class="new-btn" data-themed-export>Excel</a>
                            @endif
                            <a href="{{ route('generate-bill.index') }}" class="new-btn">Generate Bill</a>
                        </div>
                    </div>

                    @if ($bills->count())
                        <div class="table-wrap">
                            <table class="bill-table">
                                <thead>
                                    <tr>
                                        <th>Bill No</th>
                                        <th>Bill Date</th>
                                        <th>Party</th>
                                        <th>Vehicle No</th>
                                        <th>Date From</th>
                                        <th>Date To</th>
                                        <th>Slips</th>
                                        <th>Total</th>
                                        <th>Saved On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bills as $bill)
                                        <tr>
                                            <td>
                                                <a href="{{ route('generate-bill.show', $bill) }}" class="bill-link">{{ $bill->bill_no ?: '-' }}</a>
                                            </td>
                                            <td>{{ optional($bill->bill_date)->format('d/m/Y') ?: '-' }}</td>
                                            <td>{{ $bill->party ?: '-' }}</td>
                                            <td>{{ $bill->vehicle_no ?: 'All Vehicles' }}</td>
                                            <td>{{ optional($bill->date_from)->format('d/m/Y') ?: '-' }}</td>
                                            <td>{{ optional($bill->date_to)->format('d/m/Y') ?: '-' }}</td>
                                            <td>{{ $bill->items_count }}</td>
                                            <td>{{ number_format((float) $bill->total_amount, 2) }}</td>
                                            <td>{{ optional($bill->created_at)->format('d/m/Y') ?: '-' }}</td>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('generate-bill.show', $bill) }}" class="action-btn preview-btn">Preview</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">No bills found{{ $search ? ' for "' . $search . '"' : '' }}.</div>
                    @endif

                    <div class="pagination-bar">
                        <div>
                            @if ($bills->total())
                                Showing {{ $bills->firstItem() }} to {{ $bills->lastItem() }} of {{ $bills->total() }}
                            @else
                                Showing 0 records
                            @endif
                        </div>

                        @include('partials.compact-pagination', ['paginator' => $bills])
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

        document.querySelectorAll('.form-alert').forEach((alert) => {
            setTimeout(() => {
                alert.classList.add('is-hiding');
                setTimeout(() => alert.remove(), 250);
            }, 4000);
        });
    </script>
</body>

</html>
