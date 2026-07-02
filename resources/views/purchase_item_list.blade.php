<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Item List | FuelTracker</title>
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
            background: linear-gradient(135deg, #f8fbff 0%, var(--bg) 55%, #eef5f3 100%);
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 20;
            width: 100%;
            background: linear-gradient(135deg, rgba(8, 47, 73, 0.98), rgba(15, 118, 110, 0.98));
            box-shadow: 0 10px 30px rgba(23, 32, 51, 0.12);
        }

        .site-header-inner {
            width: 100%;
            min-height: 64px;
            display: grid;
            grid-template-columns: minmax(220px, 1fr) auto minmax(220px, 1fr);
            align-items: center;
            gap: 18px;
            padding: 0 12px;
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
            background: #ffffff;
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
        .logout-btn {
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
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .purchase-item-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 88px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
        }

        .purchase-item-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
        }

        .purchase-item-page {
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

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
        }

        .export-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex: 0 0 auto;
        }

        .search-form {
            width: min(100%, 980px);
            display: grid;
            grid-template-columns: minmax(180px, 1fr) 132px 132px 74px 66px 116px;
            align-items: center;
            gap: 8px;
        }

        .search-input,
        .date-input {
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

        .search-btn {
            border: 1px solid transparent;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

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

        .entries-select {
            min-height: 31px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #ffffff;
            font: inherit;
            font-size: 11px;
            font-weight: 700;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1180px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--line);
            font-size: 13px;
            text-align: left;
            vertical-align: middle;
            white-space: nowrap;
        }

        th {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            font-size: 13px;
            font-weight: 800;
        }

        tbody tr:hover {
            background: rgba(15, 118, 110, 0.045);
        }

        .sort-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #ffffff;
            text-decoration: none;
            white-space: nowrap;
        }

        .number-cell {
            text-align: right;
        }

        .empty-state,
        .pagination-bar {
            padding: 16px 18px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        .empty-state {
            text-align: center;
        }

        .pagination-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        @media (max-width: 940px) {
            .site-header-inner,
            .search-form {
                grid-template-columns: 1fr;
            }

            .header-actions,
            .header-title {
                justify-self: center;
            }

            .purchase-item-workspace.app-shell-with-sidebar {
                width: 100%;
                display: block;
                margin: 0;
                border-radius: 0;
            }

            .page-title,
            .toolbar,
            .pagination-bar {
                align-items: stretch;
                flex-direction: column;
            }
        }
    </style>
    @include('partials.theme')
</head>
<body>
    @php
        $columns = [
            'item' => 'Item Code',
            'particulars' => 'Particulars',
            'quantity' => 'Qty.',
            'rate' => 'Rate',
            'amount' => 'Amount',
            'discount_percent' => 'Discount %',
            'discount' => 'Discount',
            'taxable_amount' => 'Taxable Amt.',
            'total_amount' => 'Total Amount',
            'cgst' => 'CGST %',
            'sgst' => 'SGST %',
            'igst' => 'IGST %',
            'total_tax' => 'Total Tax',
        ];

        $sortUrl = function (string $column) use ($sort, $direction, $search, $perPage) {
            return route('purchase-item-list.index', [
                'search' => $search,
                'from_date' => request('from_date'),
                'to_date' => request('to_date'),
                'sort' => $column,
                'direction' => $sort === $column && $direction === 'asc' ? 'desc' : 'asc',
                'per_page' => $perPage,
            ]);
        };
    @endphp

    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>
            <div class="header-title">Purchase Item List</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar purchase-item-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="purchase-item-page">
            <div class="list-shell">
                <section class="page-title" aria-labelledby="purchaseItemListTitle">
                    <div>
                        <p class="eyebrow">Reports</p>
                        <h1 id="purchaseItemListTitle">Purchase Item List</h1>
                    </div>
                    <span class="record-count">{{ $purchaseItems->total() }} {{ $purchaseItems->total() === 1 ? 'item' : 'items' }}</span>
                </section>

                <section class="list-panel">
                    <div class="toolbar">
                        <form class="search-form" method="GET" action="{{ route('purchase-item-list.index') }}">
                            <input class="search-input" type="search" name="search" value="{{ $search }}" placeholder="Search ref no, invoice, party, item...">
                            <input class="date-input" type="date" name="from_date" value="{{ request('from_date') }}" aria-label="From date">
                            <input class="date-input" type="date" name="to_date" value="{{ request('to_date') }}" aria-label="To date">
                            <button type="submit" class="search-btn">Search</button>
                            <a href="{{ route('purchase-item-list.index') }}" class="reset-btn">Clear</a>
                            <select class="entries-select" name="per_page" onchange="this.form.submit()" aria-label="Entries per page">
                                @foreach ($perPageOptions as $option)
                                    <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }} Entries</option>
                                @endforeach
                            </select>
                        </form>
                        @if ($purchaseItems->count())
                            <div class="export-actions">
                                <a class="new-btn" href="{{ route('purchase-item-list.pdf', request()->except('page')) }}" target="_blank" rel="noopener" data-themed-export>PDF</a>
                                <a class="new-btn" href="{{ route('purchase-item-list.excel', request()->except('page')) }}" data-themed-export>Excel</a>
                            </div>
                        @endif
                    </div>

                    @if ($purchaseItems->count())
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        @foreach ($columns as $column => $label)
                                            <th class="{{ ! in_array($column, ['item', 'particulars'], true) ? 'number-cell' : '' }}">
                                                <a class="sort-link" href="{{ $sortUrl($column) }}">
                                                    <span>{{ $label }}</span>
                                                    @if ($sort === $column)
                                                        <span>{{ $direction === 'asc' ? 'Asc' : 'Desc' }}</span>
                                                    @endif
                                                </a>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseItems as $purchaseItem)
                                        <tr>
                                            <td>{{ $purchaseItem->item_name ?: '-' }}</td>
                                            <td>{{ $purchaseItem->item_name ?: '-' }}</td>
                                            <td class="number-cell">{{ is_numeric($purchaseItem->quantity) ? number_format((float) $purchaseItem->quantity, 3) : '-' }}</td>
                                            <td class="number-cell">{{ is_numeric($purchaseItem->rate) ? number_format((float) $purchaseItem->rate, 2) : '-' }}</td>
                                            <td class="number-cell">{{ is_numeric($purchaseItem->amount) ? number_format((float) $purchaseItem->amount, 2) : '-' }}</td>
                                            <td class="number-cell">{{ is_numeric($purchaseItem->{'discount%'}) ? number_format((float) $purchaseItem->{'discount%'}, 2) : '-' }}</td>
                                            <td class="number-cell">{{ is_numeric($purchaseItem->discountinrs) ? number_format((float) $purchaseItem->discountinrs, 2) : '-' }}</td>
                                            <td class="number-cell">{{ is_numeric($purchaseItem->taxable_amount) ? number_format((float) $purchaseItem->taxable_amount, 2) : '-' }}</td>
                                            <td class="number-cell">{{ is_numeric($purchaseItem->total_amount) ? number_format((float) $purchaseItem->total_amount, 2) : '-' }}</td>
                                            <td class="number-cell">{{ is_numeric($purchaseItem->cgst) ? number_format((float) $purchaseItem->cgst, 2) : '-' }}</td>
                                            <td class="number-cell">{{ is_numeric($purchaseItem->sgst) ? number_format((float) $purchaseItem->sgst, 2) : '-' }}</td>
                                            <td class="number-cell">{{ is_numeric($purchaseItem->igst) ? number_format((float) $purchaseItem->igst, 2) : '-' }}</td>
                                            <td class="number-cell">{{ is_numeric($purchaseItem->total_tax_amount) ? number_format((float) $purchaseItem->total_tax_amount, 2) : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">No purchase item records found{{ $search ? ' for "' . $search . '"' : '' }}.</div>
                    @endif

                    <div class="pagination-bar">
                        <div>
                            @if ($purchaseItems->total())
                                Showing {{ $purchaseItems->firstItem() }} to {{ $purchaseItems->lastItem() }} of {{ $purchaseItems->total() }}
                            @else
                                Showing 0 records
                            @endif
                        </div>
                        @include('partials.compact-pagination', ['paginator' => $purchaseItems])
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
    </script>
</body>
</html>
