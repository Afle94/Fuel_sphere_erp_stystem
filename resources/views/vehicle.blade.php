<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vehicle Master Creation | FuelTracker</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <style>
        :root{--bg:#f4f7fb;--panel:#fff;--ink:#172033;--muted:#657089;--line:#dce3ee;--primary:#0f766e;--primary-dark:#115e59;--danger:#b42318;--shadow:0 24px 70px rgba(23,32,51,.14)}
        *{box-sizing:border-box}body{margin:0;min-height:100vh;overflow-x:hidden;font-family:Arial,Helvetica,sans-serif;color:var(--ink);background:radial-gradient(circle at top left,rgba(15,118,110,.16),transparent 32rem),linear-gradient(135deg,#f8fbff 0%,var(--bg) 55%,#eef5f3 100%)}
        .site-header{position:sticky;top:0;z-index:20;width:100%;background:linear-gradient(135deg,rgba(8,47,73,.98),rgba(15,118,110,.98));box-shadow:0 10px 30px rgba(23,32,51,.12)}
        .site-header-inner{width:100%;min-height:48px;display:flex;align-items:center;justify-content:space-between;gap:18px;margin:0 auto;padding:0 18px;position:relative}
        .site-logo{display:inline-flex;align-items:center;gap:9px;color:#fff;font-size:24px;font-weight:700;text-decoration:none}.site-logo-icon{display:grid;width:34px;height:34px;place-items:center;border-radius:12px;background:#fff;box-shadow:0 10px 28px rgba(0,0,0,.18);overflow:hidden;padding:2px}.app-logo-image{display:block;width:100%;height:100%;border-radius:inherit;object-fit:cover}
        .header-title{position:absolute;left:50%;color:#fff;font-size:20px;font-weight:700;transform:translateX(-50%);white-space:nowrap}.header-actions{position:absolute;right:18px;display:flex;align-items:center;gap:12px}
        .back-link,.logout-btn,.save-btn{display:inline-flex;align-items:center;justify-content:center;min-height:36px;border-radius:10px;cursor:pointer;font-size:14px;font-weight:700;text-decoration:none;transition:background .2s ease,transform .2s ease}.back-link,.logout-btn{padding:0 16px;border:1px solid rgba(255,255,255,.24);color:#fff;background:rgba(255,255,255,.12)}.back-link:hover,.logout-btn:hover{background:rgba(255,255,255,.2);transform:translateY(-1px)}.logout-btn{font-family:inherit}
        .vehicle-workspace{min-height:calc(100vh - 48px);position:relative}.vehicle-page{width:min(100% - 36px,900px);display:block;margin:0 auto;padding:14px 0 70px;transform:translateX(86px);transition:transform .22s ease}.vehicle-workspace.menu-collapsed .vehicle-page{transform:translateX(0)}
        .page-title{display:flex;align-items:center;justify-content:space-between;gap:14px;margin:0 auto 10px}.eyebrow{margin:0 0 3px;color:var(--primary);font-size:12px;font-weight:700;text-transform:uppercase}.page-title h1{margin:0;font-size:24px;line-height:1.15}.view-list-btn{min-height:34px;display:inline-flex;align-items:center;justify-content:center;padding:0 15px;border:1px solid rgba(15,118,110,.2);border-radius:12px;color:var(--primary-dark);background:rgba(15,118,110,.08);font-size:13px;font-weight:700;text-decoration:none}.view-list-btn:hover{color:#fff;background:var(--primary);transform:translateY(-1px)}
        .panel{border:1px solid rgba(220,227,238,.86);border-radius:20px;background:var(--panel);box-shadow:var(--shadow)}.form-panel{padding:14px 16px}.form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:14px}.field{display:grid;gap:7px;align-content:start}.field label{color:var(--ink);font-size:14px;font-weight:700}.required-star{color:var(--danger);font-weight:700}
        .field input{width:100%;min-height:42px;padding:0 14px;border:1px solid var(--line);border-radius:12px;color:var(--ink);background:#fbfcfe;font:inherit;font-size:15px;outline:none;transition:border-color .2s ease,box-shadow .2s ease,background .2s ease}.field input:focus{border-color:rgba(15,118,110,.52);background:#fff;box-shadow:0 0 0 4px rgba(15,118,110,.13)}
        .theme-dropdown{position:relative;height:42px;min-height:0;display:block}.theme-dropdown-value{position:absolute;opacity:0;pointer-events:none}.theme-dropdown-button{width:100%;height:100%;min-height:0;display:flex;align-items:center;justify-content:space-between;gap:10px;padding:0 12px;border:1px solid var(--line);border-radius:12px;color:var(--ink);background:#fbfcfe;cursor:pointer;font:inherit;font-size:15px;outline:none;text-align:left;transition:border-color .2s ease,box-shadow .2s ease,background .2s ease}.theme-dropdown-button:hover,.theme-dropdown-button:focus{border-color:rgba(15,118,110,.52);background:rgba(15,118,110,.07);box-shadow:0 0 0 4px rgba(15,118,110,.13)}.theme-dropdown-text{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.theme-dropdown-arrow{width:9px;height:9px;flex:0 0 auto;border-right:2px solid currentColor;border-bottom:2px solid currentColor;transform:translateY(-2px) rotate(45deg)}.theme-dropdown-menu{position:absolute;top:calc(100% + 6px);right:0;left:0;z-index:40;display:none;max-height:180px;overflow-y:auto;margin:0;padding:4px;border:1px solid rgba(15,118,110,.22);border-radius:12px;background:#fff;box-shadow:0 14px 32px rgba(23,32,51,.16);list-style:none;scrollbar-width:thin;scrollbar-color:rgba(15,118,110,.46) rgba(220,227,238,.72)}.theme-dropdown.is-open .theme-dropdown-menu{display:block}.theme-dropdown-search-wrap{position:sticky;top:-4px;z-index:1;padding:4px 4px 6px;background:#fff}.theme-dropdown-search{width:100%;min-height:36px;padding:0 10px;border:1px solid var(--line);border-radius:9px;color:var(--ink);background:#fbfcfe;font:inherit;font-size:14px;outline:none}.theme-dropdown-search:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(15,118,110,.18)}.theme-dropdown-option{width:100%;min-height:36px;padding:0 10px;border:0;border-radius:9px;color:var(--ink);background:transparent;cursor:pointer;font:inherit;font-size:14px;text-align:left;transition:background .2s ease,color .2s ease}.theme-dropdown-option:hover,.theme-dropdown-option:focus,.theme-dropdown-option.is-selected:hover,.theme-dropdown-option.is-selected:focus{color:#fff;background:linear-gradient(135deg,var(--primary-dark),var(--primary));outline:none}.theme-dropdown-empty{display:none;min-height:32px;padding:8px 10px;color:var(--muted);font-size:14px}.theme-dropdown-empty.is-visible{display:block}
        .field-help{display:flex;align-items:center;justify-content:space-between;gap:10px;margin:-2px 2px 0;color:var(--muted);font-size:12px;line-height:1.35}.field-count{flex:0 0 auto;color:var(--primary-dark);font-weight:700}
        .form-alert{margin:0 0 12px;padding:10px 12px;border-radius:12px;font-size:14px;font-weight:700}.form-alert.success{color:#067647;background:#ecfdf3;border:1px solid rgba(6,118,71,.22)}.form-alert.error{color:var(--danger);background:#fff1f0;border:1px solid rgba(180,35,24,.22)}.form-alert ul{margin:8px 0 0;padding-left:18px}.form-alert.is-hiding{opacity:0;transform:translateY(-4px);transition:opacity .25s ease,transform .25s ease}
        .form-actions{display:flex;align-items:center;justify-content:flex-end;gap:10px;margin-top:16px;padding-top:14px;border-top:1px solid var(--line)}.save-btn{min-width:118px;border:0;color:#fff;background:linear-gradient(135deg,var(--primary-dark),var(--primary));box-shadow:0 12px 24px rgba(15,118,110,.22)}.save-btn:disabled{cursor:not-allowed;opacity:.7}.clear-btn{min-height:36px;padding:0 18px;border:1px solid var(--line);border-radius:10px;color:var(--muted);background:#fff;cursor:pointer;font-size:14px;font-weight:700}.form-note{margin:14px 0 0;color:var(--muted);font-size:13px;line-height:1.5}
        @media(max-width:940px){.vehicle-page{transform:none;padding:18px}}@media(max-width:640px){.site-header-inner{min-height:54px;padding:0 10px}.site-logo{font-size:20px}.header-actions{right:10px;gap:6px}.header-title{font-size:14px}.back-link,.logout-btn{min-height:32px;padding:0 7px;font-size:12px}.page-title h1{font-size:19px}.eyebrow{display:none}.form-panel{padding:10px;border-radius:16px}.form-grid{grid-template-columns:1fr}.field label,.field input,.theme-dropdown-button{font-size:13px}.field input,.theme-dropdown{min-height:36px;height:36px}.field input{padding:0 9px}.save-btn,.clear-btn{min-height:32px;font-size:12px}}
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
            <div class="header-title">Vehicle Master Creation</div>
            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-btn">Logout</button></form>
            </div>
        </div>
    </header>

    <div class="vehicle-workspace account-master-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')
        <main class="vehicle-page">
            <section class="page-title" aria-labelledby="vehicleMasterTitle">
                <div>
                    <p class="eyebrow">Masters</p>
                    <h1 id="vehicleMasterTitle">Vehicle Details</h1>
                </div>
                <a href="{{ route('vehicle.list') }}" class="view-list-btn">View List</a>
            </section>

            <form class="panel form-panel" id="vehicleForm" method="POST" action="{{ route('vehicle.create') }}" autocomplete="off">
                @csrf
                @if (session('success')) <div class="form-alert success">{{ session('success') }}</div> @endif
                @if ($errors->any())
                    <div class="form-alert error">
                        Please fix the highlighted details.
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <div class="form-grid">
                    <div class="field">
                        <label for="Party_name">Party Name <span class="required-star">*</span></label>
                        <div class="theme-dropdown" id="partyDropdown">
                            <input type="text" class="theme-dropdown-value" id="Party_name" name="Party_name" maxlength="100" value="{{ old('Party_name') }}" data-count-field required autocomplete="off">
                            <button type="button" class="theme-dropdown-button" id="partyButton" aria-haspopup="listbox" aria-expanded="false">
                                <span class="theme-dropdown-text" id="partyText">{{ old('Party_name', 'Select party') }}</span>
                                <span class="theme-dropdown-arrow" aria-hidden="true"></span>
                            </button>
                            <ul class="theme-dropdown-menu" id="partyMenu" role="listbox" aria-label="Party list">
                                <li class="theme-dropdown-search-wrap">
                                    <input type="search" class="theme-dropdown-search" id="partySearch" placeholder="Search party" autocomplete="off">
                                </li>
                                @forelse (($accountName ?? collect()) as $account)
                                    <li><button type="button" class="theme-dropdown-option party-option {{ old('Party_name') === $account->account_perticular ? 'is-selected' : '' }}" data-value="{{ $account->account_perticular }}" role="option" aria-selected="{{ old('Party_name') === $account->account_perticular ? 'true' : 'false' }}">{{ $account->account_perticular }}</button></li>
                                @empty
                                    <li class="theme-dropdown-empty is-visible">No parties found</li>
                                @endforelse
                                <li class="theme-dropdown-empty" id="partyEmpty">No matching parties</li>
                            </ul>
                        </div>
                        <div class="field-help"><span>Maximum 100 characters</span><span class="field-count" data-count-for="Party_name">0 / 100</span></div>
                    </div>
                    <div class="field">
                        <label for="Vehicle_no">Vehicle No.</label>
                        <input type="text" id="Vehicle_no" name="Vehicle_no" maxlength="13" value="{{ old('Vehicle_no') }}" placeholder="GJ01AB1234" data-count-field>
                        <div class="field-help"><span>Use Indian format: GJ01AB1234 or 22BH1234AA</span><span class="field-count" data-count-for="Vehicle_no">0 / 11</span></div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="clear-btn">Clear</button>
                    <button type="submit" class="save-btn">Save</button>
                </div>
                <p class="form-note"><strong>Note</strong> Maintain party-wise vehicle numbers here so sales, slips, and reports can use consistent vehicle details.</p>
            </form>
        </main>
    </div>

    <script>
        const vehicleForm = document.getElementById('vehicleForm');
        const saveButton = vehicleForm.querySelector('.save-btn');
        const partyDropdown = document.getElementById('partyDropdown');
        const partyButton = document.getElementById('partyButton');
        const partyText = document.getElementById('partyText');
        const partyInput = document.getElementById('Party_name');
        const partySearch = document.getElementById('partySearch');
        const partyOptions = Array.from(document.querySelectorAll('.party-option'));
        const partyEmpty = document.getElementById('partyEmpty');

        const openPartyMenu = () => {
            partyDropdown?.classList.add('is-open');
            partyButton?.setAttribute('aria-expanded', 'true');
            partySearch.value = '';
            filterPartyOptions();
            partySearch.focus();
        };

        const closePartyMenu = () => {
            partyDropdown?.classList.remove('is-open');
            partyButton?.setAttribute('aria-expanded', 'false');
        };

        const filterPartyOptions = () => {
            const query = partySearch.value.trim().toLowerCase();
            let visibleCount = 0;

            partyOptions.forEach((option) => {
                const isVisible = option.dataset.value.toLowerCase().includes(query);
                option.closest('li').hidden = !isVisible;
                visibleCount += isVisible ? 1 : 0;
            });

            partyEmpty?.classList.toggle('is-visible', visibleCount === 0 && partyOptions.length > 0);
        };

        partyButton.addEventListener('click', () => {
            if (partyDropdown.classList.contains('is-open')) {
                closePartyMenu();
                return;
            }

            openPartyMenu();
        });

        partySearch.addEventListener('input', filterPartyOptions);

        partyOptions.forEach((option) => {
            option.addEventListener('click', () => {
                partyOptions.forEach((item) => {
                    item.classList.remove('is-selected');
                    item.setAttribute('aria-selected', 'false');
                });

                option.classList.add('is-selected');
                option.setAttribute('aria-selected', 'true');
                partyInput.value = option.dataset.value;
                partyText.textContent = option.dataset.value;
                partyInput.dispatchEvent(new Event('input', { bubbles: true }));
                closePartyMenu();
                partyButton.focus();
            });
        });

        document.addEventListener('click', (event) => {
            if (partyDropdown && !partyDropdown.contains(event.target)) {
                closePartyMenu();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closePartyMenu();
                partyButton.focus();
            }
        });

        document.querySelectorAll('.form-alert').forEach((alert) => {
            setTimeout(() => {
                alert.classList.add('is-hiding');
                setTimeout(() => alert.remove(), 260);
            }, 4000);
        });
        document.querySelectorAll('[data-count-field]').forEach((field) => {
            const counter = document.querySelector(`[data-count-for="${field.id}"]`);
            const max = field.id === 'Vehicle_no' ? 11 : Number(field.maxLength);
            const updateCount = () => {
                const value = field.id === 'Vehicle_no' ? field.value.replace(/[^A-Z0-9]/gi, '') : field.value;
                counter.textContent = `${value.length} / ${max}`;
            };

            field.addEventListener('input', updateCount);
            updateCount();
        });
        document.getElementById('Vehicle_no').addEventListener('input', (event) => {
            event.target.value = event.target.value.toUpperCase().replace(/[^A-Z0-9 -]/g, '').slice(0, 13);
        });
        vehicleForm.addEventListener('submit', () => {
            saveButton.disabled = true;
            saveButton.textContent = 'Saving...';
        });
    </script>
</body>
</html>
