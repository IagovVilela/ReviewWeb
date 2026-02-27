<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.name'))</title>
    
    <!-- Prevent Dark Mode Flash -->
    <script>
        (function() {
            const savedMode = localStorage.getItem('darkMode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedMode === 'true' || (savedMode === null && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            bg: '#111827',
                            card: '#1f2937',
                            border: '#374151'
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* ── Paleta de Cores ────────────────────── */
        :root {
            --primary-color:  #7c3aed;
            --primary-dark:   #6d28d9;
            --primary-light:  #8b5cf6;
            --secondary-color: #3b82f6;
            --secondary-dark:  #2563eb;
            --success-color:  #16a34a;
            --success-dark:   #15803d;
            --error-color:    #dc2626;
            --error-dark:     #b91c1c;
            --warning-color:  #d97706;
            --warning-dark:   #b45309;
            --neutral-color:  #71717a;
            --neutral-dark:   #52525b;
            --shadow-sm:      0 1px 2px 0 rgba(0,0,0,.05);
            --shadow-md:      0 4px 6px -1px rgba(0,0,0,.08);
            --shadow-lg:      0 10px 15px -3px rgba(0,0,0,.1);
            --transition-smooth: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-bounce: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);

            /* Light Mode */
            --bg-primary:        #ffffff;
            --bg-secondary:      #f4f4f5;
            --bg-tertiary:       #e4e4e7;
            --text-primary:      #18181b;
            --text-secondary:    #52525b;
            --text-tertiary:     #71717a;
            --border-color:      #e4e4e7;
            --border-color-light:#f4f4f5;
            --card-bg:           #ffffff;
            --sidebar-bg:        #ffffff;
            --header-bg:         #ffffff;
        }

        /* Dark Mode Colors */
        .dark {
            --primary-color:  #8b5cf6;
            --primary-dark:   #7c3aed;

            --bg-primary:        #18181b;
            --bg-secondary:      #09090b;
            --bg-tertiary:       #27272a;
            --text-primary:      #fafafa;
            --text-secondary:    #a1a1aa;
            --text-tertiary:     #71717a;
            --border-color:      #27272a;
            --border-color-light:#3f3f46;
            --card-bg:           #18181b;
            --sidebar-bg:        #0f0f12;
            --header-bg:         #0f0f12;

            --shadow-sm: 0 1px 2px 0 rgba(0,0,0,.4);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,.4);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,.4);
        }
        
        html { scroll-behavior: smooth; }

        body {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            transition: background-color .25s, color .25s;
        }

        /* ── Tailwind dark overrides ──────────────── */
        .dark .bg-white        { background-color: var(--card-bg) !important; }
        .dark .bg-gray-50      { background-color: var(--bg-secondary) !important; }
        .dark .bg-gray-100     { background-color: var(--bg-tertiary) !important; }
        .dark .bg-gray-800     { background-color: var(--card-bg) !important; }
        .dark .text-gray-800   { color: var(--text-primary) !important; }
        .dark .text-gray-700   { color: var(--text-secondary) !important; }
        .dark .text-gray-600   { color: var(--text-tertiary) !important; }
        .dark .text-gray-500   { color: #71717a !important; }
        .dark .text-gray-400   { color: #71717a !important; }
        .dark .text-gray-100   { color: #fafafa !important; }
        .dark .border-gray-200 { border-color: var(--border-color) !important; }
        .dark .border-gray-300 { border-color: var(--border-color-light) !important; }
        .dark .border-gray-700 { border-color: var(--border-color) !important; }

        .dark input:not([type="checkbox"]):not([type="radio"]),
        .dark select, .dark textarea {
            background-color: var(--bg-tertiary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        .dark input:focus:not([type="checkbox"]):not([type="radio"]),
        .dark select:focus, .dark textarea:focus {
            background-color: var(--bg-tertiary);
            border-color: var(--primary-color);
        }

        /* ── Sidebar ──────────────────────────────── */
        .sidebar-gradient { background: var(--sidebar-bg); }

        .sidebar-gradient .language-selector-wrapper { position: relative; }
        .sidebar-gradient .language-selector-element {
            width: 100%;
            background-color: var(--bg-primary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        .sidebar-gradient .language-selector-element:hover {
            background-color: rgba(124,58,237,.07);
            border-color: var(--primary-color);
        }
        .sidebar-gradient .language-selector-element:focus {
            background-color: rgba(124,58,237,.1);
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(124,58,237,.18);
        }

        /* ── Nav Items ────────────────────────────── */
        .nav-item {
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
            color: var(--text-secondary);
            border-radius: 8px;
        }
        .nav-item::before {
            content: '';
            position: absolute; left: 0; top: 0; bottom: 0;
            width: 2.5px; background: var(--primary-color);
            transform: scaleY(0);
            transition: transform .2s cubic-bezier(0.4,0,0.2,1);
            border-radius: 0 2px 2px 0;
        }
        .nav-item:hover {
            background-color: rgba(124,58,237,.07);
            color: var(--primary-color);
        }
        .nav-item.active {
            background-color: rgba(124,58,237,.1);
            color: var(--primary-color);
            font-weight: 600;
        }
        .nav-item.active::before,
        .nav-item:hover::before { transform: scaleY(1); }

        .nav-item i { transition: color .2s; }

        /* ── Logo ─────────────────────────────────── */
        .logo-gradient { background: var(--primary-color); }
        .logo-no-bg    { background: transparent !important; }

        /* ── Buttons ──────────────────────────────── */
        .btn-primary {
            background: var(--primary-color);
            transition: var(--transition-smooth);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(124,58,237,.3);
        }
        .btn-primary:active { transform: translateY(0); }

        .btn-secondary {
            background: var(--neutral-color);
            transition: var(--transition-smooth);
        }
        .btn-secondary:hover {
            background: var(--neutral-dark);
            transform: translateY(-1px);
        }

        /* ── Cards ────────────────────────────────── */
        .card-hover {
            transition: var(--transition-smooth);
            position: relative;
            background-color: var(--card-bg);
        }
        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(0,0,0,.08);
        }
        .dark .card-hover:hover {
            box-shadow: 0 10px 24px rgba(0,0,0,.45);
        }

        /* ── Icon backgrounds ─────────────────────── */
        .icon-gradient { background: var(--primary-color); }
        
        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-in-left {
            animation: slideInLeft 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .slide-in-right {
            animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .scale-in {
            animation: scaleIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Staggered Animation */
        .stagger-item {
            opacity: 0;
            animation: fadeInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stagger-item:nth-child(1) { animation-delay: 0.05s; }
        .stagger-item:nth-child(2) { animation-delay: 0.1s; }
        .stagger-item:nth-child(3) { animation-delay: 0.15s; }
        .stagger-item:nth-child(4) { animation-delay: 0.2s; }
        .stagger-item:nth-child(5) { animation-delay: 0.25s; }
        .stagger-item:nth-child(6) { animation-delay: 0.3s; }
        .stagger-item:nth-child(7) { animation-delay: 0.35s; }
        .stagger-item:nth-child(8) { animation-delay: 0.4s; }
        .stagger-item:nth-child(9) { animation-delay: 0.45s; }
        
        /* Skeleton Loader */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s ease-in-out infinite;
        }
        
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Pulse Animation */
        .pulse-soft {
            animation: pulseSoft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulseSoft {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }
        
        /* Shimmer Effect */
        .shimmer {
            position: relative;
            overflow: hidden;
        }
        
        .shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            transform: translateX(-100%);
            background: linear-gradient(
                90deg,
                rgba(255, 255, 255, 0) 0,
                rgba(255, 255, 255, 0.3) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.2);
            border-radius: 10px;
            transition: background 0.3s ease;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 92, 246, 0.4);
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.4);
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 92, 246, 0.6);
        }
        
        /* Notifications - Dark Mode Support */
        .dark .bg-green-50 {
            background-color: rgba(16, 185, 129, 0.1) !important;
            border-color: rgba(16, 185, 129, 0.3) !important;
        }
        
        .dark .bg-red-50 {
            background-color: rgba(239, 68, 68, 0.1) !important;
            border-color: rgba(239, 68, 68, 0.3) !important;
        }
        
        .dark .bg-blue-50 {
            background-color: rgba(59, 130, 246, 0.1) !important;
            border-color: rgba(59, 130, 246, 0.3) !important;
        }
        
        .dark .bg-yellow-50 {
            background-color: rgba(251, 191, 36, 0.1) !important;
            border-color: rgba(251, 191, 36, 0.3) !important;
        }
        
        .dark .text-green-800 {
            color: rgb(134, 239, 172) !important;
        }
        
        .dark .text-red-800 {
            color: rgb(252, 165, 165) !important;
        }
        
        .dark .text-blue-800 {
            color: rgb(147, 197, 253) !important;
        }
        
        .dark .text-yellow-800 {
            color: rgb(253, 224, 71) !important;
        }
        
        /* Skeleton Loading Styles */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s ease-in-out infinite;
            border-radius: 4px;
        }
        
        .dark .skeleton {
            background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
            background-size: 200% 100%;
        }
        
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .skeleton-card {
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }
        
        .dark .skeleton-card {
            background: #1f2937;
            border-color: #374151;
        }
        
        .skeleton-avatar {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s ease-in-out infinite;
            margin-bottom: 1rem;
        }
        
        .dark .skeleton-avatar {
            background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
            background-size: 200% 100%;
        }
        
        .skeleton-line {
            height: 12px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s ease-in-out infinite;
            border-radius: 4px;
            margin-bottom: 0.5rem;
        }
        
        .dark .skeleton-line {
            background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
            background-size: 200% 100%;
        }
        
        .skeleton-line.w-full { width: 100%; }
        .skeleton-line.w-3-4 { width: 75%; }
        .skeleton-line.w-1-2 { width: 50%; }
        .skeleton-line.w-1-4 { width: 25%; }
        
        .skeleton-circle {
            border-radius: 50%;
        }
        
        .skeleton-text {
            height: 16px;
            margin-bottom: 8px;
        }
        
        .skeleton-title {
            height: 24px;
            margin-bottom: 12px;
        }
        
        /* Image Lazy Loading */
        .image-placeholder {
            position: relative;
            background: #f0f0f0;
            overflow: hidden;
            border-radius: 8px;
        }
        
        .dark .image-placeholder {
            background: #374151;
        }
        
        .image-placeholder img {
            opacity: 0;
            transition: opacity 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .image-placeholder.loaded img,
        .image-placeholder img[src] {
            opacity: 1;
        }
        
        /* Fallback: se a imagem já estiver carregada, mostrar imediatamente */
        .image-placeholder img[complete] {
            opacity: 1;
        }
        
        .image-placeholder.loaded .placeholder-shimmer {
            display: none;
        }
        
        .placeholder-shimmer {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
            animation: shimmer-move 1.5s infinite;
        }
        
        .dark .placeholder-shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        }
        
        @keyframes shimmer-move {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Input Styles */
        input:not([type="checkbox"]):not([type="radio"]),
        select,
        textarea {
            transition: var(--transition-smooth);
        }
        
        input:focus:not([type="checkbox"]):not([type="radio"]),
        select:focus,
        textarea:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.15);
        }
        
        /* Focus Ring */
        *:focus {
            outline: none;
        }
        
        *:focus-visible {
            outline: 2px solid rgba(139, 92, 246, 0.5);
            outline-offset: 2px;
        }
        
        /* Page Container */
        .page-container {
            min-height: 100vh;
        }
        
        /* Content Area */
        .content-area {
            min-height: calc(100vh - 80px);
        }
        
        /* Button Protection - Prevent buttons from disappearing */
        button,
        .btn-primary,
        .btn-secondary,
        .btn-danger,
        a[role="button"] {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }
        
        button:disabled,
        .btn-primary:disabled,
        .btn-secondary:disabled {
            opacity: 0.5 !important;
            cursor: not-allowed;
            pointer-events: auto !important;
        }
        
        /* Ensure buttons remain visible during animations */
        button,
        .btn-primary,
        .btn-secondary {
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease !important;
        }
        
        /* Prevent JS errors from hiding buttons */
        .hidden:is(button, .btn-primary, .btn-secondary, .btn-danger) {
            display: none !important;
        }
        
        /* Mobile Responsiveness */
        @media (max-width: 1023px) {
            /* Sidebar sempre oculto por padrão no mobile */
            .sidebar-mobile-hidden {
                transform: translateX(-100%) !important;
            }
            
            /* Sidebar quando aberto */
            #sidebar:not(.sidebar-mobile-hidden) {
                transform: translateX(0);
            }
            
            .sidebar-overlay {
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
                display: block;
            }
            
            .sidebar-overlay.active {
                opacity: 1;
                pointer-events: auto;
            }
            
            /* Prevenir scroll do body quando sidebar aberto */
            body.sidebar-open {
                overflow: hidden;
                position: fixed;
                width: 100%;
            }
            
            /* Mobile header */
            header {
                padding: 0.75rem 1rem !important;
            }
            
            header .text-2xl {
                font-size: 1.25rem !important;
            }
            
            header .text-xl {
                font-size: 1.125rem !important;
            }
            
            header .text-sm {
                font-size: 0.75rem !important;
            }
            
            /* Botão de menu mobile maior para touch */
            #mobileMenuBtn {
                min-width: 44px;
                min-height: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
        }
        
        /* Garantir que botões mobile não apareçam no desktop */
        @media (min-width: 1024px) {
            #mobileMenuBtn,
            #sidebar button[aria-label="Close menu"] {
                display: none !important;
            }
        }
        
        @media (max-width: 1023px) {
            
            /* Ensure main content takes full width on mobile */
            .page-container {
                position: relative;
                width: 100%;
            }
            
            /* Main content área */
            main.content-area {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                padding-top: 1rem !important;
                padding-bottom: 3rem !important;
                width: 100% !important;
                max-width: 100% !important;
                min-height: calc(100vh - 120px) !important;
            }
            
            /* Garantir que o conteúdo possa fazer scroll completo */
            main.content-area > * {
                min-height: auto !important;
            }
            
            /* Sidebar fixo no mobile */
            #sidebar {
                position: fixed !important;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 9999;
                width: 280px;
                max-width: 85vw;
            }
            
            /* Logo menor no mobile */
            .logo-container img,
            #sidebar .logo-no-bg {
                max-width: 100px !important;
                height: auto !important;
            }
            
            /* Navegação mobile */
            #sidebar nav {
                padding: 1rem 0.75rem !important;
            }
            
            #sidebar nav a {
                padding: 0.75rem 0.875rem !important;
                font-size: 0.9375rem;
                min-height: 44px;
                display: flex;
                align-items: center;
            }
            
            #sidebar nav i {
                width: 20px !important;
                height: 20px !important;
                margin-right: 0.75rem !important;
            }
            
            /* Seções do sidebar */
            #sidebar .pt-4 {
                padding-top: 1rem !important;
            }
            
            #sidebar .px-3 {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
            
            /* Seletor de idioma e links lado a lado no mobile */
            #sidebar .flex.flex-col.sm\\:flex-row {
                flex-direction: row !important;
                flex-wrap: wrap;
            }
            
            #sidebar .language-selector-wrapper {
                min-width: 80px;
                flex: 1 1 auto;
            }
            
            /* Links de suporte lado a lado no mobile */
            #sidebar .nav-item {
                flex: 1 1 auto;
                min-width: 0;
            }
            
            /* Reduzir tamanho de ícones no mobile */
            #sidebar .nav-item i.w-4,
            #sidebar .nav-item i.w-5 {
                width: 16px !important;
                height: 16px !important;
                margin-right: 0.5rem !important;
            }
            
            /* Espaçamento reduzido entre seções */
            #sidebar .border-t {
                margin-top: 0.75rem !important;
                padding-top: 0.75rem !important;
            }
            
            /* User profile no mobile */
            #sidebar .user-profile-section,
            #sidebar > div:last-child {
                padding: 1rem 0.75rem !important;
            }
            
            /* Avatar e informações do usuário mobile */
            #sidebar img[alt*="profile"],
            #sidebar .w-10.h-10.rounded-full {
                width: 2.5rem !important;
                height: 2.5rem !important;
            }
            
            #sidebar .text-sm,
            #sidebar .text-xs {
                font-size: 0.8125rem !important;
            }
            
            /* Ensure tables are scrollable on mobile */
            .table-responsive,
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                width: 100%;
            }
            
            .table-responsive table,
            .table-container table {
                min-width: 100%;
                display: table;
            }
            
            /* Mobile cards */
            .card-hover,
            .bg-white.rounded-xl {
                margin-bottom: 1rem;
                padding: 1rem !important;
            }
            
            /* Mobile grid - force single column */
            .grid-responsive,
            .grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            
            /* Botões mobile */
            button, a.btn-primary, a.btn-secondary {
                min-height: 44px;
                padding: 0.625rem 1rem !important;
                font-size: 0.9375rem;
                touch-action: manipulation;
            }
            
            /* Inputs mobile - prevenir zoom no iOS */
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="number"],
            select,
            textarea {
                font-size: 16px !important;
                min-height: 44px;
                padding: 0.75rem !important;
            }
            
            /* Header actions mobile */
            header .flex.items-center.space-x-2,
            header .flex.items-center.space-x-3 {
                gap: 0.5rem !important;
            }
            
            /* Melhorar espaçamento mobile */
            .space-y-4 > * + * {
                margin-top: 1rem !important;
            }
            
            .space-y-6 > * + * {
                margin-top: 1.5rem !important;
            }
            
            /* Cards e containers mobile */
            .max-w-3xl,
            .max-w-4xl,
            .max-w-5xl,
            .max-w-6xl,
            .max-w-7xl {
                max-width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            /* Padding reduzido em mobile */
            .p-6 {
                padding: 1rem !important;
            }
            
            .px-6 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            .py-6 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }
            
            /* Breadcrumbs mobile */
            nav ol {
                flex-wrap: wrap;
                gap: 0.25rem;
            }
            
            /* Estatísticas mobile */
            .grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3,
            .grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3 {
                grid-template-columns: 1fr !important;
            }
            
            /* Melhorar scroll em mobile */
            main {
                -webkit-overflow-scrolling: touch;
                overflow-y: auto;
            }
        }
        
        /* Tablet responsiveness */
        @media (min-width: 768px) and (max-width: 1023px) {
            .grid-responsive-md {
                grid-template-columns: repeat(2, 1fr);
            }
            
            #sidebar {
                width: 240px;
            }
        }
        
        /* Form responsive adjustments */
        @media (max-width: 768px) {
            /* Make form inputs full width on mobile */
            form input[type="text"],
            form input[type="email"],
            form input[type="password"],
            form input[type="number"],
            form input[type="tel"],
            form input[type="url"],
            form select,
            form textarea {
                width: 100%;
                font-size: 16px; /* Prevents zoom on iOS */
            }
            
            /* Ensure buttons stack on mobile */
            form .flex.items-center.space-x-2,
            form .flex.items-center.space-x-4 {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }
            
            form .flex.items-center.space-x-2 > *,
            form .flex.items-center.space-x-4 > * {
                width: 100%;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            /* Make form buttons responsive */
            form button[type="submit"],
            form .btn-primary,
            form .btn-secondary {
                width: 100%;
                margin-top: 0.5rem;
            }
            
            /* Adjust form labels */
            label {
                font-size: 0.875rem;
            }
            
            /* Stack form groups */
            .flex.flex-col.sm\\:flex-row {
                flex-direction: column;
            }
            
            /* Header actions responsive */
            header a.btn-primary,
            header button.btn-primary,
            header a.bg-green-500,
            header button.bg-green-500 {
                width: 100%;
                display: flex;
                justify-content: center;
            }
        }
        
        @yield('styles')
    </style>
</head>
<body class="bg-gray-50">
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 sidebar-overlay transition-opacity duration-300 lg:hidden"></div>

    <div class="flex h-screen page-container overflow-hidden">

        <!-- ── SIDEBAR ────────────────────────────── -->
        <div id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-60 sidebar-gradient border-r border-gray-200 dark:border-gray-800 flex flex-col transform transition-transform duration-300 ease-in-out sidebar-mobile-hidden lg:translate-x-0 lg:z-auto">

            <!-- Logo -->
            <div class="h-14 px-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between flex-shrink-0">
                <a href="/dashboard" class="flex items-center gap-2.5 min-w-0">
                    <img src="{{ asset('assets/images/lopgosDASHBOARD.png') }}" alt="{{ __('app.name') }}" class="w-7 h-7 object-contain logo-no-bg flex-shrink-0">
                    <span class="font-semibold text-sm text-gray-900 dark:text-white truncate tracking-tight">{{ __('app.name') }}</span>
                </a>
                <button
                    onclick="toggleMobileSidebar(); event.stopPropagation(); return false;"
                    type="button"
                    class="lg:hidden p-1.5 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    aria-label="Close menu"
                    id="sidebarCloseBtn"
                >
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">

                <!-- Main -->
                <a href="/dashboard" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-sm font-medium">
                    <i class="fas fa-home w-4 text-center flex-shrink-0"></i>
                    <span>{{ __('app.dashboard') }}</span>
                </a>
                <a href="/companies" class="nav-item {{ request()->is('companies*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-sm font-medium">
                    <i class="fas fa-building w-4 text-center flex-shrink-0"></i>
                    <span>{{ Auth::user()->role === 'proprietario' ? __('app.companies') : 'Minha Empresa' }}</span>
                </a>
                <a href="/reviews" class="nav-item {{ request()->is('reviews*') && !request()->is('reviews/negative') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-sm font-medium">
                    <i class="fas fa-star w-4 text-center flex-shrink-0"></i>
                    <span>{{ __('app.reviews') }}</span>
                </a>
                <a href="/reviews/negative" class="nav-item {{ request()->is('reviews/negative') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-sm font-medium">
                    <i class="fas fa-exclamation-triangle w-4 text-center flex-shrink-0"></i>
                    <span>{{ __('app.negative_reviews') }}</span>
                </a>

                <!-- Settings -->
                <div class="pt-4 mt-2 border-t border-gray-200 dark:border-gray-800">
                    <p class="px-3 pb-1.5 text-xs font-semibold text-gray-400 dark:text-gray-600 uppercase tracking-wider">{{ __('app.settings') }}</p>

                    @if(in_array(Auth::user()->role, ['admin', 'proprietario']))
                    <a href="{{ route('users.index') }}" class="nav-item {{ request()->is('users*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-users w-4 text-center flex-shrink-0"></i>
                        <span>{{ __('users.title') }}</span>
                    </a>
                    @endif

                    <a href="/profile" class="nav-item {{ request()->is('profile') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-user w-4 text-center flex-shrink-0"></i>
                        <span>{{ __('app.profile') }}</span>
                    </a>
                </div>

                <!-- Support -->
                <div class="pt-4 mt-2 border-t border-gray-200 dark:border-gray-800">
                    <p class="px-3 pb-1.5 text-xs font-semibold text-gray-400 dark:text-gray-600 uppercase tracking-wider">{{ __('app.support') }}</p>

                    <a href="{{ route('support.help-center') }}" class="nav-item {{ request()->is('support') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-life-ring w-4 text-center flex-shrink-0"></i>
                        <span>{{ __('app.help_center') }}</span>
                    </a>
                    <a href="{{ route('support.faqs') }}" class="nav-item {{ request()->is('faqs') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-question-circle w-4 text-center flex-shrink-0"></i>
                        <span>{{ __('app.faqs') }}</span>
                    </a>

                    <!-- Language Selector -->
                    <div class="px-3 pt-2">
                        <div class="relative language-selector-wrapper">
                            <select
                                class="language-selector-element appearance-none border rounded-lg px-3 py-1.5 pr-7 text-xs font-medium cursor-pointer transition-colors w-full"
                                style="-webkit-appearance:none;-moz-appearance:none;font-size:12px;"
                            >
                                <option value="pt_BR" {{ app()->getLocale() === 'pt_BR' ? 'selected' : '' }}>🇧🇷 Português</option>
                                <option value="en_US" {{ app()->getLocale() === 'en_US' ? 'selected' : '' }}>🇬🇧 English</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- User Section -->
            <div class="px-3 py-3 border-t border-gray-200 dark:border-gray-800 flex-shrink-0">
                <div class="flex items-center gap-2.5 mb-2">
                    @if(Auth::user()->photo)
                        <img src="{{ Auth::user()->photo_url }}"
                             alt="{{ Auth::user()->name }}"
                             class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-700 flex-shrink-0">
                    @else
                        <div class="w-8 h-8 bg-violet-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-semibold text-xs leading-none">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                            </span>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-900 dark:text-white truncate leading-tight">{{ Auth::user()->name ?? __('app.user') }}</p>
                        <p class="text-xs text-gray-400 truncate leading-tight">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                </div>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="w-full nav-item flex items-center gap-2.5 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-sign-out-alt w-4 text-center flex-shrink-0"></i>
                        <span>{{ __('app.logout') }}</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- ── MAIN CONTENT ────────────────────────── -->
        <div class="flex-1 flex flex-col overflow-hidden w-full lg:ml-0">

            <!-- Header -->
            <header class="h-14 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 px-4 lg:px-6 z-30 relative flex items-center">
                <div class="flex items-center justify-between w-full gap-3">
                    <!-- Left -->
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <button
                            id="mobileMenuBtn"
                            onclick="toggleMobileSidebar(event); return false;"
                            type="button"
                            class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-500 dark:text-gray-400 flex-shrink-0 touch-manipulation"
                            style="min-width:40px;min-height:40px;display:flex;align-items:center;justify-content:center;"
                            aria-label="Toggle menu"
                        >
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="min-w-0">
                            <h1 class="text-base font-semibold text-gray-900 dark:text-white truncate leading-tight tracking-tight">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-xs text-gray-500 dark:text-gray-500 truncate hidden sm:block leading-tight">@yield('page-description', '')</p>
                        </div>
                    </div>
                    <!-- Right -->
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <button
                            id="darkModeToggle"
                            type="button"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-500 dark:text-gray-400 touch-manipulation"
                            style="min-width:38px;min-height:38px;display:flex;align-items:center;justify-content:center;"
                            aria-label="Toggle dark mode"
                        >
                            <i id="darkModeIcon" class="fas fa-moon text-sm"></i>
                        </button>
                        <div class="hidden sm:flex items-center gap-2">
                            @yield('header-actions')
                        </div>
                    </div>
                </div>
                <!-- Mobile Header Actions -->
                <div class="sm:hidden absolute left-0 right-0 top-full border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 py-2" id="mobileHeaderActions" style="display:none;">
                    @yield('header-actions')
                </div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6 content-area w-full">

                @if(session('success'))
                    <div class="flex items-center gap-2.5 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg mb-5 text-sm fade-in">
                        <i class="fas fa-check-circle flex-shrink-0"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center gap-2.5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg mb-5 text-sm fade-in">
                        <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="flex items-start gap-2.5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg mb-5 text-sm fade-in">
                        <i class="fas fa-exclamation-circle flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-medium mb-1">Por favor, corrija os seguintes erros:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        // Definir funções globais ANTES de qualquer uso
        // Mobile Sidebar Toggle - Função global
        window.toggleMobileSidebar = function(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (!sidebar || !sidebarOverlay) {
                console.error('Sidebar elements not found');
                return false;
            }
            
            // Só funciona no mobile
            if (window.innerWidth >= 1024) {
                return false;
            }
            
            const isHidden = sidebar.classList.contains('sidebar-mobile-hidden');
            
            if (isHidden) {
                // Open sidebar
                sidebar.classList.remove('sidebar-mobile-hidden');
                sidebarOverlay.classList.add('active');
                document.body.classList.add('sidebar-open');
                // Prevenir scroll
                const scrollY = window.scrollY;
                document.body.style.position = 'fixed';
                document.body.style.top = `-${scrollY}px`;
                document.body.style.width = '100%';
            } else {
                // Close sidebar
                sidebar.classList.add('sidebar-mobile-hidden');
                sidebarOverlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
                // Restaurar scroll
                const scrollY = document.body.style.top;
                document.body.style.position = '';
                document.body.style.top = '';
                document.body.style.width = '';
                if (scrollY) {
                    window.scrollTo(0, parseInt(scrollY || '0') * -1);
                }
            }
            
            return false;
        };
        
        // Dark Mode Toggle - Função global
        window.toggleDarkMode = function(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            const icon = document.getElementById('darkModeIcon');
            
            if (!icon) {
                console.error('Ícone dark mode não encontrado');
                return false;
            }
            
            // Update icon
            if (isDark) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
            
            // Save preference
            localStorage.setItem('darkMode', isDark ? 'true' : 'false');
            
            // Show feedback
            if (typeof showNotification === 'function') {
                showNotification(
                    isDark ? 'Modo escuro ativado' : 'Modo claro ativado',
                    'info',
                    2000
                );
            }
            
            return false;
        };
        
        // Initialize dark mode from saved preference
        function initDarkMode() {
            const savedMode = localStorage.getItem('darkMode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const icon = document.getElementById('darkModeIcon');
            
            // Use saved preference, or system preference if no saved preference
            if (savedMode === 'true' || (savedMode === null && prefersDark)) {
                document.documentElement.classList.add('dark');
                if (icon) {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                }
            }
        }
        
        // Initialize immediately to prevent flash
        initDarkMode();
        
        // Reinitialize after DOM is ready (in case icon wasn't loaded yet)
        document.addEventListener('DOMContentLoaded', initDarkMode);
        
        // Enhanced Notification System
        function showNotification(message, type = 'info', duration = 3000) {
            const notification = document.createElement('div');
            
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };
            
            const icons = {
                success: 'check-circle',
                error: 'exclamation-circle',
                warning: 'exclamation-triangle',
                info: 'info-circle'
            };
            
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl ${colors[type]} text-white transform translate-x-full transition-all duration-500 ease-out backdrop-blur-sm`;
            notification.style.cssText = 'backdrop-filter: blur(10px); max-width: 400px;';
            
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-${icons[type]} text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 opacity-70 hover:opacity-100 transition-opacity">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-white bg-opacity-30 rounded-b-xl overflow-hidden">
                    <div class="progress-bar h-full bg-white bg-opacity-50" style="width: 100%; animation: progress ${duration}ms linear;"></div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Slide in
            requestAnimationFrame(() => {
                notification.style.transform = 'translateX(0)';
            });
            
            // Auto remove
            setTimeout(() => {
                notification.style.transform = 'translateX(calc(100% + 1rem))';
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 500);
            }, duration);
        }
        
        // Progress bar animation
        const progressStyle = document.createElement('style');
        progressStyle.textContent = `
            @keyframes progress {
                from { width: 100%; }
                to { width: 0%; }
            }
        `;
        document.head.appendChild(progressStyle);
        
        // Auto-hide success/error messages with fade - only target notification messages
        setTimeout(() => {
            // Only target notification divs in main content area with proper structure
            document.querySelectorAll('main > .bg-green-50, main > .bg-red-50, main > .bg-blue-50').forEach(el => {
                // Check if this is actually a notification (has border and specific classes)
                if (el.classList.contains('border') && (
                    el.classList.contains('border-green-200') || 
                    el.classList.contains('border-red-200') || 
                    el.classList.contains('border-blue-200')
                )) {
                    el.style.transition = 'all 0.5s ease-out';
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        if (el.parentElement && document.body.contains(el)) {
                            el.remove();
                        }
                    }, 500);
                }
            });
        }, 5000);
        
        // Add loading overlay function
        function showLoading(message = 'Carregando...') {
            const overlay = document.createElement('div');
            overlay.id = 'loading-overlay';
            overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm';
            overlay.style.cssText = 'backdrop-filter: blur(8px);';
            
            overlay.innerHTML = `
                <div class="bg-white rounded-2xl p-8 shadow-2xl scale-in">
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative">
                            <div class="w-16 h-16 border-4 border-purple-200 rounded-full"></div>
                            <div class="w-16 h-16 border-4 border-purple-600 rounded-full border-t-transparent absolute top-0 left-0" style="animation: spin 1s linear infinite;"></div>
                        </div>
                        <p class="text-gray-700 font-medium">${message}</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(overlay);
            return overlay;
        }
        
        function hideLoading() {
            const overlay = document.getElementById('loading-overlay');
            if (overlay) {
                overlay.style.opacity = '0';
                setTimeout(() => overlay.remove(), 300);
            }
        }
        
        // Spin animation
        const spinStyle = document.createElement('style');
        spinStyle.textContent = `
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(spinStyle);
        
        // Initialize mobile sidebar state - executa imediatamente
        (function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Verificar se estamos no mobile e garantir sidebar oculto
            function checkMobileState() {
                if (window.innerWidth < 1024) {
                    if (sidebar && !sidebar.classList.contains('sidebar-mobile-hidden')) {
                        sidebar.classList.add('sidebar-mobile-hidden');
                    }
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.remove('active');
                    }
                    document.body.classList.remove('sidebar-open');
                }
            }
            
            // Executar imediatamente se DOM já está pronto
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', checkMobileState);
            } else {
                checkMobileState();
            }
        })();
        
        // Initialize mobile sidebar state
        document.addEventListener('DOMContentLoaded', function() {
            // Garantir que sidebar comece oculto no mobile (verificação adicional)
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlayEl = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth < 1024) {
                if (sidebar && !sidebar.classList.contains('sidebar-mobile-hidden')) {
                    sidebar.classList.add('sidebar-mobile-hidden');
                }
                if (sidebarOverlayEl) {
                    sidebarOverlayEl.classList.remove('active');
                }
                document.body.classList.remove('sidebar-open');
            }
            
            // Close sidebar when clicking on a link (mobile only)
            const sidebarLinks = document.querySelectorAll('#sidebar nav a, #sidebar form button[type="submit"]');
            
            function closeSidebarOnMobile(e) {
                // Don't close if clicking the close button itself
                if (e.target.closest('button[aria-label="Close menu"]')) {
                    return;
                }
                
                if (window.innerWidth < 1024) {
                    setTimeout(() => {
                        const sidebar = document.getElementById('sidebar');
                        const sidebarOverlayEl = document.getElementById('sidebarOverlay');
                        if (sidebar && sidebarOverlayEl) {
                            sidebar.classList.add('sidebar-mobile-hidden');
                            sidebarOverlayEl.classList.remove('active');
                            document.body.classList.remove('sidebar-open');
                            // Restaurar scroll
                            const scrollY = document.body.style.top;
                            document.body.style.position = '';
                            document.body.style.top = '';
                            document.body.style.width = '';
                            if (scrollY) {
                                window.scrollTo(0, parseInt(scrollY || '0') * -1);
                            }
                        }
                    }, 150);
                }
            }
            
            sidebarLinks.forEach(link => {
                link.addEventListener('click', closeSidebarOnMobile);
            });
            
            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth >= 1024) {
                        const sidebar = document.getElementById('sidebar');
                        const sidebarOverlayEl = document.getElementById('sidebarOverlay');
                        if (sidebar) sidebar.classList.remove('sidebar-mobile-hidden');
                        if (sidebarOverlayEl) {
                            sidebarOverlayEl.classList.remove('active');
                        }
                        document.body.classList.remove('sidebar-open');
                        document.body.style.position = '';
                        document.body.style.top = '';
                        document.body.style.width = '';
                    } else {
                        // Ensure sidebar is hidden on mobile by default
                        const sidebar = document.getElementById('sidebar');
                        const sidebarOverlayEl = document.getElementById('sidebarOverlay');
                        if (sidebar && !sidebar.classList.contains('sidebar-mobile-hidden')) {
                            sidebar.classList.add('sidebar-mobile-hidden');
                        }
                        if (sidebarOverlayEl) {
                            sidebarOverlayEl.classList.remove('active');
                        }
                        document.body.classList.remove('sidebar-open');
                        document.body.style.position = '';
                        document.body.style.top = '';
                        document.body.style.width = '';
                    }
                }, 100);
            });
            
            // Close sidebar on overlay click
            const sidebarOverlayClick = document.getElementById('sidebarOverlay');
            if (sidebarOverlayClick) {
                sidebarOverlayClick.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (window.innerWidth < 1024) {
                        toggleMobileSidebar(e);
                    }
                });
            }
            
            // Touch gestures for mobile
            let touchStartX = 0;
            let touchEndX = 0;
            
            document.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });
            
            document.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, { passive: true });
            
            function handleSwipe() {
                if (window.innerWidth >= 1024) return;
                
                const swipeThreshold = 50;
                const diff = touchStartX - touchEndX;
                
                if (Math.abs(diff) > swipeThreshold) {
                    const sidebar = document.getElementById('sidebar');
                    if (!sidebar) return;
                    
                    const isHidden = sidebar.classList.contains('sidebar-mobile-hidden');
                    
                    // Swipe right to open (starting from left edge)
                    if (diff < 0 && touchStartX < 50 && isHidden) {
                        toggleMobileSidebar();
                    }
                    // Swipe left to close (when sidebar is open)
                    else if (diff > 0 && !isHidden) {
                        toggleMobileSidebar();
                    }
                }
            }
            
            // Add click handler to close button via event listener as well (only on mobile)
            const closeBtn = document.querySelector('#sidebar button[aria-label="Close menu"]');
            if (closeBtn && window.innerWidth < 1024) {
                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleMobileSidebar(e);
                });
                
                // Suporte touch para mobile
                closeBtn.addEventListener('touchend', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleMobileSidebar(e);
                }, { passive: false });
            }
            
            // Ensure hamburger button also works via event listener (only on mobile)
            const menuBtn = document.getElementById('mobileMenuBtn');
            if (menuBtn && window.innerWidth < 1024) {
                menuBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleMobileSidebar(e);
                });
                
                // Suporte touch para mobile
                menuBtn.addEventListener('touchend', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleMobileSidebar(e);
                }, { passive: false });
            }
            
            // Dark mode toggle via event listener - funciona em todas as telas (mobile e desktop)
            const darkModeBtn = document.getElementById('darkModeToggle');
            if (darkModeBtn && window.toggleDarkMode) {
                // Remover event listeners antigos para evitar duplicação
                const newBtn = darkModeBtn.cloneNode(true);
                darkModeBtn.parentNode.replaceChild(newBtn, darkModeBtn);
                
                // Adicionar event listeners ao novo botão
                const btn = document.getElementById('darkModeToggle');
                if (btn) {
                    // Click event - funciona em desktop e mobile
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (window.toggleDarkMode) {
                            window.toggleDarkMode(e);
                        }
                        return false;
                    }, true); // Use capture phase to ensure it runs
                    
                    // Suporte touch para mobile
                    btn.addEventListener('touchend', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (window.toggleDarkMode) {
                            window.toggleDarkMode(e);
                        }
                        return false;
                    }, { passive: false });
                }
            }
        });
        
        // Dark mode toggle - garantir funcionamento no desktop
        // Handler adicional que funciona independente de quando o DOM carrega
        (function() {
            function attachDarkModeHandler() {
                const btn = document.getElementById('darkModeToggle');
                if (btn && window.toggleDarkMode) {
                    // Usar onclick direto para garantir funcionamento no desktop
                    btn.onclick = function(e) {
                        if (e) {
                            e.preventDefault();
                            e.stopPropagation();
                        }
                        if (window.toggleDarkMode) {
                            window.toggleDarkMode(e);
                        }
                        return false;
                    };
                }
            }
            
            // Tentar várias vezes para garantir que funciona
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', attachDarkModeHandler);
            } else {
                attachDarkModeHandler();
            }
            
            // Tentar novamente após delay para garantir
            setTimeout(attachDarkModeHandler, 200);
            setTimeout(attachDarkModeHandler, 500);
        })();
        
        // Add smooth page transitions
        document.addEventListener('DOMContentLoaded', function() {
            // Fade in body
            document.body.style.opacity = '0';
            requestAnimationFrame(() => {
                document.body.style.transition = 'opacity 0.3s ease-out';
                document.body.style.opacity = '1';
            });
        });
        
        // Sistema de Modal de Confirmação Reutilizável
        window.showConfirmModal = function(options) {
            const {
                title = 'Confirmar Ação',
                message = 'Tem certeza que deseja realizar esta ação?',
                warning = 'Esta ação não pode ser desfeita.',
                confirmText = 'Confirmar',
                cancelText = 'Cancelar',
                confirmColor = 'red',
                onConfirm = null,
                onCancel = null
            } = options;
            
            // Criar modal se não existir
            let modal = document.getElementById('confirmModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'confirmModal';
                modal.className = 'fixed inset-0 z-50 hidden items-center justify-center';
                modal.style.cssText = 'backdrop-filter: blur(4px);';
                modal.innerHTML = `
                    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" id="confirmModalOverlay"></div>
                    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all scale-95 opacity-0" id="confirmModalContent">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full" id="confirmModalIcon">
                                <i class="fas fa-exclamation-triangle text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 text-center mb-2" id="confirmModalTitle"></h3>
                            <p class="text-gray-600 dark:text-gray-300 text-center mb-1" id="confirmModalMessage"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 text-center mb-6" id="confirmModalWarning"></p>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <button type="button" id="confirmModalCancelBtn" class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    <i class="fas fa-times mr-1.5"></i>
                                    <span id="confirmModalCancelText"></span>
                                </button>
                                <button type="button" id="confirmModalConfirmBtn" class="flex-1 px-3 py-2 text-white rounded-lg text-sm font-medium transition-colors shadow-md hover:shadow-lg">
                                    <i class="fas fa-check mr-1.5"></i>
                                    <span id="confirmModalConfirmText"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
            }
            
            // Atualizar conteúdo
            document.getElementById('confirmModalTitle').textContent = title;
            document.getElementById('confirmModalMessage').textContent = message;
            document.getElementById('confirmModalWarning').textContent = warning;
            document.getElementById('confirmModalCancelText').textContent = cancelText;
            document.getElementById('confirmModalConfirmText').textContent = confirmText;
            
            // Atualizar cores baseado no confirmColor
            const confirmBtn = document.getElementById('confirmModalConfirmBtn');
            const iconDiv = document.getElementById('confirmModalIcon');
            const icon = iconDiv.querySelector('i');
            
            // Mapear cores
            const colorMap = {
                red: {
                    iconBg: 'bg-red-100 dark:bg-red-900/30',
                    iconText: 'text-red-600 dark:text-red-400',
                    btnBg: 'bg-red-600 hover:bg-red-700'
                },
                blue: {
                    iconBg: 'bg-blue-100 dark:bg-blue-900/30',
                    iconText: 'text-blue-600 dark:text-blue-400',
                    btnBg: 'bg-blue-600 hover:bg-blue-700'
                },
                green: {
                    iconBg: 'bg-green-100 dark:bg-green-900/30',
                    iconText: 'text-green-600 dark:text-green-400',
                    btnBg: 'bg-green-600 hover:bg-green-700'
                },
                yellow: {
                    iconBg: 'bg-yellow-100 dark:bg-yellow-900/30',
                    iconText: 'text-yellow-600 dark:text-yellow-400',
                    btnBg: 'bg-yellow-600 hover:bg-yellow-700'
                }
            };
            
            const colors = colorMap[confirmColor] || colorMap.red;
            iconDiv.className = `flex items-center justify-center w-16 h-16 mx-auto mb-4 ${colors.iconBg} rounded-full`;
            icon.className = `fas fa-exclamation-triangle ${colors.iconText} text-3xl`;
            confirmBtn.className = `flex-1 px-3 py-2 ${colors.btnBg} text-white rounded-lg text-sm font-medium transition-colors shadow-md hover:shadow-lg`;
            
            // Mostrar modal
            const modalContent = document.getElementById('confirmModalContent');
            modal.classList.add('show');
            modal.style.display = 'flex';
            modalContent.style.opacity = '1';
            modalContent.style.transform = 'scale(1)';
            document.body.style.overflow = 'hidden';
            
            // Event listeners
            const overlay = document.getElementById('confirmModalOverlay');
            const cancelBtn = document.getElementById('confirmModalCancelBtn');
            const confirmBtnEl = document.getElementById('confirmModalConfirmBtn');
            
            const closeModal = () => {
                modal.classList.remove('show');
                modalContent.style.opacity = '0';
                modalContent.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
                document.body.style.overflow = '';
                if (onCancel) onCancel();
            };
            
            // Remover listeners antigos e adicionar novos
            const newOverlay = overlay.cloneNode(true);
            overlay.parentNode.replaceChild(newOverlay, overlay);
            const newCancelBtn = cancelBtn.cloneNode(true);
            cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
            const newConfirmBtn = confirmBtnEl.cloneNode(true);
            confirmBtnEl.parentNode.replaceChild(newConfirmBtn, confirmBtnEl);
            
            // Adicionar novos listeners
            newOverlay.addEventListener('click', closeModal);
            newCancelBtn.addEventListener('click', closeModal);
            newConfirmBtn.addEventListener('click', () => {
                closeModal();
                if (onConfirm) onConfirm();
            });
            
            // Fechar com ESC
            const escHandler = (e) => {
                if (e.key === 'Escape' && modal.classList.contains('show')) {
                    closeModal();
                    document.removeEventListener('keydown', escHandler);
                }
            };
            document.addEventListener('keydown', escHandler);
        };
        
        // Adicionar animação do modal
        const modalStyle = document.createElement('style');
        modalStyle.textContent = `
            @keyframes modalFadeIn {
                from {
                    opacity: 0;
                    transform: scale(0.95);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }
            #confirmModal.show #confirmModalContent {
                animation: modalFadeIn 0.3s ease-out forwards;
            }
            #confirmModal.show {
                display: flex !important;
            }
        `;
        document.head.appendChild(modalStyle);
    </script>
    
    <!-- Language Selector Script -->
    <script>
    (function() {
        function initLanguageSelectors() {
            const selectors = document.querySelectorAll('.language-selector-element');
            
            selectors.forEach(function(selector) {
                // Remover listener anterior se existir para evitar duplicação
                const newSelector = selector.cloneNode(true);
                selector.parentNode.replaceChild(newSelector, selector);
                
                // Adicionar listener
                newSelector.addEventListener('change', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const locale = this.value;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    
                    if (!csrfToken) {
                        console.error('CSRF token não encontrado');
                        return;
                    }
                    
                    // Trocar idioma
                    fetch('/change-locale', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ locale: locale })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Resposta não OK');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Recarregar página com o novo idioma
                            window.location.reload();
                        } else {
                            console.error('Erro ao trocar idioma:', data);
                            alert('Erro ao trocar idioma. Tente novamente.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao trocar idioma. Tente novamente.');
                    });
                });
                
                // Adicionar eventos touch para melhor suporte mobile
                newSelector.addEventListener('touchstart', function(e) {
                    e.stopPropagation();
                }, { passive: true });
                
                newSelector.addEventListener('touchend', function(e) {
                    e.stopPropagation();
                    // Forçar abertura do dropdown no mobile
                    if (window.innerWidth < 1024) {
                        this.focus();
                        this.click();
                    }
                }, { passive: true });
            });
        }
        
        // Executar quando DOM estiver pronto
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initLanguageSelectors);
        } else {
            initLanguageSelectors();
        }
    })();
    </script>
    
    @yield('scripts')
</body>
</html>

