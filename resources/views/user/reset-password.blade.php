<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password | FuelTracker</title>
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

        .page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 96px 18px 32px;
        }

        .card {
            width: min(100%, 460px);
            padding: 42px;
            border: 1px solid rgba(220, 227, 238, 0.86);
            border-radius: 24px;
            background: var(--panel);
            box-shadow: var(--shadow);
        }

        .eyebrow {
            margin: 0 0 10px;
            color: var(--primary);
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            font-size: 32px;
            line-height: 1.2;
            letter-spacing: 0;
        }

        .subtext {
            margin: 12px 0 28px;
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

        label {
            display: block;
            margin-bottom: 8px;
            color: #344054;
            font-size: 14px;
            font-weight: 700;
        }

        .form-control {
            width: 100%;
            min-height: 50px;
            padding: 0 14px;
            border: 1px solid var(--line);
            border-radius: 14px;
            color: var(--ink);
            background: #fbfcfe;
            outline: none;
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
        }

        .submit-btn:hover {
            background: var(--primary-dark);
        }

        @media (max-width: 480px) {
            .page {
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

            .card {
                min-height: calc(100vh - 82px);
                border: 0;
                border-radius: 0;
                box-shadow: none;
                padding: 34px 20px;
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

    <main class="page">
        <section class="card" aria-label="Reset password">
            <p class="eyebrow">Account help</p>
            <h1>Reset your password</h1>
            <p class="subtext">Enter your email and choose a new password for your FuelTracker account.</p>

            @if ($errors->any())
                <div class="alert">Please check the details below and try again.</div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        value="{{ old('email', request('email')) }}"
                        autocomplete="email"
                        placeholder="you@example.com"
                        required
                        autofocus
                    >
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">New password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="Minimum 8 characters"
                        required
                    >
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm password</label>
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

                <button type="submit" class="submit-btn">Update password</button>
            </form>
        </section>
    </main>
</body>
</html>
