<script>
    (function () {
        try {
            var themes = ['default', 'ocean', 'royal', 'rose', 'charcoal', 'sunset-sky', 'royal-print', 'peacock-print', 'marigold-print', 'velvet-print'];
            var theme = localStorage.getItem('fueltracker:theme') || 'default';
            document.documentElement.dataset.theme = themes.indexOf(theme) === -1 ? 'default' : theme;
        } catch (error) {
            document.documentElement.dataset.theme = 'default';
        }
    })();
</script>
<style>
    html[data-theme="default"] {
        --primary: #0f766e;
        --primary-dark: #115e59;
        --primary-shine: #2dd4bf;
        --accent: #f59e0b;
        --theme-glow: rgba(15, 118, 110, 0.22);
        --theme-bg-end: #eef5f3;
        --theme-brand-start: rgba(8, 47, 73, 0.98);
        --theme-brand-end: rgba(15, 118, 110, 0.96);
        --theme-print: none;
    }

    html[data-theme="ocean"] {
        --primary: #0369a1;
        --primary-dark: #075985;
        --primary-shine: #38bdf8;
        --accent: #14b8a6;
        --theme-glow: rgba(3, 105, 161, 0.22);
        --theme-bg-end: #edf7fb;
        --theme-brand-start: rgba(12, 74, 110, 0.98);
        --theme-brand-end: rgba(3, 105, 161, 0.96);
        --theme-print: none;
    }

    html[data-theme="royal"] {
        --primary: #4338ca;
        --primary-dark: #3730a3;
        --primary-shine: #818cf8;
        --accent: #f59e0b;
        --theme-glow: rgba(67, 56, 202, 0.22);
        --theme-bg-end: #f1f2ff;
        --theme-brand-start: rgba(49, 46, 129, 0.98);
        --theme-brand-end: rgba(67, 56, 202, 0.96);
        --theme-print: none;
    }

    html[data-theme="rose"] {
        --primary: #be123c;
        --primary-dark: #9f1239;
        --primary-shine: #fb7185;
        --accent: #0f766e;
        --theme-glow: rgba(190, 18, 60, 0.2);
        --theme-bg-end: #fff1f4;
        --theme-brand-start: rgba(136, 19, 55, 0.98);
        --theme-brand-end: rgba(190, 18, 60, 0.95);
        --theme-print: none;
    }

    html[data-theme="charcoal"] {
        --bg: #eef2f7;
        --primary: #334155;
        --primary-dark: #1e293b;
        --primary-shine: #94a3b8;
        --accent: #d97706;
        --theme-glow: rgba(51, 65, 85, 0.22);
        --theme-bg-end: #eef2f7;
        --theme-brand-start: rgba(15, 23, 42, 0.98);
        --theme-brand-end: rgba(51, 65, 85, 0.96);
        --theme-print: none;
    }

    html[data-theme="sunset-sky"] {
        --bg: #fff7ed;
        --primary: #ea580c;
        --primary-dark: #c2410c;
        --primary-shine: #fb923c;
        --accent: #be123c;
        --theme-glow: rgba(251, 146, 60, 0.28);
        --theme-bg-end: #ffe4d6;
        --theme-brand-start: rgba(124, 45, 18, 0.98);
        --theme-brand-end: rgba(234, 88, 12, 0.94);
        --theme-print: radial-gradient(circle at 16px 14px, rgba(255, 237, 213, 0.22) 0 3px, transparent 4px),
            linear-gradient(135deg, rgba(251, 146, 60, 0.12), rgba(244, 63, 94, 0.1));
    }

    html[data-theme="royal-print"] {
        --primary: #4c1d95;
        --primary-dark: #3b0764;
        --primary-shine: #a78bfa;
        --accent: #f59e0b;
        --theme-glow: rgba(76, 29, 149, 0.24);
        --theme-bg-end: #f5f0ff;
        --theme-brand-start: rgba(49, 46, 129, 0.98);
        --theme-brand-end: rgba(76, 29, 149, 0.95);
        --theme-print: radial-gradient(circle at 14px 14px, rgba(245, 158, 11, 0.2) 0 2px, transparent 3px),
            radial-gradient(circle at 34px 34px, rgba(255, 255, 255, 0.16) 0 2px, transparent 3px);
    }

    html[data-theme="peacock-print"] {
        --primary: #0f766e;
        --primary-dark: #134e4a;
        --primary-shine: #22d3ee;
        --accent: #0891b2;
        --theme-glow: rgba(8, 145, 178, 0.24);
        --theme-bg-end: #ecfeff;
        --theme-brand-start: rgba(8, 51, 68, 0.98);
        --theme-brand-end: rgba(15, 118, 110, 0.95);
        --theme-print: repeating-linear-gradient(135deg, rgba(255, 255, 255, 0.16) 0 2px, transparent 2px 14px),
            radial-gradient(circle at 18px 18px, rgba(20, 184, 166, 0.22) 0 3px, transparent 4px);
    }

    html[data-theme="marigold-print"] {
        --primary: #b45309;
        --primary-dark: #92400e;
        --primary-shine: #fbbf24;
        --accent: #be123c;
        --theme-glow: rgba(245, 158, 11, 0.26);
        --theme-bg-end: #fff7ed;
        --theme-brand-start: rgba(127, 29, 29, 0.98);
        --theme-brand-end: rgba(180, 83, 9, 0.95);
        --theme-print: repeating-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 0 4px, transparent 4px 16px),
            radial-gradient(circle at 20px 20px, rgba(251, 191, 36, 0.22) 0 4px, transparent 5px);
    }

    html[data-theme="velvet-print"] {
        --primary: #9d174d;
        --primary-dark: #831843;
        --primary-shine: #f472b6;
        --accent: #7c3aed;
        --theme-glow: rgba(157, 23, 77, 0.24);
        --theme-bg-end: #fdf2f8;
        --theme-brand-start: rgba(76, 29, 149, 0.98);
        --theme-brand-end: rgba(157, 23, 77, 0.95);
        --theme-print: radial-gradient(circle at 12px 18px, rgba(255, 255, 255, 0.18) 0 2px, transparent 3px),
            repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.1) 0 1px, transparent 1px 18px);
    }

    html[data-theme] body {
        background:
            var(--theme-print),
            radial-gradient(circle at top left, var(--theme-glow), transparent 32rem),
            linear-gradient(135deg, #f8fbff 0%, var(--bg) 55%, var(--theme-bg-end) 100%);
        background-size: 44px 44px, auto, auto;
    }

    html[data-theme] .site-header,
    html[data-theme] .sidebar-brand,
    html[data-theme] .brand-panel {
        background:
            var(--theme-print),
            linear-gradient(160deg, rgba(255, 255, 255, 0.2) 0%, transparent 28%, transparent 70%, rgba(255, 255, 255, 0.12) 100%),
            linear-gradient(135deg, var(--theme-brand-start), var(--theme-brand-end)),
            url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.12' stroke-width='2'%3E%3Cpath d='M22 116c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 78c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 40c20-18 40-18 60 0s40 18 60 0'/%3E%3C/g%3E%3C/svg%3E");
        background-size: 44px 44px, auto, auto, 160px 160px;
    }

    html[data-theme] .session-card,
    html[data-theme] .theme-card {
        background:
            var(--theme-print),
            linear-gradient(160deg, rgba(255, 255, 255, 0.22) 0%, transparent 28%, transparent 70%, rgba(255, 255, 255, 0.12) 100%),
            linear-gradient(145deg, var(--theme-brand-start), var(--theme-brand-end)),
            url("data:image/svg+xml,%3Csvg width='120' height='120' viewBox='0 0 120 120' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.12' stroke-width='2'%3E%3Ccircle cx='20' cy='20' r='18'/%3E%3Ccircle cx='88' cy='78' r='26'/%3E%3C/g%3E%3C/svg%3E");
        background-size: 44px 44px, auto, auto, 120px 120px;
    }

    html[data-theme] .menu-heading,
    html[data-theme] .save-btn,
    html[data-theme] .new-btn,
    html[data-theme] .search-btn,
    html[data-theme] .primary-btn,
    html[data-theme] .modal-yes-btn,
    html[data-theme] .theme-select-option:hover,
    html[data-theme] .theme-select-option:focus,
    html[data-theme] .theme-dropdown-option:hover,
    html[data-theme] .theme-dropdown-option:focus,
    html[data-theme] .party-dropdown-option:hover,
    html[data-theme] .party-dropdown-option:focus,
    html[data-theme] .entries-option:hover,
    html[data-theme] .entries-option:focus {
        background:
            linear-gradient(160deg, rgba(255, 255, 255, 0.34) 0%, rgba(255, 255, 255, 0.08) 28%, transparent 48%),
            linear-gradient(135deg, var(--primary-dark), var(--primary) 58%, var(--primary-shine)) !important;
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.32),
            0 10px 22px var(--theme-glow);
    }

    html[data-theme] .menu-heading:hover,
    html[data-theme] .save-btn:hover,
    html[data-theme] .new-btn:hover,
    html[data-theme] .search-btn:hover,
    html[data-theme] .primary-btn:hover {
        filter: saturate(1.08) brightness(1.04);
    }

    html[data-theme] input,
    html[data-theme] select,
    html[data-theme] textarea,
    html[data-theme] .theme-select-search,
    html[data-theme] .theme-dropdown-search,
    html[data-theme] .party-dropdown-search {
        caret-color: var(--primary);
        accent-color: var(--primary);
    }

    html[data-theme] input[type="date"],
    html[data-theme] input[data-original-type="date"],
    html[data-theme] select {
        border-color: color-mix(in srgb, var(--primary) 28%, var(--line, #dce3ee)) !important;
        color: var(--ink, #172033) !important;
        background:
            linear-gradient(135deg, var(--theme-glow), rgba(255, 255, 255, 0.96)),
            #ffffff !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7) !important;
        color-scheme: light;
    }

    html[data-theme] input[type="date"]:hover,
    html[data-theme] input[data-original-type="date"]:hover,
    html[data-theme] select:hover {
        border-color: var(--primary) !important;
        background:
            linear-gradient(135deg, color-mix(in srgb, var(--primary) 10%, transparent), rgba(255, 255, 255, 0.98)),
            #ffffff !important;
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 14%, transparent) !important;
    }

    html[data-theme] input[type="date"]:focus,
    html[data-theme] input[data-original-type="date"]:focus,
    html[data-theme] select:focus {
        border-color: var(--primary) !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 4px var(--theme-glow) !important;
        outline: none !important;
    }

    html[data-theme] input[type="date"]::-webkit-calendar-picker-indicator {
        width: 18px;
        height: 18px;
        margin-left: 6px;
        padding: 4px;
        border-radius: 8px;
        cursor: pointer;
        background-color: var(--theme-glow);
        filter: saturate(1.35);
    }

    html[data-theme] input[type="date"]::-webkit-calendar-picker-indicator:hover {
        background-color: color-mix(in srgb, var(--primary) 24%, transparent);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 12%, transparent);
    }

    html[data-theme] input[type="date"]::-webkit-datetime-edit {
        color: var(--ink, #172033);
    }

    html[data-theme] input[type="date"]::-webkit-datetime-edit-year-field:focus,
    html[data-theme] input[type="date"]::-webkit-datetime-edit-month-field:focus,
    html[data-theme] input[type="date"]::-webkit-datetime-edit-day-field:focus {
        color: #ffffff;
        background: var(--primary);
        outline: none;
    }

    html[data-theme] select {
        cursor: pointer;
        scrollbar-color: var(--primary) rgba(220, 227, 238, 0.72);
    }

    html[data-theme] select option {
        color: var(--ink, #172033);
        background: #ffffff;
    }

    html[data-theme] select option:checked {
        color: #ffffff;
        background: var(--primary);
    }

    .theme-date-picker {
        position: fixed;
        z-index: 120;
        width: 286px;
        display: none;
        padding: 10px;
        border: 1px solid color-mix(in srgb, var(--primary) 28%, var(--line, #dce3ee));
        border-radius: 12px;
        color: var(--ink, #172033);
        background: #ffffff;
        box-shadow: 0 22px 52px rgba(23, 32, 51, 0.2);
    }

    .theme-date-picker.is-open {
        display: block;
    }

    .theme-date-head {
        display: grid;
        grid-template-columns: 34px 1fr 34px;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .theme-date-title {
        color: var(--primary-dark);
        font-size: 13px;
        font-weight: 800;
        text-align: center;
    }

    .theme-date-nav {
        width: 34px;
        height: 30px;
        border: 1px solid color-mix(in srgb, var(--primary) 18%, var(--line, #dce3ee));
        border-radius: 8px;
        color: var(--primary-dark);
        background: color-mix(in srgb, var(--primary) 8%, #ffffff);
        cursor: pointer;
        font: inherit;
        font-size: 16px;
        font-weight: 800;
    }

    .theme-date-nav:hover,
    .theme-date-nav:focus {
        color: #ffffff;
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        outline: none;
    }

    .theme-date-week,
    .theme-date-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
    }

    .theme-date-week span {
        padding: 4px 0;
        color: var(--muted, #657089);
        font-size: 10px;
        font-weight: 800;
        text-align: center;
    }

    .theme-date-day {
        min-height: 32px;
        border: 0;
        border-radius: 8px;
        color: var(--ink, #172033);
        background: transparent;
        cursor: pointer;
        font: inherit;
        font-size: 12px;
        font-weight: 700;
    }

    .theme-date-day:hover,
    .theme-date-day:focus {
        color: #ffffff;
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        box-shadow: 0 8px 18px var(--theme-glow);
        outline: none;
    }

    .theme-date-day.is-today {
        color: var(--primary-dark);
        background: var(--theme-glow);
    }

    .theme-date-day.is-selected {
        color: #ffffff;
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    }

    .theme-date-day.is-muted {
        color: color-mix(in srgb, var(--muted, #657089) 54%, #ffffff);
    }

    html[data-theme] .theme-select-toggle,
    html[data-theme] .theme-dropdown-button,
    html[data-theme] .party-dropdown-button {
        background:
            linear-gradient(135deg, var(--theme-glow), rgba(255, 255, 255, 0.96)),
            #fbfcfe !important;
    }

    html[data-theme] .theme-select-toggle::after,
    html[data-theme] .theme-dropdown-arrow,
    html[data-theme] .party-dropdown-arrow {
        color: var(--primary) !important;
        border-top-color: var(--primary) !important;
        border-right-color: var(--primary) !important;
        border-bottom-color: var(--primary) !important;
    }

    html[data-theme] .theme-select-option.is-selected,
    html[data-theme] .theme-dropdown-option.is-selected,
    html[data-theme] .party-dropdown-option.is-selected,
    html[data-theme] .entries-option.is-selected {
        color: var(--primary-dark) !important;
        background: var(--theme-glow) !important;
    }

    html[data-theme] .party-dropdown-option.is-selected {
        color: #ffffff !important;
        background:
            linear-gradient(160deg, rgba(255, 255, 255, 0.28) 0%, rgba(255, 255, 255, 0.08) 32%, transparent 52%),
            linear-gradient(135deg, var(--primary-dark), var(--primary)) !important;
    }

    html[data-theme] .theme-select-toggle:hover,
    html[data-theme] .theme-select-toggle:focus,
    html[data-theme] .theme-dropdown-button:hover,
    html[data-theme] .theme-dropdown-button:focus,
    html[data-theme] .party-dropdown-button:hover,
    html[data-theme] .party-dropdown-button:focus,
    html[data-theme] .theme-select-search:focus,
    html[data-theme] .theme-dropdown-search:focus,
    html[data-theme] .party-dropdown-search:focus {
        border-color: var(--primary) !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 4px var(--theme-glow) !important;
    }

    html[data-theme] .theme-select-menu,
    html[data-theme] .theme-dropdown-menu,
    html[data-theme] .party-dropdown-menu,
    html[data-theme] .entries-menu,
    html[data-theme] .side-menu {
        scrollbar-color: var(--primary) rgba(220, 227, 238, 0.72) !important;
    }

    html[data-theme] .theme-select-menu::-webkit-scrollbar-thumb,
    html[data-theme] .theme-dropdown-menu::-webkit-scrollbar-thumb,
    html[data-theme] .party-dropdown-menu::-webkit-scrollbar-thumb,
    html[data-theme] .entries-menu::-webkit-scrollbar-thumb,
    html[data-theme] .side-menu::-webkit-scrollbar-thumb {
        background: var(--primary) !important;
    }

    .site-logo-icon.has-brand-image,
    .brand-icon.has-brand-image {
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

    .dashboard-gateway {
        grid-column: 1 / -1;
        width: min(100%, 620px);
        justify-self: center;
    }

    .dashboard-gateway img {
        display: block;
        width: 100%;
        max-height: 180px;
        object-fit: contain;
        object-position: center;
        opacity: 0.9;
        filter: drop-shadow(0 14px 24px rgba(23, 32, 51, 0.16));
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var inputs = Array.prototype.slice.call(document.querySelectorAll('input[type="date"]'));

        if (!inputs.length) {
            return;
        }

        var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        var weekNames = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
        var activeInput = null;
        var viewDate = new Date();

        var picker = document.createElement('div');
        picker.className = 'theme-date-picker';
        picker.innerHTML = '<div class="theme-date-head"><button type="button" class="theme-date-nav" data-date-prev aria-label="Previous month">&lsaquo;</button><div class="theme-date-title"></div><button type="button" class="theme-date-nav" data-date-next aria-label="Next month">&rsaquo;</button></div><div class="theme-date-week"></div><div class="theme-date-grid"></div>';
        document.body.appendChild(picker);

        var title = picker.querySelector('.theme-date-title');
        var week = picker.querySelector('.theme-date-week');
        var grid = picker.querySelector('.theme-date-grid');

        weekNames.forEach(function (name) {
            var item = document.createElement('span');
            item.textContent = name;
            week.appendChild(item);
        });

        var pad = function (value) {
            return String(value).padStart(2, '0');
        };

        var toValue = function (date) {
            return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
        };

        var toDisplayValue = function (value) {
            if (!/^\d{4}-\d{2}-\d{2}$/.test(value || '')) {
                return value || '';
            }

            var parts = value.split('-');

            return parts[2] + '-' + parts[1] + '-' + parts[0];
        };

        var toStorageValue = function (value) {
            if (/^\d{4}-\d{2}-\d{2}$/.test(value || '')) {
                return value;
            }

            var displayParts = String(value || '').match(/^(\d{2})-(\d{2})-(\d{4})$/);

            return displayParts ? displayParts[3] + '-' + displayParts[2] + '-' + displayParts[1] : '';
        };

        var fromValue = function (value) {
            var storageValue = toStorageValue(value);

            if (!/^\d{4}-\d{2}-\d{2}$/.test(storageValue || '')) {
                return null;
            }

            var parts = storageValue.split('-').map(Number);
            var date = new Date(parts[0], parts[1] - 1, parts[2]);

            return Number.isNaN(date.getTime()) ? null : date;
        };

        var sameDate = function (left, right) {
            return left && right && toValue(left) === toValue(right);
        };

        var syncDateDisplay = function (input) {
            var value = toStorageValue(input.value) || input.dataset.isoValue || '';

            if (value) {
                input.dataset.isoValue = value;
                input.value = toDisplayValue(value);
            }
        };

        var placePicker = function () {
            if (!activeInput) {
                return;
            }

            var rect = activeInput.getBoundingClientRect();
            var top = rect.bottom + 6;
            var left = Math.min(rect.left, window.innerWidth - 302);

            if (top + 338 > window.innerHeight) {
                top = Math.max(8, rect.top - 338);
            }

            picker.style.top = top + 'px';
            picker.style.left = Math.max(8, left) + 'px';
        };

        var render = function () {
            var selected = fromValue(activeInput ? (activeInput.dataset.isoValue || activeInput.value) : '');
            var today = new Date();
            var year = viewDate.getFullYear();
            var month = viewDate.getMonth();
            var firstDay = new Date(year, month, 1);
            var start = new Date(year, month, 1 - firstDay.getDay());

            title.textContent = monthNames[month] + ' ' + year;
            grid.innerHTML = '';

            for (var index = 0; index < 42; index += 1) {
                var date = new Date(start);
                date.setDate(start.getDate() + index);

                var day = document.createElement('button');
                day.type = 'button';
                day.className = 'theme-date-day';
                day.textContent = date.getDate();
                day.dataset.value = toValue(date);

                if (date.getMonth() !== month) {
                    day.classList.add('is-muted');
                }

                if (sameDate(date, today)) {
                    day.classList.add('is-today');
                }

                if (sameDate(date, selected)) {
                    day.classList.add('is-selected');
                }

                grid.appendChild(day);
            }
        };

        var openPicker = function (input) {
            activeInput = input;
            var selected = fromValue(input.dataset.isoValue || input.value);
            viewDate = selected || new Date();
            picker.classList.add('is-open');
            render();
            placePicker();
        };

        var closePicker = function () {
            picker.classList.remove('is-open');
            activeInput = null;
        };

        inputs.forEach(function (input) {
            var isoValue = toStorageValue(input.value);

            input.dataset.originalType = 'date';
            input.type = 'text';
            input.inputMode = 'numeric';
            input.placeholder = input.placeholder || 'DD-MM-YYYY';
            input.autocomplete = input.autocomplete || 'off';

            if (isoValue) {
                input.dataset.isoValue = isoValue;
                input.value = toDisplayValue(isoValue);
            }

            input.addEventListener('focus', function () {
                openPicker(input);
            });

            input.addEventListener('click', function () {
                openPicker(input);
            });

            input.addEventListener('blur', function () {
                var value = toStorageValue(input.value);

                if (value) {
                    input.dataset.isoValue = value;
                    input.value = toDisplayValue(value);
                }
            });
        });

        document.addEventListener('change', function (event) {
            var input = event.target;

            if (!input || !input.dataset || input.dataset.originalType !== 'date' || input.dataset.dateNormalizing === '1') {
                return;
            }

            var value = toStorageValue(input.value);

            if (!value) {
                input.dataset.isoValue = '';
                return;
            }

            input.dataset.isoValue = value;
            input.dataset.dateNormalizing = '1';
            input.value = value;

            setTimeout(function () {
                input.value = toDisplayValue(input.dataset.isoValue || input.value);
                delete input.dataset.dateNormalizing;
            }, 0);
        }, true);

        document.addEventListener('submit', function (event) {
            var form = event.target;

            if (!form || !form.querySelectorAll) {
                return;
            }

            Array.prototype.slice.call(form.querySelectorAll('input[data-original-type="date"]')).forEach(function (input) {
                var value = toStorageValue(input.value) || input.dataset.isoValue || '';
                input.dataset.isoValue = value;
                input.value = value;
            });
        }, true);

        picker.addEventListener('click', function (event) {
            var prev = event.target.closest('[data-date-prev]');
            var next = event.target.closest('[data-date-next]');
            var day = event.target.closest('.theme-date-day');

            if (prev) {
                viewDate.setMonth(viewDate.getMonth() - 1);
                render();
                return;
            }

            if (next) {
                viewDate.setMonth(viewDate.getMonth() + 1);
                render();
                return;
            }

            if (day && activeInput) {
                activeInput.dataset.isoValue = day.dataset.value;
                activeInput.value = day.dataset.value;
                activeInput.dispatchEvent(new Event('input', { bubbles: true }));
                activeInput.dispatchEvent(new Event('change', { bubbles: true }));
                activeInput.value = toDisplayValue(day.dataset.value);
                closePicker();
            }
        });

        document.addEventListener('mousedown', function (event) {
            if (picker.contains(event.target) || event.target === activeInput) {
                return;
            }

            closePicker();
        });

        document.addEventListener('click', function () {
            setTimeout(function () {
                inputs.forEach(syncDateDisplay);
            }, 0);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closePicker();
            }
        });

        window.addEventListener('resize', placePicker);
        window.addEventListener('scroll', placePicker, true);
    });
</script>
