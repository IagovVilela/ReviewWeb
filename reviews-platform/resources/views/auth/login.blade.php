<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('auth.title') }} — {{ __('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --purple:   #7c3aed;
            --purple-l: #8b5cf6;
            --ink:      #09090b;
            --surface:  #0f0f12;
            --card:     #141417;
            --border:   rgba(255,255,255,.09);
            --text:     #fafafa;
            --muted:    #71717a;
            --faint:    #52525b;
            --r:        10px;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
            background: var(--ink);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ── GLOW BACKGROUND ─────────────────────── */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 55% 45% at 50% 95%, rgba(124,58,237,.28) 0%, transparent 68%),
                radial-gradient(ellipse 30% 20% at 15% 10%,  rgba(124,58,237,.08) 0%, transparent 60%);
            pointer-events: none;
        }

        /* ── TOP BAR ─────────────────────────────── */
        .topbar {
            position: fixed; inset: 0 0 auto;
            z-index: 50; height: 58px;
            display: flex; align-items: center;
            padding: 0 2rem;
            justify-content: space-between;
        }

        .topbar-left {
            display: flex; align-items: center; gap: .5rem;
        }

        .back-home {
            display: inline-flex; align-items: center; gap: .45rem;
            font-size: .78rem; font-weight: 500; color: var(--muted);
            text-decoration: none; letter-spacing: .01em;
            padding: .3rem .1rem;
            transition: color .15s;
        }
        .back-home i { font-size: .68rem; }
        .back-home:hover { color: var(--text); }

        .topbar-logo {
            display: flex; align-items: center; gap: .45rem;
            text-decoration: none;
        }
        .topbar-logo img { height: 26px; width: auto; opacity: .85; }
        .topbar-logo-name {
            font-size: .88rem; font-weight: 600; color: rgba(255,255,255,.55);
            letter-spacing: -.01em;
        }

        .topbar-right { display: flex; align-items: center; gap: .5rem; }

        .lang-select {
            appearance: none; background: transparent;
            border: 1px solid var(--border); border-radius: 7px;
            padding: .32rem .55rem; font-size: .78rem; font-weight: 500;
            color: var(--muted); cursor: pointer;
            transition: border-color .15s, color .15s;
        }
        .lang-select:focus { outline: none; border-color: var(--purple-l); color: var(--text); }
        .lang-select:hover { color: var(--text); border-color: rgba(255,255,255,.2); }

        /* ── MAIN LAYOUT ─────────────────────────── */
        .page {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 5rem 1.5rem 3rem;
        }

        /* ── CARD ────────────────────────────────── */
        .card {
            width: 100%; max-width: 400px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 0 0 1px rgba(255,255,255,.03), 0 32px 64px rgba(0,0,0,.55);
        }

        .card-head {
            padding: 2.25rem 2.25rem 0;
            text-align: center;
        }

        .card-logo {
            display: inline-flex; align-items: center; justify-content: center;
            width: 52px; height: 52px;
            border-radius: 14px;
            background: rgba(124,58,237,.15);
            border: 1px solid rgba(124,58,237,.25);
            margin-bottom: 1.25rem;
        }
        .card-logo img { width: 30px; height: 30px; object-fit: contain; }

        .card-head h1 {
            font-size: 1.3rem; font-weight: 700; letter-spacing: -.03em;
            color: var(--text); margin-bottom: .35rem;
        }
        .card-head p {
            font-size: .82rem; color: var(--muted); line-height: 1.5;
        }

        .card-divider {
            height: 1px; background: var(--border); margin: 1.75rem 0 0;
        }

        .card-body { padding: 1.75rem 2.25rem 2.25rem; }

        /* ── ALERTS ──────────────────────────────── */
        .alert {
            display: flex; align-items: flex-start; gap: .55rem;
            padding: .7rem .85rem; border-radius: var(--r);
            font-size: .8rem; line-height: 1.5;
            margin-bottom: 1.25rem;
        }
        .alert i { flex-shrink: 0; margin-top: .05rem; font-size: .78rem; }
        .alert-error {
            background: rgba(239,68,68,.08);
            border: 1px solid rgba(239,68,68,.2);
            color: #f87171;
        }
        .alert-success {
            background: rgba(34,197,94,.08);
            border: 1px solid rgba(34,197,94,.2);
            color: #4ade80;
        }

        /* ── FORM ────────────────────────────────── */
        .field { margin-bottom: 1rem; }

        .field-label {
            display: block; font-size: .75rem; font-weight: 500;
            color: rgba(255,255,255,.5); margin-bottom: .4rem; letter-spacing: .01em;
        }

        .field-wrap { position: relative; }

        .field-input {
            width: 100%;
            background: rgba(255,255,255,.04);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: .7rem .9rem .7rem 2.6rem;
            font-size: .9rem; color: var(--text);
            transition: border-color .15s, background .15s, box-shadow .15s;
        }
        .field-input::placeholder { color: var(--faint); }
        .field-input:focus {
            outline: none;
            border-color: var(--purple-l);
            background: rgba(139,92,246,.05);
            box-shadow: 0 0 0 3px rgba(139,92,246,.12);
        }

        .field-icon {
            position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
            font-size: .78rem; color: var(--faint); pointer-events: none;
            transition: color .15s;
        }
        .field-wrap:focus-within .field-icon { color: var(--purple-l); }

        /* password toggle */
        .field-eye {
            position: absolute; right: .85rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            font-size: .78rem; color: var(--faint); padding: .2rem;
            transition: color .15s;
        }
        .field-eye:hover { color: var(--muted); }
        .field-input.has-eye { padding-right: 2.4rem; }

        /* ── FORGOT ──────────────────────────────── */
        .forgot-row {
            display: flex; justify-content: flex-end;
            margin-top: -.3rem; margin-bottom: 1.4rem;
        }
        .forgot-link {
            font-size: .75rem; color: var(--muted);
            text-decoration: none; transition: color .15s;
        }
        .forgot-link:hover { color: var(--purple-l); }

        /* ── SUBMIT ──────────────────────────────── */
        .btn-submit {
            width: 100%;
            background: var(--purple); color: #fff; border: none;
            border-radius: var(--r); padding: .75rem 1.5rem;
            font-size: .9rem; font-weight: 600; letter-spacing: -.01em;
            cursor: pointer; display: flex; align-items: center;
            justify-content: center; gap: .4rem;
            transition: background .15s, transform .15s, box-shadow .15s;
        }
        .btn-submit:hover:not(:disabled) {
            background: #6d28d9;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(124,58,237,.4);
        }
        .btn-submit:disabled { opacity: .5; cursor: not-allowed; }
        .btn-submit .btn-arrow { font-size: .72rem; transition: transform .15s; }
        .btn-submit:hover:not(:disabled) .btn-arrow { transform: translateX(3px); }

        /* ── FOOTER ──────────────────────────────── */
        .card-footer {
            padding: 1rem 2.25rem 1.5rem;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center; gap: .35rem;
            font-size: .72rem; color: var(--faint);
        }
        .card-footer i { font-size: .65rem; color: var(--purple-l); }

        /* ── RESPONSIVE ──────────────────────────── */
        @media (max-width: 480px) {
            .topbar { padding: 0 1.25rem; }
            .card-head { padding: 1.75rem 1.5rem 0; }
            .card-body { padding: 1.5rem; }
            .card-footer { padding: 1rem 1.5rem 1.25rem; }
            .page { padding-top: 4.5rem; }
        }
    </style>
</head>
<body>

<!-- ─── TOP BAR ─────────────────────────────────── -->
<div class="topbar">
    <a href="/" class="back-home">
        <i class="fas fa-arrow-left"></i>
        {{ __('auth.back_to_home') }}
    </a>
    <div class="topbar-right">
        <select class="lang-select" id="languageSelector">
            <option value="pt_BR" {{ app()->getLocale() === 'pt_BR' ? 'selected' : '' }}>🇧🇷 PT</option>
            <option value="en_US" {{ app()->getLocale() === 'en_US' ? 'selected' : '' }}>🇬🇧 EN</option>
        </select>
    </div>
</div>

<!-- ─── PAGE ─────────────────────────────────────── -->
<div class="page">
    <div class="card">

        <!-- HEAD -->
        <div class="card-head">
            <div class="card-logo">
                <img src="{{ asset('assets/images/lopgosDASHBOARD.png') }}" alt="{{ __('app.name') }}">
            </div>
            <h1>{{ __('auth.welcome_back') }}</h1>
            <p>{{ __('auth.login_subtitle') }}</p>
            <div class="card-divider"></div>
        </div>

        <!-- BODY -->
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label class="field-label" for="email">{{ __('auth.email_placeholder') }}</label>
                    <div class="field-wrap">
                        <i class="fas fa-envelope field-icon"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="field-input"
                            placeholder="voce@empresa.com"
                            autocomplete="email"
                            required
                        >
                    </div>
                </div>

                <div class="field">
                    <label class="field-label" for="password">{{ __('auth.password_placeholder') }}</label>
                    <div class="field-wrap">
                        <i class="fas fa-lock field-icon"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="field-input has-eye"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="field-eye" id="togglePassword" aria-label="Mostrar senha">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="forgot-row">
                    <a href="{{ route('password.forgot') }}" class="forgot-link">
                        {{ app()->getLocale() === 'pt_BR' ? 'Esqueceu a senha?' : 'Forgot password?' }}
                    </a>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    {{ __('auth.login_button') }}
                    <i class="fas fa-arrow-right btn-arrow"></i>
                </button>
            </form>
        </div>

        <!-- FOOTER -->
        <div class="card-footer">
            <i class="fas fa-shield-alt"></i>
            <span>{{ __('auth.security_message') }}</span>
        </div>

    </div>
</div>

<script>
    // Language selector
    document.getElementById('languageSelector').addEventListener('change', function () {
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        fetch('/change-locale', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ locale: this.value })
        }).then(r => r.json()).then(d => { if (d.success) window.location.reload(); });
    });

    // Password toggle
    const toggleBtn = document.getElementById('togglePassword');
    const passInput = document.getElementById('password');
    const eyeIcon   = document.getElementById('eyeIcon');
    toggleBtn.addEventListener('click', () => {
        const isHidden = passInput.type === 'password';
        passInput.type = isHidden ? 'text' : 'password';
        eyeIcon.className = isHidden ? 'fas fa-eye-slash' : 'fas fa-eye';
    });

    // Submit loading state
    document.getElementById('loginForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("auth.logging_in") }}';
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = '{{ __("auth.login_button") }} <i class="fas fa-arrow-right btn-arrow"></i>';
        }, 5000);
    });

    // Auto-focus email
    document.addEventListener('DOMContentLoaded', () => {
        const email = document.getElementById('email');
        if (email && !email.value) email.focus();
    });
</script>
</body>
</html>
