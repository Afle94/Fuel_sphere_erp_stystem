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

    .app-shell-with-sidebar>main {
        width: 100%;
        min-width: 0;
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

    .app-shell-with-sidebar .menu-section+.menu-section {
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

    .account-master-workspace .menu-section+.menu-section {
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

    @media (max-width: 1024px) {

        .app-shell-with-sidebar {
            grid-template-columns: 260px minmax(0, 1fr);
        }

        .app-shell-with-sidebar .sidebar {
            width: 260px;
        }
    }

    @media (max-width: 940px) {

        .app-shell-with-sidebar {
            grid-template-columns: 1fr;
        }

        .app-shell-with-sidebar .sidebar {
            position: sticky;
            top: 0;
            width: 100%;
            max-height: 60vh;
            overflow-y: auto;
            border-radius: 0;
        }

        .app-shell-with-sidebar .side-menu {
            max-height: none;
            padding-bottom: 24px;
        }
    }

    @media (max-width: 640px) {

        .sidebar-brand h1 {
            font-size: 18px;
        }

        .sidebar-brand p {
            font-size: 12px;
        }

        .menu-link {
            font-size: 13px;
            min-height: 38px;
        }

        .menu-heading {
            font-size: 12px;
            min-height: 40px;
        }

        .menu-count {
            font-size: 11px;
        }

        .menu-toggle {
            width: 36px;
            height: 36px;
        }
    }

    @media (max-width: 420px) {

        .sidebar-brand {
            padding: 12px;
        }

        .menu-link {
            font-size: 12px;
            padding: 0 10px;
        }

        .menu-heading {
            padding: 0 10px;
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
                    <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
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
    'Cash Receipt',
    'Cash Payment',
    'Cheque Receipt',
    'Cheque Payment',
    'Purchase',
    'Generate Bill',
    'Dip Entry',
    'Card Sales',
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
    'Stock Report',
    'Advance Stock Register',
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
                            <path d="m9 6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
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

                        $heading=== 'Registers' && $option === 'Day Fuel Sale' => route('dayfuelregisterfilter'),
                        $heading=== 'Registers' && $option === 'Credit Sales Register' => route('creditsalesregisterfilter'),
                        $heading==='Registers' && $option === 'Cash Sales Register' => route('cashsalesregisterfilter'),
                        $heading==='Registers' && $option === 'Cash Receipt Register' => route('RegisterCashReceiptFilter'),
                        $heading==='Registers' && $option === 'Cheque Receipt Register' => route('RegisterChequeReceiptFilter'),
                        $heading==='Registers' && $option === 'Cash Payment Register' => route('RegisterCashPaymentFilter'),
                        $heading==='Registers' && $option === 'Cheque Payment Register' => route('RegisterChequePaymentFilter'),
                        $heading==='Registers' && $option === 'Purchase Register' => route('RegisterPurchaseFilter'),
                        $heading==='Registers' && $option === 'Day Book Register' => route('RegisterDayBook'),
                        $heading==='Registers' && $option === 'Product Wise Sales Register' => route('RegisterProductWiseSales'),


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
</script>
@endonce