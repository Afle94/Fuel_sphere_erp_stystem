<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item Date Wise Rate | FuelTracker</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <style>
        :root { --bg:#f4f7fb; --panel:#fff; --ink:#172033; --muted:#657089; --line:#dce3ee; --primary:#0f766e; --primary-dark:#115e59; --danger:#b42318; --shadow:0 24px 70px rgba(23,32,51,.14); }
        * { box-sizing:border-box; }
        body { margin:0; min-height:100vh; font-family:Arial, Helvetica, sans-serif; color:var(--ink); background:radial-gradient(circle at top left, rgba(15,118,110,.16), transparent 32rem), linear-gradient(135deg,#f8fbff 0%,var(--bg) 55%,#eef5f3 100%); }
        .site-header { position:sticky; top:0; z-index:20; background:linear-gradient(135deg,rgba(8,47,73,.98),rgba(15,118,110,.98)); box-shadow:0 10px 30px rgba(23,32,51,.12); }
        .site-header-inner { min-height:48px; display:flex; align-items:center; justify-content:space-between; gap:18px; padding:0 18px; position:relative; }
        .site-logo { display:inline-flex; align-items:center; gap:9px; color:#fff; font-size:24px; font-weight:700; text-decoration:none; }
        .site-logo-icon { width:34px; height:34px; display:grid; place-items:center; overflow:hidden; padding:2px; border-radius:12px; background:#fff; box-shadow:0 10px 28px rgba(0,0,0,.18); }
        .app-logo-image { width:100%; height:100%; border-radius:inherit; object-fit:cover; }
        .header-title { position:absolute; left:50%; transform:translateX(-50%); color:#fff; font-size:20px; font-weight:700; white-space:nowrap; }
        .header-actions { display:flex; align-items:center; gap:12px; }
        .back-link,.logout-btn,.save-btn,.view-list-btn,.clear-btn { display:inline-flex; align-items:center; justify-content:center; min-height:36px; border-radius:10px; cursor:pointer; font-size:14px; font-weight:700; text-decoration:none; }
        .back-link,.logout-btn { padding:0 16px; border:1px solid rgba(255,255,255,.24); color:#fff; background:rgba(255,255,255,.12); }
        .logout-btn,.save-btn,.clear-btn { font-family:inherit; }
        .rate-workspace { min-height:calc(100vh - 48px); position:relative; }
        .rate-page { width:min(100% - 36px,900px); margin:0 auto; padding:14px 0 70px; transform:translateX(86px); transition:transform .22s ease; }
        .rate-workspace.menu-collapsed .rate-page { transform:translateX(0); }
        .page-title { display:flex; align-items:center; justify-content:space-between; gap:14px; margin:0 auto 10px; }
        .eyebrow { margin:0 0 3px; color:var(--primary); font-size:12px; font-weight:700; text-transform:uppercase; }
        h1 { margin:0; font-size:24px; line-height:1.15; }
        .view-list-btn { min-height:34px; padding:0 15px; border:1px solid rgba(15,118,110,.2); border-radius:12px; color:var(--primary-dark); background:rgba(15,118,110,.08); font-size:13px; }
        .panel { padding:14px 16px; border:1px solid rgba(220,227,238,.86); border-radius:20px; background:var(--panel); box-shadow:var(--shadow); }
        .form-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:14px; }
        .field { display:grid; gap:7px; }
        .field label { font-size:14px; font-weight:700; }
        .required-star { color:var(--danger); }
        .field input,.field select { width:100%; min-height:42px; padding:0 14px; border:1px solid var(--line); border-radius:12px; color:var(--ink); background:#fbfcfe; font:inherit; font-size:15px; outline:none; }
        .field input:focus,.field select:focus { border-color:rgba(15,118,110,.52); background:#fff; box-shadow:0 0 0 4px rgba(15,118,110,.13); }
        .theme-select { position:relative; }
        .theme-select-toggle { width:100%; min-height:42px; display:flex; align-items:center; justify-content:space-between; gap:12px; padding:0 14px; border:1px solid var(--line); border-radius:12px; color:var(--ink); background:linear-gradient(135deg,rgba(15,118,110,.08),rgba(20,184,166,.04)),#fbfcfe; cursor:pointer; font:inherit; font-size:15px; text-align:left; }
        .theme-select-toggle::after { content:""; width:0; height:0; border-left:5px solid transparent; border-right:5px solid transparent; border-top:6px solid var(--primary); }
        .theme-select-toggle:hover,.theme-select-toggle:focus { border-color:rgba(15,118,110,.52); background:#fff; box-shadow:0 0 0 4px rgba(15,118,110,.13); outline:none; }
        .theme-select-menu { position:absolute; top:calc(100% + 6px); left:0; right:0; z-index:30; display:none; max-height:240px; overflow:auto; padding:6px; border:1px solid var(--line); border-radius:12px; background:#fff; box-shadow:0 18px 40px rgba(23,32,51,.16); }
        .theme-select.is-open .theme-select-menu { display:grid; gap:4px; }
        .theme-select-search { width:100%; min-height:34px; margin:0 0 4px; padding:0 10px; border:1px solid var(--line); border-radius:9px; color:var(--ink); background:#fbfcfe; font:inherit; font-size:13px; outline:none; }
        .theme-select-option { min-height:36px; padding:0 10px; border:0; border-radius:9px; color:var(--ink); background:#fff; cursor:pointer; font:inherit; font-size:14px; text-align:left; }
        .theme-select-option:hover,.theme-select-option:focus { color:#fff; outline:none; }
        .theme-select-option.is-selected { color:var(--primary-dark); background:rgba(15,118,110,.09); font-weight:700; }
        .theme-select-empty { display:none; min-height:34px; align-items:center; padding:0 10px; color:var(--muted); font-size:13px; }
        .form-alert { margin-bottom:12px; padding:10px 12px; border-radius:12px; font-size:14px; font-weight:700; }
        .form-alert.success { color:#067647; background:#ecfdf3; border:1px solid rgba(6,118,71,.22); }
        .form-alert.error { color:var(--danger); background:#fff1f0; border:1px solid rgba(180,35,24,.22); }
        .form-alert ul { margin:6px 0 0 18px; padding:0; }
        .form-actions { display:flex; align-items:center; justify-content:flex-end; gap:10px; margin-top:10px; padding-top:10px; border-top:1px solid var(--line); }
        .save-btn { min-height:42px; min-width:132px; border:0; color:#fff; background:var(--primary); box-shadow:0 12px 28px rgba(15,118,110,.22); }
        .clear-btn { min-height:42px; padding:0 16px; border:1px solid var(--line); border-radius:14px; color:var(--muted); background:#fbfcfe; }
        @media (max-width:640px) { .header-title{display:none}.rate-page{width:auto;padding:18px;transform:none}.form-grid{grid-template-columns:1fr}.site-logo{font-size:20px}.back-link,.logout-btn{padding:0 7px;font-size:12px} }
    </style>
    @include('partials.theme')
</head>
<body>
    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true"><img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image"></span>
                <span>FuelTracker</span>
            </a>
            <div class="header-title">Edit Item Date Wise Rate</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-btn">Logout</button></form>
            </div>
        </div>
    </header>

    <div class="rate-workspace account-master-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="rate-page">
            <section class="page-title" aria-labelledby="itemRateEditTitle">
                <div>
                    <p class="eyebrow">Masters</p>
                    <h1 id="itemRateEditTitle">Edit Item Date Wise Rate</h1>
                </div>
                <a href="{{ route('item-date-rates.list') }}" class="view-list-btn">View List</a>
            </section>

            <form class="panel" id="itemDateRateForm" method="POST" action="{{ route('item-date-rates.update', $itemDateRate->id) }}" autocomplete="off">
                @csrf
                @method('PUT')

                @if (session('success')) <div class="form-alert success">{{ session('success') }}</div> @endif
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
                        <label for="rateDate">Date <span class="required-star">*</span></label>
                        <input type="date" id="rateDate" name="rate_date" value="{{ old('rate_date', optional($itemDateRate->rate_date)->toDateString()) }}" required>
                    </div>

                    <div class="field">
                        <label for="productId">Item Name <span class="required-star">*</span></label>
                        @php
                            $selectedProductId = old('product_id', $itemDateRate->product_id);
                            $selectedProduct = $products->firstWhere('id', (int) $selectedProductId);
                        @endphp
                        <div class="theme-select" data-theme-select>
                            <input type="hidden" id="productId" name="product_id" value="{{ $selectedProductId }}" data-placeholder="Select product" required>
                            <button type="button" class="theme-select-toggle" aria-haspopup="listbox" aria-expanded="false">
                                <span data-theme-select-label>{{ $selectedProduct?->Product_Name ?: 'Select product' }}</span>
                            </button>
                            <div class="theme-select-menu" role="listbox">
                                <input type="search" class="theme-select-search" placeholder="Search product" autocomplete="off" data-theme-select-search>
                            @foreach ($products as $product)
                                    <button type="button" class="theme-select-option {{ (string) $selectedProductId === (string) $product->id ? 'is-selected' : '' }}" role="option" aria-selected="{{ (string) $selectedProductId === (string) $product->id ? 'true' : 'false' }}" data-value="{{ $product->id }}" data-label="{{ $product->Product_Name }}">
                                        {{ $product->Product_Name }}
                                    </button>
                            @endforeach
                                <div class="theme-select-empty" data-theme-select-empty>No product found</div>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label for="rate">Rate <span class="required-star">*</span></label>
                        <input type="number" id="rate" name="rate" placeholder="Enter rate" min="0" step="0.01" value="{{ old('rate', $itemDateRate->rate) }}" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="clear-btn">Clear</button>
                    <button type="submit" class="save-btn">Update</button>
                </div>
            </form>
        </main>
    </div>

    <script>
        const form = document.getElementById('itemDateRateForm');
        const saveButton = form.querySelector('.save-btn');
        form.addEventListener('submit', () => {
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

            const closeDropdown = () => {
                dropdown.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            };

            toggle.addEventListener('click', () => {
                const isOpen = dropdown.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                if (isOpen) {
                    search.value = '';
                    options.forEach((option) => option.hidden = false);
                    empty.style.display = 'none';
                    setTimeout(() => search.focus(), 0);
                }
            });

            search.addEventListener('input', () => {
                const query = search.value.trim().toLowerCase();
                let visibleCount = 0;

                options.forEach((option) => {
                    const isVisible = option.dataset.label.toLowerCase().includes(query);
                    option.hidden = !isVisible;
                    visibleCount += isVisible ? 1 : 0;
                });

                empty.style.display = visibleCount ? 'none' : 'flex';
            });

            options.forEach((option) => {
                option.addEventListener('click', () => {
                    input.value = option.dataset.value;
                    label.textContent = option.dataset.label;
                    options.forEach((item) => {
                        const isSelected = item === option;
                        item.classList.toggle('is-selected', isSelected);
                        item.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                    });
                    closeDropdown();
                });
            });

            document.addEventListener('click', (event) => {
                if (!dropdown.contains(event.target)) {
                    closeDropdown();
                }
            });
        });
    </script>
</body>
</html>
