<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>List Of Accounts | FuelTracker</title>
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
            --accent: #f59e0b;
            --danger: #b42318;
            --shadow: 0 24px 70px rgba(23, 32, 51, 0.14);
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
            background: #ffffff;
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
        .create-link {
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
        .create-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .account-list-page {
            width: 100%;
            min-height: auto;
            padding: 16px 18px 32px;
        }

        .account-list-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 88px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
        }

        .account-list-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
        }

        .account-list-workspace.app-shell-with-sidebar > main {
            min-width: 0;
        }

        .account-list-workspace .account-list-page {
            padding: 14px;
        }

        .account-list-workspace .list-shell {
            width: 100%;
        }

        .list-shell {
            width: min(100%, calc(100vw - 24px));
            margin: 0 auto;
            display: grid;
            gap: 12px;
        }

        .page-title,
        .list-panel {
            border: 1px solid rgba(220, 227, 238, 0.86);
            border-radius: 12px;
            background: var(--panel);
            box-shadow: 0 16px 48px rgba(23, 32, 51, 0.10);
        }

        .page-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 18px;
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
            width: min(100%, 620px);
            display: grid;
            grid-template-columns: minmax(180px, 205px) 74px 66px 104px;
            align-items: center;
            gap: 8px;
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
            background:
                linear-gradient(135deg, rgba(15, 118, 110, 0.08), rgba(20, 184, 166, 0.04)),
                #ffffff;
            font: inherit;
            font-size: 11px;
            font-weight: 700;
            outline: none;
            cursor: pointer;
            text-align: left;
        }

        .entries-dropdown::after {
            content: "";
            position: absolute;
            right: 12px;
            top: 50%;
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 6px solid var(--primary);
            transform: translateY(-50%);
            pointer-events: none;
        }

        .entries-menu {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            z-index: 10;
            width: 100%;
            display: none;
            overflow: hidden;
            border: 1px solid rgba(15, 118, 110, 0.28);
            border-radius: 12px;
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
            font: inherit;
            font-size: 13px;
            text-align: left;
            cursor: pointer;
        }

        .entries-option:hover,
        .entries-option:focus {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            outline: none;
        }

        .entries-option.is-selected {
            color: var(--ink);
            background: #ffffff;
            font-weight: 700;
        }

        .entries-option.is-selected:hover,
        .entries-option.is-selected:focus {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
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

        .entries-toggle:focus {
            border-color: rgba(15, 118, 110, 0.52);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
        }

        .entries-toggle:hover {
            border-color: rgba(15, 118, 110, 0.42);
            background:
                linear-gradient(135deg, rgba(15, 118, 110, 0.12), rgba(20, 184, 166, 0.07)),
                #ffffff;
        }

        .search-btn,
        .reset-btn,
        .primary-btn {
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
        .primary-btn {
            border: 1px solid transparent;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .reset-btn {
            border: 1px solid var(--line);
            color: var(--muted);
            background: #ffffff;
        }

        .table-wrap {
            overflow-x: auto;
            max-width: 100%;
            scrollbar-width: thin;
            scrollbar-color: rgba(15, 118, 110, 0.46) rgba(220, 227, 238, 0.72);
        }

        .account-list-workspace:not(.menu-collapsed) .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 0;
            table-layout: auto;
            border-collapse: collapse;
        }

        .account-list-workspace:not(.menu-collapsed) table {
            min-width: 1320px;
            table-layout: auto;
        }

        th,
        td {
            padding: 9px 10px;
            border-bottom: 1px solid var(--line);
            font-size: 13px;
            text-align: left;
            vertical-align: top;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        th {
            position: sticky;
            top: 0;
            z-index: 1;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            font-size: 13px;
            font-weight: 800;
        }

        th:last-child,
        td:last-child {
            width: 128px;
            min-width: 128px;
        }

        td {
            color: #172033;
            font-weight: 500;
        }

        tbody tr:hover {
            background: rgba(15, 118, 110, 0.045);
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

        .text-strong {
            color: var(--ink);
            font-weight: 700;
        }

        .muted {
            color: var(--muted);
        }

        .address-cell {
            line-height: 1.45;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            min-height: 22px;
            padding: 0 7px;
            border-radius: 999px;
            color: var(--primary-dark);
            background: rgba(15, 118, 110, 0.09);
            font-size: 11px;
            font-weight: 700;
        }

        .actions {
            display: inline-flex;
            align-items: center;
            flex-wrap: nowrap;
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

        .empty-state {
            padding: 38px 18px;
            color: var(--muted);
            font-size: 14px;
            font-weight: 700;
            text-align: center;
        }

        .mobile-list {
            display: none;
            gap: 10px;
            padding: 12px;
        }

        .account-card {
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #fbfcfe;
            overflow: hidden;
        }

        .account-card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            padding: 12px;
            border-bottom: 1px solid var(--line);
            background: #ffffff;
        }

        .account-card-title {
            min-width: 0;
        }

        .account-card-title strong {
            display: block;
            overflow: hidden;
            color: var(--ink);
            font-size: 14px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .account-card-title span {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            padding: 12px;
        }

        .card-field {
            min-width: 0;
        }

        .card-field.full {
            grid-column: 1 / -1;
        }

        .card-field span {
            display: block;
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .card-field strong {
            display: block;
            overflow-wrap: anywhere;
            margin-top: 3px;
            color: var(--ink);
            font-size: 13px;
        }

        .card-actions {
            display: flex;
            gap: 8px;
            padding: 0 12px 12px;
        }

        .card-actions .action-btn,
        .card-actions .delete-form {
            flex: 1;
        }

        .card-actions .delete-form .action-btn {
            width: 100%;
        }

        .pagination-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px;
            border-top: 1px solid var(--line);
        }

        .pagination-info {
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        .pagination-links {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .page-link,
        .page-current {
            min-width: 34px;
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .page-link {
            border: 1px solid var(--line);
            color: var(--ink);
            background: #ffffff;
        }

        .page-link.muted {
            opacity: .55;
        }

        .page-link:hover,
        .page-link:focus {
            border-color: rgba(15, 118, 110, 0.42);
            color: var(--primary-dark);
            background: rgba(15, 118, 110, 0.08);
            outline: none;
        }

        .page-current {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .form-alert {
            margin: 14px 14px 0;
            padding: 10px 12px;
            border-radius: 12px;
            font-size: 13px;
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

        @media (max-width: 980px) {
            .site-header-inner {
                grid-template-columns: auto 1fr auto;
                min-height: 64px;
            }

            .header-title {
                justify-self: center;
                font-size: 17px;
            }

            .site-logo span:last-child {
                display: none;
            }

            .account-list-page {
                padding: 12px;
            }

            .page-title,
            .toolbar,
            .pagination-bar {
                align-items: stretch;
                flex-direction: column;
            }

            .toolbar {
                gap: 10px;
            }

            .search-form {
                width: 100%;
                grid-template-columns: 1fr;
            }

            .entries-dropdown,
            .entries-toggle {
                width: 100%;
            }

            .primary-btn {
                width: 100%;
            }

            .toolbar-actions {
                width: 100%;
                display: grid;
                grid-template-columns: 1fr;
            }

            .table-wrap {
                display: none;
            }

            .mobile-list {
                display: grid;
            }

            .pagination-links {
                justify-content: flex-start;
            }
        }

        @media (max-width: 560px) {
            .header-actions {
                gap: 6px;
            }

            .back-link,
            .logout-btn,
            .create-link {
                min-height: 34px;
                padding: 0 10px;
                font-size: 12px;
            }

            .card-grid {
                grid-template-columns: 1fr;
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
            'account_perticular' => 'Account Name',
            'under_group' => 'Under Group',
            'opening_balance' => 'Opening Balance',
            'transaction_type' => 'Type',
            'address' => 'Address',
            'city' => 'City / District',
            'state' => 'State',
            'email' => 'Email',
            'mobile_number' => 'Mobile',
            'phone_number' => 'Phone',
            'gst_number' => 'GST No.',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
        ];

        $sortUrl = function (string $column) use ($sort, $direction, $search, $perPage) {
            return route('accounts.index', [
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

            <div class="header-title">List Of Accounts</div>

            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar account-list-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="account-list-page">
            <div class="list-shell">
                <section class="page-title" aria-labelledby="accountListTitle">
                    <div>
                        <p class="eyebrow">Masters</p>
                        <h1 id="accountListTitle">List Of Accounts</h1>
                    </div>
                    <span class="record-count">{{ $accounts->total() }} {{ $accounts->total() === 1 ? 'record' : 'records' }}</span>
                </section>

                <section class="list-panel">
                @if (session('success'))
                    <div class="form-alert success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="form-alert error">{{ session('error') }}</div>
                @endif

                <div class="toolbar">
                    <form class="search-form" method="GET" action="{{ route('accounts.index') }}">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <input type="hidden" name="direction" value="{{ $direction }}">
                        <input class="search-input" type="search" name="search" value="{{ $search }}" placeholder="Search account, group, city, state, email, mobile, GST">
                        <button type="submit" class="search-btn">Search</button>
                        <a href="{{ route('accounts.index') }}" class="reset-btn">Clear</a>
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
                        @if ($accounts->count())
                            <a href="{{ route('accounts.pdf') }}" class="primary-btn" target="_blank" rel="noopener" data-themed-export>PDF</a>
                            <a href="{{ route('accounts.excel') }}" class="primary-btn" data-themed-export>Excel</a>
                        @endif
                        <a href="{{ route('accountmaster') }}" class="primary-btn">New Account</a>
                    </div>
                </div>

                @if ($accounts->count())
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
                                @foreach ($accounts as $account)
                                    <tr>
                                        <td>{{ $account->id }}</td>
                                        <td class="text-strong">{{ $account->account_perticular }}</td>
                                        <td>{{ $account->under_group }}</td>
                                        <td>{{ number_format((float) $account->opening_balance, 2) }}</td>
                                        <td><span class="badge">{{ $account->transaction_type }}</span></td>
                                        <td class="address-cell">{{ $account->address ?: '-' }}</td>
                                        <td>{{ $account->city }}</td>
                                        <td>{{ $account->state }}</td>
                                        <td>{{ $account->email }}</td>
                                        <td>{{ $account->mobile_number }}</td>
                                        <td>{{ $account->phone_number ?: '-' }}</td>
                                        <td>{{ $account->gst_number }}</td>
                                        <td>{{ optional($account->created_at)->format('d M Y') ?: '-' }}</td>
                                        <td>{{ optional($account->updated_at)->format('d M Y') ?: '-' }}</td>
                                        <td>
                                            <div class="actions">
                                                <a href="{{ route('accounts.edit', $account->id) }}" class="action-btn edit-btn">Edit</a>
                                                <form class="delete-form" method="POST" action="{{ route('accounts.destroy', $account->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn delete-btn" data-delete-account="{{ $account->account_perticular }}">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mobile-list">
                        @foreach ($accounts as $account)
                            <article class="account-card">
                                <div class="account-card-head">
                                    <div class="account-card-title">
                                        <strong>{{ $account->account_perticular }}</strong>
                                        <span>{{ $account->under_group }}</span>
                                    </div>
                                    <span class="badge">{{ $account->transaction_type }}</span>
                                </div>

                                <div class="card-grid">
                                    <div class="card-field">
                                        <span>ID</span>
                                        <strong>{{ $account->id }}</strong>
                                    </div>
                                    <div class="card-field">
                                        <span>Opening Balance</span>
                                        <strong>{{ number_format((float) $account->opening_balance, 2) }}</strong>
                                    </div>
                                    <div class="card-field">
                                        <span>GST No.</span>
                                        <strong>{{ $account->gst_number }}</strong>
                                    </div>
                                    <div class="card-field">
                                        <span>City / District</span>
                                        <strong>{{ $account->city }}</strong>
                                    </div>
                                    <div class="card-field">
                                        <span>State</span>
                                        <strong>{{ $account->state }}</strong>
                                    </div>
                                    <div class="card-field">
                                        <span>Mobile</span>
                                        <strong>{{ $account->mobile_number }}</strong>
                                    </div>
                                    <div class="card-field">
                                        <span>Phone</span>
                                        <strong>{{ $account->phone_number ?: '-' }}</strong>
                                    </div>
                                    <div class="card-field full">
                                        <span>Email</span>
                                        <strong>{{ $account->email }}</strong>
                                    </div>
                                    <div class="card-field full">
                                        <span>Address</span>
                                        <strong>{{ $account->address ?: '-' }}</strong>
                                    </div>
                                    <div class="card-field">
                                        <span>Created</span>
                                        <strong>{{ optional($account->created_at)->format('d M Y') ?: '-' }}</strong>
                                    </div>
                                    <div class="card-field">
                                        <span>Updated</span>
                                        <strong>{{ optional($account->updated_at)->format('d M Y') ?: '-' }}</strong>
                                    </div>
                                </div>

                                <div class="card-actions">
                                    <a href="{{ route('accounts.edit', $account->id) }}" class="action-btn edit-btn">Edit</a>
                                    <form class="delete-form" method="POST" action="{{ route('accounts.destroy', $account->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn" data-delete-account="{{ $account->account_perticular }}">Delete</button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        No accounts found{{ $search ? ' for "' . $search . '"' : '' }}.
                    </div>
                @endif

                <div class="pagination-bar">
                    <div class="pagination-info">
                        @if ($accounts->total())
                            Showing {{ $accounts->firstItem() }} to {{ $accounts->lastItem() }} of {{ $accounts->total() }}
                        @else
                            Showing 0 records
                        @endif
                    </div>

                    @include('partials.compact-pagination', ['paginator' => $accounts])
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
                    Are you sure you want to delete <strong id="deleteAccountName">this account</strong>? This action cannot be undone.
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
        const deleteAccountName = document.getElementById('deleteAccountName');
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
                const button = form.querySelector('[data-delete-account]');

                if (form.dataset.confirmed === 'true') {
                    return;
                }

                event.preventDefault();
                pendingDeleteForm = form;
                deleteAccountName.textContent = button?.dataset.deleteAccount || 'this account';
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
            }, 10000);
        });
    </script>
</body>
</html>
