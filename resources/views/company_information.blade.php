<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Company Information | FuelTracker</title>
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
            --shadow: 0 16px 48px rgba(23, 32, 51, .10);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(15, 118, 110, .16), transparent 32rem),
                linear-gradient(135deg, #f8fbff 0%, var(--bg) 55%, #eef5f3 100%);
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 20;
            width: 100%;
            background: linear-gradient(135deg, rgba(8, 47, 73, .98), rgba(15, 118, 110, .98));
            box-shadow: 0 10px 30px rgba(23, 32, 51, .12);
        }

        .site-header-inner {
            width: 100%;
            min-height: 64px;
            display: grid;
            grid-template-columns: minmax(220px, 1fr) auto minmax(220px, 1fr);
            align-items: center;
            gap: 18px;
            padding: 0 8px;
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
            overflow: hidden;
            padding: 2px;
            border-radius: 999px;
            background: #ffffff;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .18);
        }

        .app-logo-image {
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
            border: 1px solid rgba(255, 255, 255, .24);
            border-radius: 8px;
            color: #ffffff;
            background: rgba(255, 255, 255, .12);
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: background .2s ease, transform .2s ease;
        }

        .back-link:hover,
        .logout-btn:hover {
            background: rgba(255, 255, 255, .2);
            transform: translateY(-1px);
        }

        .company-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 88px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
        }

        .company-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
        }

        .company-page {
            min-width: 0;
            padding: 14px;
        }

        .page-shell {
            display: grid;
            gap: 12px;
        }

        .page-title,
        .form-panel {
            border: 1px solid rgba(220, 227, 238, .86);
            border-radius: 12px;
            background: var(--panel);
            box-shadow: var(--shadow);
        }

        .page-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px;
        }

        .eyebrow {
            margin: 0 0 5px;
            color: var(--primary);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            font-size: 30px;
            line-height: 1.2;
        }

        .status-pill {
            flex: 0 0 auto;
            padding: 6px 10px;
            border-radius: 999px;
            color: var(--primary-dark);
            background: rgba(15, 118, 110, .09);
            font-size: 11px;
            font-weight: 700;
        }

        .form-alert {
            margin: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
        }

        .form-alert.success {
            color: #067647;
            background: #ecfdf3;
            border: 1px solid rgba(6, 118, 71, .22);
        }

        .form-alert.error {
            color: var(--danger);
            background: #fff1f0;
            border: 1px solid rgba(180, 35, 24, .22);
        }

        .company-form {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) 220px;
            gap: 18px;
            padding: 18px;
        }

        .form-grid {
            display: grid;
            gap: 12px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 170px minmax(0, 1fr);
            align-items: start;
            gap: 12px;
        }

        .form-row label {
            padding-top: 10px;
            color: var(--primary-dark);
            font-size: 13px;
            font-weight: 800;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            background: #fbfcfe;
            font: inherit;
            font-size: 14px;
            outline: none;
        }

        .form-input {
            min-height: 38px;
            padding: 0 12px;
        }

        .form-textarea {
            min-height: 96px;
            resize: vertical;
            padding: 10px 12px;
            line-height: 1.45;
        }

        .form-input:focus,
        .form-textarea:focus {
            border-color: rgba(15, 118, 110, .52);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, .13);
        }

        .logo-panel {
            display: grid;
            align-content: start;
            justify-items: center;
            gap: 14px;
            padding: 18px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fbfcfe;
        }

        .logo-panel img {
            width: 120px;
            height: 120px;
            border-radius: 28px;
            object-fit: cover;
            box-shadow: 0 18px 38px rgba(23, 32, 51, .16);
        }

        .save-btn {
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            border: 1px solid transparent;
            border-radius: 8px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
        }

        .save-btn:hover {
            filter: saturate(1.08) brightness(1.04);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            padding: 0 18px 18px;
        }

        @media (max-width: 900px) {
            .site-header-inner {
                grid-template-columns: 1fr;
                gap: 8px;
                padding: 10px;
            }

            .header-actions {
                justify-self: center;
            }

            .company-workspace.app-shell-with-sidebar {
                width: 100%;
                min-height: calc(100vh - 64px);
                display: block;
                margin: 0;
                border-radius: 0;
            }

            .company-form,
            .form-row {
                grid-template-columns: 1fr;
            }

            .form-row label {
                padding-top: 0;
            }
        }
    </style>

    @include('partials.theme')
</head>

<body>
    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ url('/dashboard') }}" class="site-logo" aria-label="FuelTracker dashboard">
                <span class="site-logo-icon" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>

            <div class="header-title">Company Information</div>

            <div class="header-actions">
                <a href="{{ url('/dashboard') }}" class="back-link">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell-with-sidebar company-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="company-page">
            <div class="page-shell">
                <section class="page-title" aria-labelledby="companyInfoTitle">
                    <div>
                        <p class="eyebrow">Settings</p>
                        <h1 id="companyInfoTitle">Company Information</h1>
                    </div>
                    <span class="status-pill">{{ $companyInformation->exists ? 'Saved' : 'New' }}</span>
                </section>

                <section class="form-panel">
                    @if (session('success'))
                        <div class="form-alert success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="form-alert error">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('company-information.update') }}">
                        @csrf

                        <div class="company-form">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label for="companyName">Company Name</label>
                                    <input class="form-input" id="companyName" name="company_name" type="text" value="{{ old('company_name', $companyInformation->company_name) }}" maxlength="255">
                                </div>

                                <div class="form-row">
                                    <label for="registeredOffice">Registered Office</label>
                                    <textarea class="form-textarea" id="registeredOffice" name="registered_office" maxlength="1000">{{ old('registered_office', $companyInformation->registered_office) }}</textarea>
                                </div>

                                <div class="form-row">
                                    <label for="phoneNo">Phone No</label>
                                    <input class="form-input" id="phoneNo" name="phone_no" type="text" value="{{ old('phone_no', $companyInformation->phone_no) }}" maxlength="50">
                                </div>

                                <div class="form-row">
                                    <label for="mobileNo">Mobile No</label>
                                    <input class="form-input" id="mobileNo" name="mobile_no" type="text" value="{{ old('mobile_no', $companyInformation->mobile_no) }}" maxlength="50">
                                </div>

                                <div class="form-row">
                                    <label for="emailId">Email ID</label>
                                    <input class="form-input" id="emailId" name="email_id" type="email" value="{{ old('email_id', $companyInformation->email_id) }}" maxlength="255">
                                </div>

                                <div class="form-row">
                                    <label for="gstNo">GST No</label>
                                    <input class="form-input" id="gstNo" name="gst_no" type="text" value="{{ old('gst_no', $companyInformation->gst_no) }}" maxlength="50">
                                </div>
                            </div>

                            <aside class="logo-panel" aria-label="FuelTracker brand">
                                <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="FuelTracker">
                                <button type="submit" class="save-btn">Save Modifications</button>
                            </aside>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="save-btn">Save Company Information</button>
                        </div>
                    </form>
                </section>
            </div>
        </main>
    </div>
</body>

</html>
