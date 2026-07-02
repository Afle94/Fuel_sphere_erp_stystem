<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Category Master Update | FuelTracker</title>
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

        .category-workspace {
            min-height: calc(100vh - 48px);
            min-height: calc(100dvh - 48px);
            position: relative;
        }

        .category-page {
            width: min(100% - 36px, 780px);
            margin: 0 auto;
            padding: 14px 0 70px;
            transform: translateX(86px);
            transition: transform 0.22s ease;
        }

        .category-workspace.menu-collapsed .category-page {
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

        .field {
            display: grid;
            gap: 7px;
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

        .field input {
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

        .field input:focus {
            border-color: rgba(15, 118, 110, 0.52);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.13);
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
            color: var(--muted);
            background: #fbfcfe;
            cursor: pointer;
            font-family: inherit;
            font-size: 15px;
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

            .category-page {
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

            .field label,
            .field input,
            .clear-btn,
            .save-btn {
                font-size: 13px;
            }

            .field input,
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

            <div class="header-title">Category Master Update</div>

            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="category-workspace account-master-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="category-page">
            <section class="page-title" aria-labelledby="categoryEditTitle">
                <div>
                    <p class="eyebrow">Masters</p>
                    <h1 id="categoryEditTitle">Edit Category</h1>
                </div>
                <a href="{{ route('category.list') }}" class="view-list-btn">View List</a>
            </section>

            <form class="form-panel" id="categoryForm" method="POST" action="{{ route('category.update', $category->id) }}" autocomplete="off">
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

                <div class="field">
                    <label for="categoryName">Category Name <span class="required-star">*</span></label>
                    <input type="text" id="categoryName" name="Category_Name" placeholder="Enter category name" maxlength="50" value="{{ old('Category_Name', $category->Category_Name) }}" data-show-limit data-limit-unit="characters" required>
                </div>

                <div class="form-actions">
                    <button type="reset" class="clear-btn">Clear</button>
                    <button type="submit" class="save-btn">Update</button>
                </div>

                <p class="form-note">
                    <strong>Note</strong>
                    Update category names carefully so linked item masters and reports remain easy to read.
                </p>
            </form>
        </main>
    </div>

    <script>
        const categoryForm = document.getElementById('categoryForm');
        const saveButton = categoryForm.querySelector('.save-btn');
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

        categoryForm.addEventListener('submit', () => {
            saveButton.disabled = true;
            saveButton.textContent = 'Updating...';
        });
    </script>
</body>
</html>
