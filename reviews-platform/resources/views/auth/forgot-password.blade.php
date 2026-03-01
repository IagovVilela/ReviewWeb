<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Recuperar Senha') }} - {{ __('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/modern-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.css') }}">
    
    <!-- Dark Mode Script - Previne Flash -->
    <script>
        (function() {
            const savedMode = localStorage.getItem('darkMode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedMode === 'true' || (savedMode === null && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        body {
            background-color: #f9fafb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            transition: background-color 0.3s ease;
        }
        
        .dark body {
            background-color: #111827;
        }
        
        .login-container {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            position: relative;
            transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        .dark .login-container {
            background: #1f2937;
            border-color: #374151;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .logo-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            margin-bottom: 1.5rem;
        }
        
        .logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .logo-container h1 {
            font-size: 1.875rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }
        
        .dark .logo-container h1 {
            color: #f9fafb;
        }
        
        .logo-container p {
            color: #6b7280;
            font-size: 0.9375rem;
            transition: color 0.3s ease;
        }
        
        .dark .logo-container p {
            color: #d1d5db;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .input-field {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            font-size: 0.9375rem;
            transition: all 0.3s ease;
            background: white;
            color: #111827;
        }
        
        .dark .input-field {
            background: #374151;
            border-color: #4b5563;
            color: #f9fafb;
        }
        
        .dark .input-field::placeholder {
            color: #9ca3af;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }
        
        .dark .input-field:focus {
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            transition: color 0.3s ease;
            pointer-events: none;
            z-index: 1;
        }
        
        .dark .input-icon {
            color: #6b7280;
        }
        
        .input-group:focus-within .input-icon {
            color: #8b5cf6;
        }
        
        .btn-login {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: #8b5cf6;
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }
        
        .btn-login:hover {
            background: #7c3aed;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.25);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
        }
        
        .dark .back-link {
            color: #9ca3af;
        }
        
        .back-link:hover {
            color: #8b5cf6;
        }
        
        .success-message {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 0.875rem 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }
        
        .dark .success-message {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            color: #34d399;
        }
        
        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 0.875rem 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }
        
        .dark .error-message {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: #f87171;
        }
        
        .info-text {
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 1rem;
            text-align: center;
            transition: color 0.3s ease;
        }
        
        .dark .info-text {
            color: #9ca3af;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .login-container {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container fade-in">
        <div class="logo-container">
            <div class="logo-wrapper">
                <img src="{{ asset('assets/images/lopgosDASHBOARD.png') }}" alt="{{ __('app.name') }}">
            </div>
            <h1>Recuperar Senha</h1>
            <p>Digite seu email para receber um código de recuperação</p>
        </div>
        
        @if(session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        
        @if($errors->any())
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif
        
        <form method="POST" action="{{ url(route('password.send-code', [], false)) }}">
            @csrf
            
            <div class="input-group">
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required
                    autocomplete="email"
                    autofocus
                    class="input-field"
                    placeholder="Digite seu email"
                >
                <i class="fas fa-envelope input-icon"></i>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-paper-plane"></i>
                <span>Enviar Código</span>
            </button>
        </form>
        
        <div style="text-align: center;">
            <a href="{{ route('login') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar para o login</span>
            </a>
        </div>
        
        <p class="info-text">
            <i class="fas fa-shield-alt"></i>
            Se o email existir em nosso sistema, você receberá um código de recuperação.
        </p>
    </div>
</body>
</html>

