<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Master Update | FuelTracker</title>
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
            position: absolute;
            left: 50%;
            color: #ffffff;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0;
            transform: translateX(-50%);
            white-space: nowrap;
        }

        .header-actions {
            position: absolute;
            right: 18px;
            display: flex;
            align-items: center;
            gap: 12px;
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

        .product-workspace {
            min-height: calc(100vh - 48px);
            min-height: calc(100dvh - 48px);
            position: relative;
        }

        .product-page {
            width: min(100% - 36px, 900px);
            margin: 0 auto;
            padding: 14px 0 70px;
            transform: translateX(86px);
            transition: transform 0.22s ease;
        }

        .product-workspace.menu-collapsed .product-page {
            transform: translateX(0);
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

        .view-list-btn,
        .clear-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
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

        .form-panel {
            padding: 14px 16px;
            border: 1px solid rgba(220, 227, 238, 0.86);
            border-radius: 20px;
            background: var(--panel);
            box-shadow: var(--shadow);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 14px;
        }

        .field {
            display: grid;
            gap: 7px;
            align-content: start;
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

        .field input,
        .field select {
            width: 100%;
            min-height: 42px;
            padding: 0 14px;
            border: 1px solid var(--line);
            border-radius: 12px;
            color: var(--ink);
            background: #fbfcfe;
            font: inherit;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .field input:focus,
        .field select:focus {
            border-color: rgba(15, 118, 110, 0.52);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
        }

        .theme-select {
            position: relative;
        }

        .theme-select-toggle {
            width: 100%;
            min-height: 42px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 0 14px;
            border: 1px solid var(--line);
            border-radius: 12px;
            color: var(--ink);
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.08), rgba(20, 184, 166, 0.04)), #fbfcfe;
            cursor: pointer;
            font: inherit;
            font-size: 15px;
            font-weight: 400;
            text-align: left;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .theme-select-toggle::after {
            content: "";
            width: 0;
            height: 0;
            flex: 0 0 auto;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 6px solid var(--primary);
        }

        .theme-select-toggle:hover {
            border-color: rgba(15, 118, 110, 0.42);
            background: #ffffff;
        }

        .theme-select-toggle:focus {
            border-color: rgba(15, 118, 110, 0.52);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
            outline: none;
        }

        .theme-select-menu {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            z-index: 30;
            display: none;
            max-height: 220px;
            overflow: auto;
            padding: 6px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(23, 32, 51, 0.16);
        }

        .theme-select.is-open .theme-select-menu {
            display: grid;
            gap: 4px;
        }

        .theme-select-search {
            width: 100%;
            min-height: 34px;
            margin: 0 0 4px;
            padding: 0 10px;
            border: 1px solid var(--line);
            border-radius: 9px;
            color: var(--ink);
            background: #fbfcfe;
            font: inherit;
            font-size: 13px;
            outline: none;
        }

        .theme-select-search:focus {
            border-color: rgba(15, 118, 110, 0.52);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
        }

        .theme-select-option {
            min-height: 36px;
            padding: 0 10px;
            border: 0;
            border-radius: 9px;
            color: var(--ink);
            background: #ffffff;
            cursor: pointer;
            font: inherit;
            font-size: 14px;
            font-weight: 400;
            text-align: left;
        }

        .theme-select-option:hover,
        .theme-select-option:focus {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            outline: none;
        }

        .theme-select-option.is-selected {
            color: var(--primary-dark);
            background: rgba(15, 118, 110, 0.09);
        }

        .theme-select-option.is-selected:hover,
        .theme-select-option.is-selected:focus {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        .theme-select-empty {
            display: none;
            min-height: 34px;
            align-items: center;
            padding: 0 10px;
            color: var(--muted);
            font-size: 13px;
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

        .field-align-spacer {
            min-height: 16px;
            margin: -4px 2px 0;
        }

        .form-alert {
            margin-bottom: 12px;
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

        @media (max-width: 640px) {
            .site-header-inner {
                min-height: 54px;
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

            .product-page {
                width: auto;
                padding: 18px;
                transform: none;
            }

            .eyebrow {
                display: none;
            }

            .page-title h1 {
                font-size: 19px;
            }

            .form-panel {
                padding: 10px;
                border-radius: 16px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .field label,
            .field input,
            .field select,
            .theme-select-toggle,
            .clear-btn,
            .save-btn {
                font-size: 13px;
            }

            .field input,
            .field select,
            .theme-select-toggle,
            .clear-btn,
            .save-btn {
                min-height: 36px;
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

            <div class="header-title">Product Master Update</div>

            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="product-workspace account-master-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="product-page">
            <section class="page-title" aria-labelledby="productEditTitle">
                <div>
                    <p class="eyebrow">Masters</p>
                    <h1 id="productEditTitle">Edit Product</h1>
                </div>
                <a href="{{ route('product.list') }}" class="view-list-btn">View List</a>
            </section>

            <form class="form-panel" id="productForm" method="POST" action="{{ route('product.update', $product->id) }}" autocomplete="off">
                @csrf
                @method('PUT')

                @if (session('success'))
                    <div class="form-alert success">{{ session('success') }}</div>
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

                <div class="form-grid">
                    <div class="field">
                        <label for="productName">Product Name <span class="required-star">*</span></label>
                        <input type="text" id="productName" name="Product_Name" placeholder="Enter product name" maxlength="255" value="{{ old('Product_Name', $product->Product_Name) }}" data-show-limit data-limit-unit="characters" required>
                    </div>

                    <div class="field">
                        <label for="hsn">HSN Code</label>
                        <input type="text" id="hsn" name="HSN" placeholder="Enter HSN code" maxlength="255" value="{{ old('HSN', $product->HSN) }}">
                        <span class="field-align-spacer" aria-hidden="true"></span>
                    </div>

                    @php
                        $selectedCategory = old('Category', $product->Category);
                    @endphp
                    <div class="field">
                        <label for="category">Category <span class="required-star">*</span></label>
                        <div class="theme-select" data-theme-select>
                            <input type="hidden" id="category" name="Category" value="{{ $selectedCategory }}" data-placeholder="Select category" required>
                            <button type="button" class="theme-select-toggle" aria-haspopup="listbox" aria-expanded="false">
                                <span data-theme-select-label>{{ $selectedCategory ?: 'Select category' }}</span>
                            </button>
                            <div class="theme-select-menu" role="listbox">
                                <input type="search" class="theme-select-search" placeholder="Search category" autocomplete="off" data-theme-select-search>
                                @foreach ($categories as $category)
                                    <button type="button" class="theme-select-option {{ $selectedCategory === $category->Category_Name ? 'is-selected' : '' }}" role="option" aria-selected="{{ $selectedCategory === $category->Category_Name ? 'true' : 'false' }}" data-value="{{ $category->Category_Name }}">
                                        {{ $category->Category_Name }}
                                    </button>
                                @endforeach
                                <div class="theme-select-empty" data-theme-select-empty>No category found</div>
                            </div>
                        </div>
                        <span class="field-align-spacer" aria-hidden="true"></span>
                    </div>

                    <div class="field">
                        <label for="gstPer">GST Rate (%)</label>
                        <input type="number" id="gstPer" name="GST_per" placeholder="Enter GST percentage" min="0" step="0.01" value="{{ old('GST_per', $product->GST_per) }}">
                        <span class="field-align-spacer" aria-hidden="true"></span>
                    </div>

                    <div class="field">
                        <label for="purchaseRate">Purchase Rate</label>
                        <input type="number" id="purchaseRate" name="Purchase_rate" placeholder="Enter purchase rate" min="0" step="0.01" value="{{ old('Purchase_rate', $product->Purchase_rate) }}">
                        <span class="field-align-spacer" aria-hidden="true"></span>
                    </div>

                    <div class="field">
                        <label for="openingStock">Opening Stock</label>
                        <input type="number" id="openingStock" name="opening_stock" placeholder="Enter opening stock quantity" min="0" step="1" value="{{ old('opening_stock', $product->opening_stock) }}">
                        <span class="field-align-spacer" aria-hidden="true"></span>
                    </div>

                    <div class="field">
                        <label for="openingStockValue">Opening Stock Value</label>
                        <input type="number" id="openingStockValue" name="opening_stock_value" placeholder="Enter opening stock value" min="0" step="0.01" value="{{ old('opening_stock_value', $product->opening_stock_value) }}">
                        <span class="field-align-spacer" aria-hidden="true"></span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="clear-btn">Clear</button>
                    <button type="submit" class="save-btn">Update</button>
                </div>

                <p class="form-note">
                    <strong>Note</strong>
                    Update product details carefully so linked sales, purchase and inventory records remain accurate.
                </p>
            </form>
        </main>
    </div>

    <script>
        const productForm = document.getElementById('productForm');
        const saveButton = productForm.querySelector('.save-btn');
        const formAlerts = document.querySelectorAll('.form-alert');
        const limitFields = document.querySelectorAll('[data-show-limit]');

        formAlerts.forEach((alert) => {
            setTimeout(() => {
                alert.classList.add('is-hiding');
                setTimeout(() => alert.remove(), 260);
            }, 4000);
        });

        limitFields.forEach((field) => {
            const limit = document.createElement('p');
            const help = document.createElement('span');
            const count = document.createElement('span');
            const maxLength = Number(field.maxLength) || 0;
            const unit = field.dataset.limitUnit || 'characters';

            limit.className = 'field-limit';
            count.className = 'field-limit-count';
            help.textContent = `Maximum ${maxLength} ${unit}`;
            limit.append(help, count);
            field.insertAdjacentElement('afterend', limit);

            const updateCount = () => {
                count.textContent = `${field.value.length} / ${maxLength}`;
            };

            field.addEventListener('input', updateCount);
            updateCount();
        });

        productForm.addEventListener('submit', () => {
            saveButton.disabled = true;
            saveButton.textContent = 'Updating...';
        });

        document.querySelectorAll('[data-theme-select]').forEach((dropdown) => {
            const toggle = dropdown.querySelector('.theme-select-toggle');
            const label = dropdown.querySelector('[data-theme-select-label]');
            const input = dropdown.querySelector('input[type="hidden"]');
            const search = dropdown.querySelector('[data-theme-select-search]');
            const empty = dropdown.querySelector('[data-theme-select-empty]');
            const options = dropdown.querySelectorAll('.theme-select-option');
            const placeholder = input.dataset.placeholder || 'Select';

            const closeDropdown = () => {
                dropdown.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            };

            toggle.addEventListener('click', () => {
                const isOpen = dropdown.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                if (isOpen && search) {
                    search.value = '';
                    options.forEach((option) => option.hidden = false);
                    if (empty) {
                        empty.style.display = 'none';
                    }
                    setTimeout(() => search.focus(), 0);
                }
            });

            search?.addEventListener('input', () => {
                const query = search.value.trim().toLowerCase();
                let visibleCount = 0;

                options.forEach((option) => {
                    const isVisible = option.dataset.value.toLowerCase().includes(query);
                    option.hidden = !isVisible;
                    visibleCount += isVisible ? 1 : 0;
                });

                if (empty) {
                    empty.style.display = visibleCount ? 'none' : 'flex';
                }
            });

            options.forEach((option) => {
                option.addEventListener('click', () => {
                    input.value = option.dataset.value;
                    label.textContent = option.dataset.value;
                    options.forEach((item) => {
                        item.classList.toggle('is-selected', item === option);
                        item.setAttribute('aria-selected', item === option ? 'true' : 'false');
                    });
                    closeDropdown();
                });
            });

            productForm.addEventListener('reset', () => {
                setTimeout(() => {
                    label.textContent = input.value || placeholder;
                    options.forEach((item) => {
                        const isSelected = item.dataset.value === input.value;
                        item.classList.toggle('is-selected', isSelected);
                        item.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                    });
                    closeDropdown();
                }, 0);
            });
        });

        document.addEventListener('click', (event) => {
            document.querySelectorAll('[data-theme-select].is-open').forEach((dropdown) => {
                if (!dropdown.contains(event.target)) {
                    dropdown.classList.remove('is-open');
                    dropdown.querySelector('.theme-select-toggle').setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
</body>
</html>
