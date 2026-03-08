<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verificar Código - {{ __('app.name') }}</title>
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
        
        .code-inputs {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            margin: 2rem 0;
        }
        
        .code-input {
            width: 60px;
            height: 70px;
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            background: white;
            color: #111827;
            transition: all 0.3s ease;
        }
        
        .dark .code-input {
            background: #374151;
            border-color: #4b5563;
            color: #f9fafb;
        }
        
        .code-input:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
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
        
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            text-decoration: none;
            font-size: 0.875rem;
            margin-top: 1.5rem;
        }
        
        .back-link:hover {
            color: #8b5cf6;
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
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <h1>🔐 Verificar Código</h1>
            <p>Digite o código de 6 dígitos enviado para seu email</p>
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
        
        <form method="POST" action="{{ url(route('password.verify-code', [], false)) }}" id="codeForm">
            @csrf
            
            <div class="code-inputs">
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" required>
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" required>
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" required>
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" required>
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" required>
                <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" required>
            </div>
            
            <input type="hidden" name="code" id="fullCode">
            
            <button type="submit" class="btn-login">
                <i class="fas fa-check"></i>
                <span>Verificar Código</span>
            </button>
        </form>
        
        <div style="text-align: center;">
            <a href="{{ route('password.forgot') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar</span>
            </a>
        </div>
    </div>
    
    <script>
        const inputs = document.querySelectorAll('.code-input');
        const form = document.getElementById('codeForm');
        const fullCodeInput = document.getElementById('fullCode');
        
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
            
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = e.clipboardData.getData('text').slice(0, 6);
                paste.split('').forEach((char, i) => {
                    if (inputs[i] && /[0-9]/.test(char)) {
                        inputs[i].value = char;
                    }
                });
                if (paste.length === 6) {
                    inputs[5].focus();
                } else {
                    inputs[paste.length].focus();
                }
            });
        });
        
        form.addEventListener('submit', (e) => {
            const code = Array.from(inputs).map(input => input.value).join('');
            fullCodeInput.value = code;
            
            if (code.length !== 6) {
                e.preventDefault();
                alert('Por favor, digite o código completo de 6 dígitos.');
            }
        });
        
        inputs[0].focus();
    </script>
</body>
</html>

