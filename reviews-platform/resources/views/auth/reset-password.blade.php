<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Redefinir Senha - {{ __('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    <link rel="stylesheet" href="{{ asset('assets/css/modern-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.css') }}">
    
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
        }
        
        body {
            background-color: #f9fafb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
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
        }
        
        .dark .login-container {
            background: #1f2937;
            border-color: #374151;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .logo-container h1 {
            font-size: 1.875rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 0.5rem;
        }
        
        .dark .logo-container h1 {
            color: #f9fafb;
        }
        
        .logo-container p {
            color: #6b7280;
            font-size: 0.9375rem;
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
            background: white;
            color: #111827;
        }
        
        .dark .input-field {
            background: #374151;
            border-color: #4b5563;
            color: #f9fafb;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
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
            margin-top: 1.5rem;
        }
        
        .btn-login:hover {
            background: #7c3aed;
        }
        
        .success-message, .error-message {
            padding: 0.875rem 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }
        
        .success-message {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }
        
        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <h1>🔑 Nova Senha</h1>
            <p>Defina uma nova senha para sua conta</p>
        </div>
        
        @if(session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        @if($errors->any())
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif
        
        <form method="POST" action="{{ url(route('password.reset', [], false)) }}">
            @csrf
            
            <div class="input-group">
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    autocomplete="new-password"
                    autofocus
                    class="input-field"
                    placeholder="Nova senha (mínimo 6 caracteres)"
                    minlength="6"
                >
                <i class="fas fa-lock input-icon"></i>
            </div>
            
            <div class="input-group">
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required
                    autocomplete="new-password"
                    class="input-field"
                    placeholder="Confirmar nova senha"
                    minlength="6"
                >
                <i class="fas fa-lock input-icon"></i>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-check"></i>
                <span>Redefinir Senha</span>
            </button>
        </form>
    </div>
</body>
</html>

