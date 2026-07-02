<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register | FuelTracker</title>
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
            --danger-bg: #fff1f0;
            --shadow: 0 24px 70px rgba(23, 32, 51, 0.14);
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
                radial-gradient(circle at top left, rgba(15, 118, 110, 0.16), transparent 32rem),
                linear-gradient(135deg, #f8fbff 0%, var(--bg) 55%, #eef5f3 100%);
        }

        .auth-page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 96px 18px 32px;
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
            width: min(100%, 980px);
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            padding: 0 18px;
        }

        .site-logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: #ffffff;
            font-size: 30px;
            font-weight: 700;
            text-decoration: none;
        }

        .site-logo-icon {
            display: grid;
            width: 42px;
            height: 42px;
            place-items: center;
            border-radius: 14px;
            color: var(--primary);
            background: #ffffff;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.18);
        }

        .auth-shell {
            width: min(100%, 980px);
            display: grid;
            grid-template-columns: 0.95fr 1.05fr;
            overflow: hidden;
            border: 1px solid rgba(220, 227, 238, 0.86);
            border-radius: 24px;
            background: var(--panel);
            box-shadow: var(--shadow);
        }

        .brand-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 620px;
            padding: 42px;
            color: #ffffff;
            background:
                linear-gradient(145deg, rgba(8, 47, 73, 0.98), rgba(15, 118, 110, 0.96)),
                url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-opacity='0.12' stroke-width='2'%3E%3Cpath d='M22 116c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 78c20-18 40-18 60 0s40 18 60 0'/%3E%3Cpath d='M22 40c20-18 40-18 60 0s40 18 60 0'/%3E%3C/g%3E%3C/svg%3E");
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-size: 18px;
            font-weight: 700;
        }

        .brand-icon {
            display: grid;
            width: 44px;
            height: 44px;
            place-items: center;
            border-radius: 14px;
            color: #0f766e;
            background: #ffffff;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.18);
        }

        .brand-copy h1 {
            max-width: 390px;
            margin: 0 0 16px;
            font-size: 42px;
            line-height: 1.08;
            letter-spacing: 0;
        }

        .brand-copy p {
            max-width: 380px;
            margin: 0;
            color: rgba(255, 255, 255, 0.78);
            font-size: 16px;
            line-height: 1.7;
        }

        .brand-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .stat-card {
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .stat-card strong {
            display: block;
            margin-bottom: 4px;
            font-size: 24px;
        }

        .stat-card span {
            color: rgba(255, 255, 255, 0.72);
            font-size: 13px;
        }

        .form-panel {
            display: flex;
            align-items: center;
            padding: 44px 48px;
        }

        .auth-card {
            width: 100%;
            max-width: 430px;
            margin: 0 auto;
        }

        .eyebrow {
            margin: 0 0 10px;
            color: var(--primary);
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .auth-card h2 {
            margin: 0;
            font-size: 32px;
            line-height: 1.2;
            letter-spacing: 0;
        }

        .subtext {
            margin: 12px 0 24px;
            color: var(--muted);
            line-height: 1.6;
        }

        .alert {
            margin-bottom: 18px;
            padding: 12px 14px;
            border: 1px solid #fecdca;
            border-radius: 12px;
            color: var(--danger);
            background: var(--danger-bg);
            font-size: 14px;
            line-height: 1.5;
        }

        .field {
            margin-bottom: 16px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            color: #344054;
            font-size: 14px;
            font-weight: 700;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            width: 18px;
            height: 18px;
            color: #8590a3;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            min-height: 50px;
            padding: 0 14px 0 44px;
            border: 1px solid var(--line);
            border-radius: 14px;
            color: var(--ink);
            background: #fbfcfe;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.12);
        }

        .field-error {
            margin: 8px 0 0;
            color: var(--danger);
            font-size: 13px;
        }

        .submit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 52px;
            margin-top: 8px;
            border: 0;
            border-radius: 14px;
            color: #ffffff;
            background: var(--primary);
            cursor: pointer;
            font-weight: 700;
            box-shadow: 0 12px 24px rgba(15, 118, 110, 0.24);
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .submit-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 16px 30px rgba(15, 94, 89, 0.26);
        }

        .submit-btn:focus {
            outline: 4px solid rgba(15, 118, 110, 0.18);
            outline-offset: 3px;
        }

        .auth-switch {
            margin-top: 20px;
            color: var(--muted);
            font-size: 14px;
            text-align: center;
        }

        .auth-switch a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-switch a:hover {
            text-decoration: underline;
        }

        .secure-note {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
            color: var(--muted);
            font-size: 13px;
        }

        .secure-note svg {
            flex: 0 0 auto;
            color: var(--accent);
        }

        @media (max-width: 820px) {
            .auth-page {
                align-items: start;
                padding: 90px 18px 18px;
            }

            .auth-shell {
                grid-template-columns: 1fr;
                border-radius: 20px;
            }

            .brand-panel {
                min-height: auto;
                padding: 28px;
                gap: 28px;
            }

            .brand-copy h1 {
                font-size: 30px;
            }

            .brand-copy p {
                font-size: 15px;
            }

            .form-panel {
                padding: 32px 24px;
            }
        }

        @media (max-width: 480px) {
            .auth-page {
                padding: 82px 0 0;
                background: #ffffff;
            }

            .site-header-inner {
                min-height: 64px;
                padding: 0 20px;
            }

            .site-logo {
                font-size: 24px;
            }

            .site-logo-icon {
                width: 38px;
                height: 38px;
            }

            .auth-shell {
                min-height: 100vh;
                border: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .brand-panel {
                padding: 24px 20px;
                border-radius: 0 0 24px 24px;
            }

            .brand-stats {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 14px;
            }

            .form-panel {
                align-items: start;
                padding: 30px 20px 36px;
            }

            .auth-card h2 {
                font-size: 28px;
            }
        }
    </style>
    @include('partials.theme')
</head>
<body>
    <header class="site-header">
        <div class="site-header-inner">
            <a href="{{ route('login') }}" class="site-logo" aria-label="FuelTracker home">
                <span class="site-logo-icon has-brand-image" aria-hidden="true">
                    <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                </span>
                <span>FuelTracker</span>
            </a>
        </div>
    </header>

    <main class="auth-page">
        <section class="auth-shell" aria-label="FuelTracker registration">
            <aside class="brand-panel">
                <div class="brand-mark">
                    <span class="brand-icon has-brand-image" aria-hidden="true">
                        <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="app-logo-image">
                    </span>
                    <span>FuelTracker</span>
                </div>

                <div class="brand-copy">
                    <h1>Start tracking fuel with confidence.</h1>
                    <p>Create your account to manage fuel entries, monitor spending, and keep vehicle records ready whenever you need them.</p>
                </div>

                <div class="brand-stats" aria-label="FuelTracker highlights">
                    <div class="stat-card">
                        <strong>Fast</strong>
                        <span>Simple account setup</span>
                    </div>
                    <div class="stat-card">
                        <strong>Secure</strong>
                        <span>Password protected access</span>
                    </div>
                </div>
            </aside>

            <div class="form-panel">
                <div class="auth-card">
                    <p class="eyebrow">Create account</p>
                    <h2>Register for FuelTracker</h2>
                    <p class="subtext">Fill in your details and continue to your dashboard.</p>

                    @if ($errors->any())
                        <div class="alert">Please fix the highlighted fields and try again.</div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        <div class="field">
                            <label for="name">Full name</label>
                            <div class="input-wrap">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M4 21a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    autocomplete="name"
                                    placeholder="Your name"
                                    required
                                    autofocus
                                >
                            </div>
                            @error('name')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="email">Email address</label>
                            <div class="input-wrap">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    placeholder="you@example.com"
                                    required
                                >
                            </div>
                            @error('email')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="password">Password</label>
                            <div class="input-wrap">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M7 11V8a5 5 0 0 1 10 0v3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M6 11h12v9H6v-9Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    autocomplete="new-password"
                                    placeholder="Minimum 8 characters"
                                    required
                                >
                            </div>
                            @error('password')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="password_confirmation">Confirm password</label>
                            <div class="input-wrap">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 3 5 6v5c0 4.6 2.9 8.8 7 10 4.1-1.2 7-5.4 7-10V6l-7-3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <path d="m9 12 2 2 4-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    placeholder="Re-enter password"
                                    required
                                >
                            </div>
                        </div>

                        <button type="submit" class="submit-btn">Create account</button>

                        <p class="secure-note">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 3 5 6v5c0 4.6 2.9 8.8 7 10 4.1-1.2 7-5.4 7-10V6l-7-3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                <path d="m9 12 2 2 4-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Passwords are stored using Laravel's hashed password cast.
                        </p>

                        <p class="auth-switch">
                            Already have an account? <a href="{{ route('login') }}">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
