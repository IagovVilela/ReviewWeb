<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('auth.title')) — {{ __('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    <script>
        (function () {
            const saved = localStorage.getItem('darkMode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'true' || (saved === null && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    @vite(['resources/css/marketing.css'])
    @stack('head')
</head>
<body class="min-h-screen bg-surface text-ink antialiased">
    <div class="grid min-h-screen lg:grid-cols-2">
        <aside class="relative hidden flex-col justify-between border-r border-surface-border bg-ink p-10 text-white lg:flex dark:border-neutral-800">
            <div>
                <a href="/" class="inline-flex items-center gap-3">
                    <img src="{{ asset('assets/images/lopgosDASHBOARD.png') }}" alt="{{ __('app.name') }}" class="h-9 w-auto brightness-0 invert" />
                    <span class="text-sm font-semibold tracking-tight">{{ __('app.name') }}</span>
                </a>
                <h1 class="editorial-display mt-16 max-w-md text-4xl leading-tight text-white">
                    {{ __('landing.hero_title') }}
                </h1>
                <p class="mt-6 max-w-sm text-sm leading-relaxed text-white/65">
                    {{ __('landing.hero_description') }}
                </p>
            </div>
            <div class="space-y-3 text-sm text-white/50">
                <p class="flex items-center gap-2">
                    <span class="inline-block h-px w-8 bg-white/30"></span>
                    {{ __('landing.prize_amount_display') }} {{ __('landing.prize_draw_badge') }}
                </p>
                <p>© {{ date('Y') }} {{ __('app.name') }}</p>
            </div>
        </aside>

        <main class="flex flex-col">
            <header class="flex items-center justify-between border-b border-surface-border px-6 py-4 lg:border-none lg:px-10 lg:pt-8 dark:border-neutral-800">
                <a href="/" class="inline-flex items-center gap-2 lg:hidden">
                    <img src="{{ asset('assets/images/lopgosDASHBOARD.png') }}" alt="" class="h-8 w-auto" />
                    <span class="text-sm font-semibold">{{ __('app.name') }}</span>
                </a>
                <button
                    type="button"
                    onclick="document.documentElement.classList.toggle('dark'); localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));"
                    class="rounded-full p-2 text-ink-muted transition hover:bg-ink/5"
                    aria-label="Toggle theme"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                </button>
            </header>

            <div class="flex flex-1 items-center justify-center px-6 py-10 lg:px-16">
                <div class="w-full max-w-md">
                    @yield('content')
                </div>
            </div>

            <footer class="px-6 py-6 text-center text-xs text-ink-subtle lg:text-left lg:px-16">
                <a href="/" class="hover:text-ink">{{ __('auth.back_to_home') }}</a>
            </footer>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
