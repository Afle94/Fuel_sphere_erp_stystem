<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | FuelTracker</title>
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
            --shell-width: min(calc(100vw - 96px), 1210px);
            --shadow: 0 24px 70px rgba(23, 32, 51, 0.14);
            --menu-top-offset: 72px;
            --content-top-offset: 60px;
            --dashboard-workspace-width: min(100%, 1040px);
            --dashboard-workspace-max: 1040px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(15, 118, 110, 0.16), transparent 32rem),
                linear-gradient(135deg, #f8fbff 0%, var(--bg) 55%, #eef5f3 100%);
        }

        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 20;
            width: 100%;
            background:
                linear-gradient(135deg, rgba(8, 47, 73, 0.98), rgba(15, 118, 110, 0.98)),
                url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.12' stroke-width='2'%3E%3Cpath d='M22 116c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 78c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 40c20-18 40-18 60 0s40 18 60 0'/%3E%3C/g%3E%3C/svg%3E");
            box-shadow: 0 10px 30px rgba(23, 32, 51, 0.12);
        }

        .site-header-inner {
            width: 100%;
            min-height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 0 auto;
            padding: 0 18px;
            position: relative;
        }

        .site-logo {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #ffffff;
            font-size: 23px;
            font-weight: 700;
            text-decoration: none;
        }

        .site-logo-icon {
            display: grid;
            width: 25px;
            height: 25px;
            place-items: center;
            border-radius: 9px;
            color: var(--primary);
            background: #ffffff;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.18);
        }

        .menu-toggle {
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

        .menu-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .menu-toggle:focus {
            outline: 4px solid rgba(255, 255, 255, 0.22);
            outline-offset: 3px;
        }

        .header-actions {
            position: absolute;
            right: 18px;
            display: flex;
            align-items: center;
            gap: 9px;
            color: rgba(255, 255, 255, 0.82);
            font-size: 13px;
            font-weight: 700;
        }

        .company-info-link,
        .logout-btn {
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 9px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .company-info-link:hover,
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .dashboard-page {
            width: calc(100vw - 18px);
            min-height: 100vh;
            overflow: visible;
            display: grid;
            grid-template-columns: 300px minmax(0, 1fr);
            gap: clamp(28px, 4vw, 54px);
            margin: 0 0 0 18px;
            padding: 0 0 108px;
            transition: grid-template-columns 0.2s ease, padding-left 0.2s ease;
        }

        .dashboard-page.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
            gap: 28px;
            padding-left: 0;
        }

        .sidebar {
            position: sticky;
            top: var(--menu-top-offset);
            align-self: start;
            width: 300px;
            margin-top: var(--menu-top-offset);
            max-height: calc(100vh - 230px);
            overflow: hidden;
            border: 1px solid rgba(220, 227, 238, 0.9);
            border-radius: 24px;
            background: var(--panel);
            box-shadow: var(--shadow);
            transition: width 0.2s ease, border-radius 0.2s ease;
        }

        .dashboard-page.menu-collapsed .sidebar {
            width: 64px;
        }

        .dashboard-page.menu-collapsed .dashboard-hero-logo {
            max-width: var(--dashboard-workspace-max);
            margin-left: auto;
            margin-right: auto;
        }

        .dashboard-page.menu-collapsed .dashboard-top,
        .dashboard-page.menu-collapsed .activity-panel {
            width: calc(100% - 24px);
            max-width: none;
            margin-left: 0;
            margin-right: 24px;
        }

        .sidebar-brand {
            padding: 22px;
            color: #ffffff;
            background:
                linear-gradient(145deg, rgba(8, 47, 73, 0.98), rgba(15, 118, 110, 0.96)),
                url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.12' stroke-width='2'%3E%3Cpath d='M22 116c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 78c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 40c20-18 40-18 60 0s40 18 60 0'/%3E%3C/g%3E%3C/svg%3E");
        }

        .dashboard-page.menu-collapsed .sidebar-brand {
            min-height: 72px;
            padding: 14px 10px;
        }

        .sidebar-brand-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .sidebar-brand h1 {
            margin: 0;
            font-size: 25px;
            line-height: 1.15;
            letter-spacing: 0;
            white-space: nowrap;
        }

        .sidebar-brand p {
            margin: 8px 0 0;
            color: rgba(255, 255, 255, 0.76);
            font-size: 15px;
            line-height: 1.6;
        }

        .dashboard-page.menu-collapsed .sidebar-brand h1,
        .dashboard-page.menu-collapsed .sidebar-brand p,
        .dashboard-page.menu-collapsed .side-menu {
            display: none;
        }

        .dashboard-page.menu-collapsed .sidebar-brand-heading {
            justify-content: center;
        }

        .side-menu {
            max-height: calc(100vh - 354px);
            overflow-y: auto;
            margin: 0;
            padding: 16px 14px 18px;
            list-style: none;
            scrollbar-width: thin;
            scrollbar-color: rgba(15, 118, 110, 0.46) rgba(220, 227, 238, 0.72);
        }

        .side-menu::-webkit-scrollbar {
            width: 8px;
        }

        .side-menu::-webkit-scrollbar-track {
            border-radius: 999px;
            background: rgba(220, 227, 238, 0.72);
        }

        .side-menu::-webkit-scrollbar-thumb {
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.46);
        }

        .menu-section + .menu-section {
            margin-top: 10px;
        }

        .menu-section details {
            display: block;
        }

        .menu-section summary {
            list-style: none;
        }

        .menu-section summary::-webkit-details-marker {
            display: none;
        }

        .menu-heading {
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

        .menu-heading:focus {
            outline: 4px solid rgba(15, 118, 110, 0.18);
            outline-offset: 3px;
        }

        .menu-heading-main {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .menu-arrow {
            transition: transform 0.2s ease;
        }

        .menu-section details[open] .menu-arrow {
            transform: rotate(90deg);
        }

        .menu-options {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .menu-count {
            min-width: 28px;
            padding: 4px 8px;
            border-radius: 999px;
            color: var(--primary-dark);
            background: #ffffff;
            font-size: 12px;
            text-align: center;
        }

        .menu-link {
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

        .menu-link:hover,
        .menu-link:focus {
            border-color: rgba(15, 118, 110, 0.45);
            color: var(--primary-dark);
            box-shadow: 0 10px 22px rgba(15, 118, 110, 0.1);
            outline: none;
            transform: translateY(-1px);
        }

        .menu-link svg {
            flex: 0 0 auto;
            color: var(--primary);
        }

        .dashboard-space {
            width: 100%;
            min-width: 0;
            display: grid;
            align-content: start;
            gap: 16px;
            overflow: visible;
            align-self: start;
            margin-top: var(--content-top-offset);
        }

        .dashboard-hero-logo {
            width: var(--dashboard-workspace-width);
            max-width: var(--dashboard-workspace-max);
            margin: 0 auto;
            padding: 12px 22px 14px;
            border: 0;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .dashboard-hero-logo .dashboard-gateway img {
            max-height: 150px;
        }

        .dashboard-top {
            width: var(--dashboard-workspace-width);
            max-width: var(--dashboard-workspace-max);
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 250px;
            gap: 16px;
            align-items: stretch;
        }

        .welcome-panel {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 190px;
            gap: 18px;
            align-items: stretch;
            padding: 22px;
            border: 1px solid rgba(220, 227, 238, 0.86);
            border-radius: 16px;
            background: var(--panel);
            box-shadow: var(--shadow);
        }

        .activity-panel {
            width: var(--dashboard-workspace-width);
            max-width: var(--dashboard-workspace-max);
            margin: 0 auto;
            padding: 18px 20px;
            border: 1px solid rgba(220, 227, 238, 0.86);
            border-radius: 16px;
            background: var(--panel);
            box-shadow: 0 18px 48px rgba(23, 32, 51, 0.1);
        }

        .activity-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }

        .activity-header h2 {
            margin: 0;
            font-size: 24px;
            line-height: 1.25;
            letter-spacing: 0;
        }

        .activity-limit {
            flex: 0 0 auto;
            padding: 6px 10px;
            border-radius: 999px;
            color: var(--primary-dark);
            background: rgba(15, 118, 110, 0.09);
            font-size: 13px;
            font-weight: 700;
        }

        .activity-list {
            display: grid;
            gap: 10px;
            max-height: 240px;
            overflow-y: auto;
            margin: 0;
            padding: 0 4px 0 0;
            list-style: none;
            scrollbar-width: thin;
            scrollbar-color: rgba(15, 118, 110, 0.46) rgba(220, 227, 238, 0.72);
        }

        .activity-list::-webkit-scrollbar {
            width: 8px;
        }

        .activity-list::-webkit-scrollbar-track {
            border-radius: 999px;
            background: rgba(220, 227, 238, 0.72);
        }

        .activity-list::-webkit-scrollbar-thumb {
            border-radius: 999px;
            background: rgba(15, 118, 110, 0.46);
        }

        .activity-link,
        .activity-row {
            display: grid;
            grid-template-columns: 42px minmax(0, 1fr);
            gap: 12px;
            align-items: center;
            min-height: 56px;
            padding: 8px 10px;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: #fbfcfe;
        }

        .activity-link {
            color: inherit;
            text-decoration: none;
            transition: border-color 0.2s ease, color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .activity-link:hover,
        .activity-link:focus {
            border-color: rgba(15, 118, 110, 0.45);
            color: var(--primary-dark);
            box-shadow: 0 10px 22px rgba(15, 118, 110, 0.1);
            outline: none;
            transform: translateY(-1px);
        }

        .activity-icon {
            display: grid;
            width: 42px;
            height: 42px;
            place-items: center;
            border-radius: 14px;
            color: var(--primary);
            background: rgba(15, 118, 110, 0.1);
        }

        .activity-text {
            min-width: 0;
        }

        .activity-name {
            display: block;
            overflow: hidden;
            color: var(--ink);
            font-size: 15px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .activity-meta {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        .activity-empty {
            margin: 0;
            padding: 18px;
            border: 1px dashed rgba(101, 112, 137, 0.38);
            border-radius: 16px;
            color: var(--muted);
            background: #fbfcfe;
            font-size: 15px;
            line-height: 1.6;
        }

        .eyebrow {
            margin: 0 0 7px;
            color: var(--primary);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .welcome-panel h1 {
            margin: 0;
            font-size: clamp(27px, 2.3vw, 35px);
            line-height: 1.15;
            letter-spacing: 0;
        }

        .welcome-panel p {
            max-width: 470px;
            margin: 9px 0 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.55;
        }

        .session-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 118px;
            padding: 14px;
            border-radius: 10px;
            color: #ffffff;
            background:
                linear-gradient(145deg, rgba(8, 47, 73, 0.98), rgba(15, 118, 110, 0.94)),
                url("data:image/svg+xml,%3Csvg width='120' height='120' viewBox='0 0 120 120' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.12' stroke-width='2'%3E%3Ccircle cx='20' cy='20' r='18'/%3E%3Ccircle cx='88' cy='78' r='26'/%3E%3C/g%3E%3C/svg%3E");
        }

        .session-icon {
            position: relative;
            display: grid;
            width: 24px;
            height: 24px;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 7px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.16);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.24);
        }

        .session-icon::before {
            content: "";
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            height: 2px;
            border-radius: 999px;
            background: #ffffff;
        }

        .session-card span {
            display: block;
            color: rgba(255, 255, 255, 0.76);
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .session-card strong {
            display: block;
            margin-top: 3px;
            font-size: 23px;
            white-space: nowrap;
        }

        .theme-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100%;
            padding: 22px 20px;
            border-radius: 14px;
            color: #ffffff;
            background:
                linear-gradient(145deg, rgba(8, 47, 73, 0.98), rgba(15, 118, 110, 0.94)),
                url("data:image/svg+xml,%3Csvg width='120' height='120' viewBox='0 0 120 120' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.12' stroke-width='2'%3E%3Ccircle cx='20' cy='20' r='18'/%3E%3Ccircle cx='88' cy='78' r='26'/%3E%3C/g%3E%3C/svg%3E");
            box-shadow: var(--shadow);
        }

        .theme-card label {
            display: block;
            color: rgba(255, 255, 255, 0.78);
            font-size: 11px;
            font-weight: 700;
        }

        .theme-select-wrap {
            position: relative;
            margin-top: 10px;
        }

        .theme-select {
            width: 100%;
            min-height: 42px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 34px 0 12px;
            border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: 8px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.14);
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            text-align: left;
        }

        .theme-select:focus {
            outline: 3px solid rgba(255, 255, 255, 0.24);
            outline-offset: 3px;
        }

        .theme-select-menu {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            z-index: 40;
            display: none;
            overflow: hidden;
            padding: 6px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(23, 32, 51, 0.18);
        }

        .theme-select-wrap.is-open .theme-select-menu {
            display: grid;
            gap: 4px;
        }

        .theme-select-option {
            min-height: 34px;
            padding: 0 10px;
            border: 0;
            border-radius: 8px;
            color: var(--ink);
            background: #ffffff;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 600;
            text-align: left;
        }

        .theme-select-option:hover,
        .theme-select-option:focus {
            color: #ffffff;
            outline: none;
        }

        .theme-select-arrow {
            position: absolute;
            top: 50%;
            right: 9px;
            width: 7px;
            height: 7px;
            border-right: 1px solid #ffffff;
            border-bottom: 1px solid #ffffff;
            pointer-events: none;
            transform: translateY(-65%) rotate(45deg);
        }

        .theme-swatches {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-top: 24px;
        }

        .theme-swatch {
            width: 18px;
            height: 18px;
            border: 1px solid rgba(255, 255, 255, 0.72);
            border-radius: 999px;
            background: var(--swatch);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.18);
        }

        .site-footer {
            position: fixed;
            right: 18px;
            bottom: 24px;
            left: 18px;
            z-index: 25;
            margin: 0;
            overflow: hidden;
            border: 1px solid rgba(220, 227, 238, 0.86);
            border-radius: 24px;
            background: var(--panel);
            box-shadow: 0 14px 38px rgba(23, 32, 51, 0.08);
        }

        .footer-inner {
            display: grid;
            grid-template-columns: 1fr 1.4fr 1fr;
            gap: 18px;
            align-items: center;
            padding: 18px 22px;
        }

        .footer-link {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            min-height: 42px;
            padding: 0 14px;
            border: 1px solid rgba(15, 118, 110, 0.18);
            border-radius: 14px;
            color: var(--primary);
            background: rgba(15, 118, 110, 0.08);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .footer-link:hover {
            color: var(--primary-dark);
            background: rgba(15, 118, 110, 0.12);
            transform: translateY(-1px);
        }

        .footer-company {
            text-align: center;
        }

        .footer-company strong {
            display: block;
            color: var(--primary-dark);
            font-size: 22px;
            letter-spacing: 0;
        }

        .footer-company span {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 14px;
            font-weight: 700;
            line-height: 1.45;
        }

        .footer-station {
            justify-self: end;
            padding: 12px 14px;
            border: 1px solid rgba(245, 158, 11, 0.26);
            border-radius: 14px;
            color: #92400e;
            background: rgba(245, 158, 11, 0.1);
            font-size: 13px;
            font-weight: 700;
            line-height: 1.5;
            text-align: right;
        }

        @media (max-width: 940px) {
            :root {
                --shell-width: min(calc(100vw - 36px), 100%);
            }

            body {
                overflow-y: auto;
            }

            .dashboard-page {
                grid-template-columns: 1fr;
                width: auto;
                height: auto;
                min-height: 100vh;
                overflow: visible;
                margin: 0 12px;
                padding: 0 0 184px;
            }

            .dashboard-page.menu-collapsed {
                grid-template-columns: 1fr;
                gap: 20px;
                padding-right: 0;
            }

            .sidebar {
                position: sticky;
                top: 82px;
                width: 100%;
                margin-top: 82px;
                max-height: calc(100vh - 180px);
                border-radius: 20px;
            }

            .dashboard-space {
                margin-top: 20px;
            }

            .dashboard-page.menu-collapsed .sidebar {
                width: 64px;
            }

            .side-menu {
                max-height: calc(100vh - 318px);
            }

            .dashboard-hero-logo,
            .dashboard-top,
            .welcome-panel,
            .activity-panel {
                max-width: none;
                width: 100%;
            }

            .dashboard-top {
                grid-template-columns: 1fr;
            }

            .activity-panel {
                max-width: none;
            }

            .activity-list {
                max-height: 180px;
            }

        }

        @media (max-width: 640px) {
            :root {
                --shell-width: min(calc(100vw - 24px), 100%);
            }

            .site-header-inner {
                min-height: 64px;
            }

            .site-logo {
                font-size: 23px;
            }

            .site-logo-icon {
                width: 38px;
                height: 38px;
            }

            .menu-toggle {
                width: 38px;
                height: 38px;
            }

            .header-actions span {
                display: none;
            }

            .header-actions {
                right: 12px;
            }

            .dashboard-page {
                padding: 0 0 214px;
            }

            .dashboard-hero-logo {
                margin-bottom: 10px;
                padding: 10px 14px;
            }

            .dashboard-hero-logo .dashboard-gateway img {
                max-height: 82px;
            }

            .sidebar {
                border-radius: 20px;
            }

            .sidebar-brand h1 {
                font-size: 22px;
            }

            .sidebar-brand p {
                font-size: 13px;
            }

            .menu-link {
                font-size: 13px;
            }

            .welcome-panel {
                padding: 24px 20px;
                border-radius: 20px;
            }

            .activity-panel {
                padding: 18px 16px;
                border-radius: 20px;
            }

            .activity-list {
                max-height: 120px;
            }

            .activity-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
            }

            .welcome-panel {
                grid-template-columns: 1fr;
            }

            .welcome-panel h1 {
                font-size: 28px;
            }

            .session-card {
                width: 100%;
                min-height: 142px;
            }

            .theme-card {
                width: 100%;
                min-height: 142px;
                border-radius: 20px;
            }

            .footer-inner {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 10px;
                padding: 12px 14px;
            }

            .footer-link {
                justify-self: center;
            }

            .footer-station {
                justify-self: center;
                text-align: center;
            }
        }
    </style>
    @include('partials.theme')
    <link rel="stylesheet" href="{{ asset('mobile_css/dashboard_mobile_view.css') }}">
</head>
<body>
    @php
        $currentYear = now()->year;
        $financialYearStart = now()->month >= 4 ? $currentYear : $currentYear - 1;
        $financialYear = $financialYearStart . ' - ' . ($financialYearStart + 1);
        $copyrightFinancialYear = $financialYearStart . '-' . ($financialYearStart + 1);
    @endphp

    <header class="site-header">
        <div class="site-header-inner">
            <button type="button" class="menu-toggle mobile-header-menu-toggle" id="mobileMenuToggle" aria-label="Show menu" aria-controls="dashboardMenu" aria-expanded="false">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon has-brand-image" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>

            <div class="header-actions">
                <span>{{ auth()->user()->name ?? 'User' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
                <a href="{{ route('company-information.edit') }}" class="company-info-link">Company Info</a>
            </div>
        </div>
    </header>

    <main class="dashboard-page" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <section class="dashboard-space" aria-label="Dashboard workspace">
            <div class="dashboard-hero-logo">
                <div class="dashboard-gateway" aria-label="Gateway of FuelTracker">
                    <img src="{{ asset('images/fueltracker-gateway-transparent.png') }}" alt="Gateway of FuelTracker">
                </div>
            </div>

            <div class="dashboard-top">
                <div class="welcome-panel">
                    <div>
                        <p class="eyebrow">Welcome back</p>
                        <h1>Welcome to FuelTracker dashboard.</h1>
                        <p>Select any heading from the side menu to open daily transactions, master records, reports, and registers.</p>
                    </div>

                    <div class="session-card">
                        <span class="session-icon" aria-hidden="true">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M7 3h10v4H7V3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M5 7h14v14H5V7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M8 11h8M8 15h5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <span>
                            <span>Financial Year Session</span>
                            <strong>{{ $financialYear }}</strong>
                        </span>
                    </div>

                </div>

                <div class="theme-card">
                    <div>
                        <label for="themeSelectButton">Theme</label>
                        <div class="theme-select-wrap" id="themeSelect" data-theme-select>
                            <button type="button" class="theme-select" id="themeSelectButton" aria-haspopup="listbox" aria-expanded="false" aria-label="Choose dashboard theme">
                                <span id="themeSelectText">Default</span>
                            </button>
                            <div class="theme-select-menu" role="listbox" aria-label="Choose dashboard theme">
                                <button type="button" class="theme-select-option" data-value="default" role="option">Default</button>
                                <button type="button" class="theme-select-option" data-value="ocean" role="option">Ocean</button>
                                <button type="button" class="theme-select-option" data-value="royal" role="option">Royal</button>
                                <button type="button" class="theme-select-option" data-value="rose" role="option">Rose</button>
                                <button type="button" class="theme-select-option" data-value="charcoal" role="option">Charcoal</button>
                                <button type="button" class="theme-select-option" data-value="sunset-sky" role="option">Sunset Sky</button>
                                <button type="button" class="theme-select-option" data-value="royal-print" role="option">Royal Print</button>
                                <button type="button" class="theme-select-option" data-value="peacock-print" role="option">Peacock Print</button>
                                <button type="button" class="theme-select-option" data-value="marigold-print" role="option">Marigold Print</button>
                                <button type="button" class="theme-select-option" data-value="velvet-print" role="option">Velvet Print</button>
                            </div>
                            <span class="theme-select-arrow" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="theme-swatches" aria-hidden="true">
                        <span class="theme-swatch" style="--swatch: #0f766e"></span>
                        <span class="theme-swatch" style="--swatch: #0369a1"></span>
                        <span class="theme-swatch" style="--swatch: #4338ca"></span>
                        <span class="theme-swatch" style="--swatch: #be123c"></span>
                        <span class="theme-swatch" style="--swatch: #334155"></span>
                        <span class="theme-swatch" style="--swatch: linear-gradient(135deg, #7c2d12 0%, #ea580c 48%, #fb7185 100%)"></span>
                        <span class="theme-swatch" style="--swatch: linear-gradient(135deg, #312e81 50%, #f59e0b 50%)"></span>
                        <span class="theme-swatch" style="--swatch: linear-gradient(135deg, #0f766e 50%, #0891b2 50%)"></span>
                        <span class="theme-swatch" style="--swatch: linear-gradient(135deg, #b45309 50%, #be123c 50%)"></span>
                        <span class="theme-swatch" style="--swatch: linear-gradient(135deg, #581c87 50%, #be185d 50%)"></span>
                    </div>
                </div>
            </div>

            <div class="activity-panel" aria-labelledby="lastActivityTitle">
                <div class="activity-header">
                    <div>
                        <p class="eyebrow">Recent work</p>
                        <h2 id="lastActivityTitle">Last Activity</h2>
                    </div>
                    <span class="activity-limit">Maximum 5</span>
                </div>

                <ul class="activity-list" id="lastActivityList"></ul>
                <p class="activity-empty" id="lastActivityEmpty">Click any menu option to show it here.</p>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-inner">
            <a href="https://www.anjalicomputers.com" class="footer-link" target="_blank" rel="noopener noreferrer">www.anjalicomputers.com</a>

            <div class="footer-company">
                <strong>ANJALI COMPUTERS</strong>
                <span>Mobile : 9826612055, Email : deepakkharpate@gmail.com</span>
            </div>

            <div class="footer-station">
                &copy; {{ $copyrightFinancialYear }} All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        const dashboardPage = document.getElementById('dashboardPage');
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const themeSelect = document.getElementById('themeSelect');
        const themeSelectButton = document.getElementById('themeSelectButton');
        const themeSelectText = document.getElementById('themeSelectText');
        const themeOptions = document.querySelectorAll('#themeSelect .theme-select-option');
        const themeStorageKey = 'fueltracker:theme';
        const activityList = document.getElementById('lastActivityList');
        const activityEmpty = document.getElementById('lastActivityEmpty');
        const activityStorageKey = 'fueltracker:last-activity';
        const maxActivityItems = 5;

        if (mobileMenuToggle && dashboardPage) {
            const closeMobileMenu = () => {
                dashboardPage.classList.remove('mobile-menu-open');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                mobileMenuToggle.setAttribute('aria-label', 'Show menu');
            };

            mobileMenuToggle.addEventListener('click', () => {
                const isOpen = dashboardPage.classList.toggle('mobile-menu-open');

                mobileMenuToggle.setAttribute('aria-expanded', String(isOpen));
                mobileMenuToggle.setAttribute('aria-label', isOpen ? 'Hide menu' : 'Show menu');
            });

            document.querySelector('#dashboardMenu .menu-toggle')?.addEventListener('click', closeMobileMenu);

            document.querySelectorAll('#dashboardMenu .menu-link').forEach((menuLink) => {
                menuLink.addEventListener('click', closeMobileMenu);
            });
        }

        const applyTheme = (theme) => {
            document.documentElement.dataset.theme = theme;

            try {
                localStorage.setItem(themeStorageKey, theme);
            } catch (error) {
                return;
            }
        };

        const setThemeSelectValue = (theme) => {
            const selectedOption = document.querySelector(`#themeSelect .theme-select-option[data-value="${theme}"]`);

            themeOptions.forEach((option) => {
                const isSelected = option === selectedOption;

                option.classList.toggle('is-selected', isSelected);
                option.setAttribute('aria-selected', String(isSelected));
            });

            if (selectedOption) {
                themeSelectText.textContent = selectedOption.textContent;
            }
        };

        const closeThemeSelect = () => {
            themeSelect.classList.remove('is-open');
            themeSelectButton.setAttribute('aria-expanded', 'false');
        };

        const getStoredActivities = () => {
            try {
                return JSON.parse(localStorage.getItem(activityStorageKey)) || [];
            } catch (error) {
                return [];
            }
        };

        const saveActivities = (activities) => {
            localStorage.setItem(activityStorageKey, JSON.stringify(activities));
        };

        const formatActivityTime = (timestamp) => {
            const activityDate = new Date(timestamp);

            if (Number.isNaN(activityDate.getTime())) {
                return 'Just now';
            }

            return activityDate.toLocaleString('en-IN', {
                day: '2-digit',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit',
            });
        };

        const renderActivities = () => {
            const activities = getStoredActivities().slice(0, maxActivityItems);

            activityList.innerHTML = '';
            activityEmpty.hidden = activities.length > 0;

            activities.forEach((activity) => {
                const item = document.createElement('li');
                item.className = 'activity-entry';
                const hasUrl = activity.url && activity.url !== '#';
                const rowTag = hasUrl ? 'a' : 'span';
                const hrefAttr = hasUrl ? ` href="${activity.url}"` : '';

                item.innerHTML = `
                    <${rowTag} class="${hasUrl ? 'activity-link' : 'activity-row'}"${hrefAttr}>
                        <span class="activity-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="activity-text">
                            <span class="activity-name"></span>
                            <span class="activity-meta"></span>
                        </span>
                    </${rowTag}>
                `;

                item.querySelector('.activity-name').textContent = activity.name;
                item.querySelector('.activity-meta').textContent = `${activity.section} - ${formatActivityTime(activity.timestamp)}`;
                activityList.appendChild(item);
            });
        };

        const addActivity = (section, name, url) => {
            const activities = getStoredActivities().filter((activity) => {
                return activity.section !== section || activity.name !== name;
            });

            activities.unshift({
                section,
                name,
                url,
                timestamp: new Date().toISOString(),
            });

            saveActivities(activities.slice(0, maxActivityItems));
            renderActivities();
        };

        document.querySelectorAll('.menu-link').forEach((menuLink) => {
            menuLink.addEventListener('click', (event) => {
                addActivity(menuLink.dataset.section, menuLink.dataset.activity, menuLink.getAttribute('href'));

                if (menuLink.getAttribute('href') === '#') {
                    event.preventDefault();
                }
            });
        });

        setThemeSelectValue(document.documentElement.dataset.theme || 'default');

        themeSelectButton.addEventListener('click', () => {
            const isOpen = themeSelect.classList.toggle('is-open');

            themeSelectButton.setAttribute('aria-expanded', String(isOpen));
        });

        themeOptions.forEach((option) => {
            option.addEventListener('click', () => {
                const theme = option.dataset.value;

                applyTheme(theme);
                setThemeSelectValue(theme);
                closeThemeSelect();
            });
        });

        document.addEventListener('click', (event) => {
            if (!themeSelect.contains(event.target)) {
                closeThemeSelect();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeThemeSelect();
                themeSelectButton.focus();
            }
        });

        renderActivities();
    </script>
</body>
</html>
