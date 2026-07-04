<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? __('app.name') }} — {{ __('landing.hero_title') }}</title>
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
    @vite(['resources/css/marketing.css', 'resources/js/home-entry.jsx'])
    @stack('head')
</head>
<body class="min-h-screen bg-surface text-ink antialiased">
    @yield('content')
    @stack('scripts')
</body>
</html>
