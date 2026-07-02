<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase | FuelTracker</title>
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

        [hidden] {
            display: none !important;
        }

        body {
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
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

        .purchase-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 88px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
            overflow: visible;
        }

        .purchase-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
        }

        .purchase-page {
            width: 100%;
            min-width: 0;
            min-height: calc(100vh - 112px);
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 14px;
            overflow: visible;
        }

        .page-title,
        .panel {
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
            margin-bottom: 12px;
            padding: 18px;
        }

        .eyebrow {
            margin: 0 0 5px;
            color: var(--primary);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        h1,
        h2 {
            margin: 0;
        }

        h1 {
            font-size: 30px;
            line-height: 1.2;
        }

        .panel {
            overflow: hidden;
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

        .content-grid {
            display: grid;
            gap: 12px;
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            padding-right: 4px;
            scrollbar-width: thin;
            scrollbar-color: var(--primary) color-mix(in srgb, var(--primary) 12%, var(--line));
        }

        .content-grid::-webkit-scrollbar,
        .purchase-form::-webkit-scrollbar,
        .table-wrap::-webkit-scrollbar,
        .theme-dropdown-menu::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        .content-grid::-webkit-scrollbar-track,
        .purchase-form::-webkit-scrollbar-track,
        .table-wrap::-webkit-scrollbar-track,
        .theme-dropdown-menu::-webkit-scrollbar-track {
            border-radius: 999px;
            background: color-mix(in srgb, var(--primary) 12%, var(--line));
        }

        .content-grid::-webkit-scrollbar-thumb,
        .purchase-form::-webkit-scrollbar-thumb,
        .table-wrap::-webkit-scrollbar-thumb,
        .theme-dropdown-menu::-webkit-scrollbar-thumb {
            border: 2px solid color-mix(in srgb, var(--primary) 12%, var(--line));
            border-radius: 999px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary) 62%, var(--primary-shine));
        }

        .content-grid::-webkit-scrollbar-thumb:hover,
        .purchase-form::-webkit-scrollbar-thumb:hover,
        .table-wrap::-webkit-scrollbar-thumb:hover,
        .theme-dropdown-menu::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .form-panel {
            position: relative;
            z-index: 10;
            display: flex;
            max-height: none;
            min-height: min(720px, calc(100vh - 150px));
            flex-direction: column;
        }

        .form-panel.has-open-dropdown {
            z-index: 200;
        }

        .list-panel {
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .panel-head,
        .table-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 13px 14px;
            border-bottom: 1px solid var(--line);
            background: #fbfcfe;
        }

        .panel-head h2,
        .toolbar-title {
            color: var(--ink);
            font-size: 18px;
            font-weight: 800;
            line-height: 1.25;
        }

        .entry-nav {
            width: 100%;
            display: grid;
            grid-template-columns: 48px minmax(0, 1fr) 48px;
            align-items: center;
            gap: 12px;
        }

        .entry-nav-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid color-mix(in srgb, var(--primary) 24%, var(--line));
            border-radius: 8px;
            color: var(--primary-dark);
            background: color-mix(in srgb, var(--primary) 8%, #ffffff);
            cursor: pointer;
            font: inherit;
            font-size: 18px;
            font-weight: 900;
            line-height: 1;
        }

        .entry-nav-btn:hover {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .entry-nav-btn:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }

        #nextPurchaseEntryBtn {
            justify-self: end;
        }

        .entry-nav-title {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-width: 0;
            text-align: center;
        }

        .entry-nav-count {
            color: var(--muted);
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex: 1 1 auto;
            min-width: 0;
            flex-wrap: nowrap;
        }

        .toolbar-total {
            flex: 0 0 auto;
            color: var(--primary-dark);
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .list-search {
            width: min(260px, 28vw);
            flex: 0 1 260px;
            min-height: 34px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #ffffff;
            color: var(--ink);
            font-size: 12px;
            font-weight: 700;
        }

        .export-actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex: 0 0 auto;
        }

        .export-btn {
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            border: 1px solid transparent;
            border-radius: 8px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .export-btn:hover,
        .export-btn:focus {
            outline: none;
        }

        .purchase-form {
            display: grid;
            flex: 1 1 auto;
            gap: 0;
            min-height: 0;
            padding: 18px 14px 0;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--primary) color-mix(in srgb, var(--primary) 12%, var(--line));
        }

        .purchase-summary-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(120px, 1fr));
            gap: 10px;
            margin: 12px 14px 14px;
            padding-top: 2px;
        }

        .summary-box {
            display: grid;
            gap: 5px;
            min-height: 74px;
            padding: 11px 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fbfcfe;
            box-shadow: 0 8px 22px rgba(23, 32, 51, 0.06);
        }

        .summary-label {
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
            line-height: 1.25;
            text-transform: uppercase;
        }

        .summary-value {
            color: var(--ink);
            font-size: 17px;
            font-weight: 800;
            line-height: 1.2;
            overflow-wrap: anywhere;
        }

        .summary-box.total {
            border-color: rgba(15, 118, 110, 0.25);
            background: rgba(15, 118, 110, 0.08);
        }

        .summary-box.total .summary-value {
            color: var(--primary-dark);
        }

        .form-alert {
            margin: 14px 14px 0;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
        }

        .form-alert.success {
            color: #115e59;
            border: 1px solid rgba(15, 118, 110, 0.22);
            background: rgba(15, 118, 110, 0.08);
        }

        .form-alert.is-hiding {
            opacity: 0;
            transform: translateY(-6px);
            transition: opacity 180ms ease, transform 180ms ease;
        }

        .form-alert.error {
            color: #b42318;
            border: 1px solid rgba(180, 35, 24, 0.16);
            background: #fff1f0;
        }

        .form-alert ul {
            margin: 6px 0 0;
            padding-left: 18px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(150px, 1fr));
            gap: 12px;
            align-items: end;
        }

        .field {
            position: relative;
            display: grid;
            gap: 5px;
            min-width: 0;
        }

        .field.wide {
            grid-column: span 2;
        }

        .purchase-form > .form-grid {
            grid-template-columns: repeat(12, minmax(0, 1fr));
            column-gap: 12px;
            row-gap: 18px;
            align-items: start;
        }

        .purchase-form > .form-grid > .field {
            grid-column: span 4;
        }

        .purchase-form > .form-grid > .field.wide {
            grid-column: span 6;
        }

        .purchase-form > .form-grid > .field:nth-of-type(6),
        .purchase-form > .form-grid > .field:nth-of-type(7) {
            grid-column: span 6;
        }

        label {
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
        }

        .char-limit {
            position: absolute;
            right: 0;
            top: calc(100% + 3px);
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            text-align: right;
        }

        input,
        select,
        textarea {
            width: 100%;
            min-height: 36px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #ffffff;
            font: inherit;
            font-size: 13px;
            outline: none;
            padding: 0 10px;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
        }

        input:disabled {
            color: var(--muted);
            background: #f1f4f8;
            cursor: not-allowed;
        }

        textarea {
            min-height: 64px;
            resize: vertical;
        }

        .theme-dropdown {
            position: relative;
            height: 36px;
            min-height: 0;
            display: block;
        }

        .theme-dropdown.is-open {
            z-index: 120;
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
            padding: 0 10px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #ffffff;
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            outline: none;
            text-align: left;
        }

        .theme-dropdown-button:hover,
        .theme-dropdown-button:focus {
            border-color: rgba(15, 118, 110, 0.52);
            background: rgba(15, 118, 110, 0.07);
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
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
            max-height: 210px;
            overflow-y: auto;
            margin: 0;
            padding: 4px;
            border: 1px solid color-mix(in srgb, var(--primary) 22%, var(--line));
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 14px 32px rgba(23, 32, 51, 0.16);
            list-style: none;
            scrollbar-width: thin;
            scrollbar-color: var(--primary) color-mix(in srgb, var(--primary) 12%, var(--line));
        }

        .theme-dropdown.is-open .theme-dropdown-menu {
            display: block;
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
            min-height: 34px;
            padding: 0 10px;
            border: 1px solid var(--line);
            border-radius: 9px;
            color: var(--ink);
            background: #fbfcfe;
            font: inherit;
            font-size: 13px;
            outline: none;
        }

        .theme-dropdown-search:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 18%, transparent);
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
            font-size: 13px;
            text-align: left;
        }

        .theme-dropdown-option:hover,
        .theme-dropdown-option:focus,
        .theme-dropdown-option.is-selected {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            outline: none;
        }

        .theme-dropdown-option:disabled {
            color: var(--muted);
            background: transparent;
            cursor: default;
        }

        .theme-dropdown-empty {
            display: none;
            min-height: 32px;
            padding: 8px 10px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        .theme-dropdown-empty.is-visible {
            display: block;
        }

        .table-wrap {
            max-height: 420px;
            overflow: auto;
            background: #ffffff;
            scrollbar-width: thin;
            scrollbar-color: var(--primary) color-mix(in srgb, var(--primary) 12%, var(--line));
        }

        table {
            width: 100%;
            min-width: 1220px;
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
            font-weight: 800;
        }

        tbody tr:hover {
            background: color-mix(in srgb, var(--primary) 8%, #ffffff);
        }

        tbody tr[data-pending-row="true"] {
            cursor: pointer;
        }

        tbody tr[data-pending-row="true"].is-editing {
            background: color-mix(in srgb, var(--primary) 12%, #ffffff);
            box-shadow: inset 3px 0 0 var(--primary);
        }

        td {
            background: #ffffff;
        }

        .items-table input,
        .items-table select {
            min-height: 34px;
            border: 0;
            border-radius: 0;
            background: transparent;
            font-size: 13px;
            box-shadow: none;
        }

        .items-table input:focus,
        .items-table select:focus {
            box-shadow: inset 0 0 0 2px rgba(15, 118, 110, 0.20);
        }

        .number-input,
        .number-cell {
            text-align: right;
        }

        .form-actions {
            grid-column: 1 / -1;
            position: static;
            z-index: 30;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 6px -14px 0;
            padding: 10px 14px;
            border-top: 1px solid var(--line);
            background:
                linear-gradient(135deg, color-mix(in srgb, var(--primary) 7%, #ffffff), rgba(255, 255, 255, 0.98)),
                #ffffff;
            box-shadow: 0 -8px 18px rgba(23, 32, 51, 0.05);
        }

        .secondary-actions,
        .primary-actions,
        .row-actions {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .action-btn,
        .update-btn,
        .delete-btn,
        .modal-no-btn,
        .modal-yes-btn {
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            border-radius: 8px;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .save-btn {
            border: 1px solid transparent;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .add-item-btn {
            border: 1px solid color-mix(in srgb, var(--primary) 24%, transparent);
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            box-shadow: 0 8px 18px rgba(15, 118, 110, 0.18);
        }

        .clear-btn {
            border: 1px solid var(--line);
            color: var(--muted);
            background: #ffffff;
        }

        .sample-btn {
            border: 1px solid color-mix(in srgb, var(--primary) 34%, var(--line));
            color: var(--primary-dark);
            background: color-mix(in srgb, var(--primary) 10%, #ffffff);
        }

        .cancel-edit-btn,
        .modal-no-btn {
            border: 1px solid var(--line);
            color: var(--muted);
            background: #ffffff;
        }

        .update-btn {
            min-height: 30px;
            padding: 0 12px;
            border: 1px solid rgba(15, 118, 110, 0.24);
            color: var(--primary-dark);
            background: rgba(15, 118, 110, 0.08);
        }

        .delete-btn,
        .modal-yes-btn {
            min-height: 30px;
            padding: 0 12px;
            border: 1px solid rgba(180, 35, 24, 0.16);
            color: var(--danger);
            background: #fff1f0;
        }

        .update-btn:hover {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .delete-btn:hover,
        .modal-yes-btn {
            color: #ffffff;
            background: var(--danger);
        }

        .clear-btn:hover {
            color: var(--primary-dark);
            border-color: rgba(15, 118, 110, 0.36);
        }

        .add-item-btn:hover,
        .sample-btn:hover {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .action-btn:disabled {
            opacity: 0.55;
            cursor: not-allowed;
            filter: grayscale(0.25);
        }

        .empty-state {
            padding: 34px 16px;
            color: var(--muted);
            font-size: 14px;
            font-weight: 700;
            text-align: center;
        }

        .delete-modal {
            position: fixed;
            inset: 0;
            z-index: 80;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: rgba(15, 23, 42, 0.45);
        }

        .delete-modal.is-open {
            display: flex;
        }

        .delete-dialog {
            width: min(420px, 100%);
            padding: 18px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.24);
        }

        .delete-dialog-title {
            margin: 0 0 8px;
            font-size: 18px;
        }

        .delete-dialog-body {
            margin: 0 0 16px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        .delete-dialog-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .sample-modal {
            position: fixed;
            inset: 0;
            z-index: 90;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(15, 23, 42, 0.45);
        }

        .sample-modal.is-open {
            display: flex;
        }

        .item-modal {
            position: fixed;
            inset: 0;
            z-index: 80;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(15, 23, 42, 0.45);
        }

        .item-modal.is-open {
            display: flex;
        }

        .preview-modal {
            position: fixed;
            inset: 0;
            z-index: 95;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: rgba(15, 23, 42, 0.58);
        }

        .preview-modal.is-open {
            display: flex;
        }

        .preview-window {
            width: min(1180px, 96vw);
            max-height: 92vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 12px;
            background: #f8fbff;
            box-shadow: 0 26px 80px rgba(15, 23, 42, 0.30);
        }

        .preview-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .preview-title {
            font-size: 18px;
            font-weight: 800;
        }

        .preview-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .preview-action-btn,
        .preview-close-btn {
            min-height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 8px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.14);
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
        }

        .preview-body {
            overflow: auto;
            padding: 18px;
        }

        .invoice-preview {
            width: min(960px, 100%);
            min-height: 640px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid var(--line);
            background: #ffffff;
            color: var(--ink);
            box-shadow: 0 12px 34px rgba(23, 32, 51, 0.12);
        }

        .invoice-company {
            display: grid;
            gap: 4px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--ink);
            text-align: center;
        }

        .invoice-company h2 {
            color: var(--primary-dark);
            font-size: 24px;
            letter-spacing: 0;
        }

        .invoice-meta-strip {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px solid var(--ink);
            font-size: 12px;
            font-weight: 800;
        }

        .invoice-title {
            padding: 7px 0;
            border-bottom: 1px solid var(--ink);
            color: var(--primary-dark);
            font-size: 15px;
            font-weight: 900;
            text-align: center;
            text-transform: uppercase;
        }

        .invoice-parties {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            padding: 12px 0;
            border-bottom: 1px solid var(--line);
        }

        .invoice-block {
            display: grid;
            gap: 6px;
            font-size: 12px;
        }

        .invoice-block strong {
            color: var(--primary-dark);
            font-size: 13px;
        }

        .invoice-preview table {
            width: 100%;
            min-width: 0;
            border-collapse: collapse;
        }

        .invoice-preview th,
        .invoice-preview td {
            padding: 7px 6px;
            border: 1px solid #1f2937;
            font-size: 11px;
            white-space: normal;
        }

        .invoice-preview th {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            text-align: center;
        }

        .invoice-preview .number-cell {
            text-align: right;
            white-space: nowrap;
        }

        .invoice-totals {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 280px;
            gap: 16px;
            padding-top: 14px;
        }

        .invoice-total-box {
            border: 1px solid var(--line);
            border-radius: 8px;
            overflow: hidden;
        }

        .invoice-total-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 10px;
            border-bottom: 1px solid var(--line);
            font-size: 12px;
            font-weight: 800;
        }

        .invoice-total-row:last-child {
            border-bottom: 0;
            color: var(--primary-dark);
            background: rgba(15, 118, 110, 0.08);
        }

        .invoice-note {
            align-self: end;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #purchasePreviewPrint,
            #purchasePreviewPrint * {
                visibility: visible;
            }

            #purchasePreviewPrint {
                position: absolute;
                inset: 0;
                width: 100%;
                min-height: 0;
                margin: 0;
                padding: 0;
                border: 0;
                box-shadow: none;
            }
        }

        .item-window {
            width: min(920px, 100%);
            max-height: min(760px, calc(100vh - 42px));
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.25);
        }

        .item-window-head {
            min-height: 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 0 14px 0 18px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .item-window-title {
            font-size: 17px;
            font-weight: 800;
        }

        .item-close-btn {
            width: 34px;
            height: 34px;
            border: 1px solid rgba(255, 255, 255, 0.38);
            border-radius: 8px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            cursor: pointer;
            font: inherit;
            font-size: 20px;
            line-height: 1;
        }

        .item-window-body {
            display: grid;
            gap: 14px;
            padding: 16px;
            overflow: auto;
        }

        .item-form-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            align-items: start;
        }

        .item-window-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding-top: 2px;
        }

        .sample-window {
            width: min(1180px, 100%);
            max-height: calc(100vh - 40px);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid color-mix(in srgb, var(--primary) 36%, var(--line));
            border-radius: 12px;
            background: #f8fbff;
            box-shadow: 0 28px 80px rgba(15, 23, 42, 0.28);
        }

        .sample-window-head {
            min-height: 46px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 0 14px 0 18px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .sample-window-title {
            font-size: 18px;
            font-weight: 800;
        }

        .sample-close-btn {
            width: 34px;
            height: 34px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 8px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            cursor: pointer;
            font-size: 20px;
            font-weight: 800;
            line-height: 1;
        }

        .sample-form {
            display: grid;
            grid-template-columns: 390px minmax(0, 1fr);
            gap: 18px;
            padding: 18px;
            overflow: auto;
        }

        .sample-details,
        .sample-product-card {
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 10px 28px rgba(23, 32, 51, 0.08);
        }

        .sample-details {
            display: grid;
            gap: 12px;
            align-content: start;
            padding: 16px;
        }

        .sample-field {
            display: grid;
            grid-template-columns: 140px minmax(0, 1fr);
            align-items: center;
            gap: 12px;
        }

        .sample-field label,
        .sample-line label {
            color: var(--ink);
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .sample-field input,
        .sample-line input {
            min-height: 34px;
            border-radius: 6px;
            background: #ffffff;
        }

        .sample-products {
            display: grid;
            grid-template-columns: repeat(3, minmax(190px, 1fr));
            gap: 14px;
        }

        .sample-product-card {
            padding: 14px;
        }

        .sample-product-card.is-inactive {
            opacity: 0.42;
            filter: grayscale(0.25);
        }

        .sample-product-card.is-inactive input {
            cursor: not-allowed;
            background: #f1f4f8;
        }

        .sample-product-card.is-active {
            border-color: color-mix(in srgb, var(--primary) 34%, var(--line));
            box-shadow: 0 12px 28px color-mix(in srgb, var(--primary) 14%, transparent);
        }

        .sample-product-card h3 {
            margin: 0 0 14px;
            color: var(--ink);
            font-size: 20px;
            text-align: center;
        }

        .sample-line {
            display: grid;
            grid-template-columns: minmax(95px, 1fr) minmax(86px, 110px);
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .sample-preview-btn {
            width: 100%;
            min-height: 36px;
            margin-top: 10px;
            border: 1px solid color-mix(in srgb, var(--primary) 34%, var(--line));
            border-radius: 8px;
            color: var(--primary-dark);
            background: color-mix(in srgb, var(--primary) 8%, #ffffff);
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            font-weight: 900;
        }

        .sample-preview-btn:hover {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .sample-window-actions {
            display: flex;
            justify-content: center;
            padding: 14px 18px 18px;
            border-top: 1px solid var(--line);
            background: #ffffff;
        }

        .sample-save-btn {
            min-width: 150px;
            min-height: 40px;
            border: 1px solid transparent;
            border-radius: 8px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            cursor: pointer;
            font: inherit;
            font-size: 15px;
            font-weight: 800;
        }

        .add-item-field {
            align-self: end;
        }

        @media (max-width: 980px) {
            .site-header-inner {
                grid-template-columns: 1fr;
                gap: 8px;
                padding: 10px;
            }

            .header-title,
            .header-actions {
                justify-self: center;
            }

            .form-grid {
                grid-template-columns: 1fr 1fr;
            }

            .field.wide {
                grid-column: span 2;
            }

            .purchase-summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .sample-form {
                grid-template-columns: 1fr;
            }

            .sample-products {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .purchase-workspace.app-shell-with-sidebar {
                width: auto;
                margin: 10px;
            }

            .purchase-page {
                padding: 10px;
            }

            .page-title,
            .panel-head,
            .form-actions {
                align-items: flex-start;
                flex-direction: column;
            }

            .table-toolbar,
            .toolbar-actions {
                align-items: flex-start;
                flex-direction: column;
            }

            .list-search {
                width: 100%;
                flex-basis: auto;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .purchase-summary-grid {
                grid-template-columns: 1fr;
            }

            .field.wide {
                grid-column: auto;
            }

            .sample-field {
                grid-template-columns: 1fr;
            }

        }
    </style>
    @include('partials.theme')
</head>
<body>
    @php
        $purchases = collect($purchases ?? []);
        $totalTaxableAmount = $purchases->sum(fn ($purchase) => (float) ($purchase->taxable_amount ?? 0));
        $totalCgstAmount = $purchases->sum(fn ($purchase) => (float) ($purchase->total_cgst_amount ?? (((float) ($purchase->taxable_amount ?? 0) * (float) ($purchase->cgst ?? 0)) / 100)));
        $totalSgstAmount = $purchases->sum(fn ($purchase) => (float) ($purchase->total_sgst_amount ?? (((float) ($purchase->taxable_amount ?? 0) * (float) ($purchase->sgst ?? 0)) / 100)));
        $totalIgstAmount = $purchases->sum(fn ($purchase) => (float) ($purchase->total_igst_amount ?? (((float) ($purchase->taxable_amount ?? 0) * (float) ($purchase->igst ?? 0)) / 100)));
        $totalDiscountAmount = $purchases->sum(fn ($purchase) => (float) ($purchase->discountinrs ?? 0));
        $totalPurchaseAmount = $purchases->sum(fn ($purchase) => (float) ($purchase->total_amount ?? 0));
        $today = old('date', $selectedDate ?? now()->toDateString());
        $particularAccounts = collect($perticular ?? []);
        $accountDetailsByName = $particularAccounts->mapWithKeys(function ($party) {
            $partyName = is_object($party) ? ($party->account_perticular ?? '') : (string) $party;

            return [$partyName => [
                'address' => is_object($party) ? ($party->address ?? '') : '',
                'location' => is_object($party) ? ($party->city ?? '') : '',
            ]];
        });
        $items = collect($item ?? []);
        $selectedParticular = old('perticulars', '');
        $selectedItem = old('item_name', '');
        $selectedInterstate = old('interstate', 'No');
        $storeUrl = \Illuminate\Support\Facades\Route::has('purchase.store') ? route('purchase.store') : route('purchase');
        $listUrl = \Illuminate\Support\Facades\Route::has('purchase') ? route('purchase') : url('/purchase');
        $hasUpdateRoute = \Illuminate\Support\Facades\Route::has('purchase.update');
        $hasDestroyRoute = \Illuminate\Support\Facades\Route::has('purchase.destroy');
        $hasPdfRoute = \Illuminate\Support\Facades\Route::has('purchase.pdf');
        $hasExcelRoute = \Illuminate\Support\Facades\Route::has('purchase.excel');
        $hasSampleStoreRoute = \Illuminate\Support\Facades\Route::has('purchase.sample.store');
        $hasSamplePreviewRoute = \Illuminate\Support\Facades\Route::has('purchase.sample.preview');
        $hasSamplePdfRoute = \Illuminate\Support\Facades\Route::has('purchase.sample.pdf');
        $hasSampleExcelRoute = \Illuminate\Support\Facades\Route::has('purchase.sample.excel');
        $purchaseSamples = collect($purchaseSamples ?? []);
        $previewPurchases = collect($previewPurchases ?? []);
        $previewFirst = $previewPurchases->first();
        $previewSubtotal = $previewPurchases->sum(fn ($purchase) => (float) ($purchase->amount ?? 0));
        $previewTaxable = $previewPurchases->sum(fn ($purchase) => (float) ($purchase->taxable_amount ?? 0));
        $previewTax = $previewPurchases->sum(fn ($purchase) => (float) ($purchase->total_tax_amount ?? 0));
        $previewDiscount = $previewPurchases->sum(fn ($purchase) => (float) ($purchase->discountinrs ?? 0));
        $previewTotal = $previewPurchases->sum(fn ($purchase) => (float) ($purchase->total_amount ?? 0));
        $companyName = $companyInformation->company_name ?? 'FuelTracker';
        $companyOffice = $companyInformation->registered_office ?? '';
        $companyPhone = $companyInformation->phone_no ?? '';
        $companyMobile = $companyInformation->mobile_no ?? '';
        $companyEmail = $companyInformation->email_id ?? '';
        $companyGstNo = $companyInformation->gst_no ?? '';
        $hasPreviewReferencePdfRoute = \Illuminate\Support\Facades\Route::has('RegisterPurchaseFilter.reference.pdf');
        $hasPreviewReferenceExcelRoute = \Illuminate\Support\Facades\Route::has('RegisterPurchaseFilter.reference.excel');
        $purchaseNavigationSource = collect($purchaseNavigationPurchases ?? $purchases);
        $purchaseNavigationItemRows = function ($purchase) {
            $itemsForPurchase = method_exists($purchase, 'relationLoaded') && $purchase->relationLoaded('items') && $purchase->items->isNotEmpty()
                ? $purchase->items
                : collect([$purchase]);

            return $itemsForPurchase->map(fn ($item) => [
                'item_name' => (string) ($item->item_name ?? ''),
                'quantity' => number_format((float) ($item->quantity ?? 0), 3, '.', ''),
                'rate' => number_format((float) ($item->rate ?? 0), 2, '.', ''),
                'amount' => number_format((float) ($item->amount ?? 0), 2, '.', ''),
                'discount%' => number_format((float) ($item->{'discount%'} ?? 0), 2, '.', ''),
                'discountinrs' => number_format((float) ($item->discountinrs ?? 0), 2, '.', ''),
                'taxable_amount' => number_format((float) ($item->taxable_amount ?? 0), 2, '.', ''),
                'total_amount' => number_format((float) ($item->total_amount ?? 0), 2, '.', ''),
                'cgst' => number_format((float) ($item->cgst ?? 0), 2, '.', ''),
                'sgst' => number_format((float) ($item->sgst ?? 0), 2, '.', ''),
                'igst' => number_format((float) ($item->igst ?? 0), 2, '.', ''),
                'total_tax_amount' => number_format((float) ($item->total_tax_amount ?? 0), 2, '.', ''),
            ]);
        };
        $purchaseNavigationEntries = $purchaseNavigationSource
            ->groupBy(fn ($purchase) => (string) ($purchase->Ref_no ?? ''))
            ->sortKeysUsing(function ($leftRef, $rightRef) {
                $leftNumber = (int) preg_replace('/\D+/', '', (string) $leftRef);
                $rightNumber = (int) preg_replace('/\D+/', '', (string) $rightRef);

                return $leftNumber === $rightNumber
                    ? strnatcasecmp((string) $leftRef, (string) $rightRef)
                    : $leftNumber <=> $rightNumber;
            })
            ->values()
            ->map(function ($group) use ($purchaseNavigationItemRows) {
                $first = $group->first();

                return [
                    'date' => substr((string) ($first->date ?? ''), 0, 10),
                    'Ref_no' => (string) ($first->Ref_no ?? ''),
                    'invoice_no' => (string) ($first->invoice_no ?? ''),
                    'perticulars' => (string) ($first->perticulars ?? ''),
                    'postal address' => (string) ($first->{'postal address'} ?? ''),
                    'location' => (string) ($first->location ?? ''),
                    'interstate' => (string) ($first->interstate ?? 'No'),
                    'vehicle_no' => (string) ($first->vehicle_no ?? ''),
                    'transporter' => (string) ($first->transporter ?? ''),
                    'driver' => (string) ($first->driver ?? ''),
                    'items' => $group->values()->flatMap($purchaseNavigationItemRows)->values(),
                ];
            });
    @endphp

    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>
            <div class="header-title">Purchase Entry</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar purchase-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="purchase-page">
            <section class="page-title" aria-labelledby="purchaseTitle">
                <div>
                    <p class="eyebrow">Transactions</p>
                    <h1 id="purchaseTitle">Purchase</h1>
                </div>
                <span class="record-count" id="pendingRowCount">0 entries</span>
            </section>

            <div class="content-grid">
            <section class="panel form-panel" aria-labelledby="purchaseEntryTitle">
                <div class="panel-head">
                    <div class="entry-nav">
                        <button type="button" class="entry-nav-btn" id="previousPurchaseEntryBtn" aria-label="Previous purchase entry">&lt;</button>
                        <div class="entry-nav-title">
                            <h2 id="purchaseEntryTitle">Purchase Entry</h2>
                            <span class="entry-nav-count" id="purchaseEntryNavCount">0/0</span>
                        </div>
                        <button type="button" class="entry-nav-btn" id="nextPurchaseEntryBtn" aria-label="Next purchase entry">&gt;</button>
                    </div>
                </div>

                @if (session('success'))
                    <div class="form-alert success" id="success-message">{{ session('success') }}</div>
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

                <form class="purchase-form" id="purchaseForm" method="POST" action="{{ $storeUrl }}" data-store-url="{{ $storeUrl }}" autocomplete="off">
                    @csrf
                    <input type="hidden" name="_method" id="purchaseFormMethod" value="PUT" disabled>

                    <div class="form-grid">
                        <div class="field">
                            <label for="purchaseDate">Date</label>
                            <input id="purchaseDate" type="date" name="date" value="{{ $today }}">
                        </div>

                        <div class="field">
                            <label for="refNo">Ref. No.</label>
                            <input id="refNo" type="text" name="Ref_no" value="{{ old('Ref_no', $nextRefNo ?? 1) }}" readonly>
                        </div>

                        <div class="field">
                            <label for="invoiceNo">Invoice No.</label>
                            <input id="invoiceNo" type="text" name="invoice_no" value="{{ old('invoice_no') }}">
                        </div>

                        <div class="field wide">
                            <label for="perticulars">Perticulars</label>
                            <div class="theme-dropdown" data-theme-dropdown>
                                <input type="text" class="theme-dropdown-value" id="perticulars" name="perticulars" value="{{ $selectedParticular }}">
                                <button type="button" class="theme-dropdown-button" aria-haspopup="listbox" aria-expanded="false">
                                    <span class="theme-dropdown-text">{{ $selectedParticular ?: 'Select Particulars' }}</span>
                                    <span class="theme-dropdown-arrow" aria-hidden="true"></span>
                                </button>
                                <ul class="theme-dropdown-menu" role="listbox" aria-label="Particulars list">
                                    <li class="theme-dropdown-search-wrap">
                                        <input type="search" class="theme-dropdown-search" placeholder="Search particulars" autocomplete="off">
                                    </li>
                                    @forelse ($particularAccounts as $party)
                                        @php $partyName = is_object($party) ? ($party->account_perticular ?? '') : $party; @endphp
                                        <li>
                                            <button
                                                type="button"
                                                class="theme-dropdown-option {{ $selectedParticular === $partyName ? 'is-selected' : '' }}"
                                                data-value="{{ $partyName }}"
                                                data-address="{{ is_object($party) ? ($party->address ?? '') : '' }}"
                                                data-location="{{ is_object($party) ? ($party->city ?? '') : '' }}"
                                                role="option"
                                                aria-selected="{{ $selectedParticular === $partyName ? 'true' : 'false' }}"
                                            >
                                                {{ $partyName }}
                                            </button>
                                        </li>
                                    @empty
                                        <li><button type="button" class="theme-dropdown-option" disabled>No particulars found</button></li>
                                    @endforelse
                                    @if ($selectedParticular && !$particularAccounts->contains(fn ($party) => (is_object($party) ? ($party->account_perticular ?? '') : $party) === $selectedParticular))
                                        <li><button type="button" class="theme-dropdown-option is-selected" data-value="{{ $selectedParticular }}" role="option" aria-selected="true">{{ $selectedParticular }}</button></li>
                                    @endif
                                    <li class="theme-dropdown-empty">No matching particulars</li>
                                </ul>
                            </div>
                        </div>

                        <div class="field wide">
                            <label for="postalAddress">Postal Address</label>
                            <input id="postalAddress" type="text" name="postal address" maxlength="255" value="{{ old('postal address') }}" readonly>
                            <span class="char-limit" data-char-limit-for="postalAddress">0/255</span>
                        </div>

                        <div class="field">
                            <label for="location">Location</label>
                            <input id="location" type="text" name="location" maxlength="255" value="{{ old('location') }}" readonly>
                            <span class="char-limit" data-char-limit-for="location">0/255</span>
                        </div>

                        <div class="field">
                            <label for="interstate">Interstate</label>
                            <div class="theme-dropdown" data-theme-dropdown>
                                <input type="text" class="theme-dropdown-value" id="interstate" name="interstate" value="{{ $selectedInterstate }}">
                                <button type="button" class="theme-dropdown-button" aria-haspopup="listbox" aria-expanded="false">
                                    <span class="theme-dropdown-text">{{ $selectedInterstate }}</span>
                                    <span class="theme-dropdown-arrow" aria-hidden="true"></span>
                                </button>
                                <ul class="theme-dropdown-menu" role="listbox" aria-label="Interstate options">
                                    <li><button type="button" class="theme-dropdown-option {{ $selectedInterstate === 'Yes' ? 'is-selected' : '' }}" data-value="Yes" role="option" aria-selected="{{ $selectedInterstate === 'Yes' ? 'true' : 'false' }}">Yes</button></li>
                                    <li><button type="button" class="theme-dropdown-option {{ $selectedInterstate === 'No' ? 'is-selected' : '' }}" data-value="No" role="option" aria-selected="{{ $selectedInterstate === 'No' ? 'true' : 'false' }}">No</button></li>
                                    <li class="theme-dropdown-empty">No matching options</li>
                                </ul>
                            </div>
                        </div>

                        <div class="field">
                            <label for="vehicleNo">Vehicle No.</label>
                            <input id="vehicleNo" type="text" name="vehicle_no" maxlength="50" value="{{ old('vehicle_no') }}" placeholder="Vehicle no.">
                        </div>

                        <div class="field">
                            <label for="transporter">Transporter</label>
                            <input id="transporter" type="text" name="transporter" maxlength="255" value="{{ old('transporter') }}">
                            <span class="char-limit" data-char-limit-for="transporter">0/255</span>
                        </div>

                        <div class="field">
                            <label for="driver">Driver</label>
                            <input id="driver" type="text" name="driver" maxlength="255" value="{{ old('driver') }}">
                            <span class="char-limit" data-char-limit-for="driver">0/255</span>
                        </div>

                        <div class="form-actions">
                            <div class="secondary-actions">
                                <button class="action-btn clear-btn" type="reset">Clear</button>
                                <button class="action-btn sample-btn" type="button" id="sampleButton">Sample</button>
                                <button class="action-btn cancel-edit-btn" type="button" id="cancelEditButton" hidden>Cancel</button>
                            </div>
                            <div class="primary-actions">
                                <button class="action-btn add-item-btn" type="button" id="addItemButton">Add Items</button>
                            </div>
                        </div>
                    </div>

                    <div class="item-modal" id="itemModal" role="dialog" aria-modal="true" aria-labelledby="itemModalTitle" aria-hidden="true">
                        <div class="item-window">
                            <div class="item-window-head">
                                <div class="item-window-title" id="itemModalTitle">Add Item</div>
                                <button type="button" class="item-close-btn" id="itemCloseButton" aria-label="Close add item form">&times;</button>
                            </div>

                            <div class="item-window-body">
                                <div class="form-grid item-form-grid">
                                    <div class="field wide">
                                        <label for="itemName">Item Name</label>
                                        <div class="theme-dropdown" data-theme-dropdown>
                                            <input type="text" class="theme-dropdown-value" id="itemName" name="item_name" value="{{ $selectedItem }}">
                                            <button type="button" class="theme-dropdown-button" aria-haspopup="listbox" aria-expanded="false">
                                                <span class="theme-dropdown-text">{{ $selectedItem ?: 'Select Item' }}</span>
                                                <span class="theme-dropdown-arrow" aria-hidden="true"></span>
                                            </button>
                                            <ul class="theme-dropdown-menu" role="listbox" aria-label="Item list">
                                                <li class="theme-dropdown-search-wrap">
                                                    <input type="search" class="theme-dropdown-search" placeholder="Search item" autocomplete="off">
                                                </li>
                                                @forelse ($items as $product)
                                                    @php $productName = is_object($product) ? ($product->Product_Name ?? '') : $product; @endphp
                                                    <li>
                                                        <button type="button" class="theme-dropdown-option {{ $selectedItem === $productName ? 'is-selected' : '' }}" data-value="{{ $productName }}" role="option" aria-selected="{{ $selectedItem === $productName ? 'true' : 'false' }}">
                                                            {{ $productName }}
                                                        </button>
                                                    </li>
                                                @empty
                                                    <li><button type="button" class="theme-dropdown-option" disabled>No items found</button></li>
                                                @endforelse
                                                @if ($selectedItem && !$items->contains(fn ($product) => (is_object($product) ? ($product->Product_Name ?? '') : $product) === $selectedItem))
                                                    <li><button type="button" class="theme-dropdown-option is-selected" data-value="{{ $selectedItem }}" role="option" aria-selected="true">{{ $selectedItem }}</button></li>
                                                @endif
                                                <li class="theme-dropdown-empty">No matching items</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label for="quantity">Quantity</label>
                                        <input id="quantity" class="number-input" type="number" name="quantity" min="0" max="999999.999" step="0.001" data-decimal-places="3" data-max-value="999999.999" value="{{ old('quantity', '0.000') }}">
                                    </div>

                                    <div class="field">
                                        <label for="rate">Rate</label>
                                        <input id="rate" class="number-input" type="number" name="rate" min="0" max="99999999.99" step="0.01" data-decimal-places="2" data-max-value="99999999.99" value="{{ old('rate', '0.00') }}" readonly>
                                    </div>

                                    <div class="field">
                                        <label for="amount">Amount</label>
                                        <input id="amount" class="number-input" type="number" name="amount" min="0" step="0.01" value="{{ old('amount', '0.00') }}" readonly>
                                    </div>

                                    <div class="field">
                                        <label for="discountPercent">Discount %</label>
                                        <input id="discountPercent" class="number-input" type="number" name="discount%" min="0" max="100" step="0.01" data-decimal-places="2" data-max-value="100" value="{{ old('discount%', '0.00') }}">
                                    </div>

                                    <div class="field">
                                        <label for="discountInRs">Discount In Rs</label>
                                        <input id="discountInRs" class="number-input" type="number" name="discountinrs" min="0" step="0.01" value="{{ old('discountinrs', '0.00') }}" readonly>
                                    </div>

                                    <div class="field">
                                        <label for="taxableAmount">Taxable Amount</label>
                                        <input id="taxableAmount" class="number-input" type="number" name="taxable_amount" min="0" step="0.01" value="{{ old('taxable_amount', '0.00') }}" readonly>
                                    </div>

                                    <div class="field">
                                        <label for="cgst">CGST %</label>
                                        <input id="cgst" class="number-input" type="text" inputmode="decimal" name="cgst" min="0" max="100" step="0.01" data-decimal-places="2" data-max-value="100" value="{{ old('cgst', '0.00') }}">
                                    </div>

                                    <div class="field">
                                        <label for="sgst">SGST %</label>
                                        <input id="sgst" class="number-input" type="text" inputmode="decimal" name="sgst" min="0" max="100" step="0.01" data-decimal-places="2" data-max-value="100" value="{{ old('sgst', '0.00') }}">
                                    </div>

                                    <div class="field">
                                        <label for="igst">IGST %</label>
                                        <input id="igst" class="number-input" type="text" inputmode="decimal" name="igst" min="0" max="100" step="0.01" data-decimal-places="2" data-max-value="100" value="{{ old('igst', '0.00') }}">
                                    </div>

                                    <div class="field">
                                        <label for="totalTaxAmount">Total Tax Amount</label>
                                        <input id="totalTaxAmount" class="number-input" type="number" name="total_tax_amount" min="0" step="0.01" value="{{ old('total_tax_amount', '0.00') }}" readonly>
                                    </div>

                                    <div class="field">
                                        <label for="totalAmount">Total Amount</label>
                                        <input id="totalAmount" class="number-input" type="number" name="total_amount" min="0" step="0.01" value="{{ old('total_amount', '0.00') }}" readonly>
                                    </div>
                                </div>

                                <div class="item-window-actions">
                                    <button type="button" class="action-btn clear-btn" id="itemModalClearButton">Clear</button>
                                    <button type="button" class="action-btn save-btn" id="acceptItemButton">Accept</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <datalist id="purchaseItems">
                        @foreach ($items as $product)
                            <option value="{{ is_object($product) ? ($product->Product_Name ?? '') : $product }}"></option>
                        @endforeach
                    </datalist>

                    <div id="pendingPurchaseItems" hidden></div>
                </form>
            </section>

            <section class="panel list-panel" id="purchaseList" aria-labelledby="purchaseListTitle">
                <div class="table-toolbar">
                    <div class="toolbar-title" id="purchaseListTitle">Purchase List</div>
                    <div class="toolbar-actions">
                        <input type="search" class="list-search" id="purchaseListSearch" placeholder="Search purchase" data-table-search="purchaseItemsBody">
                        <button class="action-btn save-btn" type="submit" id="savePendingButton" form="purchaseForm" value="savePending">Save</button>
                        <a href="#" class="action-btn sample-btn" id="purchaseEntryPreviewBtn" hidden>Preview</a>
                        <div class="toolbar-total">Total: <span id="pendingTotalAmount">0.00</span></div>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Particulars</th>
                                <th class="number-cell">Qty.</th>
                                <th class="number-cell">Rate</th>
                                <th class="number-cell">Amount</th>
                                <th class="number-cell">Discount %</th>
                                <th class="number-cell">Discount</th>
                                <th class="number-cell">Taxable Amt.</th>
                                <th class="number-cell">Total Amount</th>
                                <th class="number-cell">CGST %</th>
                                <th class="number-cell">SGST %</th>
                                <th class="number-cell">IGST %</th>
                                <th class="number-cell">Total Tax</th>
                                <th class="actions-column">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="purchaseItemsBody">
                            <tr>
                                <td colspan="14" class="empty-state">No purchase entries found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="purchase-summary-grid" aria-label="Pending purchase summary">
                    <div class="summary-box">
                        <span class="summary-label">Total Entry</span>
                        <span class="summary-value" id="pendingSummaryCount">0</span>
                    </div>
                    <div class="summary-box">
                        <span class="summary-label">Taxable Amount</span>
                        <span class="summary-value" id="pendingSummaryTaxable">0.00</span>
                    </div>
                    <div class="summary-box">
                        <span class="summary-label">CGST Amount</span>
                        <span class="summary-value" id="pendingSummaryCgst">0.00</span>
                    </div>
                    <div class="summary-box">
                        <span class="summary-label">SGST Amount</span>
                        <span class="summary-value" id="pendingSummarySgst">0.00</span>
                    </div>
                    <div class="summary-box">
                        <span class="summary-label">IGST Amount</span>
                        <span class="summary-value" id="pendingSummaryIgst">0.00</span>
                    </div>
                    <div class="summary-box">
                        <span class="summary-label">Total Discount</span>
                        <span class="summary-value" id="pendingSummaryDiscount">0.00</span>
                    </div>
                    <div class="summary-box total">
                        <span class="summary-label">Total Amount</span>
                        <span class="summary-value" id="pendingSummaryTotal">0.00</span>
                    </div>
                </div>
            </section>

            </div>
        </main>
    </div>

    <div class="sample-modal" id="samplePurchaseModal" role="dialog" aria-modal="true" aria-labelledby="sampleModalTitle" aria-hidden="true">
        <div class="sample-window">
            <div class="sample-window-head">
                <div class="sample-window-title" id="sampleModalTitle">Purchase Sample</div>
                <button type="button" class="sample-close-btn" id="sampleCloseButton" aria-label="Close sample form">&times;</button>
            </div>

            <form class="sample-form" id="samplePurchaseForm" method="POST" action="{{ $hasSampleStoreRoute ? route('purchase.sample.store') : '#' }}" autocomplete="off">
                @csrf
                <input id="sampleRefNo" name="ref_no" type="hidden" value="">
                <div class="sample-details">
                    <div class="sample-field">
                        <label for="sampleDate">Date</label>
                        <input id="sampleDate" name="date" type="date" value="{{ $today }}">
                    </div>
                    <div class="sample-field">
                        <label for="sampleTanker">Tanker</label>
                        <input id="sampleTanker" name="tanker" type="text" value="">
                    </div>
                    <div class="sample-field">
                        <label for="sampleTransport">Transport</label>
                        <input id="sampleTransport" name="transport" type="text" value="">
                    </div>
                    <div class="sample-field">
                        <label for="sampleOilCompany">Oil Company</label>
                        <input id="sampleOilCompany" name="oil_company" type="text" value="">
                    </div>
                    <div class="sample-field">
                        <label for="sampleInvoiceNo">Invoice No.</label>
                        <input id="sampleInvoiceNo" name="invoice_no" type="text" value="">
                    </div>
                    <div class="sample-field">
                        <label for="sampleProduct">Product</label>
                        <input id="sampleProduct" name="product" type="text" value="">
                    </div>
                </div>

                <div class="sample-products">
                    @foreach (['HSD', 'MS', 'POWER MS'] as $productLabel)
                        @php
                            $productKey = \Illuminate\Support\Str::of($productLabel)->lower()->replace(' ', '-');
                        @endphp
                        <div class="sample-product-card" data-sample-product="{{ $productLabel }}">
                            <h3>{{ $productLabel }}</h3>
                            <div class="sample-line">
                                <label for="sample-{{ $productKey }}-temp">Temp</label>
                                <input id="sample-{{ $productKey }}-temp" name="{{ str_replace('-', '_', $productKey) }}_temp" type="number" step="0.01" value="">
                            </div>
                            <div class="sample-line">
                                <label for="sample-{{ $productKey }}-density">Base Density</label>
                                <input id="sample-{{ $productKey }}-density" name="{{ str_replace('-', '_', $productKey) }}_base_density" type="number" step="0.0001" value="">
                            </div>
                            <div class="sample-line">
                                <label for="sample-{{ $productKey }}-value">Value</label>
                                <input id="sample-{{ $productKey }}-value" name="{{ str_replace('-', '_', $productKey) }}_value" type="number" step="0.0001" value="" readonly>
                            </div>
                            <div class="sample-line">
                                <label for="sample-{{ $productKey }}-sample">Sample</label>
                                <input id="sample-{{ $productKey }}-sample" name="{{ str_replace('-', '_', $productKey) }}_sample" type="text" value="" readonly>
                            </div>
                            <div class="sample-line">
                                <label for="sample-{{ $productKey }}-invoice-sample">Invoice Sample</label>
                                <input id="sample-{{ $productKey }}-invoice-sample" name="{{ str_replace('-', '_', $productKey) }}_invoice_sample" type="text" value="">
                            </div>
                            <div class="sample-line">
                                <label for="sample-{{ $productKey }}-plastic-seal">Plastic Seal</label>
                                <input id="sample-{{ $productKey }}-plastic-seal" name="{{ str_replace('-', '_', $productKey) }}_plastic_seal" type="text" value="">
                            </div>
                            <div class="sample-line">
                                <label for="sample-{{ $productKey }}-aluminium-seal">Aluminium Seal</label>
                                <input id="sample-{{ $productKey }}-aluminium-seal" name="{{ str_replace('-', '_', $productKey) }}_aluminium_seal" type="text" value="">
                            </div>
                            <button type="button" class="sample-preview-btn" data-sample-preview="{{ $productLabel }}">Preview</button>
                        </div>
                    @endforeach
                </div>
            </form>

            <div class="sample-window-actions">
                <button type="submit" form="samplePurchaseForm" class="sample-save-btn" id="sampleSaveButton">Save</button>
            </div>
        </div>
    </div>

    <div class="delete-modal" id="deleteConfirmModal" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle" aria-hidden="true">
        <div class="delete-dialog">
            <h2 class="delete-dialog-title" id="deleteModalTitle">Do you want to delete?</h2>
            <p class="delete-dialog-body">Are you sure you want to delete <strong id="deletePurchaseName">this entry</strong>? This action cannot be undone.</p>
            <div class="delete-dialog-actions">
                <button type="button" class="modal-no-btn" id="deleteCancelBtn">No</button>
                <button type="button" class="modal-yes-btn" id="deleteConfirmBtn">Yes</button>
            </div>
        </div>
    </div>

    @if ($previewRefNo && $previewPurchases->isNotEmpty())
        <div class="preview-modal is-open" id="purchasePreviewModal" role="dialog" aria-modal="true" aria-labelledby="purchasePreviewTitle" aria-hidden="false">
            <div class="preview-window">
                <div class="preview-head">
                    <div class="preview-title" id="purchasePreviewTitle">Purchase Invoice Preview</div>
                    <div class="preview-actions">
                        @if ($hasPreviewReferencePdfRoute)
                            <a class="preview-action-btn" href="{{ route('RegisterPurchaseFilter.reference.pdf', ['refNo' => $previewRefNo]) }}" target="_blank" rel="noopener" data-themed-export>PDF</a>
                        @endif
                        @if ($hasPreviewReferenceExcelRoute)
                            <a class="preview-action-btn" href="{{ route('RegisterPurchaseFilter.reference.excel', ['refNo' => $previewRefNo]) }}" data-themed-export>Excel</a>
                        @endif
                        <button type="button" class="preview-action-btn" id="purchasePreviewPrintBtn">Print</button>
                        <button type="button" class="preview-close-btn" id="purchasePreviewCloseBtn" aria-label="Close preview">&times;</button>
                    </div>
                </div>

                <div class="preview-body">
                    <section class="invoice-preview" id="purchasePreviewPrint">
                        <div class="invoice-company">
                            <h2>{{ $companyName }}</h2>
                            @if ($companyOffice)
                                <div>{{ $companyOffice }}</div>
                            @endif
                            @if ($companyPhone || $companyMobile || $companyEmail)
                                <div>
                                    @if ($companyPhone || $companyMobile)
                                        Phone: {{ $companyPhone ?: $companyMobile }}
                                    @endif
                                    @if (($companyPhone || $companyMobile) && $companyEmail)
                                        |
                                    @endif
                                    @if ($companyEmail)
                                        Email: {{ $companyEmail }}
                                    @endif
                                </div>
                            @endif
                            @if ($companyGstNo)
                                <div>GSTIN: {{ $companyGstNo }}</div>
                            @endif
                        </div>

                        <div class="invoice-meta-strip">
                            <span>Ref No: {{ $previewRefNo }}</span>
                            <span>Date: {{ optional($previewFirst)->date ? \Carbon\Carbon::parse($previewFirst->date)->format('d-m-Y') : $today }}</span>
                            <span>Original</span>
                        </div>

                        <div class="invoice-title">Purchase Invoice</div>

                        <div class="invoice-parties">
                            <div class="invoice-block">
                                <strong>Invoice Details</strong>
                                <span>Ref No: {{ $previewRefNo }}</span>
                                <span>Invoice No: {{ optional($previewFirst)->invoice_no ?: '-' }}</span>
                                <span>Date: {{ optional($previewFirst)->date ? \Carbon\Carbon::parse($previewFirst->date)->format('d-m-Y') : $today }}</span>
                                <span>Interstate: {{ optional($previewFirst)->interstate ?: 'No' }}</span>
                                <span>Total Items: {{ $previewPurchases->count() }}</span>
                            </div>
                            <div class="invoice-block">
                                <strong>Supplier</strong>
                                <span>{{ optional($previewFirst)->perticulars ?: '-' }}</span>
                                <span>{{ data_get($previewFirst, 'postal address') ?: '-' }}</span>
                                <span>{{ optional($previewFirst)->location ?: '-' }}</span>
                            </div>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <th>S.N.</th>
                                    <th>Product No.</th>
                                    <th>Particulars</th>
                                    <th class="number-cell">Qty</th>
                                    <th class="number-cell">Rate</th>
                                    <th class="number-cell">Disc %</th>
                                    <th class="number-cell">Taxable Amt</th>
                                    <th class="number-cell">CGST %</th>
                                    <th class="number-cell">SGST %</th>
                                    <th class="number-cell">IGST %</th>
                                    <th class="number-cell">Total Tax</th>
                                    <th class="number-cell">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($previewPurchases as $index => $purchase)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $purchase->item_name ?: '-' }}</td>
                                        <td>{{ $purchase->item_name ?: '-' }}</td>
                                        <td class="number-cell">{{ number_format((float) $purchase->quantity, 3) }}</td>
                                        <td class="number-cell">{{ number_format((float) $purchase->rate, 2) }}</td>
                                        <td class="number-cell">{{ number_format((float) $purchase->{'discount%'}, 2) }}</td>
                                        <td class="number-cell">{{ number_format((float) $purchase->taxable_amount, 2) }}</td>
                                        <td class="number-cell">{{ number_format((float) $purchase->cgst, 2) }}</td>
                                        <td class="number-cell">{{ number_format((float) $purchase->sgst, 2) }}</td>
                                        <td class="number-cell">{{ number_format((float) $purchase->igst, 2) }}</td>
                                        <td class="number-cell">{{ number_format((float) $purchase->total_tax_amount, 2) }}</td>
                                        <td class="number-cell">{{ number_format((float) $purchase->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="invoice-totals">
                            <div class="invoice-note">This preview is generated after saving the purchase entry.</div>
                            <div class="invoice-total-box">
                                <div class="invoice-total-row">
                                    <span>Subtotal</span>
                                    <span>Rs {{ number_format($previewSubtotal, 2) }}</span>
                                </div>
                                <div class="invoice-total-row">
                                    <span>Discount</span>
                                    <span>Rs {{ number_format($previewDiscount, 2) }}</span>
                                </div>
                                <div class="invoice-total-row">
                                    <span>Taxable Amount</span>
                                    <span>Rs {{ number_format($previewTaxable, 2) }}</span>
                                </div>
                                <div class="invoice-total-row">
                                    <span>Total Tax</span>
                                    <span>Rs {{ number_format($previewTax, 2) }}</span>
                                </div>
                                <div class="invoice-total-row">
                                    <span>Total Purchase Amount</span>
                                    <span>Rs {{ number_format($previewTotal, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.querySelectorAll('[data-theme-dropdown]').forEach((dropdown) => {
            const valueInput = dropdown.querySelector('.theme-dropdown-value');
            const button = dropdown.querySelector('.theme-dropdown-button');
            const text = dropdown.querySelector('.theme-dropdown-text');
            const search = dropdown.querySelector('.theme-dropdown-search');
            const options = Array.from(dropdown.querySelectorAll('.theme-dropdown-option:not(:disabled)'));
            const empty = dropdown.querySelector('.theme-dropdown-empty');
            const placeholder = text.textContent.trim();

            const closeDropdown = () => {
                dropdown.classList.remove('is-open');
                button.setAttribute('aria-expanded', 'false');
                dropdown.closest('.form-panel')?.classList.remove('has-open-dropdown');
            };

            const openDropdown = () => {
                document.querySelectorAll('[data-theme-dropdown].is-open').forEach((openItem) => {
                    if (openItem !== dropdown) {
                        openItem.classList.remove('is-open');
                        openItem.querySelector('.theme-dropdown-button')?.setAttribute('aria-expanded', 'false');
                        openItem.closest('.form-panel')?.classList.remove('has-open-dropdown');
                    }
                });

                dropdown.classList.add('is-open');
                button.setAttribute('aria-expanded', 'true');
                dropdown.closest('.form-panel')?.classList.add('has-open-dropdown');
                search?.focus();
            };

            const setValue = (value, label = value) => {
                valueInput.value = value;
                valueInput.dispatchEvent(new Event('change', { bubbles: true }));
                text.textContent = label || placeholder;
                options.forEach((option) => {
                    const isSelected = option.dataset.value === value;
                    option.classList.toggle('is-selected', isSelected);
                    option.setAttribute('aria-selected', String(isSelected));
                });
            };

            const filterOptions = () => {
                const query = (search?.value || '').trim().toLowerCase();
                let visibleCount = 0;

                options.forEach((option) => {
                    const isVisible = option.textContent.trim().toLowerCase().includes(query);
                    option.closest('li').style.display = isVisible ? '' : 'none';
                    visibleCount += isVisible ? 1 : 0;
                });

                empty?.classList.toggle('is-visible', visibleCount === 0);
            };

            button.addEventListener('click', () => {
                if (dropdown.classList.contains('is-open')) {
                    closeDropdown();
                    return;
                }

                openDropdown();
            });

            options.forEach((option) => {
                option.addEventListener('click', () => {
                    const selectedValue = option.dataset.value || option.textContent.trim();
                    setValue(selectedValue, option.textContent.trim());
                    if (valueInput?.id === 'perticulars') {
                        syncSupplierDetails(option);
                    }
                    if (valueInput?.id === 'itemName') {
                        applyItemRate(selectedValue);
                    }
                    closeDropdown();
                    button.focus();
                });
            });

            search?.addEventListener('input', filterOptions);

            dropdown.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeDropdown();
                    button.focus();
                }
            });
        });

        document.addEventListener('click', (event) => {
            document.querySelectorAll('[data-theme-dropdown].is-open').forEach((dropdown) => {
                if (!dropdown.contains(event.target)) {
                    dropdown.classList.remove('is-open');
                    dropdown.querySelector('.theme-dropdown-button')?.setAttribute('aria-expanded', 'false');
                    dropdown.closest('.form-panel')?.classList.remove('has-open-dropdown');
                }
            });
        });

        const interstateInput = document.getElementById('interstate');
        const itemNameInput = document.getElementById('itemName');
        const postalAddressInput = document.getElementById('postalAddress');
        const locationInput = document.getElementById('location');
        const vehicleNoInput = document.getElementById('vehicleNo');
        const quantityInput = document.getElementById('quantity');
        const rateInput = document.getElementById('rate');
        const amountInput = document.getElementById('amount');
        const discountPercentInput = document.getElementById('discountPercent');
        const discountInRsInput = document.getElementById('discountInRs');
        const taxableAmountInput = document.getElementById('taxableAmount');
        const totalAmountInput = document.getElementById('totalAmount');
        const cgstInput = document.getElementById('cgst');
        const sgstInput = document.getElementById('sgst');
        const igstInput = document.getElementById('igst');
        const totalTaxAmountInput = document.getElementById('totalTaxAmount');
        const limitedNumberInputs = document.querySelectorAll('[data-decimal-places]');
        const charLimitLabels = document.querySelectorAll('[data-char-limit-for]');
        const purchaseForm = document.getElementById('purchaseForm');
        const formMethodInput = document.getElementById('purchaseFormMethod');
        const saveButton = document.getElementById('saveButton');
        const sampleButton = document.getElementById('sampleButton');
        const sampleModal = document.getElementById('samplePurchaseModal');
        const samplePurchaseForm = document.getElementById('samplePurchaseForm');
        const sampleSaveButton = document.getElementById('sampleSaveButton');
        const sampleCloseButton = document.getElementById('sampleCloseButton');
        const addItemButton = document.getElementById('addItemButton');
        const addItemModal = document.getElementById('itemModal');
        const itemCloseButton = document.getElementById('itemCloseButton');
        const acceptItemButton = document.getElementById('acceptItemButton');
        const itemModalClearButton = document.getElementById('itemModalClearButton');
        const itemModalTitle = document.getElementById('itemModalTitle');
        const cancelEditButton = document.getElementById('cancelEditButton');
        const purchaseList = document.getElementById('purchaseList');
        const purchaseItemsBody = document.getElementById('purchaseItemsBody');
        const pendingPurchaseItems = document.getElementById('pendingPurchaseItems');
        const savePendingButton = document.getElementById('savePendingButton');
        const pendingRowCount = document.getElementById('pendingRowCount');
        const pendingTotalAmount = document.getElementById('pendingTotalAmount');
        const pendingSummaryCount = document.getElementById('pendingSummaryCount');
        const pendingSummaryTaxable = document.getElementById('pendingSummaryTaxable');
        const pendingSummaryCgst = document.getElementById('pendingSummaryCgst');
        const pendingSummarySgst = document.getElementById('pendingSummarySgst');
        const pendingSummaryIgst = document.getElementById('pendingSummaryIgst');
        const pendingSummaryDiscount = document.getElementById('pendingSummaryDiscount');
        const pendingSummaryTotal = document.getElementById('pendingSummaryTotal');
        const purchasePreviewModal = document.getElementById('purchasePreviewModal');
        const purchasePreviewCloseBtn = document.getElementById('purchasePreviewCloseBtn');
        const purchasePreviewPrintBtn = document.getElementById('purchasePreviewPrintBtn');
        const previousPurchaseEntryBtn = document.getElementById('previousPurchaseEntryBtn');
        const nextPurchaseEntryBtn = document.getElementById('nextPurchaseEntryBtn');
        const purchaseEntryNavCount = document.getElementById('purchaseEntryNavCount');
        const purchaseEntryPreviewBtn = document.getElementById('purchaseEntryPreviewBtn');

        const purchaseListUrl = @json($listUrl);
        const samplePreviewUrl = @json($hasSamplePreviewRoute ? route('purchase.sample.preview') : null);
        const nextRefNo = @json((string) ($nextRefNo ?? 1));
        const latestRates = @json($latestRates ?? []);
        const densityLookup = @json($densityLookup ?? []);
        const purchaseSamplesByRef = @json($purchaseSamplesByRef ?? []);
        const purchaseNavigationEntries = @json($purchaseNavigationEntries);
        const initialViewRef = @json((string) request('view_ref', ''));
        const editButtons = document.querySelectorAll('[data-edit-purchase]');
        const tableSearchInputs = document.querySelectorAll('[data-table-search]');
        const deleteForms = document.querySelectorAll('.delete-form');
        const deleteModal = document.getElementById('deleteConfirmModal');
        const deletePurchaseName = document.getElementById('deletePurchaseName');
        const deleteCancelBtn = document.getElementById('deleteCancelBtn');
        const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');
        let pendingDeleteForm = null;
        let pendingRemoveRow = null;
        let pendingRemoveGroup = null;
        let editingPendingRow = null;
        let editingPendingGroup = null;
        let currentPurchaseEntryIndex = purchaseNavigationEntries.length;

        window.setTimeout(() => {
            const successMessage = document.getElementById('success-message');

            if (!successMessage) {
                return;
            }

            successMessage.classList.add('is-hiding');
            window.setTimeout(() => successMessage.remove(), 200);
        }, 2000);

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

        const removePreviewRefFromUrl = () => {
            const url = new URL(window.location.href);

            if (!url.searchParams.has('preview_ref')) {
                return;
            }

            url.searchParams.delete('preview_ref');
            window.history.replaceState({}, '', url.toString());
        };

        tableSearchInputs.forEach((input) => {
            const tableBody = document.getElementById(input.dataset.tableSearch);

            if (!tableBody) {
                return;
            }

            const dataRows = Array.from(tableBody.querySelectorAll('tr')).filter((row) => !row.dataset.searchEmpty);
            const columnCount = tableBody.closest('table')?.querySelectorAll('thead th').length || 1;
            const emptyRow = document.createElement('tr');
            emptyRow.dataset.searchEmpty = 'true';
            emptyRow.hidden = true;
            emptyRow.innerHTML = `<td colspan="${columnCount}" class="empty-state">No matching entries found.</td>`;
            tableBody.appendChild(emptyRow);

            input.addEventListener('input', () => {
                const query = input.value.trim().toLowerCase();
                let visibleCount = 0;

                dataRows.forEach((row) => {
                    const isVisible = !query || row.textContent.toLowerCase().includes(query);
                    row.hidden = !isVisible;
                    visibleCount += isVisible ? 1 : 0;
                });

                emptyRow.hidden = visibleCount !== 0;
            });
        });

        const syncSupplierDetails = (option) => {
            if (!option) {
                return;
            }

            if (postalAddressInput) {
                postalAddressInput.value = (option.dataset.address || '').slice(0, postalAddressInput.maxLength || 255);
                postalAddressInput.dispatchEvent(new Event('input', { bubbles: true }));
            }

            if (locationInput) {
                locationInput.value = (option.dataset.location || '').slice(0, locationInput.maxLength || 255);
                locationInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        };

        const applyItemRate = (itemName) => {
            const normalizedName = String(itemName || '').trim();

            if (!rateInput || !normalizedName || !Object.prototype.hasOwnProperty.call(latestRates, normalizedName)) {
                return;
            }

            rateInput.value = Number.parseFloat(latestRates[normalizedName] || '0').toFixed(2);

            if (typeof calculatePurchaseTotals === 'function') {
                calculatePurchaseTotals();
            }
        };

        charLimitLabels.forEach((label) => {
            const input = document.getElementById(label.dataset.charLimitFor);
            const maxLength = Number(input?.maxLength || 0);

            const updateLimit = () => {
                label.textContent = `${input?.value.length || 0}/${maxLength}`;
            };

            input?.addEventListener('input', updateLimit);
            updateLimit();
        });

        const setDropdownValue = (dropdown, value) => {
            if (!dropdown) {
                return;
            }

            const input = dropdown.querySelector('.theme-dropdown-value');
            const text = dropdown.querySelector('.theme-dropdown-text');
            const options = Array.from(dropdown.querySelectorAll('.theme-dropdown-option:not(:disabled)'));
            const fallback = input?.id === 'perticulars'
                ? 'Select Particulars'
                : (input?.id === 'itemName' ? 'Select Item' : 'No');

            if (input) {
                input.value = value || '';
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }

            if (text) {
                text.textContent = value || fallback;
            }

            options.forEach((option) => {
                const selected = option.dataset.value === value;
                option.classList.toggle('is-selected', selected);
                option.setAttribute('aria-selected', String(selected));
            });
        };

        purchaseForm?.addEventListener('focusin', (event) => {
            const focusedField = event.target.closest('.field, .form-actions');

            if (!focusedField || !purchaseForm) {
                return;
            }

            window.requestAnimationFrame(() => {
                const formRect = purchaseForm.getBoundingClientRect();
                const fieldRect = focusedField.getBoundingClientRect();
                const actionsHeight = purchaseForm.querySelector('.form-actions')?.offsetHeight || 0;
                const topGap = 12;
                const bottomGap = actionsHeight + 18;
                const visibleTop = formRect.top + topGap;
                const visibleBottom = formRect.bottom - bottomGap;

                if (fieldRect.bottom > visibleBottom) {
                    purchaseForm.scrollBy({
                        top: fieldRect.bottom - visibleBottom,
                        behavior: 'smooth',
                    });
                } else if (fieldRect.top < visibleTop) {
                    purchaseForm.scrollBy({
                        top: fieldRect.top - visibleTop,
                        behavior: 'smooth',
                    });
                }
            });
        });

        limitedNumberInputs.forEach((input) => {
            input.addEventListener('keydown', (event) => {
                if (['e', 'E', '+', '-'].includes(event.key)) {
                    event.preventDefault();
                }
            });

            input.addEventListener('input', () => {
                const decimalPlaces = Number(input.dataset.decimalPlaces || 2);
                const maxValue = Number(input.dataset.maxValue || input.max || 0);
                const parts = input.value.replace(',', '.').replace(/[^0-9.]/g, '').split('.');
                const integerPart = parts[0] || '';
                const decimalPart = parts.slice(1).join('').slice(0, decimalPlaces);
                let cleanedValue = parts.length > 1 ? `${integerPart}.${decimalPart}` : integerPart;

                if (maxValue && Number(cleanedValue) > maxValue) {
                    cleanedValue = String(maxValue);
                }

                input.value = cleanedValue;
            });
        });

        const toggleTaxFields = () => {
            const value = interstateInput?.value;
            const isInterstate = value === 'Yes';
            const isLocal = value === 'No';

            if (cgstInput) {
                cgstInput.disabled = isInterstate;
                if (isInterstate) {
                    cgstInput.value = '';
                }
            }

            if (sgstInput) {
                sgstInput.disabled = isInterstate;
                if (isInterstate) {
                    sgstInput.value = '';
                }
            }

            if (igstInput) {
                igstInput.disabled = isLocal;
                if (isLocal) {
                    igstInput.value = '0.00';
                }
            }

            calculateTaxAmounts();
        };

        const setCreateMode = () => {
            if (!purchaseForm || !formMethodInput) {
                return;
            }

            purchaseForm.action = purchaseForm.dataset.storeUrl;
            formMethodInput.disabled = true;
            if (saveButton) saveButton.textContent = 'Enter';
            cancelEditButton.hidden = true;
            document.getElementById('refNo').value = nextRefNo;
            currentPurchaseEntryIndex = purchaseNavigationEntries.length;
            updatePurchaseEntryNavState();
        };

        const setEditMode = (button) => {
            purchaseForm.action = button.dataset.updateUrl;
            formMethodInput.disabled = false;
            if (saveButton) saveButton.textContent = 'Enter';
            cancelEditButton.hidden = false;

            document.getElementById('refNo').value = button.dataset.refNo || '';
            setDropdownValue(document.querySelector('#perticulars')?.closest('[data-theme-dropdown]'), button.dataset.perticulars || '');
            setDropdownValue(document.querySelector('#interstate')?.closest('[data-theme-dropdown]'), button.dataset.interstate || 'No');
            document.getElementById('postalAddress').value = button.dataset.postalAddress || '';
            document.getElementById('location').value = button.dataset.location || '';
            document.getElementById('purchaseDate').value = button.dataset.date || '';
            document.getElementById('invoiceNo').value = button.dataset.invoiceNo || '';
            document.getElementById('vehicleNo').value = button.dataset.vehicleNo || '';
            document.getElementById('transporter').value = button.dataset.transporter || '';
            document.getElementById('driver').value = button.dataset.driver || '';
            setDropdownValue(document.querySelector('#itemName')?.closest('[data-theme-dropdown]'), button.dataset.itemName || '');
            quantityInput.value = button.dataset.quantity || '0.000';
            rateInput.value = button.dataset.rate || '0.00';
            discountPercentInput.value = button.dataset.discountPercent || '0.00';
            cgstInput.value = button.dataset.cgst || '0.00';
            sgstInput.value = button.dataset.sgst || '0.00';
            igstInput.value = button.dataset.igst || '0.00';

            calculatePurchaseTotals();
            toggleTaxFields();
            purchaseForm.closest('.form-panel')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        };

        const updatePurchaseEntryNavState = () => {
            const total = purchaseNavigationEntries.length;
            const totalWithNewEntry = total + 1;
            const isNewEntry = currentPurchaseEntryIndex >= total;

            if (previousPurchaseEntryBtn) {
                previousPurchaseEntryBtn.disabled = currentPurchaseEntryIndex <= 0;
            }

            if (nextPurchaseEntryBtn) {
                nextPurchaseEntryBtn.disabled = isNewEntry;
            }

            if (purchaseEntryNavCount) {
                const visibleIndex = Math.min(currentPurchaseEntryIndex, total) + 1;
                purchaseEntryNavCount.textContent = `${visibleIndex}/${totalWithNewEntry}`;
            }

            if (purchaseEntryPreviewBtn) {
                const entry = purchaseNavigationEntries[currentPurchaseEntryIndex];
                const isViewingSavedEntry = Boolean(entry?.Ref_no);

                purchaseEntryPreviewBtn.hidden = !isViewingSavedEntry;

                if (isViewingSavedEntry) {
                    const previewUrl = new URL(purchaseListUrl, window.location.origin);
                    previewUrl.searchParams.set('date', entry.date || @json($today));
                    previewUrl.searchParams.set('preview_ref', entry.Ref_no);
                    purchaseEntryPreviewBtn.href = previewUrl.toString();
                }
            }
        };

        const loadNewPurchaseEntryForView = () => {
            currentPurchaseEntryIndex = purchaseNavigationEntries.length;
            purchaseForm.action = purchaseForm.dataset.storeUrl;
            formMethodInput.disabled = true;
            cancelEditButton.hidden = true;
            pendingPurchaseItems?.querySelectorAll('[data-pending-purchase-item]').forEach((group) => group.remove());

            if (savePendingButton) {
                savePendingButton.disabled = true;
            }

            document.getElementById('purchaseDate').value = @json($selectedDate ?? $today);
            document.getElementById('refNo').value = nextRefNo;
            document.getElementById('invoiceNo').value = '';
            document.getElementById('postalAddress').value = '';
            document.getElementById('location').value = '';
            document.getElementById('vehicleNo').value = '';
            document.getElementById('transporter').value = '';
            document.getElementById('driver').value = '';
            setDropdownValue(document.querySelector('#perticulars')?.closest('[data-theme-dropdown]'), '');
            setDropdownValue(document.querySelector('#interstate')?.closest('[data-theme-dropdown]'), 'No');
            setDropdownValue(document.querySelector('#itemName')?.closest('[data-theme-dropdown]'), '');
            quantityInput.value = '0.000';
            rateInput.value = '0.00';
            amountInput.value = '0.00';
            discountPercentInput.value = '0.00';
            discountInRsInput.value = '0.00';
            taxableAmountInput.value = '0.00';
            cgstInput.value = '0.00';
            sgstInput.value = '0.00';
            igstInput.value = '0.00';
            totalTaxAmountInput.value = '0.00';
            totalAmountInput.value = '0.00';

            renderReferenceRows([]);
            calculatePurchaseTotals();
            toggleTaxFields();
            updateCharLimitLabels();
            updatePurchaseEntryNavState();
        };

        const loadPurchaseEntryForView = (index) => {
            const total = purchaseNavigationEntries.length;
            const safeIndex = Math.max(0, Math.min(total - 1, index));
            const entry = purchaseNavigationEntries[safeIndex];

            if (!entry) {
                return;
            }

            const firstItem = entry.items?.[0] || {};
            currentPurchaseEntryIndex = safeIndex;
            purchaseForm.action = purchaseForm.dataset.storeUrl;
            formMethodInput.disabled = true;
            cancelEditButton.hidden = true;
            pendingPurchaseItems?.querySelectorAll('[data-pending-purchase-item]').forEach((group) => group.remove());
            if (savePendingButton) {
                savePendingButton.disabled = true;
            }

            document.getElementById('purchaseDate').value = entry.date || @json($today);
            document.getElementById('refNo').value = entry.Ref_no || nextRefNo;
            document.getElementById('invoiceNo').value = entry.invoice_no || '';
            document.getElementById('postalAddress').value = entry['postal address'] || '';
            document.getElementById('location').value = entry.location || '';
            document.getElementById('vehicleNo').value = entry.vehicle_no || '';
            document.getElementById('transporter').value = entry.transporter || '';
            document.getElementById('driver').value = entry.driver || '';
            setDropdownValue(document.querySelector('#perticulars')?.closest('[data-theme-dropdown]'), entry.perticulars || '');
            setDropdownValue(document.querySelector('#interstate')?.closest('[data-theme-dropdown]'), entry.interstate || 'No');
            setDropdownValue(document.querySelector('#itemName')?.closest('[data-theme-dropdown]'), firstItem.item_name || '');
            quantityInput.value = firstItem.quantity || '0.000';
            rateInput.value = firstItem.rate || '0.00';
            discountPercentInput.value = firstItem['discount%'] || '0.00';
            cgstInput.value = firstItem.cgst || '0.00';
            sgstInput.value = firstItem.sgst || '0.00';
            igstInput.value = firstItem.igst || '0.00';

            calculatePurchaseTotals();
            toggleTaxFields();
            updateCharLimitLabels();
            renderReferenceRows(entry.items || []);
            updatePurchaseEntryNavState();
        };

        const openSampleModal = () => {
            syncSampleFormFromPurchase();
            sampleModal?.classList.add('is-open');
            sampleModal?.setAttribute('aria-hidden', 'false');
        };

        const closeSampleModal = () => {
            sampleModal?.classList.remove('is-open');
            sampleModal?.setAttribute('aria-hidden', 'true');
        };

        const sampleProductKeyForItem = (itemName) => {
            const normalized = String(itemName || '').toUpperCase();

            if (normalized.includes('POWER')) {
                return 'POWER MS';
            }

            if (normalized.includes('DIESEL') || normalized.includes('HSD')) {
                return 'HSD';
            }

            if (normalized.includes('PETROL') || normalized === 'MS' || normalized.includes(' MS')) {
                return 'MS';
            }

            return '';
        };

        const sampleDensityKey = (value) => {
            const number = Number.parseFloat(value || '0');

            if (!Number.isFinite(number)) {
                return '';
            }

            return number.toFixed(4).replace(/\.?0+$/, '');
        };

        const sampleLookupKeyForCard = (productLabel) => {
            if (productLabel === 'HSD') {
                return 'hsd';
            }

            if (productLabel === 'MS') {
                return 'ms';
            }

            if (productLabel === 'POWER MS') {
                return 'power-ms';
            }

            return '';
        };

        const setSampleCalculatedValue = (card, value) => {
            const valueInput = card?.querySelector('input[id$="-value"]');
            const sampleInput = card?.querySelector('input[id$="-sample"]');
            const formattedValue = value === '' || value === null || value === undefined
                ? ''
                : Number.parseFloat(value || '0').toFixed(4);

            if (valueInput) {
                valueInput.value = formattedValue;
            }

            if (sampleInput) {
                sampleInput.value = formattedValue;
            }
        };

        const applySampleValueFromDensity = (card) => {
            const lookupKey = sampleLookupKeyForCard(card?.dataset.sampleProduct || '');
            const tempInput = card?.querySelector('input[id$="-temp"]');
            const densityInput = card?.querySelector('input[id$="-density"]');
            const tempKey = sampleDensityKey(tempInput?.value);
            const densityKey = sampleDensityKey(densityInput?.value);
            const match = densityLookup?.[lookupKey]?.[`${tempKey}|${densityKey}`];

            if (!String(tempInput?.value || '').trim() || !String(densityInput?.value || '').trim() || !match) {
                setSampleCalculatedValue(card, '');
                return;
            }

            setSampleCalculatedValue(card, match.chart_val);
        };

        const applySampleDensityFromTemp = (card) => {
            const lookupKey = sampleLookupKeyForCard(card?.dataset.sampleProduct || '');
            const tempInput = card?.querySelector('input[id$="-temp"]');
            const densityInput = card?.querySelector('input[id$="-density"]');
            const tempKey = sampleDensityKey(tempInput?.value);
            const match = densityLookup?.[lookupKey]?.by_temp?.[tempKey];

            if (!densityInput) {
                return;
            }

            if (!String(tempInput?.value || '').trim()) {
                densityInput.value = '';
                setSampleCalculatedValue(card, '');
                return;
            }

            if (!match) {
                densityInput.value = '';
                setSampleCalculatedValue(card, '');
                return;
            }

            densityInput.value = Number.parseFloat(match.base_density || '0').toFixed(4);
            setSampleCalculatedValue(card, match.chart_val);
        };

        const sampleProductFieldPrefix = (productLabel) => {
            if (productLabel === 'HSD') {
                return 'hsd';
            }

            if (productLabel === 'MS') {
                return 'ms';
            }

            if (productLabel === 'POWER MS') {
                return 'power_ms';
            }

            return '';
        };

        const setSampleInputValue = (id, value) => {
            const input = document.getElementById(id);

            if (input && value !== undefined && value !== null) {
                input.value = value;
            }
        };

        const applySavedSampleForRef = (refNo) => {
            const sample = purchaseSamplesByRef?.[refNo];

            if (!sample) {
                return;
            }

            setSampleInputValue('sampleDate', sample.date || document.getElementById('sampleDate')?.value);
            setSampleInputValue('sampleTanker', sample.tanker);
            setSampleInputValue('sampleTransport', sample.transport);
            setSampleInputValue('sampleOilCompany', sample.oil_company);
            setSampleInputValue('sampleInvoiceNo', sample.invoice_no);
            setSampleInputValue('sampleProduct', sample.product);

            document.querySelectorAll('[data-sample-product]').forEach((card) => {
                const productPrefix = sampleProductFieldPrefix(card.dataset.sampleProduct);
                const inputPrefix = card.dataset.sampleProduct.toLowerCase().replace(/\s+/g, '-');

                if (!productPrefix) {
                    return;
                }

                setSampleInputValue(`sample-${inputPrefix}-temp`, sample[`${productPrefix}_temp`]);
                setSampleInputValue(`sample-${inputPrefix}-density`, sample[`${productPrefix}_base_density`]);
                setSampleInputValue(`sample-${inputPrefix}-value`, sample[`${productPrefix}_value`]);
                setSampleCalculatedValue(card, sample[`${productPrefix}_value`]);
                setSampleInputValue(`sample-${inputPrefix}-invoice-sample`, sample[`${productPrefix}_invoice_sample`]);
                setSampleInputValue(`sample-${inputPrefix}-plastic-seal`, sample[`${productPrefix}_plastic_seal`]);
                setSampleInputValue(`sample-${inputPrefix}-aluminium-seal`, sample[`${productPrefix}_aluminium_seal`]);
            });
        };

        const currentSampleItemNames = () => {
            const pendingItems = Array.from(pendingPurchaseItems?.querySelectorAll('[data-pending-purchase-item]') || [])
                .map((group) => readHiddenPendingItem(group).item_name)
                .filter(Boolean);

            if (pendingItems.length) {
                return pendingItems;
            }

            const viewedEntry = purchaseNavigationEntries[currentPurchaseEntryIndex];
            const viewedItems = viewedEntry?.items?.map((item) => item.item_name).filter(Boolean) || [];

            if (viewedItems.length) {
                return viewedItems;
            }

            const currentItemName = (itemNameInput?.value || '').trim();

            return currentItemName ? [currentItemName] : [];
        };

        const syncSampleFormFromPurchase = () => {
            const itemNames = currentSampleItemNames();
            const activeProducts = new Set(itemNames.map(sampleProductKeyForItem).filter(Boolean));

            document.getElementById('sampleDate').value = document.getElementById('purchaseDate')?.value || @json($today);
            document.getElementById('sampleRefNo').value = document.getElementById('refNo')?.value || '';
            document.getElementById('sampleTanker').value = vehicleNoInput?.value || '';
            document.getElementById('sampleTransport').value = document.getElementById('transporter')?.value || '';
            document.getElementById('sampleOilCompany').value = document.getElementById('perticulars')?.value || '';
            document.getElementById('sampleInvoiceNo').value = document.getElementById('invoiceNo')?.value || '';
            document.getElementById('sampleProduct').value = itemNames.join(', ');

            document.querySelectorAll('[data-sample-product]').forEach((card) => {
                const isActive = activeProducts.has(card.dataset.sampleProduct);
                card.classList.toggle('is-active', isActive);
                card.classList.toggle('is-inactive', !isActive);
                card.querySelectorAll('input').forEach((input) => {
                    input.disabled = !isActive;
                });

                if (isActive) {
                    applySampleDensityFromTemp(card);
                }
            });

            applySavedSampleForRef(document.getElementById('sampleRefNo')?.value || '');
        };

        const sampleInputValue = (id) => document.getElementById(id)?.value || '';

        const currentThemeName = () => {
            try {
                return localStorage.getItem('fueltracker:theme') || 'default';
            } catch (error) {
                return 'default';
            }
        };

        const openSampleProductPreview = (button) => {
            if (!samplePreviewUrl) {
                return;
            }

            const card = button.closest('[data-sample-product]');
            const productLabel = card?.dataset.sampleProduct || '';
            const inputPrefix = productLabel.toLowerCase().replace(/\s+/g, '-');
            const productKey = sampleLookupKeyForCard(productLabel);
            const previewUrl = new URL(samplePreviewUrl, window.location.origin);

            previewUrl.searchParams.set('product_key', productKey);
            previewUrl.searchParams.set('theme', currentThemeName());
            previewUrl.searchParams.set('product_label', productLabel);
            previewUrl.searchParams.set('date', sampleInputValue('sampleDate'));
            previewUrl.searchParams.set('ref_no', sampleInputValue('sampleRefNo'));
            previewUrl.searchParams.set('tanker', sampleInputValue('sampleTanker'));
            previewUrl.searchParams.set('transport', sampleInputValue('sampleTransport'));
            previewUrl.searchParams.set('oil_company', sampleInputValue('sampleOilCompany'));
            previewUrl.searchParams.set('invoice_no', sampleInputValue('sampleInvoiceNo'));
            previewUrl.searchParams.set('product', sampleInputValue('sampleProduct') || productLabel);
            previewUrl.searchParams.set('temp', sampleInputValue(`sample-${inputPrefix}-temp`));
            previewUrl.searchParams.set('base_density', sampleInputValue(`sample-${inputPrefix}-density`));
            previewUrl.searchParams.set('value', sampleInputValue(`sample-${inputPrefix}-value`));
            previewUrl.searchParams.set('sample', sampleInputValue(`sample-${inputPrefix}-sample`));
            previewUrl.searchParams.set('invoice_sample', sampleInputValue(`sample-${inputPrefix}-invoice-sample`));
            previewUrl.searchParams.set('plastic_seal', sampleInputValue(`sample-${inputPrefix}-plastic-seal`));
            previewUrl.searchParams.set('aluminium_seal', sampleInputValue(`sample-${inputPrefix}-aluminium-seal`));

            window.open(previewUrl.toString(), '_blank', 'noopener');
        };

        const saveSampleWithoutLeavingEntry = async (event) => {
            event.preventDefault();

            if (!samplePurchaseForm || !samplePurchaseForm.action || samplePurchaseForm.action.endsWith('#')) {
                return;
            }

            const formData = new FormData(samplePurchaseForm);

            if (sampleSaveButton) {
                sampleSaveButton.disabled = true;
                sampleSaveButton.textContent = 'Saving...';
            }

            try {
                const response = await fetch(samplePurchaseForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                const payload = await response.json();

                if (!response.ok) {
                    const messages = payload?.errors
                        ? Object.values(payload.errors).flat()
                        : [payload?.message || 'Sample save nahi ho paya.'];
                    window.alert(messages.join('\n'));
                    return;
                }

                const refNo = payload?.sample?.ref_no || formData.get('ref_no');

                if (refNo && payload?.sample) {
                    purchaseSamplesByRef[refNo] = payload.sample;
                }

                closeSampleModal();
            } catch (error) {
                window.alert('Sample save nahi ho paya. Dobara try karo.');
            } finally {
                if (sampleSaveButton) {
                    sampleSaveButton.disabled = false;
                    sampleSaveButton.textContent = 'Save';
                }
            }
        };

        const openItemModal = () => {
            addItemModal?.classList.add('is-open');
            addItemModal?.setAttribute('aria-hidden', 'false');
            document.getElementById('itemName')?.focus();
        };

        const clearPendingRowEdit = () => {
            editingPendingRow?.classList.remove('is-editing');
            editingPendingRow = null;
            editingPendingGroup = null;
            if (itemModalTitle) itemModalTitle.textContent = 'Add Item';
        };

        const startNewItemEntry = () => {
            clearPendingRowEdit();
            openItemModal();
        };

        const closeItemModal = () => {
            addItemModal?.classList.remove('is-open');
            addItemModal?.setAttribute('aria-hidden', 'true');
            clearPendingRowEdit();
            addItemButton?.focus();
        };

        const clearItemModal = () => {
            setDropdownValue(document.querySelector('#itemName')?.closest('[data-theme-dropdown]'), '');
            quantityInput.value = '0.000';
            rateInput.value = '0.00';
            amountInput.value = '0.00';
            discountPercentInput.value = '0.00';
            if (discountInRsInput) discountInRsInput.value = '0.00';
            if (taxableAmountInput) taxableAmountInput.value = '0.00';
            if (cgstInput) cgstInput.value = '0.00';
            if (sgstInput) sgstInput.value = '0.00';
            if (igstInput) igstInput.value = '0.00';
            if (totalTaxAmountInput) totalTaxAmountInput.value = '0.00';
            if (totalAmountInput) totalAmountInput.value = '0.00';
            calculatePurchaseTotals();
            toggleTaxFields();
        };

        const formatNumber = (value, decimals = 2) => Number.parseFloat(value || '0').toFixed(decimals);
        const formatDisplayAmount = (value) => Number.parseFloat(value || '0').toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        const pendingItemFields = [
            'date',
            'Ref_no',
            'invoice_no',
            'perticulars',
            'postal address',
            'location',
            'interstate',
            'vehicle_no',
            'transporter',
            'driver',
            'item_name',
            'quantity',
            'rate',
            'amount',
            'discount%',
            'discountinrs',
            'taxable_amount',
            'cgst',
            'sgst',
            'igst',
            'total_tax_amount',
            'total_amount',
        ];

        const pendingRowValues = (item) => [
            item.item_name,
            item.item_name,
            item.quantity,
            item.rate,
            item.amount,
            item['discount%'],
            item.discountinrs,
            item.taxable_amount,
            item.total_amount,
            item.cgst,
            item.sgst,
            item.igst,
            item.total_tax_amount,
        ];

        const updatePendingTableRow = (row, item) => {
            if (!row) {
                return;
            }

            row.innerHTML = '';

            pendingRowValues(item).forEach((value, index) => {
                const cell = document.createElement('td');
                cell.textContent = value || '-';
                if (index >= 2) {
                    cell.className = 'number-cell';
                }
                row.appendChild(cell);
            });

            const actionCell = document.createElement('td');
            actionCell.innerHTML = `
                <button type="button" class="delete-btn remove-row-btn">
                    Remove
                </button>
            `;

            actionCell.querySelector('.remove-row-btn')?.addEventListener('click', (event) => {
                event.stopPropagation();

                const rowIndex = Array.from(
                    purchaseItemsBody.querySelectorAll('[data-pending-row="true"]')
                ).indexOf(row);

                pendingRemoveRow = row;
                pendingRemoveGroup = pendingPurchaseItems.querySelectorAll('[data-pending-purchase-item]')[rowIndex] || null;
                pendingDeleteForm = null;
                deletePurchaseName.textContent = item.item_name || 'this entry';
                deleteModal.classList.add('is-open');
                deleteModal.setAttribute('aria-hidden', 'false');
            });

            row.appendChild(actionCell);
        };

        const writeHiddenPendingItem = (group, item) => {
            if (!group) {
                return;
            }

            pendingItemFields.forEach((field) => {
                const input = Array.from(group.querySelectorAll('input[type="hidden"]'))
                    .find((hiddenInput) => hiddenInput.dataset.field === field);
                if (input) {
                    input.value = item[field] || '';
                }
            });
        };

        const readHiddenPendingItem = (group) => {
            const item = {};
            group?.querySelectorAll('input[type="hidden"]').forEach((input) => {
                item[input.dataset.field] = input.value;
            });

            return item;
        };

        const buildPendingPurchaseItem = () => {
            calculatePurchaseTotals();

            const purchaseDate = (document.getElementById('purchaseDate')?.value || '').trim();
            const refNo = (document.getElementById('refNo')?.value || '').trim();
            const invoiceNo = (document.getElementById('invoiceNo')?.value || '').trim();
            const perticulars = (document.getElementById('perticulars')?.value || '').trim();
            const postalAddress = (document.getElementById('postalAddress')?.value || '').trim();
            const location = (document.getElementById('location')?.value || '').trim();
            const interstate = interstateInput?.value || 'No';
            const vehicleNo = (vehicleNoInput?.value || '').trim();
            const transporter = (document.getElementById('transporter')?.value || '').trim();
            const driver = (document.getElementById('driver')?.value || '').trim();
            const itemName = (itemNameInput?.value || '').trim();
            const quantity = Number.parseFloat(quantityInput?.value || '0');
            const rate = Number.parseFloat(rateInput?.value || '0');

            if (!purchaseDate) {
                document.getElementById('purchaseDate')?.focus();
                return null;
            }

            if (!refNo) {
                document.getElementById('refNo')?.focus();
                return null;
            }

            if (!perticulars) {
                document.getElementById('perticulars')?.closest('[data-theme-dropdown]')?.querySelector('.theme-dropdown-button')?.focus();
                return null;
            }

            if (!itemName) {
                itemNameInput?.closest('[data-theme-dropdown]')?.querySelector('.theme-dropdown-button')?.focus();
                return null;
            }

            if (quantity <= 0) {
                quantityInput?.focus();
                return null;
            }

            if (rate <= 0) {
                rateInput?.focus();
                return null;
            }

            return {
                date: purchaseDate,
                Ref_no: refNo,
                invoice_no: invoiceNo,
                perticulars,
                'postal address': postalAddress,
                location,
                interstate,
                vehicle_no: vehicleNo,
                transporter,
                driver,
                item_name: itemName,
                quantity: formatNumber(quantity, 3),
                rate: formatNumber(rate),
                amount: formatNumber(amountInput?.value),
                'discount%': formatNumber(discountPercentInput?.value),
                discountinrs: formatNumber(discountInRsInput?.value),
                taxable_amount: formatNumber(taxableAmountInput?.value),
                cgst: formatNumber(cgstInput?.value),
                sgst: formatNumber(sgstInput?.value),
                igst: formatNumber(igstInput?.value),
                total_tax_amount: formatNumber(totalTaxAmountInput?.value),
                total_amount: formatNumber(totalAmountInput?.value),
            };
        };

        const ensureEmptyPurchaseRow = () => {
            if (!purchaseItemsBody || purchaseItemsBody.querySelector('tr')) {
                return;
            }

            const row = document.createElement('tr');
            const cell = document.createElement('td');
            cell.colSpan = 14;
            cell.className = 'empty-state';
            cell.textContent = 'No purchase entries found.';
            row.appendChild(cell);
            purchaseItemsBody.appendChild(row);
        };

        const setSummaryValue = (element, value) => {
            if (element) {
                element.textContent = formatDisplayAmount(value);
            }
        };

        const renderReferenceRows = (items = []) => {
            if (!purchaseItemsBody) {
                return;
            }

            purchaseItemsBody.innerHTML = '';

            items.forEach((item) => {
                const row = document.createElement('tr');
                row.dataset.referenceRow = 'true';

                pendingRowValues(item).forEach((value, index) => {
                    const cell = document.createElement('td');
                    cell.textContent = value || '-';
                    if (index >= 2) {
                        cell.className = 'number-cell';
                    }
                    row.appendChild(cell);
                });

                const actionCell = document.createElement('td');
                actionCell.textContent = '-';
                row.appendChild(actionCell);
                purchaseItemsBody.appendChild(row);
            });

            const summary = items.reduce((totals, item) => {
                const taxableAmount = Number.parseFloat(item.taxable_amount || '0');

                totals.taxable += taxableAmount;
                totals.cgst += (taxableAmount * Number.parseFloat(item.cgst || '0')) / 100;
                totals.sgst += (taxableAmount * Number.parseFloat(item.sgst || '0')) / 100;
                totals.igst += (taxableAmount * Number.parseFloat(item.igst || '0')) / 100;
                totals.discount += Number.parseFloat(item.discountinrs || '0');
                totals.total += Number.parseFloat(item.total_amount || '0');

                return totals;
            }, {
                taxable: 0,
                cgst: 0,
                sgst: 0,
                igst: 0,
                discount: 0,
                total: 0,
            });

            if (pendingRowCount) {
                pendingRowCount.textContent = `${items.length} ${items.length === 1 ? 'entry' : 'entries'}`;
            }

            if (pendingTotalAmount) {
                pendingTotalAmount.textContent = formatDisplayAmount(summary.total);
            }

            if (pendingSummaryCount) {
                pendingSummaryCount.textContent = String(items.length);
            }

            setSummaryValue(pendingSummaryTaxable, summary.taxable);
            setSummaryValue(pendingSummaryCgst, summary.cgst);
            setSummaryValue(pendingSummarySgst, summary.sgst);
            setSummaryValue(pendingSummaryIgst, summary.igst);
            setSummaryValue(pendingSummaryDiscount, summary.discount);
            setSummaryValue(pendingSummaryTotal, summary.total);
            ensureEmptyPurchaseRow();
        };

        const togglePendingSaveButton = () => {
            const pendingGroups = Array.from(pendingPurchaseItems?.querySelectorAll('[data-pending-purchase-item]') || []);
            const pendingCount = pendingGroups.length;

            if (!savePendingButton) {
                return;
            }

            savePendingButton.disabled = pendingCount === 0;

            if (pendingRowCount) {
                pendingRowCount.textContent = `${pendingCount} ${pendingCount === 1 ? 'entry' : 'entries'}`;
            }

            if (pendingTotalAmount) {
                const pendingTotal = pendingGroups.reduce((sum, group) => {
                    return sum + Number.parseFloat(readHiddenPendingItem(group).total_amount || '0');
                }, 0);
                pendingTotalAmount.textContent = formatDisplayAmount(pendingTotal);
            }

            const summary = pendingGroups.reduce((totals, group) => {
                const item = readHiddenPendingItem(group);
                const taxableAmount = Number.parseFloat(item.taxable_amount || '0');

                totals.taxable += taxableAmount;
                totals.cgst += (taxableAmount * Number.parseFloat(item.cgst || '0')) / 100;
                totals.sgst += (taxableAmount * Number.parseFloat(item.sgst || '0')) / 100;
                totals.igst += (taxableAmount * Number.parseFloat(item.igst || '0')) / 100;
                totals.discount += Number.parseFloat(item.discountinrs || '0');
                totals.total += Number.parseFloat(item.total_amount || '0');

                return totals;
            }, {
                taxable: 0,
                cgst: 0,
                sgst: 0,
                igst: 0,
                discount: 0,
                total: 0,
            });

            if (pendingSummaryCount) {
                pendingSummaryCount.textContent = String(pendingCount);
            }

            setSummaryValue(pendingSummaryTaxable, summary.taxable);
            setSummaryValue(pendingSummaryCgst, summary.cgst);
            setSummaryValue(pendingSummarySgst, summary.sgst);
            setSummaryValue(pendingSummaryIgst, summary.igst);
            setSummaryValue(pendingSummaryDiscount, summary.discount);
            setSummaryValue(pendingSummaryTotal, summary.total);

            if (pendingCount === 0) {
                ensureEmptyPurchaseRow();
            }
        };

        const addHiddenPendingItem = (item) => {
            if (!pendingPurchaseItems) {
                return null;
            }

            const index = pendingPurchaseItems.querySelectorAll('[data-pending-purchase-item]').length;
            const group = document.createElement('div');
            group.dataset.pendingPurchaseItem = String(index);

            pendingItemFields.forEach((field) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `items[${index}][${field}]`;
                input.dataset.field = field;
                input.value = item[field] || '';
                group.appendChild(input);
            });

            pendingPurchaseItems.appendChild(group);
            togglePendingSaveButton();

            return group;
        };

        const reindexHiddenPendingItems = () => {
            pendingPurchaseItems?.querySelectorAll('[data-pending-purchase-item]').forEach((group, index) => {
                group.dataset.pendingPurchaseItem = String(index);
                group.querySelectorAll('input[type="hidden"]').forEach((input) => {
                    input.name = input.name.replace(/items\[\d+\]/, `items[${index}]`);
                });
            });
            togglePendingSaveButton();
        };

        const removeEmptyPurchaseRow = () => {
            const emptyRow = purchaseItemsBody?.querySelector('.empty-state')?.closest('tr');
            emptyRow?.remove();
        };

        const updateCharLimitLabels = () => {
            charLimitLabels.forEach((label) => {
                const input = document.getElementById(label.dataset.charLimitFor);
                const maxLength = Number(input?.maxLength || 0);
                label.textContent = `${input?.value.length || 0}/${maxLength}`;
            });
        };

        const loadPendingItemIntoForm = (item) => {
            document.getElementById('purchaseDate').value = item.date || @json($today);
            document.getElementById('refNo').value = item.Ref_no || nextRefNo;
            document.getElementById('invoiceNo').value = item.invoice_no || '';
            document.getElementById('postalAddress').value = item['postal address'] || '';
            document.getElementById('location').value = item.location || '';
            document.getElementById('vehicleNo').value = item.vehicle_no || '';
            document.getElementById('transporter').value = item.transporter || '';
            document.getElementById('driver').value = item.driver || '';
            setDropdownValue(document.querySelector('#perticulars')?.closest('[data-theme-dropdown]'), item.perticulars || '');
            setDropdownValue(document.querySelector('#interstate')?.closest('[data-theme-dropdown]'), item.interstate || 'No');
            setDropdownValue(document.querySelector('#itemName')?.closest('[data-theme-dropdown]'), item.item_name || '');
            quantityInput.value = item.quantity || '0.000';
            rateInput.value = item.rate || '0.00';
            discountPercentInput.value = item['discount%'] || '0.00';
            cgstInput.value = item.cgst || '0.00';
            sgstInput.value = item.sgst || '0.00';
            igstInput.value = item.igst || '0.00';
            calculatePurchaseTotals();
            toggleTaxFields();
            updateCharLimitLabels();
        };

        const editPendingRow = (row) => {
            const rowIndex = Array.from(purchaseItemsBody?.querySelectorAll('[data-pending-row="true"]') || []).indexOf(row);
            const group = pendingPurchaseItems?.querySelectorAll('[data-pending-purchase-item]')[rowIndex];

            if (!row || !group) {
                return;
            }

            clearPendingRowEdit();
            editingPendingRow = row;
            editingPendingGroup = group;
            editingPendingRow.classList.add('is-editing');
            if (itemModalTitle) itemModalTitle.textContent = 'Edit Item';
            loadPendingItemIntoForm(readHiddenPendingItem(group));
            openItemModal();
        };

        const addPendingTableRow = (item) => {
            if (!purchaseItemsBody) {
                return null;
            }

            removeEmptyPurchaseRow();

            const row = document.createElement('tr');
            row.dataset.pendingRow = 'true';
            updatePendingTableRow(row, item);
            //row.addEventListener('click', () => editPendingRow(row));
            purchaseItemsBody.appendChild(row);

            return row;
        };

        const resetPurchaseEntryAfterAccept = () => {
            clearItemModal();
            updateCharLimitLabels();
        };

        const acceptItem = () => {
            const item = buildPendingPurchaseItem();

            if (!item) {
                return;
            }

            if (editingPendingRow && editingPendingGroup) {
                writeHiddenPendingItem(editingPendingGroup, item);
                updatePendingTableRow(editingPendingRow, item);
                togglePendingSaveButton();
            } else {
                addHiddenPendingItem(item);
                addPendingTableRow(item);
            }

            resetPurchaseEntryAfterAccept();
            closeItemModal();
        };

        const calculateAmount = () => {
            const quantity = parseFloat(quantityInput?.value || '0');
            const rate = parseFloat(rateInput?.value || '0');
            const amount = quantity * rate;

            if (amountInput) {
                amountInput.value = amount.toFixed(2);
            }
        };

        const calculateDiscountAmount = () => {
            const amount = parseFloat(amountInput?.value || '0');
            const discountPercent = parseFloat(discountPercentInput?.value || '0');
            const discountAmount = (amount * discountPercent) / 100;

            if (discountInRsInput) {
                discountInRsInput.value = discountAmount.toFixed(2);
            }
        };

        const calculateTaxableAmount = () => {
            const amount = parseFloat(amountInput?.value || '0');
            const discountAmount = parseFloat(discountInRsInput?.value || '0');
            const taxableAmount = amount - discountAmount;

            if (taxableAmountInput) {
                taxableAmountInput.value = taxableAmount.toFixed(2);
            }
        };

        const calculateTaxAmounts = () => {
            const taxableAmount = parseFloat(taxableAmountInput?.value || '0');
            const cgstPercent = interstateInput?.value === 'No' ? parseFloat(cgstInput?.value || '0') : 0;
            const sgstPercent = interstateInput?.value === 'No' ? parseFloat(sgstInput?.value || '0') : 0;
            const igstPercent = interstateInput?.value === 'Yes' ? parseFloat(igstInput?.value || '0') : 0;
            const cgstAmount = (taxableAmount * cgstPercent) / 100;
            const sgstAmount = (taxableAmount * sgstPercent) / 100;
            const igstAmount = (taxableAmount * igstPercent) / 100;
            const totalTaxAmount = cgstAmount + sgstAmount + igstAmount;
            const totalAmount = taxableAmount + totalTaxAmount;

            if (totalTaxAmountInput) {
                totalTaxAmountInput.value = totalTaxAmount.toFixed(2);
            }

            if (totalAmountInput) {
                totalAmountInput.value = totalAmount.toFixed(2);
            }
        };

        const calculatePurchaseTotals = () => {
            calculateAmount();
            calculateDiscountAmount();
            calculateTaxableAmount();
            calculateTaxAmounts();
        };

        const syncSgstFromCgst = () => {
            if (!cgstInput || !sgstInput || sgstInput.disabled) {
                return;
            }

            sgstInput.value = cgstInput.value;
        };

        quantityInput?.addEventListener('input', calculatePurchaseTotals);
        rateInput?.addEventListener('input', calculatePurchaseTotals);
        amountInput?.addEventListener('input', calculatePurchaseTotals);
        discountPercentInput?.addEventListener('input', calculatePurchaseTotals);
        discountInRsInput?.addEventListener('input', () => {
            calculateTaxableAmount();
            calculateTaxAmounts();
        });
        taxableAmountInput?.addEventListener('input', calculateTaxAmounts);
        cgstInput?.addEventListener('input', () => {
            syncSgstFromCgst();
            calculateTaxAmounts();
        });
        sgstInput?.addEventListener('input', calculateTaxAmounts);
        igstInput?.addEventListener('input', calculateTaxAmounts);
        interstateInput?.addEventListener('change', toggleTaxFields);
        vehicleNoInput?.addEventListener('input', (event) => {
            event.target.value = event.target.value.slice(0, 50);
        });
        toggleTaxFields();
        applyExportThemeLinks();

        editButtons.forEach((button) => {
            button.addEventListener('click', () => setEditMode(button));
        });

        const showPreviousPurchaseEntry = () => {
            const total = purchaseNavigationEntries.length;

            if (total === 0) {
                return;
            }

            const targetIndex = currentPurchaseEntryIndex >= total
                ? total - 1
                : Math.max(0, currentPurchaseEntryIndex - 1);
            loadPurchaseEntryForView(targetIndex);
        };

        const showNextPurchaseEntry = () => {
            const total = purchaseNavigationEntries.length;

            if (total === 0 || currentPurchaseEntryIndex >= total) {
                return;
            }

            if (currentPurchaseEntryIndex === total - 1) {
                loadNewPurchaseEntryForView();
                return;
            }

            const targetIndex = currentPurchaseEntryIndex + 1;
            loadPurchaseEntryForView(targetIndex);
        };

        document.addEventListener('click', (event) => {
            const navButton = event.target.closest('#previousPurchaseEntryBtn, #nextPurchaseEntryBtn');

            if (!navButton || navButton.disabled) {
                return;
            }

            if (navButton.id === 'previousPurchaseEntryBtn') {
                showPreviousPurchaseEntry();
                return;
            }

            showNextPurchaseEntry();
        });

        if (initialViewRef) {
            const viewRefIndex = purchaseNavigationEntries.findIndex((entry) => String(entry.Ref_no || '') === initialViewRef);

            if (viewRefIndex >= 0) {
                loadPurchaseEntryForView(viewRefIndex);
            }
        }

        updatePurchaseEntryNavState();

        deleteForms.forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                pendingDeleteForm = form;
                pendingRemoveRow = null;
                pendingRemoveGroup = null;
                deletePurchaseName.textContent = form.querySelector('[data-delete-purchase]')?.dataset.deletePurchase || 'this entry';
                deleteModal.classList.add('is-open');
                deleteModal.setAttribute('aria-hidden', 'false');
            });
        });

        deleteCancelBtn?.addEventListener('click', () => {
            pendingDeleteForm = null;
            pendingRemoveRow = null;
            pendingRemoveGroup = null;
            deleteModal.classList.remove('is-open');
            deleteModal.setAttribute('aria-hidden', 'true');
        });

        deleteConfirmBtn?.addEventListener('click', () => {
            if (pendingRemoveRow) {
                pendingRemoveGroup?.remove();
                pendingRemoveRow.remove();
                pendingRemoveRow = null;
                pendingRemoveGroup = null;
                reindexHiddenPendingItems();
                deleteModal.classList.remove('is-open');
                deleteModal.setAttribute('aria-hidden', 'true');
                return;
            }

            if (pendingDeleteForm) {
                pendingDeleteForm.submit();
            }
        });

        deleteModal?.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                deleteCancelBtn.click();
            }
        });

        document.getElementById('purchaseDate')?.addEventListener('change', (event) => {
            if (!formMethodInput || formMethodInput.disabled) {
                const url = new URL(purchaseListUrl, window.location.origin);
                url.searchParams.set('date', event.target.value);
                window.location.href = url.toString();
            }
        });

        cancelEditButton?.addEventListener('click', () => {
            purchaseForm.reset();
            setDropdownValue(document.querySelector('#perticulars')?.closest('[data-theme-dropdown]'), '');
            setDropdownValue(document.querySelector('#interstate')?.closest('[data-theme-dropdown]'), 'No');
            setDropdownValue(document.querySelector('#itemName')?.closest('[data-theme-dropdown]'), '');
            setCreateMode();
            calculatePurchaseTotals();
            toggleTaxFields();
        });

        sampleButton?.addEventListener('click', openSampleModal);
        samplePurchaseForm?.addEventListener('submit', saveSampleWithoutLeavingEntry);
        sampleCloseButton?.addEventListener('click', closeSampleModal);
        document.querySelectorAll('[data-sample-preview]').forEach((button) => {
            button.addEventListener('click', () => openSampleProductPreview(button));
        });
        document.querySelectorAll('[data-sample-product] input[id$="-temp"]').forEach((input) => {
            input.addEventListener('input', () => {
                applySampleDensityFromTemp(input.closest('[data-sample-product]'));
            });
        });
        document.querySelectorAll('[data-sample-product] input[id$="-density"]').forEach((input) => {
            input.addEventListener('input', () => {
                applySampleValueFromDensity(input.closest('[data-sample-product]'));
            });
        });
        purchasePreviewCloseBtn?.addEventListener('click', () => {
            purchasePreviewModal?.classList.remove('is-open');
            purchasePreviewModal?.setAttribute('aria-hidden', 'true');
            removePreviewRefFromUrl();
        });
        purchasePreviewPrintBtn?.addEventListener('click', () => window.print());
        purchasePreviewModal?.addEventListener('click', (event) => {
            if (event.target === purchasePreviewModal) {
                purchasePreviewCloseBtn?.click();
            }
        });
        if (purchasePreviewModal?.classList.contains('is-open')) {
            removePreviewRefFromUrl();
        }
        addItemButton?.addEventListener('click', startNewItemEntry);
        itemCloseButton?.addEventListener('click', closeItemModal);
        acceptItemButton?.addEventListener('click', acceptItem);
        itemModalClearButton?.addEventListener('click', clearItemModal);
        addItemModal?.addEventListener('click', (event) => {
            if (event.target === addItemModal) {
                closeItemModal();
            }
        });
        sampleModal?.addEventListener('click', (event) => {
            if (event.target === sampleModal) {
                closeSampleModal();
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && addItemModal?.classList.contains('is-open')) {
                closeItemModal();
                return;
            }

            if (event.key === 'Escape' && sampleModal?.classList.contains('is-open')) {
                closeSampleModal();
            }
        });

        togglePendingSaveButton();

        purchaseForm?.addEventListener('submit', (event) => {
            if (event.submitter !== savePendingButton) {
                return;
            }

            const hasPendingItems = (pendingPurchaseItems?.querySelectorAll('[data-pending-purchase-item]').length || 0) > 0;

            if (!hasPendingItems) {
                event.preventDefault();
                addItemButton?.focus();
            }
        });

        purchaseForm?.addEventListener('reset', () => {

            window.setTimeout(() => {
                setDropdownValue(document.querySelector('#perticulars')?.closest('[data-theme-dropdown]'), '');
                setDropdownValue(document.querySelector('#interstate')?.closest('[data-theme-dropdown]'), 'No');
                setDropdownValue(document.querySelector('#itemName')?.closest('[data-theme-dropdown]'), '');
                setCreateMode();
                calculatePurchaseTotals();
                toggleTaxFields();
            }, 0);
        });

        @if (session('success'))
            window.addEventListener('load', () => {
                if (purchaseList) {
                    purchaseList.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        @endif
    </script>
</body>
</html>
