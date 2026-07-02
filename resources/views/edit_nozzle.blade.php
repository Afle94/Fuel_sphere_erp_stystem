<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Nozzle | FuelTracker</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <style>
        :root { --bg:#f4f7fb; --panel:#ffffff; --ink:#172033; --muted:#657089; --line:#dce3ee; --primary:#0f766e; --primary-dark:#115e59; --danger:#b42318; --shadow:0 24px 70px rgba(23,32,51,.14); }
        * { box-sizing:border-box; }
        body { margin:0; min-height:100vh; min-height:100dvh; overflow-x:hidden; font-family:Arial, Helvetica, sans-serif; color:var(--ink); background:radial-gradient(circle at top left, rgba(15,118,110,.16), transparent 32rem), linear-gradient(135deg,#f8fbff 0%,var(--bg) 55%,#eef5f3 100%); }
        .site-header { position:sticky; top:0; z-index:20; width:100%; background:linear-gradient(135deg,rgba(8,47,73,.98),rgba(15,118,110,.98)); box-shadow:0 10px 30px rgba(23,32,51,.12); }
        .site-header-inner { width:100%; min-height:48px; display:flex; align-items:center; justify-content:space-between; gap:18px; margin:0 auto; padding:0 18px; position:relative; }
        .site-logo { display:inline-flex; align-items:center; gap:9px; color:#fff; font-size:24px; font-weight:700; text-decoration:none; }
        .site-logo-icon { display:grid; width:34px; height:34px; place-items:center; border-radius:12px; background:#fff; box-shadow:0 10px 28px rgba(0,0,0,.18); overflow:hidden; padding:2px; }
        .app-logo-image { display:block; width:100%; height:100%; border-radius:inherit; object-fit:cover; }
        .header-title { position:absolute; left:50%; color:#fff; font-size:20px; font-weight:700; transform:translateX(-50%); white-space:nowrap; }
        .header-actions { position:absolute; right:18px; display:flex; align-items:center; gap:12px; }
        .back-link,.logout-btn,.save-btn { display:inline-flex; align-items:center; justify-content:center; min-height:36px; border-radius:10px; cursor:pointer; font-size:14px; font-weight:700; text-decoration:none; transition:background .2s ease, transform .2s ease; }
        .back-link,.logout-btn { padding:0 16px; border:1px solid rgba(255,255,255,.24); color:#fff; background:rgba(255,255,255,.12); }
        .back-link:hover,.logout-btn:hover { background:rgba(255,255,255,.2); transform:translateY(-1px); }
        .logout-btn { font-family:inherit; }
        .nozzle-workspace { min-height:calc(100vh - 48px); min-height:calc(100dvh - 48px); position:relative; }
        .nozzle-page { width:min(100% - 36px,900px); margin:0 auto; padding:14px 0 70px; transform:translateX(86px); transition:transform .22s ease; }
        .nozzle-workspace.menu-collapsed .nozzle-page { transform:translateX(0); }
        .page-title { display:flex; align-items:center; justify-content:space-between; gap:14px; margin:0 auto 10px; }
        .eyebrow { margin:0 0 3px; color:var(--primary); font-size:12px; font-weight:700; text-transform:uppercase; }
        .page-title h1 { margin:0; font-size:24px; line-height:1.15; }
        .view-list-btn { min-height:34px; display:inline-flex; align-items:center; justify-content:center; padding:0 15px; border:1px solid rgba(15,118,110,.2); border-radius:12px; color:var(--primary-dark); background:rgba(15,118,110,.08); font-size:13px; font-weight:700; text-decoration:none; }
        .view-list-btn:hover { color:#fff; background:var(--primary); transform:translateY(-1px); }
        .panel { border:1px solid rgba(220,227,238,.86); border-radius:20px; background:var(--panel); box-shadow:var(--shadow); }
        .form-panel { padding:14px 16px; }
        .form-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:14px; }
        .field { display:grid; gap:7px; align-content:start; }
        .field label { color:var(--ink); font-size:14px; font-weight:700; }
        .required-star { color:var(--danger); font-weight:700; }
        .field input,.field select { width:100%; min-height:42px; padding:0 14px; border:1px solid var(--line); border-radius:12px; color:var(--ink); background:#fbfcfe; font:inherit; font-size:15px; outline:none; transition:border-color .2s ease, box-shadow .2s ease, background .2s ease; }
        .field select { padding-right:42px; appearance:none; background:linear-gradient(135deg,rgba(15,118,110,.08),rgba(20,184,166,.04)),#fbfcfe url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='m6 9 6 6 6-6' stroke='%230f766e' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 14px center; cursor:pointer; font-weight:600; }
        .field input:focus,.field select:focus { border-color:rgba(15,118,110,.52); background:#fff; box-shadow:0 0 0 4px rgba(15,118,110,.13); }
        .field select:focus { background:#fff url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='m6 9 6 6 6-6' stroke='%23115e59' stroke-width='2.4' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 14px center; }
        .field select:hover { border-color:rgba(15,118,110,.42); background-color:#fff; }
        .theme-select { position:relative; }
        .theme-select-toggle { width:100%; min-height:42px; display:flex; align-items:center; justify-content:space-between; gap:12px; padding:0 14px; border:1px solid var(--line); border-radius:12px; color:var(--ink); background:linear-gradient(135deg,rgba(15,118,110,.08),rgba(20,184,166,.04)),#fbfcfe; cursor:pointer; font:inherit; font-size:15px; font-weight:400; text-align:left; transition:border-color .2s ease, box-shadow .2s ease, background .2s ease; }
        .theme-select-toggle::after { content:""; width:0; height:0; flex:0 0 auto; border-left:5px solid transparent; border-right:5px solid transparent; border-top:6px solid var(--primary); }
        .theme-select-toggle:hover { border-color:rgba(15,118,110,.42); background:#fff; }
        .theme-select-toggle:focus { border-color:rgba(15,118,110,.52); background:#fff; box-shadow:0 0 0 4px rgba(15,118,110,.13); outline:none; }
        .theme-select-menu { position:absolute; top:calc(100% + 6px); left:0; right:0; z-index:30; display:none; max-height:220px; overflow:auto; padding:6px; border:1px solid var(--line); border-radius:12px; background:#fff; box-shadow:0 18px 40px rgba(23,32,51,.16); }
        .theme-select.is-open .theme-select-menu { display:grid; gap:4px; }
        .theme-select-search { width:100%; min-height:34px; margin:0 0 4px; padding:0 10px; border:1px solid var(--line); border-radius:9px; color:var(--ink); background:#fbfcfe; font:inherit; font-size:13px; outline:none; }
        .theme-select-search:focus { border-color:rgba(15,118,110,.52); background:#fff; box-shadow:0 0 0 3px rgba(15,118,110,.12); }
        .theme-select-option { min-height:36px; padding:0 10px; border:0; border-radius:9px; color:var(--ink); background:#fff; cursor:pointer; font:inherit; font-size:14px; font-weight:400; text-align:left; }
        .theme-select-option:hover,.theme-select-option:focus { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); outline:none; }
        .theme-select-option.is-selected { color:var(--primary-dark); background:rgba(15,118,110,.09); }
        .theme-select-option.is-selected:hover,.theme-select-option.is-selected:focus { color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); }
        .theme-select-empty { display:none; min-height:34px; align-items:center; padding:0 10px; color:var(--muted); font-size:13px; }
        .form-alert { margin:0 0 12px; padding:10px 12px; border-radius:12px; font-size:14px; font-weight:700; }
        .form-alert.success { color:#067647; background:#ecfdf3; border:1px solid rgba(6,118,71,.22); }
        .form-alert.error { color:var(--danger); background:#fff1f0; border:1px solid rgba(180,35,24,.22); }
        .form-alert ul { margin:8px 0 0; padding-left:18px; }
        .form-alert.is-hiding { opacity:0; transform:translateY(-4px); transition:opacity .25s ease, transform .25s ease; }
        .form-actions { display:flex; align-items:center; justify-content:flex-end; gap:12px; margin:18px -16px -14px; padding:14px 16px; border-top:1px solid var(--line); border-radius:0 0 20px 20px; background:#fbfcfe; }
        .save-btn { min-width:132px; min-height:40px; border:0; border-radius:11px; color:#fff; background:linear-gradient(135deg,var(--primary-dark),var(--primary)); box-shadow:0 12px 24px rgba(15,118,110,.22); }
        .save-btn:hover,.save-btn:focus { transform:translateY(-1px); box-shadow:0 16px 30px rgba(15,118,110,.26); outline:none; }
        .clear-btn { min-width:112px; min-height:40px; display:inline-flex; align-items:center; justify-content:center; padding:0 18px; border:1px solid var(--line); border-radius:11px; color:var(--ink); background:#fff; cursor:pointer; font-size:14px; font-weight:700; text-decoration:none; box-shadow:0 8px 18px rgba(23,32,51,.06); }
        .clear-btn:hover,.clear-btn:focus { border-color:rgba(15,118,110,.32); color:var(--primary-dark); background:rgba(15,118,110,.08); outline:none; transform:translateY(-1px); }
        @media (max-width:940px) { .nozzle-page{transform:none;padding:18px} }
        @media (max-width:640px) { .site-header-inner{min-height:54px;padding:0 10px}.site-logo{font-size:20px}.header-actions{right:10px;gap:6px}.header-title{font-size:14px}.back-link,.logout-btn{min-height:32px;padding:0 7px;font-size:12px}.page-title h1{font-size:19px}.eyebrow{display:none}.form-panel{padding:10px;border-radius:16px}.form-grid{grid-template-columns:1fr}.field label,.field input,.field select,.theme-select-toggle{font-size:13px}.field input,.field select,.theme-select-toggle{min-height:36px;padding:0 9px}.field select{padding-right:36px;background-position:right 10px center}.form-actions{align-items:stretch;flex-direction:column-reverse;margin:14px -10px -10px;padding:12px;border-radius:0 0 16px 16px}.save-btn,.clear-btn{width:100%;min-height:36px;font-size:12px} }
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
            <div class="header-title">Edit Nozzle</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-btn">Logout</button></form>
            </div>
        </div>
    </header>

    <div class="nozzle-workspace account-master-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')
        <main class="nozzle-page">
            <section class="page-title" aria-labelledby="editNozzleTitle">
                <div>
                    <p class="eyebrow">Masters</p>
                    <h1 id="editNozzleTitle">Edit Nozzle Details</h1>
                </div>
                <a href="{{ route('nozzle.list') }}" class="view-list-btn">View List</a>
            </section>

            <form class="panel form-panel" id="nozzleForm" method="POST" action="{{ route('nozzle.update', $nozzle->id) }}" autocomplete="off">
                @csrf
                @method('PUT')
                @if (session('success')) <div class="form-alert success">{{ session('success') }}</div> @endif
                @if ($errors->any())
                    <div class="form-alert error">
                        Please fix the highlighted details.
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <div class="form-grid">
                    <div class="field">
                        <label for="Nozzle_Name">Nozzle Name <span class="required-star">*</span></label>
                        <input type="text" id="Nozzle_Name" name="Nozzle_Name" maxlength="50" value="{{ old('Nozzle_Name', $nozzle->Nozzle_Name) }}" required>
                    </div>
                    @php
                        $selectedItem = old('Item', $nozzle->Item);
                    @endphp
                    <div class="field">
                        <label for="Item">Item <span class="required-star">*</span></label>
                        <div class="theme-select" data-theme-select>
                            <input type="hidden" id="Item" name="Item" value="{{ $selectedItem }}" required>
                            <button type="button" class="theme-select-toggle" aria-haspopup="listbox" aria-expanded="false">
                                <span data-theme-select-label>{{ $selectedItem ?: 'Select product' }}</span>
                            </button>
                            <div class="theme-select-menu" role="listbox">
                                <input type="search" class="theme-select-search" placeholder="Search product" autocomplete="off" data-theme-select-search>
                                @foreach ($products as $product)
                                    <button type="button" class="theme-select-option {{ $selectedItem === $product->Product_Name ? 'is-selected' : '' }}" role="option" aria-selected="{{ $selectedItem === $product->Product_Name ? 'true' : 'false' }}" data-value="{{ $product->Product_Name }}">
                                        {{ $product->Product_Name }}
                                    </button>
                                @endforeach
                                <div class="theme-select-empty" data-theme-select-empty>No product found</div>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label for="Open_Date">Open Date</label>
                        <input type="date" id="Open_Date" name="Open_Date" value="{{ old('Open_Date', optional($nozzle->Open_Date)->format('Y-m-d')) }}">
                    </div>
                    <div class="field">
                        <label for="Close_Date">Close Date</label>
                        <input type="date" id="Close_Date" name="Close_Date" value="{{ old('Close_Date', optional($nozzle->Close_Date)->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('nozzle.list') }}" class="clear-btn">Cancel</a>
                    <button type="submit" class="save-btn">Update</button>
                </div>
            </form>
        </main>
    </div>

    <script>
        const nozzleForm = document.getElementById('nozzleForm');
        const saveButton = nozzleForm.querySelector('.save-btn');
        document.querySelectorAll('.form-alert').forEach((alert) => {
            setTimeout(() => {
                alert.classList.add('is-hiding');
                setTimeout(() => alert.remove(), 260);
            }, 4000);
        });
        nozzleForm.addEventListener('submit', () => {
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
                    dropdown.classList.remove('is-open');
                    toggle.setAttribute('aria-expanded', 'false');
                });
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
