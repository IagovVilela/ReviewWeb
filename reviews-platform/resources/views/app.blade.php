<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('app.name') }} — {{ __('landing.hero_title') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --purple:      #7c3aed;
            --purple-l:    #8b5cf6;
            --purple-dim:  rgba(124, 58, 237, .1);
            --ink:         #09090b;
            --text:        #18181b;
            --muted:       #71717a;
            --faint:       #a1a1aa;
            --border:      #e4e4e7;
            --surface:     #ffffff;
            --surface-2:   #f4f4f5;
            --nav-h:       66px;
            --max:         1120px;
            --r:           12px;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
            background: var(--surface);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
            transition: background .25s, color .25s;
        }

        /* ── DARK MODE ────────────────────────────── */
        .dark body            { --text: #fafafa; --muted: #a1a1aa; --faint: #71717a; --border: #27272a; --surface: #09090b; --surface-2: #18181b; background: var(--surface); color: var(--text); }

        /* ── NAV ──────────────────────────────────── */
        .nav {
            position: fixed; inset: 0 0 auto;
            z-index: 200; height: var(--nav-h);
            display: flex; align-items: center;
            transition: background .3s, border-color .3s, backdrop-filter .3s;
        }
        .nav.scrolled {
            background: rgba(255,255,255,.88);
            border-bottom: 1px solid var(--border);
            backdrop-filter: saturate(180%) blur(20px);
            -webkit-backdrop-filter: saturate(180%) blur(20px);
        }
        .dark .nav.scrolled { background: rgba(9,9,11,.9); border-color: var(--border); }

        .nav-inner {
            width: 100%; max-width: var(--max);
            margin: 0 auto; padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        }
        .nav-logo { display: flex; align-items: center; gap: .55rem; text-decoration: none; }
        .nav-logo img { height: 30px; width: auto; }
        .nav-logo-name {
            font-size: 1rem; font-weight: 700; color: var(--text);
            letter-spacing: -.025em;
        }
        /* logo is white when nav is NOT scrolled (sits on dark hero) */
        .nav:not(.scrolled) .nav-logo-name { color: #fff; }
        .nav:not(.scrolled) .lang-select { color: rgba(255,255,255,.6); border-color: rgba(255,255,255,.18); }

        .nav-right { display: flex; align-items: center; gap: .4rem; }

        .lang-select {
            appearance: none; background: transparent;
            border: 1px solid var(--border); border-radius: 8px;
            padding: .38rem .6rem; font-size: .82rem; font-weight: 500;
            color: var(--muted); cursor: pointer;
            transition: border-color .15s, color .15s;
        }
        .lang-select:focus { outline: none; border-color: var(--purple-l); }

        .btn-nav {
            background: var(--purple); color: #fff; border: none;
            border-radius: 8px; padding: .45rem 1rem;
            font-size: .82rem; font-weight: 600; letter-spacing: -.01em;
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: .35rem;
            transition: background .15s, transform .15s;
        }
        .btn-nav:hover { background: #6d28d9; transform: translateY(-1px); }

        /* ── HERO ─────────────────────────────────── */
        .hero {
            background: var(--ink);
            min-height: 100svh;
            display: flex; align-items: center;
            padding: calc(var(--nav-h) + 5rem) 2rem 6rem;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 70% 65% at 65% 105%, rgba(124,58,237,.4) 0%, transparent 68%),
                radial-gradient(ellipse 45% 35% at 8% 15%, rgba(124,58,237,.13) 0%, transparent 60%);
            pointer-events: none;
        }

        .hero-inner {
            position: relative; z-index: 1;
            max-width: var(--max); margin: 0 auto; width: 100%;
            display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center;
        }

        .hero-kicker {
            display: inline-flex; align-items: center; gap: .5rem;
            font-size: .7rem; font-weight: 600;
            letter-spacing: .14em; text-transform: uppercase;
            color: rgba(255,255,255,.38); margin-bottom: 1.4rem;
        }
        .hero-kicker-line { width: 20px; height: 1.5px; background: var(--purple-l); border-radius: 2px; }

        .hero h1 {
            font-size: clamp(2.5rem, 4.5vw, 3.75rem);
            font-weight: 800; line-height: 1.07; letter-spacing: -.045em;
            color: #fff; margin-bottom: 1.25rem;
        }
        .hero h1 em { font-style: normal; color: var(--purple-l); }

        .hero-lead {
            font-size: 1.05rem; color: rgba(255,255,255,.5);
            line-height: 1.7; max-width: 29rem; margin-bottom: 2.25rem;
        }

        .hero-cta { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; margin-bottom: 3rem; }

        .btn-primary {
            background: var(--purple); color: #fff; border: none;
            border-radius: var(--r); padding: .8rem 1.6rem;
            font-size: .92rem; font-weight: 600; letter-spacing: -.01em;
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: .4rem;
            transition: background .15s, transform .15s, box-shadow .15s;
        }
        .btn-primary:hover { background: #6d28d9; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(124,58,237,.4); }

        .btn-outline {
            background: transparent; color: rgba(255,255,255,.6);
            border: 1px solid rgba(255,255,255,.17); border-radius: var(--r);
            padding: .8rem 1.6rem; font-size: .92rem; font-weight: 500;
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: .4rem;
            transition: border-color .15s, color .15s, background .15s;
        }
        .btn-outline:hover { border-color: rgba(255,255,255,.38); color: #fff; background: rgba(255,255,255,.05); }

        .hero-numbers {
            display: flex; gap: 2.5rem;
            padding-top: 2rem; border-top: 1px solid rgba(255,255,255,.1);
        }
        .hero-num-val  { font-size: 1.6rem; font-weight: 700; letter-spacing: -.04em; color: #fff; display: block; }
        .hero-num-label { font-size: .72rem; color: rgba(255,255,255,.38); display: block; margin-top: .1rem; letter-spacing: .02em; }

        /* ── HERO VISUAL (dashboard mock) ─────────── */
        .hero-visual { display: flex; flex-direction: column; gap: 1rem; align-items: flex-end; }

        .mock-card {
            width: 100%; max-width: 380px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: var(--r);
            padding: 1.4rem 1.6rem;
            backdrop-filter: blur(10px);
        }
        .mock-card-label {
            font-size: .68rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .1em;
            color: rgba(255,255,255,.32); margin-bottom: 1rem; display: block;
        }
        .mock-rating-row { display: flex; align-items: baseline; gap: .7rem; margin-bottom: .9rem; }
        .mock-rating-num { font-size: 2.4rem; font-weight: 700; letter-spacing: -.06em; color: #fff; }
        .mock-stars { color: #facc15; font-size: .85rem; letter-spacing: .06em; }

        .mock-bars { display: flex; flex-direction: column; gap: .45rem; }
        .mock-bar-row { display: flex; align-items: center; gap: .6rem; }
        .mock-bar-lbl { font-size: .68rem; color: rgba(255,255,255,.3); width: .8rem; text-align: right; }
        .mock-bar-track { flex: 1; height: 5px; background: rgba(255,255,255,.07); border-radius: 99px; overflow: hidden; }
        .mock-bar-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--purple-l), #a78bfa); }

        .mock-badges { display: flex; gap: .75rem; max-width: 380px; width: 100%; }
        .mock-badge {
            flex: 1;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: var(--r);
            padding: .85rem 1rem;
            display: flex; align-items: center; gap: .75rem;
        }
        .mock-badge-icon {
            width: 34px; height: 34px; border-radius: 9px;
            background: var(--purple-dim); border: 1px solid rgba(139,92,246,.2);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .mock-badge-icon i { font-size: .85rem; color: var(--purple-l); }
        .mock-badge strong { font-size: .8rem; font-weight: 600; color: #fff; display: block; }
        .mock-badge span   { font-size: .7rem; color: rgba(255,255,255,.35); }

        /* ── STRIP ────────────────────────────────── */
        .strip {
            background: var(--surface-2); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);
            padding: 2.25rem 2rem;
            transition: background .25s, border-color .25s;
        }
        .dark .strip { background: #111113; border-color: var(--border); }
        .strip-inner {
            max-width: var(--max); margin: 0 auto;
            display: flex; align-items: center; justify-content: center;
            gap: 4.5rem; flex-wrap: wrap;
        }
        .strip-item { text-align: center; }
        .strip-num   { font-size: 1.75rem; font-weight: 700; letter-spacing: -.04em; color: var(--purple); display: block; }
        .strip-lbl   { font-size: .75rem; color: var(--muted); display: block; margin-top: .1rem; }
        .strip-sep   { width: 1px; height: 2.2rem; background: var(--border); }

        /* ── SECTION CORE ─────────────────────────── */
        .section { padding: 6rem 2rem; }
        .section-inner { max-width: var(--max); margin: 0 auto; }
        .section-tag  { font-size: .7rem; font-weight: 600; letter-spacing: .13em; text-transform: uppercase; color: var(--purple); display: block; margin-bottom: .85rem; }
        .section-h    { font-size: clamp(1.85rem, 3.5vw, 2.6rem); font-weight: 700; letter-spacing: -.035em; line-height: 1.15; color: var(--text); margin-bottom: .85rem; }
        .section-sub  { font-size: 1rem; color: var(--muted); line-height: 1.7; max-width: 36rem; }

        .dark .section-h { color: #fafafa; }

        /* ── FEATURES ─────────────────────────────── */
        .features-bg { background: var(--surface); transition: background .25s; }
        .dark .features-bg { background: #09090b; }

        .features-head { margin-bottom: 3.5rem; }

        .features-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 1px; background: var(--border);
            border: 1px solid var(--border); border-radius: var(--r); overflow: hidden;
        }
        .feat {
            background: var(--surface); padding: 1.75rem;
            transition: background .2s;
        }
        .dark .feat { background: #0d0d10; }
        .feat:hover { background: var(--surface-2); }
        .dark .feat:hover { background: #18181b; }

        .feat-icon {
            width: 38px; height: 38px; border-radius: 9px;
            background: var(--purple-dim); border: 1px solid rgba(139,92,246,.2);
            display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;
        }
        .feat-icon i { font-size: .88rem; color: var(--purple-l); }
        .feat h3     { font-size: .88rem; font-weight: 600; letter-spacing: -.015em; color: var(--text); margin-bottom: .4rem; }
        .feat p      { font-size: .82rem; color: var(--muted); line-height: 1.62; }
        .dark .feat h3 { color: #e4e4e7; }

        /* ── HOW IT WORKS ─────────────────────────── */
        .how-bg { background: var(--surface-2); transition: background .25s; }
        .dark .how-bg { background: #0c0c0f; }

        .steps {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 2.5rem; margin-top: 3.5rem; position: relative;
        }
        .steps::before {
            content: ''; position: absolute;
            top: 1.45rem; left: calc(12.5% + .2rem); width: calc(75% - .4rem);
            height: 1px; background: var(--border); z-index: 0;
        }
        .step-item { position: relative; z-index: 1; }
        .step-num {
            width: 2.9rem; height: 2.9rem; border-radius: 50%;
            border: 1px solid var(--border); background: var(--surface);
            color: var(--muted); font-size: .8rem; font-weight: 600;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem; transition: background .2s, border-color .2s, color .2s;
        }
        .dark .step-num { background: #18181b; }
        .step-item:hover .step-num { background: var(--purple); border-color: var(--purple); color: #fff; }
        .step-item h3 { font-size: .88rem; font-weight: 600; letter-spacing: -.01em; color: var(--text); margin-bottom: .4rem; }
        .step-item p  { font-size: .8rem; color: var(--muted); line-height: 1.65; }
        .dark .step-item h3 { color: #e4e4e7; }

        /* ── PRIZE ────────────────────────────────── */
        .prize-section { background: var(--surface); padding: 3.5rem 2rem; transition: background .25s; }
        .dark .prize-section { background: #09090b; }
        .prize-inner {
            max-width: var(--max); margin: 0 auto;
            border: 1px solid var(--border); border-radius: calc(var(--r) + 4px);
            padding: 2.75rem 3.25rem;
            background: linear-gradient(135deg, rgba(124,58,237,.06) 0%, transparent 55%);
            display: flex; align-items: center; justify-content: space-between; gap: 3rem;
        }
        .prize-tag   { font-size: .7rem; font-weight: 600; letter-spacing: .13em; text-transform: uppercase; color: var(--purple); display: block; margin-bottom: .65rem; }
        .prize-title { font-size: 1.5rem; font-weight: 700; letter-spacing: -.03em; color: var(--text); line-height: 1.2; margin-bottom: .6rem; }
        .prize-desc  { font-size: .85rem; color: var(--muted); line-height: 1.65; max-width: 26rem; }
        .dark .prize-title { color: #fafafa; }

        .prize-right { text-align: center; flex-shrink: 0; }
        .prize-num   { font-size: 2.4rem; font-weight: 800; letter-spacing: -.05em; color: var(--purple); display: block; }
        .prize-subl  { font-size: .72rem; color: var(--muted); display: block; margin-top: .15rem; }

        /* ── CTA ──────────────────────────────────── */
        .cta-section {
            background: var(--ink); color: #fff;
            padding: 7rem 2rem; text-align: center;
            position: relative; overflow: hidden;
        }
        .cta-section::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse 60% 80% at 50% 120%, rgba(124,58,237,.5) 0%, transparent 68%);
            pointer-events: none;
        }
        .cta-inner { position: relative; z-index: 1; max-width: 600px; margin: 0 auto; }
        .cta-section h2 { font-size: clamp(1.9rem, 4vw, 2.85rem); font-weight: 700; letter-spacing: -.04em; line-height: 1.1; margin-bottom: 1rem; }
        .cta-section p  { font-size: .97rem; color: rgba(255,255,255,.5); line-height: 1.7; margin-bottom: 2.25rem; }
        .btn-white {
            background: #fff; color: var(--purple); border: none;
            border-radius: var(--r); padding: .85rem 1.8rem;
            font-size: .92rem; font-weight: 600; letter-spacing: -.01em;
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: .45rem;
            transition: transform .15s, box-shadow .15s;
        }
        .btn-white:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(255,255,255,.12); }

        /* ── FOOTER ───────────────────────────────── */
        .footer { background: #08080a; padding: 3.5rem 2rem 2rem; color: rgba(255,255,255,.4); }
        .footer-inner {
            max-width: var(--max); margin: 0 auto;
            display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr; gap: 3rem; margin-bottom: 2.75rem;
        }
        .footer-brand { font-size: .92rem; font-weight: 600; color: #fff; display: block; margin-bottom: .5rem; }
        .footer-desc  { font-size: .78rem; line-height: 1.7; max-width: 17rem; }
        .footer-col-h { font-size: .68rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.55); display: block; margin-bottom: .9rem; }
        .footer-col a  { display: block; font-size: .8rem; color: rgba(255,255,255,.4); text-decoration: none; margin-bottom: .5rem; transition: color .15s; }
        .footer-col a:hover { color: #fff; }
        .footer-col p  { font-size: .8rem; margin-bottom: .5rem; }
        .footer-bot {
            max-width: var(--max); margin: 0 auto;
            padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; justify-content: space-between;
            gap: .75rem; flex-wrap: wrap;
        }
        .footer-bot-txt { font-size: .74rem; }

        /* ── MODAL ────────────────────────────────── */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.55); backdrop-filter: blur(5px);
            z-index: 9999; opacity: 0; transition: opacity .25s;
            align-items: center; justify-content: center;
        }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal-box {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: calc(var(--r) + 4px); max-width: 480px; width: 90%;
            max-height: 90vh; overflow-y: auto;
            box-shadow: 0 24px 60px rgba(0,0,0,.28);
            transform: scale(.97) translateY(6px); transition: transform .25s;
        }
        .modal-overlay.active .modal-box { transform: scale(1) translateY(0); }
        .modal-head {
            padding: 1.6rem 1.75rem 1.1rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: flex-start; justify-content: space-between; gap: .75rem;
        }
        .modal-head h2 { font-size: 1.1rem; font-weight: 700; letter-spacing: -.02em; color: var(--text); margin-bottom: .15rem; }
        .modal-head p  { font-size: .82rem; color: var(--muted); }
        .dark .modal-head h2 { color: #fafafa; }
        .modal-close-btn {
            background: var(--surface-2); border: none; cursor: pointer;
            width: 1.9rem; height: 1.9rem; border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            color: var(--muted); font-size: .8rem; flex-shrink: 0;
            transition: background .15s, color .15s;
        }
        .modal-close-btn:hover { background: var(--border); color: var(--text); }
        .modal-body { padding: 1.6rem 1.75rem; }
        .form-group  { margin-bottom: 1.1rem; }
        .form-label  { display: block; font-size: .8rem; font-weight: 500; color: var(--text); margin-bottom: .35rem; }
        .dark .form-label { color: #d4d4d8; }
        .form-input  {
            width: 100%; padding: .65rem .85rem;
            border: 1px solid var(--border); border-radius: 8px;
            font-size: .88rem; background: var(--surface); color: var(--text);
            transition: border-color .15s, box-shadow .15s;
        }
        .dark .form-input { background: #18181b; color: #fafafa; }
        .form-input:focus { outline: none; border-color: var(--purple-l); box-shadow: 0 0 0 3px rgba(139,92,246,.12); }
        .form-input::placeholder { color: var(--faint); }
        .btn-submit {
            width: 100%; background: var(--purple); color: #fff; border: none;
            border-radius: 8px; padding: .75rem 1.5rem;
            font-size: .88rem; font-weight: 600; cursor: pointer; margin-top: .25rem;
            display: flex; align-items: center; justify-content: center; gap: .4rem;
            transition: background .15s;
        }
        .btn-submit:hover { background: #6d28d9; }
        .btn-submit:disabled { opacity: .55; cursor: not-allowed; }
        .success-msg { display: none; text-align: center; padding: 2.5rem 1rem; }
        .success-msg.active { display: block; }
        .success-icon { width: 52px; height: 52px; background: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.1rem; }
        .success-icon i { color: #fff; font-size: 1.3rem; }
        .success-msg h3 { font-size: 1rem; font-weight: 600; color: var(--text); margin-bottom: .4rem; }
        .success-msg p  { font-size: .82rem; color: var(--muted); }
        .dark .success-msg h3 { color: #fafafa; }

        /* ── REVEAL ANIMATION ─────────────────────── */
        .reveal { opacity: 0; transform: translateY(18px); transition: opacity .55s ease, transform .55s ease; }
        .reveal.visible { opacity: 1; transform: none; }

        /* ── SCROLLBAR ────────────────────────────── */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(124,58,237,.25); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(124,58,237,.45); }

        /* ── RESPONSIVE ───────────────────────────── */
        @media (max-width: 1024px) {
            .hero-inner  { grid-template-columns: 1fr; gap: 3.5rem; }
            .hero-visual { align-items: flex-start; flex-direction: row; flex-wrap: wrap; }
            .mock-card, .mock-badges { max-width: 100%; }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .steps { grid-template-columns: repeat(2, 1fr); }
            .steps::before { display: none; }
            .footer-inner { grid-template-columns: 1fr 1fr; gap: 2rem; }
            .prize-inner { flex-direction: column; align-items: flex-start; text-align: left; padding: 2rem 2.25rem; }
        }

        @media (max-width: 768px) {
            :root { --nav-h: 58px; }
            .hero { padding: calc(var(--nav-h) + 3rem) 1.5rem 4rem; }
            .section { padding: 4rem 1.5rem; }
            .features-grid { grid-template-columns: 1fr; }
            .steps { grid-template-columns: 1fr 1fr; gap: 1.5rem; }
            .strip-inner { gap: 2.25rem; }
            .strip-sep   { display: none; }
            .footer-inner { grid-template-columns: 1fr; gap: 1.75rem; }
            .footer-bot   { flex-direction: column; text-align: center; }
            .hero-numbers { gap: 1.5rem; }
            .prize-inner  { padding: 1.75rem; }
            .nav-inner { padding: 0 1.25rem; }
        }

        @media (max-width: 480px) {
            .steps { grid-template-columns: 1fr; }
            .hero-cta { flex-direction: column; align-items: stretch; }
            .hero h1 { font-size: 2.25rem; }
        }
    </style>
</head>
<body>

<!-- ─── NAV ─────────────────────────────────────── -->
<nav class="nav" id="mainNav">
    <div class="nav-inner">
        <a href="/" class="nav-logo">
            <img src="{{ asset('assets/images/lopgosDASHBOARD.png') }}" alt="{{ __('app.name') }}">
            <span class="nav-logo-name">{{ __('app.name') }}</span>
        </a>
        <div class="nav-right">
            <select class="lang-select" id="languageSelector">
                <option value="pt_BR" {{ app()->getLocale() === 'pt_BR' ? 'selected' : '' }}>🇧🇷 PT</option>
                <option value="en_US" {{ app()->getLocale() === 'en_US' ? 'selected' : '' }}>🇬🇧 EN</option>
            </select>
            <a href="/login" class="btn-nav">{{ __('landing.access_panel') }}</a>
        </div>
    </div>
</nav>

<!-- ─── HERO ─────────────────────────────────────── -->
<section class="hero">
    <div class="hero-inner">

        <div>
            <div class="hero-kicker">
                <span class="hero-kicker-line"></span>
                {{ __('app.name') }}
            </div>
            <h1>{{ __('landing.hero_title') }}</h1>
            <p class="hero-lead">{{ __('landing.hero_description') }}</p>
            <div class="hero-cta">
                <button onclick="openContactModal()" class="btn-primary">
                    {{ __('landing.start_now') }}
                    <i class="fas fa-arrow-right" style="font-size:.75rem;"></i>
                </button>
                <a href="#como-funciona" class="btn-outline">
                    {{ __('landing.learn_more') }}
                </a>
            </div>
            <div class="hero-numbers">
                <div>
                    <span class="hero-num-val">+10k</span>
                    <span class="hero-num-label">{{ __('landing.reviews_processed') }}</span>
                </div>
                <div>
                    <span class="hero-num-val">4,9 ★</span>
                    <span class="hero-num-label">{{ __('landing.more_google_reviews') }}</span>
                </div>
                <div>
                    <span class="hero-num-val">100%</span>
                    <span class="hero-num-label">{{ app()->getLocale() === 'pt_BR' ? 'Controle total' : 'Full control' }}</span>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="mock-card">
                <span class="mock-card-label">{{ app()->getLocale() === 'pt_BR' ? 'Avaliações · Google' : 'Reviews · Google' }}</span>
                <div class="mock-rating-row">
                    <span class="mock-rating-num">4,9</span>
                    <span class="mock-stars">★★★★★</span>
                </div>
                <div class="mock-bars">
                    <div class="mock-bar-row">
                        <span class="mock-bar-lbl">5</span>
                        <div class="mock-bar-track"><div class="mock-bar-fill" style="width:92%"></div></div>
                    </div>
                    <div class="mock-bar-row">
                        <span class="mock-bar-lbl">4</span>
                        <div class="mock-bar-track"><div class="mock-bar-fill" style="width:6%;opacity:.55"></div></div>
                    </div>
                    <div class="mock-bar-row">
                        <span class="mock-bar-lbl">3</span>
                        <div class="mock-bar-track"><div class="mock-bar-fill" style="width:2%;opacity:.3"></div></div>
                    </div>
                </div>
            </div>

            <div class="mock-badges">
                <div class="mock-badge">
                    <div class="mock-badge-icon"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <strong>{{ app()->getLocale() === 'pt_BR' ? 'Filtro ativo' : 'Filter active' }}</strong>
                        <span>{{ app()->getLocale() === 'pt_BR' ? 'Negativas bloqueadas' : 'Negatives blocked' }}</span>
                    </div>
                </div>
                <div class="mock-badge">
                    <div class="mock-badge-icon"><i class="fas fa-bell"></i></div>
                    <div>
                        <strong>{{ app()->getLocale() === 'pt_BR' ? 'Alerta enviado' : 'Alert sent' }}</strong>
                        <span>{{ app()->getLocale() === 'pt_BR' ? 'Agora mesmo' : 'Just now' }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ─── STRIP ─────────────────────────────────────── -->
<div class="strip">
    <div class="strip-inner">
        <div class="strip-item">
            <span class="strip-num">+10.000</span>
            <span class="strip-lbl">{{ __('landing.reviews_processed') }}</span>
        </div>
        <div class="strip-sep"></div>
        <div class="strip-item">
            <span class="strip-num">10×</span>
            <span class="strip-lbl">{{ __('landing.more_google_reviews') }}</span>
        </div>
        <div class="strip-sep"></div>
        <div class="strip-item">
            <span class="strip-num">100%</span>
            <span class="strip-lbl">{{ app()->getLocale() === 'pt_BR' ? 'Reputação protegida' : 'Protected reputation' }}</span>
        </div>
        <div class="strip-sep"></div>
        <div class="strip-item">
            <span class="strip-num">&lt; 1 min</span>
            <span class="strip-lbl">{{ app()->getLocale() === 'pt_BR' ? 'Para começar' : 'To get started' }}</span>
        </div>
    </div>
</div>

<!-- ─── FEATURES ─────────────────────────────────── -->
<section class="section features-bg" id="features">
    <div class="section-inner">
        <div class="features-head reveal">
            <span class="section-tag">{{ __('landing.features_title') }}</span>
            <h2 class="section-h">{{ __('landing.features_description') }}</h2>
        </div>
        <div class="features-grid">
            <div class="feat reveal">
                <div class="feat-icon"><i class="fas fa-crosshairs"></i></div>
                <h3>{{ __('landing.feature_redirect_title') }}</h3>
                <p>{{ __('landing.feature_redirect_desc') }}</p>
            </div>
            <div class="feat reveal">
                <div class="feat-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>{{ __('landing.feature_protection_title') }}</h3>
                <p>{{ __('landing.feature_protection_desc') }}</p>
            </div>
            <div class="feat reveal">
                <div class="feat-icon"><i class="fas fa-chart-bar"></i></div>
                <h3>{{ __('landing.feature_dashboard_title') }}</h3>
                <p>{{ __('landing.feature_dashboard_desc') }}</p>
            </div>
            <div class="feat reveal">
                <div class="feat-icon"><i class="fas fa-bell"></i></div>
                <h3>{{ __('landing.feature_notifications_title') }}</h3>
                <p>{{ __('landing.feature_notifications_desc') }}</p>
            </div>
            <div class="feat reveal">
                <div class="feat-icon"><i class="fas fa-mobile-alt"></i></div>
                <h3>{{ __('landing.feature_contacts_title') }}</h3>
                <p>{{ __('landing.feature_contacts_desc') }}</p>
            </div>
            <div class="feat reveal">
                <div class="feat-icon"><i class="fas fa-globe"></i></div>
                <h3>{{ __('landing.feature_multilang_title') }}</h3>
                <p>{{ __('landing.feature_multilang_desc') }}</p>
            </div>
            <div class="feat reveal">
                <div class="feat-icon"><i class="fas fa-file-export"></i></div>
                <h3>{{ __('landing.feature_export_title') }}</h3>
                <p>{{ __('landing.feature_export_desc') }}</p>
            </div>
            <div class="feat reveal">
                <div class="feat-icon"><i class="fas fa-palette"></i></div>
                <h3>{{ __('landing.feature_customization_title') }}</h3>
                <p>{{ __('landing.feature_customization_desc') }}</p>
            </div>
            <div class="feat reveal">
                <div class="feat-icon"><i class="fas fa-qrcode"></i></div>
                <h3>{{ __('landing.feature_darkmode_title') }}</h3>
                <p>{{ __('landing.feature_darkmode_desc') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- ─── PRIZE ─────────────────────────────────────── -->
<section class="prize-section">
    <div class="prize-inner reveal">
        <div>
            <span class="prize-tag">{{ __('landing.prize_draw_title') }}</span>
            <h2 class="prize-title">{{ __('landing.prize_draw_description') }}</h2>
            <p class="prize-desc">{{ __('landing.prize_draw_badge') }}</p>
        </div>
        <div class="prize-right">
            <span class="prize-num">R$ 10.000</span>
            <span class="prize-subl">{{ app()->getLocale() === 'pt_BR' ? 'em prêmio' : 'in prizes' }}</span>
            <div style="margin-top:1.25rem;">
                <button onclick="openContactModal()" class="btn-primary" style="border:none;">
                    {{ __('landing.start_free') }}
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ─── HOW IT WORKS ──────────────────────────────── -->
<section class="section how-bg" id="como-funciona">
    <div class="section-inner">
        <div class="reveal">
            <span class="section-tag">{{ __('landing.how_title') }}</span>
            <h2 class="section-h">{{ __('landing.how_description') }}</h2>
        </div>
        <div class="steps">
            <div class="step-item reveal">
                <div class="step-num">01</div>
                <h3>{{ __('landing.step1_title') }}</h3>
                <p>{{ __('landing.step1_desc') }}</p>
            </div>
            <div class="step-item reveal">
                <div class="step-num">02</div>
                <h3>{{ __('landing.step2_title') }}</h3>
                <p>{{ __('landing.step2_desc') }}</p>
            </div>
            <div class="step-item reveal">
                <div class="step-num">03</div>
                <h3>{{ __('landing.step3_title') }}</h3>
                <p>{{ __('landing.step3_desc') }}</p>
            </div>
            <div class="step-item reveal">
                <div class="step-num">04</div>
                <h3>{{ __('landing.step4_title') }}</h3>
                <p>{{ __('landing.step4_desc') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- ─── CTA ───────────────────────────────────────── -->
<section class="cta-section">
    <div class="cta-inner">
        <h2>{{ __('landing.cta_title') }}</h2>
        <p>{{ __('landing.cta_description') }}</p>
        <button onclick="openContactModal()" class="btn-white">
            {{ __('landing.start_free') }}
            <i class="fas fa-arrow-right" style="font-size:.75rem;"></i>
        </button>
    </div>
</section>

<!-- ─── FOOTER ────────────────────────────────────── -->
<footer class="footer">
    <div class="footer-inner">
        <div>
            <span class="footer-brand">{{ __('app.name') }}</span>
            <p class="footer-desc">{{ __('landing.footer_description') }}</p>
        </div>
        <div class="footer-col">
            <span class="footer-col-h">{{ __('landing.product') }}</span>
            <a href="#como-funciona">{{ __('landing.how_works') }}</a>
            <a href="/login">{{ __('landing.control_panel') }}</a>
            <a href="/login">{{ __('landing.create_account') }}</a>
            <a href="#features">{{ __('landing.features') }}</a>
        </div>
        <div class="footer-col">
            <span class="footer-col-h">{{ __('landing.resources') }}</span>
            <a href="#">{{ __('landing.documentation') }}</a>
            <a href="#">{{ __('landing.help_center') }}</a>
            <a href="#">{{ __('landing.faq') }}</a>
            <a href="#">{{ __('landing.tutorials') }}</a>
        </div>
        <div class="footer-col">
            <span class="footer-col-h">{{ __('landing.contact') }}</span>
            <p>contato@reviewsplatform.com</p>
            <p>(11) 9 9999-9999</p>
            <p>{{ __('landing.technical_support') }}</p>
        </div>
    </div>
    <div class="footer-bot">
        <span class="footer-bot-txt">© 2025 {{ __('app.name') }}. {{ __('landing.all_rights') }}</span>
        <span class="footer-bot-txt">{{ __('landing.developed_with') }} ♥ {{ __('landing.by') }} Iago Vilela & Mateus Bittencourt</span>
    </div>
</footer>

<!-- ─── MODAL ─────────────────────────────────────── -->
<div id="contactModal" class="modal-overlay" onclick="closeModalOnOutsideClick(event)">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <h2>{{ __('landing.contact_form_title') }}</h2>
                <p>{{ __('landing.contact_form_subtitle') }}</p>
            </div>
            <button class="modal-close-btn" onclick="closeContactModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="contactForm" onsubmit="handleContactSubmit(event)">
                <div class="form-group">
                    <label class="form-label" for="contactName">{{ __('landing.contact_name') }}</label>
                    <input type="text" id="contactName" name="contact_name" class="form-input"
                           placeholder="{{ __('landing.contact_name_placeholder') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="companyName">{{ __('landing.company_name') }}</label>
                    <input type="text" id="companyName" name="company_name" class="form-input"
                           placeholder="{{ __('landing.company_name_placeholder') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">{{ __('landing.email') }}</label>
                    <input type="email" id="email" name="email" class="form-input"
                           placeholder="{{ __('landing.email_placeholder') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="whatsapp">{{ __('landing.whatsapp') }}</label>
                    <input type="tel" id="whatsapp" name="whatsapp" class="form-input"
                           placeholder="{{ __('landing.whatsapp_placeholder') }}" required>
                </div>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i>
                    {{ __('landing.submit_button') }}
                </button>
            </form>
            <div id="successMessage" class="success-msg">
                <div class="success-icon"><i class="fas fa-check"></i></div>
                <h3>{{ __('landing.success_title') }}</h3>
                <p>{{ __('landing.success_message') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Favicon
    (function () {
        document.querySelectorAll('link[rel="icon"]').forEach(l => l.remove());
        const l = document.createElement('link');
        l.rel = 'icon'; l.type = 'image/png';
        l.href = '{{ asset("assets/images/lopgosDASHBOARD.png") }}?v=' + Date.now();
        document.head.appendChild(l);
    })();

    // Nav scroll glass effect
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 24);
    }, { passive: true });

    // Language selector
    document.getElementById('languageSelector').addEventListener('change', function () {
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        fetch('/change-locale', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ locale: this.value })
        }).then(r => r.json()).then(d => { if (d.success) window.location.reload(); });
    });

    // Reveal on scroll
    const revealObs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.07, rootMargin: '0px 0px -30px 0px' });
    document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            e.preventDefault();
            const t = document.querySelector(a.getAttribute('href'));
            if (t) window.scrollTo({ top: t.getBoundingClientRect().top + window.pageYOffset - 80, behavior: 'smooth' });
        });
    });

    // Modal
    function openContactModal() {
        document.getElementById('contactModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeContactModal() {
        document.getElementById('contactModal').classList.remove('active');
        document.body.style.overflow = '';
        setTimeout(() => {
            document.getElementById('contactForm').reset();
            document.getElementById('contactForm').style.display = '';
            document.getElementById('successMessage').classList.remove('active');
        }, 260);
    }
    function closeModalOnOutsideClick(e) { if (e.target.id === 'contactModal') closeContactModal(); }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeContactModal(); });

    // Form submit
    function handleContactSubmit(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const data = {
            contact_name:  formData.get('contact_name'),
            company_name:  formData.get('company_name'),
            email:         formData.get('email'),
            whatsapp:      formData.get('whatsapp')
        };
        const btn = event.target.querySelector('.btn-submit');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("landing.sending") }}';
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        fetch('/contact-trial', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(() => {
            document.getElementById('contactForm').style.display = 'none';
            document.getElementById('successMessage').classList.add('active');
            setTimeout(closeContactModal, 3000);
        })
        .catch(() => {
            alert('{{ __("landing.error_message") }}');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> {{ __("landing.submit_button") }}';
        });
    }
</script>
</body>
</html>
