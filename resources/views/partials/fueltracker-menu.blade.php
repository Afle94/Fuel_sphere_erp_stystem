@once
    <style>
        .app-shell-with-sidebar {
            width: min(calc(100vw - 36px), 1180px);
            min-height: calc(100vh - 108px);
            display: grid;
            grid-template-columns: 300px minmax(0, 1fr);
            gap: 0;
            overflow: hidden;
            margin: 24px 18px 32px;
            padding: 0;
            border: 1px solid rgba(220, 227, 238, 0.86);
            border-radius: 24px;
            background: #ffffff;
            box-shadow: 0 24px 70px rgba(23, 32, 51, 0.14);
            transition: grid-template-columns 0.2s ease, padding-left 0.2s ease;
        }

        .app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
            gap: 0;
        }

        .app-shell-with-sidebar > main {
            width: 100%;
            min-width: 0;
        }

        :where(.table-wrap, .matrix-table-wrap, .detail-table-wrap, .bill-table-wrap, .modern-table-wrap, .table-responsive, .invoice-table-wrap, .items-table-wrap, .format-table-wrap) {
            display: block;
            width: 100%;
            max-width: 100%;
            max-height: max(260px, calc(100vh - 360px));
            overflow: auto !important;
            scrollbar-gutter: auto;
            -webkit-overflow-scrolling: touch;
        }

        :where(.table-wrap, .matrix-table-wrap, .detail-table-wrap, .bill-table-wrap, .modern-table-wrap, .table-responsive, .invoice-table-wrap, .items-table-wrap, .format-table-wrap) > table {
            width: max-content;
            min-width: 100%;
        }

        :where(.table-wrap, .matrix-table-wrap, .detail-table-wrap, .bill-table-wrap, .modern-table-wrap, .table-responsive, .invoice-table-wrap, .items-table-wrap, .format-table-wrap) th,
        :where(.table-wrap, .matrix-table-wrap, .detail-table-wrap, .bill-table-wrap, .modern-table-wrap, .table-responsive, .invoice-table-wrap, .items-table-wrap, .format-table-wrap) td {
            white-space: nowrap;
        }

        :where(.table-wrap, .matrix-table-wrap, .detail-table-wrap, .bill-table-wrap, .modern-table-wrap, .table-responsive, .invoice-table-wrap, .items-table-wrap, .format-table-wrap)::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        :where(.table-wrap, .matrix-table-wrap, .detail-table-wrap, .bill-table-wrap, .modern-table-wrap, .table-responsive, .invoice-table-wrap, .items-table-wrap, .format-table-wrap)::-webkit-scrollbar-track {
            border-radius: 999px;
            background: rgba(220, 227, 238, 0.78);
        }

        :where(.table-wrap, .matrix-table-wrap, .detail-table-wrap, .bill-table-wrap, .modern-table-wrap, .table-responsive, .invoice-table-wrap, .items-table-wrap, .format-table-wrap)::-webkit-scrollbar-thumb {
            border: 2px solid rgba(220, 227, 238, 0.78);
            border-radius: 999px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        :where([class*="-register-page"], [class*="-ledger-page"], [class*="-report-page"], [class*="-list-page"]) .list-panel {
            overflow: hidden !important;
            max-width: 100% !important;
        }

        :where([class*="-register-page"], [class*="-ledger-page"], [class*="-report-page"], [class*="-list-page"]) :where(.table-wrap, .matrix-table-wrap, .detail-table-wrap, .bill-table-wrap, .modern-table-wrap, .table-responsive, .invoice-table-wrap, .items-table-wrap, .format-table-wrap) {
            width: 100% !important;
            max-width: 100% !important;
            max-height: clamp(260px, 42vh, 520px) !important;
            overflow: auto !important;
            scrollbar-gutter: auto !important;
            overscroll-behavior: contain;
        }

        :where([class*="-register-page"], [class*="-ledger-page"], [class*="-report-page"], [class*="-list-page"]) :where(.table-wrap, .matrix-table-wrap, .detail-table-wrap, .bill-table-wrap, .modern-table-wrap, .table-responsive, .invoice-table-wrap, .items-table-wrap, .format-table-wrap) > table {
            width: max-content !important;
            min-width: 100% !important;
        }

        :where([class*="-register-page"], [class*="-ledger-page"], [class*="-report-page"], [class*="-list-page"]) :where(.list-shell, .toolbar, .search-form, .toolbar-actions, .pagination-bar) {
            max-width: 100% !important;
        }

        @media (min-width: 761px) {
            body:has(.app-shell-with-sidebar[class*="-register-workspace"]),
            body:has(.app-shell-with-sidebar[class*="-ledger-workspace"]),
            body:has(.app-shell-with-sidebar[class*="-report-workspace"]),
            body:has(.app-shell-with-sidebar[class*="-list-workspace"]) {
                height: 100vh;
                overflow: hidden !important;
            }

            body:has(.app-shell-with-sidebar[class*="-register-workspace"]) .site-header,
            body:has(.app-shell-with-sidebar[class*="-ledger-workspace"]) .site-header,
            body:has(.app-shell-with-sidebar[class*="-report-workspace"]) .site-header,
            body:has(.app-shell-with-sidebar[class*="-list-workspace"]) .site-header {
                position: sticky;
                top: 0;
            }

            body:has(.app-shell-with-sidebar[class*="-register-workspace"]) .app-shell-with-sidebar,
            body:has(.app-shell-with-sidebar[class*="-ledger-workspace"]) .app-shell-with-sidebar,
            body:has(.app-shell-with-sidebar[class*="-report-workspace"]) .app-shell-with-sidebar,
            body:has(.app-shell-with-sidebar[class*="-list-workspace"]) .app-shell-with-sidebar {
                height: calc(100vh - 88px) !important;
                min-height: 0 !important;
                overflow: hidden !important;
            }

            body:has(.app-shell-with-sidebar[class*="-register-workspace"]) .app-shell-with-sidebar:not(.menu-collapsed),
            body:has(.app-shell-with-sidebar[class*="-ledger-workspace"]) .app-shell-with-sidebar:not(.menu-collapsed),
            body:has(.app-shell-with-sidebar[class*="-report-workspace"]) .app-shell-with-sidebar:not(.menu-collapsed),
            body:has(.app-shell-with-sidebar[class*="-list-workspace"]) .app-shell-with-sidebar:not(.menu-collapsed) {
                display: grid !important;
                grid-template-columns: minmax(220px, 300px) minmax(0, 1fr) !important;
                width: calc(100vw - 24px) !important;
                margin: 12px !important;
                border-radius: 12px !important;
            }

            body:has(.app-shell-with-sidebar[class*="-register-workspace"]) .app-shell-with-sidebar.menu-collapsed,
            body:has(.app-shell-with-sidebar[class*="-ledger-workspace"]) .app-shell-with-sidebar.menu-collapsed,
            body:has(.app-shell-with-sidebar[class*="-report-workspace"]) .app-shell-with-sidebar.menu-collapsed,
            body:has(.app-shell-with-sidebar[class*="-list-workspace"]) .app-shell-with-sidebar.menu-collapsed {
                display: grid !important;
                grid-template-columns: 64px minmax(0, 1fr) !important;
                width: calc(100vw - 24px) !important;
                margin: 12px !important;
                border-radius: 12px !important;
            }

            body:has(.app-shell-with-sidebar[class*="-register-workspace"]) .app-shell-with-sidebar > main,
            body:has(.app-shell-with-sidebar[class*="-ledger-workspace"]) .app-shell-with-sidebar > main,
            body:has(.app-shell-with-sidebar[class*="-report-workspace"]) .app-shell-with-sidebar > main,
            body:has(.app-shell-with-sidebar[class*="-list-workspace"]) .app-shell-with-sidebar > main {
                height: 100%;
                min-height: 0;
                overflow-y: auto;
                overflow-x: hidden;
            }
        }

        .app-shell-with-sidebar .sidebar {
            position: sticky;
            top: 0;
            align-self: stretch;
            width: 300px;
            height: auto;
            max-height: none;
            overflow: hidden;
            border: 0;
            border-radius: 0;
            background: rgba(255, 255, 255, 0.72);
            box-shadow: none;
            transition: width 0.2s ease, border-radius 0.2s ease;
        }

        .app-shell-with-sidebar.menu-collapsed .sidebar {
            width: 64px;
        }

        .app-shell-with-sidebar .sidebar-brand {
            min-height: 164px;
            padding: 30px 24px;
            color: #ffffff;
            background:
                linear-gradient(145deg, rgba(8, 47, 73, 0.98), rgba(15, 118, 110, 0.96)),
                url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.12' stroke-width='2'%3E%3Cpath d='M22 116c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 78c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 40c20-18 40-18 60 0s40 18 60 0'/%3E%3C/g%3E%3C/svg%3E");
        }

        .app-shell-with-sidebar.menu-collapsed .sidebar-brand {
            min-height: 72px;
            padding: 14px 10px;
        }

        .app-shell-with-sidebar .sidebar-brand-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .app-shell-with-sidebar .sidebar-brand h1 {
            margin: 0;
            font-size: 24px;
            line-height: 1.15;
            letter-spacing: 0;
            white-space: nowrap;
        }

        .app-shell-with-sidebar .sidebar-brand p {
            margin: 8px 0 0;
            color: rgba(255, 255, 255, 0.76);
            font-size: 14px;
            line-height: 1.6;
        }

        .app-shell-with-sidebar .menu-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 14px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .app-shell-with-sidebar .menu-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .app-shell-with-sidebar .menu-toggle:focus {
            outline: 4px solid rgba(255, 255, 255, 0.22);
            outline-offset: 3px;
        }

        .app-shell-with-sidebar.menu-collapsed .sidebar-brand h1,
        .app-shell-with-sidebar.menu-collapsed .sidebar-brand p,
        .app-shell-with-sidebar.menu-collapsed .side-menu {
            display: none;
        }

        .app-shell-with-sidebar.menu-collapsed .sidebar-brand-heading {
            justify-content: center;
        }

        .app-shell-with-sidebar .side-menu {
            max-height: calc(100vh - 278px);
            overflow-y: auto;
            margin: 0;
            padding: 18px;
            list-style: none;
            scrollbar-width: thin;
            scrollbar-color: rgba(15, 118, 110, 0.46) rgba(220, 227, 238, 0.72);
        }

        .app-shell-with-sidebar .side-menu::-webkit-scrollbar {
            width: 8px;
        }

        .app-shell-with-sidebar .side-menu::-webkit-scrollbar-track {
            border-radius: 999px;
            background: rgba(220, 227, 238, 0.72);
        }

        .app-shell-with-sidebar .side-menu::-webkit-scrollbar-thumb {
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.46);
        }

        .app-shell-with-sidebar .menu-section + .menu-section {
            margin-top: 10px;
        }

        .app-shell-with-sidebar .menu-section details {
            display: block;
        }

        .app-shell-with-sidebar .menu-section summary {
            list-style: none;
        }

        .app-shell-with-sidebar .menu-section summary::-webkit-details-marker {
            display: none;
        }

        .app-shell-with-sidebar .menu-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            min-height: 44px;
            padding: 0 14px;
            border: 0;
            border-radius: 14px;
            color: #ffffff;
            background: var(--primary);
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .app-shell-with-sidebar .menu-heading:focus {
            outline: 4px solid rgba(15, 118, 110, 0.18);
            outline-offset: 3px;
        }

        .app-shell-with-sidebar .menu-heading-main {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .app-shell-with-sidebar .menu-arrow {
            width: 16px;
            height: 16px;
            transition: transform 0.2s ease;
        }

        .app-shell-with-sidebar .menu-section details[open] .menu-arrow {
            transform: rotate(90deg);
        }

        .app-shell-with-sidebar .menu-options {
            overflow: hidden;
            margin: 0;
            padding: 0;
            list-style: none;
            max-height: 0;
            opacity: 0;
            transform: translateY(-4px);
            transition: max-height 0.3s ease, opacity 0.22s ease, transform 0.3s ease;
            will-change: max-height, opacity, transform;
        }

        .app-shell-with-sidebar .menu-section details[open] .menu-options {
            opacity: 1;
            transform: translateY(0);
        }

        .app-shell-with-sidebar .menu-count {
            min-width: 28px;
            padding: 4px 8px;
            border-radius: 999px;
            color: var(--primary-dark);
            background: #ffffff;
            font-size: 12px;
            text-align: center;
        }

        .app-shell-with-sidebar .menu-link {
            display: flex;
            align-items: center;
            gap: 8px;
            min-height: 42px;
            margin-top: 8px;
            padding: 0 14px;
            border: 1px solid var(--line);
            border-radius: 14px;
            color: var(--ink);
            background: #fbfcfe;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: border-color 0.2s ease, color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .app-shell-with-sidebar .menu-link:hover,
        .app-shell-with-sidebar .menu-link:focus {
            border-color: rgba(15, 118, 110, 0.45);
            color: var(--primary-dark);
            box-shadow: 0 10px 22px rgba(15, 118, 110, 0.1);
            outline: none;
            transform: translateY(-1px);
        }

        .app-shell-with-sidebar .menu-link svg {
            flex: 0 0 auto;
            color: var(--primary);
        }

        @media (max-width: 940px) {
            .app-shell-with-sidebar {
                grid-template-columns: 1fr;
                margin: 18px;
                border-radius: 20px;
            }

            .app-shell-with-sidebar.menu-collapsed {
                grid-template-columns: 1fr;
            }

            .app-shell-with-sidebar .sidebar {
                position: sticky;
                top: 72px;
                max-height: calc(100vh - 150px);
                width: 100%;
                border-radius: 0;
            }

            .app-shell-with-sidebar .side-menu {
                max-height: calc(100vh - 254px);
            }
        }

        @media (max-width: 640px) {
            .app-shell-with-sidebar .menu-toggle {
                width: 38px;
                height: 38px;
            }
        }

        @media (max-width: 760px) {
            body:has(.app-shell-with-sidebar) .site-header-inner {
                min-height: 48px !important;
                gap: 6px !important;
                padding: 6px 10px !important;
            }

            body:has(.app-shell-with-sidebar) .site-logo {
                font-size: 18px !important;
                gap: 8px !important;
            }

            body:has(.app-shell-with-sidebar) .site-logo-icon {
                width: 34px !important;
                height: 34px !important;
            }

            body:has(.app-shell-with-sidebar) .header-title {
                font-size: 16px !important;
                line-height: 1.2 !important;
                white-space: normal !important;
            }

            body:has(.app-shell-with-sidebar) .back-link,
            body:has(.app-shell-with-sidebar) .logout-btn {
                min-height: 28px !important;
                padding: 0 10px !important;
                font-size: 12px !important;
            }

            .app-shell-with-sidebar {
                margin: 0 !important;
                border-radius: 0 !important;
            }

            .app-shell-with-sidebar .sidebar {
                position: relative !important;
                top: auto !important;
                max-height: none !important;
                width: 100% !important;
            }

            .app-shell-with-sidebar .sidebar-brand {
                min-height: 96px !important;
                padding: 16px 18px !important;
            }

            .app-shell-with-sidebar .sidebar-brand h1 {
                font-size: 22px !important;
                white-space: normal !important;
            }

            .app-shell-with-sidebar .sidebar-brand p {
                display: none !important;
            }

            .app-shell-with-sidebar .side-menu {
                max-height: 320px !important;
                padding: 12px !important;
            }
        }

        .account-master-workspace .sidebar {
            position: fixed;
            top: 72px;
            left: 18px;
            z-index: 15;
            width: 300px;
            max-height: calc(100vh - 108px);
            overflow: hidden;
            border: 1px solid rgba(220, 227, 238, 0.9);
            border-radius: 24px;
            background: var(--panel);
            box-shadow: var(--shadow);
            transition: width 0.2s ease;
        }

        .account-master-workspace.menu-collapsed .sidebar {
            width: 64px;
        }

        .account-master-workspace .sidebar-brand {
            padding: 22px;
            color: #ffffff;
            background:
                linear-gradient(145deg, rgba(8, 47, 73, 0.98), rgba(15, 118, 110, 0.96)),
                url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.12' stroke-width='2'%3E%3Cpath d='M22 116c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 78c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 40c20-18 40-18 60 0s40 18 60 0'/%3E%3C/g%3E%3C/svg%3E");
        }

        .account-master-workspace.menu-collapsed .sidebar-brand {
            min-height: 72px;
            padding: 14px 10px;
        }

        .account-master-workspace .sidebar-brand-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .account-master-workspace .sidebar-brand h1 {
            margin: 0;
            font-size: 25px;
            line-height: 1.15;
            letter-spacing: 0;
            white-space: nowrap;
        }

        .account-master-workspace .sidebar-brand p {
            margin: 8px 0 0;
            color: rgba(255, 255, 255, 0.76);
            font-size: 15px;
            line-height: 1.6;
        }

        .account-master-workspace .menu-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 14px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .account-master-workspace .menu-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .account-master-workspace.menu-collapsed .sidebar-brand h1,
        .account-master-workspace.menu-collapsed .sidebar-brand p,
        .account-master-workspace.menu-collapsed .side-menu {
            display: none;
        }

        .account-master-workspace.menu-collapsed .sidebar-brand-heading {
            justify-content: center;
        }

        .account-master-workspace .side-menu {
            max-height: calc(100vh - 304px);
            overflow-y: auto;
            margin: 0;
            padding: 16px 14px 18px;
            list-style: none;
            scrollbar-width: thin;
            scrollbar-color: rgba(15, 118, 110, 0.46) rgba(220, 227, 238, 0.72);
        }

        .account-master-workspace .side-menu::-webkit-scrollbar {
            width: 8px;
        }

        .account-master-workspace .side-menu::-webkit-scrollbar-track {
            border-radius: 999px;
            background: rgba(220, 227, 238, 0.72);
        }

        .account-master-workspace .side-menu::-webkit-scrollbar-thumb {
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.46);
        }

        .account-master-workspace .menu-section + .menu-section {
            margin-top: 10px;
        }

        .account-master-workspace .menu-section summary {
            list-style: none;
        }

        .account-master-workspace .menu-section summary::-webkit-details-marker {
            display: none;
        }

        .account-master-workspace .menu-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            min-height: 42px;
            padding: 0 12px;
            border: 0;
            border-radius: 12px;
            color: #ffffff;
            background: var(--primary);
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .account-master-workspace .menu-heading-main {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .account-master-workspace .menu-arrow {
            transition: transform 0.2s ease;
        }

        .account-master-workspace .menu-section details[open] .menu-arrow {
            transform: rotate(90deg);
        }

        .account-master-workspace .menu-options {
            overflow: hidden;
            margin: 0;
            padding: 0;
            list-style: none;
            max-height: 0;
            opacity: 0;
            transform: translateY(-4px);
            transition: max-height 0.3s ease, opacity 0.22s ease, transform 0.3s ease;
            will-change: max-height, opacity, transform;
        }

        .account-master-workspace .menu-section details[open] .menu-options {
            opacity: 1;
            transform: translateY(0);
        }

        @media (prefers-reduced-motion: reduce) {
            .app-shell-with-sidebar .menu-options,
            .account-master-workspace .menu-options,
            .app-shell-with-sidebar .menu-arrow,
            .account-master-workspace .menu-arrow {
                transition: none;
            }
        }

        .account-master-workspace .menu-count {
            min-width: 28px;
            padding: 4px 8px;
            border-radius: 999px;
            color: var(--primary-dark);
            background: #ffffff;
            font-size: 12px;
            text-align: center;
        }

        .account-master-workspace .menu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 40px;
            margin-top: 8px;
            padding: 0 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            color: var(--ink);
            background: #fbfcfe;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: border-color 0.2s ease, color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .account-master-workspace .menu-link:hover,
        .account-master-workspace .menu-link:focus {
            border-color: rgba(15, 118, 110, 0.45);
            color: var(--primary-dark);
            box-shadow: 0 10px 22px rgba(15, 118, 110, 0.1);
            outline: none;
            transform: translateY(-1px);
        }

        @media (min-width: 941px) {
            .account-master-workspace {
                --fueltracker-sidebar-width: 300px;
                --fueltracker-content-gap: 36px;
            }

            .account-master-workspace.menu-collapsed {
                --fueltracker-sidebar-width: 64px;
                --fueltracker-content-gap: 24px;
            }

            .account-master-workspace > main {
                width: auto !important;
                max-width: calc(100vw - var(--fueltracker-sidebar-width) - var(--fueltracker-content-gap) - 36px) !important;
                margin-left: calc(var(--fueltracker-sidebar-width) + var(--fueltracker-content-gap)) !important;
                margin-right: 18px !important;
                transform: none !important;
            }
        }

        @media (max-width: 940px) {
            .account-master-workspace .sidebar {
                position: sticky;
                top: 64px;
                left: auto;
                width: calc(100% - 24px);
                margin: 12px auto 0;
            }

            .account-master-workspace.menu-collapsed .sidebar {
                width: 64px;
                margin-left: 12px;
            }
        }
    </style>
@endonce

<aside class="sidebar" id="dashboardMenu" aria-label="Dashboard menu">
    <div class="sidebar-brand">
        <div class="sidebar-brand-heading">
            <h1>FuelTracker Menu</h1>
            <button type="button" class="menu-toggle" id="menuToggle" aria-label="Hide menu" aria-controls="dashboardMenu" aria-expanded="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        <p>Click any heading to show or hide its options.</p>
    </div>

    @php
        $currentYear = now()->year;
        $financialYearStart = now()->month >= 4 ? $currentYear : $currentYear - 1;
        $financialYear = $financialYearStart . ' - ' . ($financialYearStart + 1);

        $menuSections = [
            'Transactions' => [
                'Day Fuel Sale',
                'Credit Sales',
                'Cash Sales',
                'Card Sales',
                'Cash Receipt',
                'Cash Payment',
                'Cheque Receipt',
                'Cheque Payment',
                'Purchase',
                'Generate Bill',
                'Dip Chart',
                
            ],
            'Masters' => [
                'Nozzle Names Maintenance',
                'List Of Nozzles',
                'Create Accounts Master',
                'List Of Accounts',
                'Item / Date Wise Rates',
                'List Of Item Date Wise Rates',
                'Create Product Master',
                'List Of Products',
                'Create Vehicle Master',
                'List Of Vehicles',
                'Create Category Master',
                'List Of Category',
            ],
            'Reports' => [
                'Accounts Ledger',
                'Outstanding (Debtors)',
                'Generate Bill Report',
                'Stock Report',
                'Purchase Item List',
                'Advance Stock Register',
            ],
            'Static Values' => [
                'Density Chart',
                'Dip Parameter',
            ],
            'Registers' => [
                'Day Fuel Sale',
                'Credit Sales Register',
                'Cash Sales Register',
                'Cash Receipt Register',
                'Cheque Receipt Register',
                'Cash Payment Register',
                'Cheque Payment Register',
                'Purchase Register',
                'Day Book Register',
                'Product Wise Sales Register',
            ],
        ];
    @endphp

    <ul class="side-menu">
        @foreach ($menuSections as $heading => $options)
            <li class="menu-section">
                <details open>
                    <summary class="menu-heading">
                        <span class="menu-heading-main">
                            <svg class="menu-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ $heading }}
                        </span>
                        <span class="menu-count">{{ count($options) }}</span>
                    </summary>
                    <ul class="menu-options">
                        @foreach ($options as $option)
                            <li>
                                @php
                                    $optionUrl = match (true) {
                                        $heading === 'Transactions' && $option === 'Day Fuel Sale' => route('day-fuel.list'),
                                        $heading === 'Transactions' && $option === 'Credit Sales' => route('creditsales'),
                                        $heading === 'Transactions' && $option === 'Cash Sales' => route('cashsales'),
                                        $heading === 'Transactions' && $option === 'Cash Receipt' => route('cashreceipt'),
                                        $heading === 'Transactions' && $option === 'Cash Payment' => route('cashpayment'),
                                        $heading === 'Transactions' && $option === 'Cheque Receipt' => route('chequereceipt'),
                                        $heading === 'Transactions' && $option === 'Cheque Payment' => route('chequepayment'),
                                        $heading === 'Transactions' && $option === 'Card Sales' => route('cardsales'),
                                        $heading === 'Transactions' && $option === 'Purchase' => route('purchase'),
                                        $heading === 'Transactions' && $option === 'Generate Bill' => route('generate-bill.index'),
                                        $heading === 'Transactions' && $option === 'Dip Chart' => route('daily-dip.index'),
                                        $heading === 'Reports' && $option === 'Generate Bill Report' => route('generate-bill.list'),
                                        $heading === 'Reports' && $option === 'Accounts Ledger' => route('accounts.ledger'),
                                        $heading === 'Reports' && $option === 'Outstanding (Debtors)' => route('outstanding.debtors'),
                                        $heading === 'Reports' && $option === 'Stock Report' => route('stock-report.index'),
                                        $heading === 'Reports' && $option === 'Purchase Item List' => route('purchase-item-list.index'),
                                        $heading === 'Reports' && $option === 'Advance Stock Register' => route('advance-stock-register.index'),

                                            $heading=== 'Registers' && $option === 'Day Fuel Sale' => route('dayfuelregisterfilter'),
                                            $heading=== 'Registers' && $option === 'Credit Sales Register' => route('creditsalesregisterfilter'),
                                            $heading=== 'Registers' && $option === 'Cash Sales Register' => route('cashsalesregisterfilter'),
                                            $heading=== 'Registers' && $option === 'Cash Receipt Register' => route('RegisterCashReceiptFilter'),
                                            $heading=== 'Registers' && $option === 'Cheque Receipt Register' => route('RegisterChequeReceiptFilter'),
                                            $heading=== 'Registers' && $option === 'Cash Payment Register' => route('RegisterCashPaymentFilter'),
                                            $heading=== 'Registers' && $option === 'Cheque Payment Register' => route('RegisterChequePaymentFilter'),
                                            $heading=== 'Registers' && $option === 'Purchase Register' => route('RegisterPurchaseFilter'),
                                            $heading=== 'Registers' && $option === 'Day Book Register' => route('RegisterDayBook'),
                                            $heading=== 'Registers' && $option === 'Product Wise Sales Register' => route('RegisterProductWiseSales'),

                                            

                                        $option === 'Nozzle Names Maintenance' => route('nozzle'),
                                        $option === 'List Of Nozzles' => route('nozzle.list'),
                                        $option === 'Create Accounts Master' => route('accountmaster'),
                                        $option === 'List Of Accounts' => route('accounts.index'),
                                        $option === 'Item / Date Wise Rates' => route('item-date-rates'),
                                        $option === 'List Of Item Date Wise Rates' => route('item-date-rates.list'),
                                        $option === 'Create Product Master' => route('product'),
                                        $option === 'List Of Products' => route('product.list'),
                                        $option === 'Create Vehicle Master' => route('vehicle'),
                                        $option === 'List Of Vehicles' => route('vehicle.list'),
                                        $option === 'Create Category Master' => route('category'),
                                        $option === 'List Of Category' => route('category.list'),
                                        $option === 'Density Chart' => route('density.chart'),
                                        $option === 'Dip Parameter' => route('dipparameter.index'),
                                        default => '#',
                                    };
                                @endphp
                                <a href="{{ $optionUrl }}" class="menu-link" data-section="{{ $heading }}" data-activity="{{ $option }}">{{ $option }}</a>
                            </li>
                        @endforeach
                    </ul>
                </details>
            </li>
        @endforeach
    </ul>
</aside>

@once
    <script>
        document.querySelectorAll('.menu-section details').forEach((details) => {
            const summary = details.querySelector('summary');
            const options = details.querySelector('.menu-options');

            if (!summary || !options) {
                return;
            }

            const setOpenHeight = () => {
                if (details.open) {
                    options.style.maxHeight = `${options.scrollHeight}px`;
                }
            };

            setOpenHeight();
            window.addEventListener('resize', setOpenHeight);

            summary.addEventListener('click', (event) => {
                event.preventDefault();

                const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                if (details.open) {
                    options.style.maxHeight = `${options.scrollHeight}px`;

                    requestAnimationFrame(() => {
                        options.style.maxHeight = '0px';
                        details.classList.add('is-closing');
                    });

                    const closeDetails = (transitionEvent) => {
                        if (transitionEvent && transitionEvent.propertyName !== 'max-height') {
                            return;
                        }

                        details.open = false;
                        details.classList.remove('is-closing');
                        options.removeEventListener('transitionend', closeDetails);
                    };

                    if (prefersReducedMotion) {
                        closeDetails();
                    } else {
                        options.addEventListener('transitionend', closeDetails);
                    }

                    return;
                }

                details.open = true;
                options.style.maxHeight = '0px';

                requestAnimationFrame(() => {
                    options.style.maxHeight = `${options.scrollHeight}px`;
                });
            });
        });

        document.querySelectorAll('.menu-toggle').forEach((toggle) => {
            const menuShell = toggle.closest('.dashboard-page, .app-shell-with-sidebar, .account-master-workspace');

            if (!menuShell) {
                return;
            }

            toggle.addEventListener('click', () => {
                const isCollapsed = menuShell.classList.toggle('menu-collapsed');

                toggle.setAttribute('aria-expanded', String(!isCollapsed));
                toggle.setAttribute('aria-label', isCollapsed ? 'Show menu' : 'Hide menu');
            });
        });

        document.querySelectorAll('.menu-link[href="#"]').forEach((menuLink) => {
            menuLink.addEventListener('click', (event) => event.preventDefault());
        });

        const isEditableNumericField = (field) => {
            if (!(field instanceof HTMLInputElement) || field.readOnly || field.disabled) {
                return false;
            }

            return field.type === 'number'
                || field.inputMode === 'decimal'
                || field.classList.contains('number-input')
                || field.classList.contains('entry-input')
                || field.hasAttribute('data-decimal-limit')
                || field.hasAttribute('data-decimal-places');
        };

        const isZeroLikeValue = (value) => /^0+(?:\.0+)?$/.test(String(value || '').trim());
        const decimalPlacesForField = (field) => {
            if (field.dataset.decimalPlaces) {
                return Number(field.dataset.decimalPlaces) || 2;
            }

            const step = String(field.getAttribute('step') || '');
            const decimalPart = step.includes('.') ? step.split('.')[1] : '';

            return decimalPart ? decimalPart.length : 2;
        };

        const dispatchNumericUpdate = (field) => {
            field.dispatchEvent(new Event('input', { bubbles: true }));
            field.dispatchEvent(new Event('change', { bubbles: true }));
        };

        document.addEventListener('focusin', (event) => {
            const field = event.target;

            if (!isEditableNumericField(field) || !isZeroLikeValue(field.value)) {
                return;
            }

            field.dataset.zeroClearedOnFocus = '1';
            field.value = '';
            dispatchNumericUpdate(field);
        });

        document.addEventListener('blur', (event) => {
            const field = event.target;

            if (!isEditableNumericField(field) || field.value.trim() !== '') {
                return;
            }

            const places = decimalPlacesForField(field);
            field.value = places > 0 ? Number(0).toFixed(places) : '0';
            delete field.dataset.zeroClearedOnFocus;
            dispatchNumericUpdate(field);
        }, true);
    </script>
@endonce
