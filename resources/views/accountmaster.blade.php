<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Accounts Master Creation | FuelTracker</title>
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
            min-height: 100dvh;
            overflow-x: hidden;
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
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin: 0 auto;
            padding: 0 18px;
            position: relative;
        }

        .site-logo {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
        }

        .site-logo-icon {
            display: grid;
            width: 34px;
            height: 34px;
            place-items: center;
            border-radius: 12px;
            color: var(--primary);
            background: #ffffff;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.18);
        }

        .header-actions {
            position: absolute;
            right: 18px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-title {
            position: absolute;
            left: 50%;
            color: #ffffff;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0;
            transform: translateX(-50%);
            white-space: nowrap;
        }

        .back-link,
        .logout-btn,
        .save-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .back-link,
        .logout-btn {
            padding: 0 16px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
        }

        .back-link:hover,
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .logout-btn {
            font-family: inherit;
        }

        .account-page {
            width: min(100% - 36px, 780px);
            height: auto;
            min-height: auto;
            display: block;
            overflow: visible;
            margin: 0 auto;
            padding: 14px 0 70px;
            transform: translateX(86px);
            transition: transform 0.22s ease;
        }

        .account-master-workspace {
            min-height: calc(100vh - 48px);
            min-height: calc(100dvh - 48px);
            position: relative;
        }

        .account-master-workspace.menu-collapsed .account-page {
            transform: translateX(0);
        }

        .account-content {
            width: 100%;
        }

        .page-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin: 0 auto 10px;
        }

        .eyebrow {
            margin: 0 0 3px;
            color: var(--primary);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .page-title h1 {
            margin: 0;
            font-size: 24px;
            line-height: 1.15;
            letter-spacing: 0;
        }

        .page-title p {
            max-width: 700px;
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.35;
        }

        .view-list-btn {
            flex: 0 0 auto;
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 15px;
            border: 1px solid rgba(15, 118, 110, 0.2);
            border-radius: 12px;
            color: var(--primary-dark);
            background: rgba(15, 118, 110, 0.08);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .view-list-btn:hover {
            color: #ffffff;
            background: var(--primary);
            transform: translateY(-1px);
        }

        .form-note {
            margin: 12px 0 0;
            padding: 12px 14px;
            border: 1px solid rgba(15, 118, 110, 0.18);
            border-radius: 14px;
            color: var(--muted);
            background: rgba(15, 118, 110, 0.07);
            font-size: 13px;
            line-height: 1.55;
        }

        .form-note strong {
            display: block;
            margin-bottom: 4px;
            color: var(--primary-dark);
            font-size: 14px;
        }

        .account-shell {
            width: 100%;
            margin: 0;
        }

        .form-panel {
            border: 1px solid rgba(220, 227, 238, 0.86);
            border-radius: 20px;
            background: var(--panel);
            box-shadow: var(--shadow);
        }

        .form-panel {
            padding: 14px 16px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px 14px;
        }

        .field {
            display: grid;
            gap: 7px;
            align-content: start;
        }

        .field:has(> .theme-dropdown),
        .field:has(> .balance-control) {
            grid-template-rows: 18px 42px auto;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        .field label {
            color: var(--ink);
            font-size: 14px;
            font-weight: 700;
        }

        .required-star {
            color: var(--danger);
            font-weight: 700;
        }

        .form-alert {
            grid-column: 1 / -1;
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

        .form-alert ul {
            margin: 6px 0 0 18px;
            padding: 0;
            font-weight: 600;
        }

        .form-alert.is-hiding {
            opacity: 0;
            transform: translateY(-4px);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .field input,
        .field select,
        .field textarea {
            width: 100%;
            min-height: 42px;
            border: 1px solid var(--line);
            border-radius: 12px;
            color: var(--ink);
            background: #fbfcfe;
            font: inherit;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .field input,
        .field select {
            padding: 0 14px;
        }

        .field textarea {
            min-height: 96px;
            resize: vertical;
            padding: 12px 14px;
            line-height: 1.45;
        }

        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            border-color: rgba(15, 118, 110, 0.52);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
        }

        .field.is-invalid input,
        .field.is-invalid select,
        .field.is-invalid textarea,
        .field.is-invalid .theme-dropdown-button {
            border-color: var(--danger);
            background: #fffafa;
            box-shadow: 0 0 0 4px rgba(180, 35, 24, 0.08);
        }

        .field-error {
            color: var(--danger);
            font-size: 12px;
            font-weight: 700;
            line-height: 1.25;
            margin: -2px 0 0;
        }

        .field-limit {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            min-height: 16px;
            margin: -4px 2px 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.3;
        }

        .field-limit-count {
            flex: 0 0 auto;
            color: var(--primary-dark);
            font-weight: 700;
        }

        .balance-control {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 112px;
            gap: 8px;
            align-items: start;
            height: 42px;
        }

        .balance-control > input {
            height: 42px;
            min-height: 0;
        }

        .balance-type {
            position: relative;
            height: 42px;
            min-height: 0;
        }

        .balance-type-button {
            width: 100%;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 0 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            color: var(--ink);
            background: #fbfcfe;
            cursor: pointer;
            font: inherit;
            font-size: 15px;
            font-weight: 700;
        }

        .balance-type-button:hover,
        .balance-type-button:focus {
            border-color: rgba(15, 118, 110, 0.52);
            background: rgba(15, 118, 110, 0.07);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
            outline: none;
        }

        .balance-type-arrow {
            width: 9px;
            height: 9px;
            border-right: 2px solid currentColor;
            border-bottom: 2px solid currentColor;
            transform: translateY(-2px) rotate(45deg);
        }

        .balance-type-menu {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            left: 0;
            z-index: 30;
            display: none;
            overflow: hidden;
            margin: 0;
            padding: 4px;
            border: 1px solid rgba(15, 118, 110, 0.22);
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 14px 32px rgba(23, 32, 51, 0.16);
            list-style: none;
        }

        .balance-type.is-open .balance-type-menu {
            display: block;
        }

        .balance-type-option {
            width: 100%;
            min-height: 36px;
            padding: 0 10px;
            border: 0;
            border-radius: 9px;
            color: var(--ink);
            background: transparent;
            cursor: pointer;
            font: inherit;
            font-size: 14px;
            font-weight: 700;
            text-align: left;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .balance-type-option:hover,
        .balance-type-option:focus {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            outline: none;
        }

        .balance-type-option.is-selected {
            color: var(--ink);
            background: transparent;
        }

        .balance-type-option.is-selected:hover,
        .balance-type-option.is-selected:focus {
            color: #ffffff !important;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary)) !important;
        }

        .theme-dropdown {
            position: relative;
            height: 42px;
            min-height: 0;
            display: block;
        }

        .theme-dropdown-value {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .theme-dropdown-button {
            width: 100%;
            height: 100%;
            min-height: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 0 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            color: var(--ink);
            background: #fbfcfe;
            cursor: pointer;
            font: inherit;
            font-size: 15px;
            outline: none;
            text-align: left;
        }

        .theme-dropdown-button:hover,
        .theme-dropdown-button:focus {
            border-color: rgba(15, 118, 110, 0.52);
            background: rgba(15, 118, 110, 0.07);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
        }

        .theme-dropdown-button:disabled {
            color: var(--muted);
            background: #f1f4f8;
            cursor: not-allowed;
            box-shadow: none;
        }

        .theme-dropdown-text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .theme-dropdown-arrow {
            width: 9px;
            height: 9px;
            flex: 0 0 auto;
            border-right: 2px solid currentColor;
            border-bottom: 2px solid currentColor;
            transform: translateY(-2px) rotate(45deg);
        }

        .theme-dropdown-menu {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            left: 0;
            z-index: 40;
            display: none;
            max-height: 180px;
            overflow-y: auto;
            margin: 0;
            padding: 4px;
            border: 1px solid rgba(15, 118, 110, 0.22);
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 14px 32px rgba(23, 32, 51, 0.16);
            list-style: none;
            scrollbar-width: thin;
            scrollbar-color: rgba(15, 118, 110, 0.46) rgba(220, 227, 238, 0.72);
        }

        #underGroupDropdown .theme-dropdown-menu {
            max-height: min(410px, calc(100vh - 240px));
        }

        .theme-dropdown-search-wrap {
            position: sticky;
            top: -4px;
            z-index: 1;
            padding: 4px 4px 6px;
            background: #ffffff;
        }

        .theme-dropdown-search {
            width: 100%;
            min-height: 36px;
            padding: 0 10px;
            border: 1px solid var(--line);
            border-radius: 9px;
            color: var(--ink);
            background: #fbfcfe;
            font: inherit;
            font-size: 14px;
            outline: none;
        }

        .theme-dropdown-search:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 18%, transparent);
        }

        .theme-dropdown-empty {
            display: none;
            min-height: 32px;
            padding: 8px 10px;
            color: var(--muted);
            font-size: 14px;
        }

        .theme-dropdown-empty.is-visible {
            display: block;
        }

        .theme-dropdown-menu::-webkit-scrollbar {
            width: 8px;
        }

        .theme-dropdown-menu::-webkit-scrollbar-track {
            border-radius: 999px;
            background: rgba(220, 227, 238, 0.72);
        }

        .theme-dropdown-menu::-webkit-scrollbar-thumb {
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.46);
        }

        .theme-dropdown.is-open .theme-dropdown-menu {
            display: block;
        }

        .theme-dropdown-option {
            width: 100%;
            min-height: 36px;
            padding: 0 10px;
            border: 0;
            border-radius: 9px;
            color: var(--ink);
            background: transparent;
            cursor: pointer;
            font: inherit;
            font-size: 14px;
            text-align: left;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .theme-dropdown-option:hover,
        .theme-dropdown-option:focus {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            outline: none;
        }

        .theme-dropdown-option.is-selected {
            color: var(--ink);
            background: transparent;
        }

        .theme-dropdown-option.is-selected:hover,
        .theme-dropdown-option.is-selected:focus {
            color: #ffffff !important;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary)) !important;
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--line);
        }

        .save-btn {
            min-height: 42px;
            min-width: 132px;
            border: 0;
            color: #ffffff;
            background: var(--primary);
            font-family: inherit;
            box-shadow: 0 12px 28px rgba(15, 118, 110, 0.22);
        }

        .save-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .clear-btn {
            min-height: 42px;
            padding: 0 16px;
            border: 1px solid var(--line);
            border-radius: 14px;
            color: var(--muted);
            background: #fbfcfe;
            cursor: pointer;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
        }

        .clear-btn:hover {
            color: var(--ink);
            border-color: rgba(15, 118, 110, 0.36);
        }

        @media (max-width: 640px) {
            .site-header-inner {
                min-height: 54px;
                align-items: center;
                flex-direction: row;
                justify-content: space-between;
                gap: 10px;
                padding: 0 10px;
            }

            .site-logo {
                min-width: 0;
                font-size: 20px;
            }

            .site-logo-icon {
                width: 32px;
                height: 32px;
            }

            .header-actions {
                right: 10px;
                gap: 6px;
            }

            .header-title {
                font-size: 15px;
            }

            .back-link,
            .logout-btn {
                min-height: 32px;
                padding: 0 7px;
                border-radius: 11px;
                font-size: 12px;
            }

            .account-page {
                height: auto;
                min-height: auto;
                overflow: visible;
                padding: 18px;
                transform: none;
            }

            .page-title {
                margin-bottom: 6px;
            }

            .view-list-btn {
                min-height: 32px;
                padding: 0 10px;
                border-radius: 10px;
                font-size: 12px;
            }

            .eyebrow {
                display: none;
            }

            .page-title h1 {
                font-size: 19px;
            }

            .page-title p {
                display: none;
            }

            .form-note {
                margin-top: 8px;
                padding: 8px 10px;
                border-radius: 11px;
                font-size: 11px;
                line-height: 1.35;
            }

            .form-note strong {
                font-size: 12px;
            }

            .form-panel {
                padding: 10px;
                border-radius: 16px;
            }

            .form-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 7px 8px;
            }

            .field.full {
                grid-column: 1 / -1;
            }

            .field {
                gap: 5px;
            }

            .field:has(> .theme-dropdown),
            .field:has(> .balance-control) {
                grid-template-rows: 16px 36px auto;
            }

            .field label {
                font-size: 13px;
            }

            .field input,
            .field select,
            .field textarea {
                min-height: 36px;
                border-radius: 10px;
                font-size: 13px;
            }

            .field input,
            .field select {
                padding: 0 9px;
            }

            .field textarea {
                min-height: 70px;
                padding: 6px 9px;
            }

            .balance-control {
                grid-template-columns: minmax(0, 1fr) 72px;
                gap: 5px;
                height: 36px;
            }

            .balance-control > input {
                height: 36px;
            }

            .balance-type {
                height: 36px;
                min-height: 0;
            }

            .balance-type-button {
                height: 36px;
                padding: 0 8px;
                border-radius: 10px;
                font-size: 13px;
            }

            .balance-type-menu {
                border-radius: 10px;
            }

            .balance-type-option {
                min-height: 32px;
                font-size: 13px;
            }

            .theme-dropdown,
            .theme-dropdown-button {
                height: 36px;
                min-height: 0;
            }

            .theme-dropdown-button {
                padding: 0 8px;
                border-radius: 10px;
                font-size: 13px;
            }

            .theme-dropdown-menu {
                max-height: 130px;
                border-radius: 10px;
            }

            .theme-dropdown-option {
                min-height: 32px;
                font-size: 13px;
            }

            .form-actions {
                align-items: center;
                flex-direction: row;
                gap: 8px;
                margin-top: 8px;
                padding-top: 8px;
            }

            .save-btn,
            .clear-btn {
                width: auto;
                min-height: 32px;
                border-radius: 11px;
                font-size: 12px;
            }

            .save-btn {
                min-width: 104px;
            }
        }

        @media (max-height: 680px) {
            .site-header-inner {
                min-height: 54px;
            }

            .account-page {
                height: calc(100vh - 54px);
                height: calc(100dvh - 54px);
                padding-top: 7px;
                padding-bottom: 9px;
                transform: none;
            }

            .page-title {
                margin-bottom: 6px;
            }

            .page-title h1 {
                font-size: 22px;
            }

            .page-title p {
                display: none;
            }

            .form-note {
                margin-top: 8px;
                padding: 8px 10px;
                font-size: 12px;
                line-height: 1.35;
            }

            .form-panel {
                padding: 10px 12px;
            }

            .form-grid {
                gap: 7px 12px;
            }

            .field label {
                font-size: 13px;
            }

            .field input,
            .field select {
                min-height: 36px;
            }

            .field textarea {
                min-height: 72px;
            }

            .form-actions {
                margin-top: 7px;
                padding-top: 7px;
            }
        }
    </style>
    @include('partials.theme')
</head>
<body>
    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon has-brand-image" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>

            <div class="header-title">Accounts Master Creation</div>

            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="account-master-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="account-page">
            <div class="account-content">
                <section class="page-title" aria-labelledby="accountMasterTitle">
                    <div>
                        <p class="eyebrow">Masters</p>
                        <h1 id="accountMasterTitle">Account Details</h1>
                    </div>
                    <a href="{{ route('accounts.index') }}" class="view-list-btn">View List</a>
                </section>

                <section class="account-shell">
                    <form class="form-panel" id="accountMasterForm" method="POST" action="{{ route('accountmaster.store') }}" autocomplete="off" novalidate>
                    @csrf
                    <div class="form-grid">
                        @if (session('success'))
                            <div class="form-alert success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="form-alert error">{{ session('error') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="form-alert error">
                                Please fix the highlighted details.
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="field full">
                            <label for="particulars">Particulars <span class="required-star">*</span></label>
                            <input type="text" id="particulars" name="particulars" placeholder="Enter account name" maxlength="50" value="{{ old('particulars') }}" data-show-limit data-limit-unit="characters" required>
                        </div>

                        <div class="field">
                            <label for="underGroup">Under Group <span class="required-star">*</span></label>
                            <div class="theme-dropdown" id="underGroupDropdown">
                                <input type="text" class="theme-dropdown-value" id="underGroup" name="under_group" maxlength="255" value="{{ old('under_group') }}" required>
                                <button type="button" class="theme-dropdown-button" id="underGroupButton" aria-haspopup="listbox" aria-expanded="false">
                                    <span class="theme-dropdown-text" id="underGroupText">{{ old('under_group', 'Select under group') }}</span>
                                    <span class="theme-dropdown-arrow" aria-hidden="true"></span>
                                </button>
                                <ul class="theme-dropdown-menu" role="listbox" aria-label="Under group list">
                                    <li class="theme-dropdown-search-wrap">
                                        <input type="search" class="theme-dropdown-search" id="underGroupSearch" placeholder="Search under group" autocomplete="off">
                                    </li>
                                    @forelse (($underGroups ?? collect()) as $underGroup)
                                        <li><button type="button" class="theme-dropdown-option {{ old('under_group') === $underGroup->group_name ? 'is-selected' : '' }}" data-value="{{ $underGroup->group_name }}" role="option" aria-selected="{{ old('under_group') === $underGroup->group_name ? 'true' : 'false' }}">{{ $underGroup->group_name }}</button></li>
                                    @empty
                                        <li><button type="button" class="theme-dropdown-option" disabled>No under groups found</button></li>
                                    @endforelse
                                    <li class="theme-dropdown-empty" id="underGroupEmpty">No matching groups</li>
                                </ul>
                            </div>
                        </div>

                        <div class="field">
                            <label for="openingBalance">Opening Balance</label>
                            <div class="balance-control">
                                <input type="number" id="openingBalance" name="opening_balance" min="0" max="99999999.99" step="0.01" value="{{ old('opening_balance', '0.00') }}" data-decimal-limit="8" data-show-limit data-limit-text="Maximum 8 digits before decimal and 2 after decimal">
                                <div class="balance-type" id="balanceTypeSelect">
                                    <input type="hidden" name="balance_type" id="balanceTypeValue" value="{{ old('balance_type', 'dr') }}">
                                    <button type="button" class="balance-type-button" id="balanceTypeButton" aria-haspopup="listbox" aria-expanded="false">
                                        <span id="balanceTypeText">{{ old('balance_type', 'dr') === 'cr' ? 'Cr' : 'Dr' }}</span>
                                        <span class="balance-type-arrow" aria-hidden="true"></span>
                                    </button>
                                    <ul class="balance-type-menu" role="listbox" aria-label="Opening balance type">
                                        <li><button type="button" class="balance-type-option {{ old('balance_type', 'dr') === 'dr' ? 'is-selected' : '' }}" data-value="dr" role="option" aria-selected="{{ old('balance_type', 'dr') === 'dr' ? 'true' : 'false' }}">Dr</button></li>
                                        <li><button type="button" class="balance-type-option {{ old('balance_type') === 'cr' ? 'is-selected' : '' }}" data-value="cr" role="option" aria-selected="{{ old('balance_type') === 'cr' ? 'true' : 'false' }}">Cr</button></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="field full">
                            <label for="postalAddress">Postal Address</label>
                            <textarea id="postalAddress" name="postal_address" placeholder="Enter full postal address" maxlength="500" data-show-limit data-limit-unit="characters">{{ old('postal_address') }}</textarea>
                        </div>

                        <div class="field">
                            <label for="state">State <span class="required-star location-required-star" id="stateRequiredStar" hidden>*</span></label>
                            <div class="theme-dropdown" id="stateDropdown">
                                <input type="text" class="theme-dropdown-value" id="state" name="state" value="{{ old('state') }}">
                                <button type="button" class="theme-dropdown-button" id="stateButton" aria-haspopup="listbox" aria-expanded="false">
                                    <span class="theme-dropdown-text" id="stateText">{{ old('state', 'Select state') }}</span>
                                    <span class="theme-dropdown-arrow" aria-hidden="true"></span>
                                </button>
                                <ul class="theme-dropdown-menu" id="stateMenu" role="listbox" aria-label="State list">
                                    <li class="theme-dropdown-search-wrap">
                                        <input type="search" class="theme-dropdown-search" id="stateSearch" placeholder="Search state" autocomplete="off">
                                    </li>
                                    <li class="theme-dropdown-empty" id="stateEmpty">No matching states</li>
                                </ul>
                            </div>
                        </div>

                        <div class="field">
                            <label for="location">City / District <span class="required-star location-required-star" id="cityRequiredStar" hidden>*</span></label>
                            <div class="theme-dropdown" id="cityDropdown">
                                <input type="text" class="theme-dropdown-value" id="location" name="location" value="{{ old('location') }}">
                                <button type="button" class="theme-dropdown-button" id="cityButton" aria-haspopup="listbox" aria-expanded="false" disabled>
                                    <span class="theme-dropdown-text" id="cityText">{{ old('location', 'Select state first') }}</span>
                                    <span class="theme-dropdown-arrow" aria-hidden="true"></span>
                                </button>
                                <ul class="theme-dropdown-menu" id="cityMenu" role="listbox" aria-label="City and district list">
                                    <li class="theme-dropdown-search-wrap">
                                        <input type="search" class="theme-dropdown-search" id="citySearch" placeholder="Search city / district" autocomplete="off">
                                    </li>
                                    <li class="theme-dropdown-empty" id="cityEmpty">No matching cities</li>
                                </ul>
                            </div>
                        </div>

                        <div class="field">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="name@example.com" maxlength="255" value="{{ old('email') }}" data-show-limit data-limit-unit="characters">
                        </div>

                        <div class="field">
                            <label for="phoneLandline">Phone Land Line</label>
                            <input type="tel" id="phoneLandline" name="phone_landline" placeholder="Landline number" maxlength="10" inputmode="numeric" pattern="[0-9]{10}" value="{{ old('phone_landline') }}" data-digits-only data-show-limit data-limit-unit="digits">
                        </div>

                        <div class="field">
                            <label for="mobile">Mobile</label>
                            <input type="tel" id="mobile" name="mobile" placeholder="Mobile number" maxlength="10" inputmode="numeric" pattern="[0-9]{10}" value="{{ old('mobile') }}" data-digits-only data-show-limit data-limit-unit="digits">
                        </div>

                        <div class="field">
                            <label for="gstNo">GST No.</label>
                            <input type="text" id="gstNo" name="gst_no" placeholder="GSTIN" maxlength="15" value="{{ old('gst_no') }}" data-alphanumeric-only data-show-limit data-limit-unit="characters">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="reset" class="clear-btn">Clear</button>
                        <button type="submit" class="save-btn">Save</button>
                    </div>

                    <p class="form-note">
                        <strong>Note</strong>
                        Use this form to create customer, supplier, bank, cash, and ledger accounts. Add the account name, group, opening balance, address, location, state, contact numbers, email, and GST number carefully so daily transactions, bills, reports, and account ledgers remain accurate.
                    </p>
                    </form>
                </section>
            </div>
        </main>
    </div>
    <script src="{{ asset('js/india-state-districts.js') }}"></script>
    <script>
        const balanceTypeSelect = document.getElementById('balanceTypeSelect');
        const balanceTypeButton = document.getElementById('balanceTypeButton');
        const balanceTypeText = document.getElementById('balanceTypeText');
        const balanceTypeValue = document.getElementById('balanceTypeValue');
        const balanceTypeOptions = document.querySelectorAll('.balance-type-option');
        const formAlerts = document.querySelectorAll('.form-alert');
        const underGroupDropdown = document.getElementById('underGroupDropdown');
        const underGroupButton = document.getElementById('underGroupButton');
        const underGroupText = document.getElementById('underGroupText');
        const underGroupValue = document.getElementById('underGroup');
        const underGroupSearch = document.getElementById('underGroupSearch');
        const underGroupEmpty = document.getElementById('underGroupEmpty');
        const underGroupOptions = document.querySelectorAll('#underGroupDropdown .theme-dropdown-option:not(:disabled)');
        const accountMasterForm = document.getElementById('accountMasterForm');
        const saveButton = accountMasterForm.querySelector('.save-btn');
        const stateSelect = document.getElementById('state');
        const citySelect = document.getElementById('location');
        const stateDropdown = document.getElementById('stateDropdown');
        const stateButton = document.getElementById('stateButton');
        const stateText = document.getElementById('stateText');
        const stateSearch = document.getElementById('stateSearch');
        const stateMenu = document.getElementById('stateMenu');
        const stateEmpty = document.getElementById('stateEmpty');
        const cityDropdown = document.getElementById('cityDropdown');
        const cityButton = document.getElementById('cityButton');
        const cityText = document.getElementById('cityText');
        const citySearch = document.getElementById('citySearch');
        const cityMenu = document.getElementById('cityMenu');
        const cityEmpty = document.getElementById('cityEmpty');
        const stateRequiredStar = document.getElementById('stateRequiredStar');
        const cityRequiredStar = document.getElementById('cityRequiredStar');
        const digitsOnlyFields = document.querySelectorAll('[data-digits-only]');
        const alphaOnlyFields = document.querySelectorAll('[data-alpha-only]');
        const alphanumericOnlyFields = document.querySelectorAll('[data-alphanumeric-only]');
        const decimalLimitFields = document.querySelectorAll('[data-decimal-limit]');
        const limitFields = document.querySelectorAll('[data-show-limit]');

        formAlerts.forEach((alert) => {
            setTimeout(() => {
                alert.classList.add('is-hiding');
                setTimeout(() => alert.remove(), 250);
            }, 10000);
        });

        digitsOnlyFields.forEach((field) => {
            field.addEventListener('beforeinput', (event) => {
                if (event.data && /\D/.test(event.data)) {
                    event.preventDefault();
                }
            });

            field.addEventListener('input', () => {
                field.value = field.value.replace(/\D/g, '').slice(0, Number(field.maxLength) || undefined);
            });
        });

        alphaOnlyFields.forEach((field) => {
            field.addEventListener('beforeinput', (event) => {
                if (event.data && /[0-9]/.test(event.data)) {
                    event.preventDefault();
                }
            });

            field.addEventListener('input', () => {
                field.value = field.value.replace(/[0-9]/g, '').slice(0, Number(field.maxLength) || undefined);
            });
        });

        alphanumericOnlyFields.forEach((field) => {
            field.addEventListener('beforeinput', (event) => {
                if (event.data && /[^a-z0-9]/i.test(event.data)) {
                    event.preventDefault();
                }
            });

            field.addEventListener('input', () => {
                field.value = field.value.replace(/[^a-z0-9]/gi, '').slice(0, Number(field.maxLength) || undefined).toUpperCase();
            });
        });

        decimalLimitFields.forEach((field) => {
            const integerLimit = Number(field.dataset.decimalLimit);

            field.addEventListener('keydown', (event) => {
                if (['e', 'E', '+', '-'].includes(event.key)) {
                    event.preventDefault();
                }
            });

            field.addEventListener('input', () => {
                const parts = field.value.replace(/[^0-9.]/g, '').split('.');
                const integerPart = parts[0].slice(0, integerLimit);
                const decimalPart = parts.slice(1).join('').slice(0, 2);

                field.value = parts.length > 1 ? `${integerPart}.${decimalPart}` : integerPart;
            });
        });

        limitFields.forEach((field) => {
            const limit = document.createElement('p');
            const help = document.createElement('span');
            const count = document.createElement('span');
            const maxLength = Number(field.maxLength);
            const unit = field.dataset.limitUnit || 'characters';

            limit.className = 'field-limit';
            count.className = 'field-limit-count';
            help.textContent = field.dataset.limitText || `Maximum ${maxLength} ${unit}`;
            limit.append(help, count);

            const updateLimit = () => {
                if (field.dataset.decimalLimit) {
                    count.textContent = `${field.value || '0'} / ${field.max}`;
                    return;
                }

                count.textContent = `${field.value.length} / ${maxLength}`;
            };

            (field.closest('.balance-control') || field).insertAdjacentElement('afterend', limit);
            field.addEventListener('input', updateLimit);
            updateLimit();
        });

        const stateDistricts = window.INDIA_STATE_DISTRICTS || {};
        const initialState = stateSelect.value;
        const initialCity = citySelect.value;
        const stateCityRequiredGroups = new Set([
            'SUNDRY DEBTORS',
            'SUNDRY CREDITORS',
            'SUNDURY DEBTORS',
            'SUNDURY CREDITORS',
        ]);

        const normalizeGroup = (value) => value.trim().replace(/\s+/g, ' ').toUpperCase();

        const toggleStateCityRequired = () => {
            const isRequired = stateCityRequiredGroups.has(normalizeGroup(underGroupValue.value));

            stateSelect.required = isRequired;
            citySelect.required = isRequired;
            stateRequiredStar.hidden = !isRequired;
            cityRequiredStar.hidden = !isRequired;

            if (!isRequired) {
                clearFieldError(stateSelect);
                clearFieldError(citySelect);
            }
        };

        const requiredMessages = {
            particulars: 'Please enter particulars.',
            under_group: 'Please select under group.',
            state: 'Please select state.',
            location: 'Please select city / district.',
        };

        const focusField = (field) => {
            const dropdownButton = field.closest('.field')?.querySelector('.theme-dropdown-button');

            (dropdownButton || field).focus();
        };

        const showFieldError = (field, message) => {
            const wrapper = field.closest('.field');
            let error = wrapper.querySelector('.field-error');

            if (!error) {
                error = document.createElement('p');
                error.className = 'field-error';
                wrapper.appendChild(error);
            }

            wrapper.classList.add('is-invalid');
            error.textContent = message;
        };

        const clearFieldError = (field) => {
            const wrapper = field.closest('.field');
            const error = wrapper.querySelector('.field-error');

            wrapper.classList.remove('is-invalid');

            if (error) {
                error.remove();
            }
        };

        const validateRequiredField = (field) => {
            if (!field.required || field.value.trim()) {
                clearFieldError(field);
                return true;
            }

            showFieldError(field, requiredMessages[field.name] || 'This field is required.');
            return false;
        };

        const validateRequiredFields = () => {
            const requiredFields = Array.from(accountMasterForm.querySelectorAll('[required]'));
            let firstInvalid = null;

            requiredFields.forEach((field) => {
                if (!validateRequiredField(field) && !firstInvalid) {
                    firstInvalid = field;
                }
            });

            if (firstInvalid) {
                focusField(firstInvalid);
                return false;
            }

            return true;
        };

        const filterDropdownOptions = (options, searchField, emptyState) => {
            const searchTerm = searchField.value.trim().toLowerCase();
            let visibleCount = 0;

            options.forEach((option) => {
                const isVisible = option.textContent.trim().toLowerCase().includes(searchTerm);
                option.closest('li').hidden = !isVisible;
                visibleCount += isVisible ? 1 : 0;
            });

            emptyState.classList.toggle('is-visible', visibleCount === 0);
        };

        const closeThemeDropdown = (dropdown, button) => {
            dropdown.classList.remove('is-open');
            button.setAttribute('aria-expanded', 'false');
        };

        const openThemeDropdown = (dropdown, button, searchField, options, emptyState) => {
            if (button.disabled) {
                return;
            }

            const isOpen = dropdown.classList.toggle('is-open');
            button.setAttribute('aria-expanded', String(isOpen));

            if (isOpen) {
                searchField.value = '';
                filterDropdownOptions(options, searchField, emptyState);
                searchField.focus();
            }
        };

        const renderDropdownOptions = (menu, emptyState, values, optionClass) => {
            menu.querySelectorAll(`.${optionClass}`).forEach((option) => option.closest('li').remove());

            values.forEach((value) => {
                const item = document.createElement('li');
                const button = document.createElement('button');

                button.type = 'button';
                button.className = `theme-dropdown-option ${optionClass}`;
                button.dataset.value = value;
                button.setAttribute('role', 'option');
                button.setAttribute('aria-selected', 'false');
                button.textContent = value;
                item.appendChild(button);
                menu.insertBefore(item, emptyState);
            });

            return Array.from(menu.querySelectorAll(`.${optionClass}`));
        };

        const resetCityDropdown = () => {
            citySelect.value = '';
            cityText.textContent = 'Select state first';
            cityButton.disabled = true;
            closeThemeDropdown(cityDropdown, cityButton);
            renderDropdownOptions(cityMenu, cityEmpty, [], 'city-dropdown-option');
            cityEmpty.classList.remove('is-visible');
        };

        let stateOptions = renderDropdownOptions(stateMenu, stateEmpty, Object.keys(stateDistricts).sort(), 'state-dropdown-option');
        let cityOptions = [];

        const bindCityOptions = () => {
            cityOptions.forEach((cityOption) => {
                cityOption.addEventListener('click', () => {
                    cityOptions.forEach((item) => {
                        item.classList.remove('is-selected');
                        item.setAttribute('aria-selected', 'false');
                    });

                    cityOption.classList.add('is-selected');
                    cityOption.setAttribute('aria-selected', 'true');
                    cityText.textContent = cityOption.textContent;
                    citySelect.value = cityOption.dataset.value;
                    validateRequiredField(citySelect);
                    closeThemeDropdown(cityDropdown, cityButton);
                    cityButton.focus();
                });
            });
        };

        stateOptions.forEach((option) => {
            option.addEventListener('click', () => {
                stateOptions.forEach((item) => {
                    item.classList.remove('is-selected');
                    item.setAttribute('aria-selected', 'false');
                });

                option.classList.add('is-selected');
                option.setAttribute('aria-selected', 'true');
                stateText.textContent = option.textContent;
                stateSelect.value = option.dataset.value;
                validateRequiredField(stateSelect);
                closeThemeDropdown(stateDropdown, stateButton);

                const districts = stateDistricts[stateSelect.value] || [];
                cityOptions = renderDropdownOptions(cityMenu, cityEmpty, districts, 'city-dropdown-option');
                citySelect.value = '';
                cityText.textContent = districts.length ? 'Select city / district' : 'Select state first';
                cityButton.disabled = districts.length === 0;
                cityEmpty.classList.remove('is-visible');

                bindCityOptions();

                cityButton.focus();
            });
        });

        if (initialState && stateDistricts[initialState]) {
            const selectedStateOption = stateOptions.find((option) => option.dataset.value === initialState);
            const districts = stateDistricts[initialState] || [];

            if (selectedStateOption) {
                selectedStateOption.classList.add('is-selected');
                selectedStateOption.setAttribute('aria-selected', 'true');
            }

            stateText.textContent = initialState;
            cityOptions = renderDropdownOptions(cityMenu, cityEmpty, districts, 'city-dropdown-option');
            cityButton.disabled = districts.length === 0;
            cityText.textContent = initialCity || (districts.length ? 'Select city / district' : 'Select state first');
            bindCityOptions();

            if (initialCity) {
                const selectedCityOption = cityOptions.find((option) => option.dataset.value === initialCity);

                if (selectedCityOption) {
                    selectedCityOption.classList.add('is-selected');
                    selectedCityOption.setAttribute('aria-selected', 'true');
                }
            }
        }

        accountMasterForm.addEventListener('reset', () => {
            setTimeout(() => {
                limitFields.forEach((field) => field.dispatchEvent(new Event('input')));
                accountMasterForm.querySelectorAll('.is-invalid').forEach((field) => field.classList.remove('is-invalid'));
                accountMasterForm.querySelectorAll('.field-error').forEach((error) => error.remove());
                toggleStateCityRequired();
                stateSelect.value = '';
                stateText.textContent = 'Select state';
                stateOptions.forEach((option) => {
                    option.classList.remove('is-selected');
                    option.setAttribute('aria-selected', 'false');
                    option.closest('li').hidden = false;
                });
                stateSearch.value = '';
                stateEmpty.classList.remove('is-visible');
                closeThemeDropdown(stateDropdown, stateButton);
                resetCityDropdown();
            }, 0);
        });

        accountMasterForm.addEventListener('submit', (event) => {
            if (!validateRequiredFields()) {
                event.preventDefault();
                return;
            }

            saveButton.disabled = true;
            saveButton.textContent = 'Saving...';
        });

        balanceTypeButton.addEventListener('click', () => {
            const isOpen = balanceTypeSelect.classList.toggle('is-open');
            balanceTypeButton.setAttribute('aria-expanded', String(isOpen));
        });

        balanceTypeOptions.forEach((option) => {
            option.addEventListener('click', () => {
                balanceTypeOptions.forEach((item) => {
                    item.classList.remove('is-selected');
                    item.setAttribute('aria-selected', 'false');
                });

                option.classList.add('is-selected');
                option.setAttribute('aria-selected', 'true');
                balanceTypeText.textContent = option.textContent;
                balanceTypeValue.value = option.dataset.value;
                balanceTypeSelect.classList.remove('is-open');
                balanceTypeButton.setAttribute('aria-expanded', 'false');
                balanceTypeButton.focus();
            });
        });

        document.addEventListener('click', (event) => {
            if (!balanceTypeSelect.contains(event.target)) {
                balanceTypeSelect.classList.remove('is-open');
                balanceTypeButton.setAttribute('aria-expanded', 'false');
            }

            if (!underGroupDropdown.contains(event.target)) {
                underGroupDropdown.classList.remove('is-open');
                underGroupButton.setAttribute('aria-expanded', 'false');
            }

            if (!stateDropdown.contains(event.target)) {
                closeThemeDropdown(stateDropdown, stateButton);
            }

            if (!cityDropdown.contains(event.target)) {
                closeThemeDropdown(cityDropdown, cityButton);
            }
        });

        stateButton.addEventListener('click', () => {
            openThemeDropdown(stateDropdown, stateButton, stateSearch, stateOptions, stateEmpty);
        });

        cityButton.addEventListener('click', () => {
            openThemeDropdown(cityDropdown, cityButton, citySearch, cityOptions, cityEmpty);
        });

        stateSearch.addEventListener('input', () => {
            filterDropdownOptions(stateOptions, stateSearch, stateEmpty);
        });

        citySearch.addEventListener('input', () => {
            filterDropdownOptions(cityOptions, citySearch, cityEmpty);
        });

        stateSearch.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeThemeDropdown(stateDropdown, stateButton);
                stateButton.focus();
            }
        });

        citySearch.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeThemeDropdown(cityDropdown, cityButton);
                cityButton.focus();
            }
        });

        const filterUnderGroups = () => {
            const searchTerm = underGroupSearch.value.trim().toLowerCase();
            let visibleCount = 0;

            underGroupOptions.forEach((option) => {
                const isVisible = option.textContent.trim().toLowerCase().includes(searchTerm);
                option.closest('li').hidden = !isVisible;
                visibleCount += isVisible ? 1 : 0;
            });

            underGroupEmpty.classList.toggle('is-visible', visibleCount === 0);
        };

        underGroupButton.addEventListener('click', () => {
            const isOpen = underGroupDropdown.classList.toggle('is-open');
            underGroupButton.setAttribute('aria-expanded', String(isOpen));

            if (isOpen) {
                underGroupSearch.value = '';
                filterUnderGroups();
                underGroupSearch.focus();
            }
        });

        underGroupSearch.addEventListener('input', filterUnderGroups);

        underGroupSearch.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                underGroupDropdown.classList.remove('is-open');
                underGroupButton.setAttribute('aria-expanded', 'false');
                underGroupButton.focus();
            }
        });

        underGroupOptions.forEach((option) => {
            option.addEventListener('click', () => {
                underGroupOptions.forEach((item) => {
                    item.classList.remove('is-selected');
                    item.setAttribute('aria-selected', 'false');
                });

                option.classList.add('is-selected');
                option.setAttribute('aria-selected', 'true');
                underGroupText.textContent = option.textContent;
                underGroupValue.value = option.dataset.value;
                toggleStateCityRequired();
                validateRequiredField(underGroupValue);
                underGroupDropdown.classList.remove('is-open');
                underGroupButton.setAttribute('aria-expanded', 'false');
                underGroupButton.focus();
            });
        });

        [document.getElementById('particulars'), underGroupValue, stateSelect, citySelect].forEach((field) => {
            field.addEventListener('input', () => validateRequiredField(field));
        });

        toggleStateCityRequired();
    </script>
</body>
</html>
