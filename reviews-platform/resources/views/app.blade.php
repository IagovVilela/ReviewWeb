<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('app.name') }} - {{ __('landing.hero_title') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/home-entry.jsx'])
    
    <!-- Favicon - Logo da Plataforma -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    
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
            color: #111827;
            line-height: 1.6;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Dark Mode */
        .dark body {
            background-color: #111827;
            color: #f9fafb;
        }
        
        /* Header */
        .header {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1.5rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        
        .dark .header {
            background: #1f2937;
            border-bottom-color: #374151;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .logo img {
            height: 40px;
            width: auto;
        }
        
        .logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1f2937;
            transition: color 0.3s ease;
        }
        
        .dark .logo-text {
            color: #f9fafb;
        }
        
        /* Language Selector */
        .language-selector {
            position: relative;
            margin-right: 1rem;
        }
        
        .language-selector select {
            appearance: none;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem 2rem 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .dark .language-selector select {
            background: #1f2937;
            border-color: #374151;
            color: #d1d5db;
        }
        
        .language-selector select:hover {
            border-color: #8b5cf6;
            background: #f9fafb;
        }
        
        .dark .language-selector select:hover {
            background: #374151;
        }
        
        .language-selector select:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }
        
        .language-selector::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #6b7280;
            font-size: 0.75rem;
            transition: color 0.3s ease;
        }
        
        .dark .language-selector::after {
            color: #9ca3af;
        }
        
        .btn-login {
            background: #8b5cf6;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-login:hover {
            background: #7c3aed;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.25);
        }
        
        /* Dark Mode Toggle Button */
        .dark-mode-toggle {
            background: transparent;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #374151;
        }
        
        .dark .dark-mode-toggle {
            border-color: #374151;
            color: #d1d5db;
        }
        
        .dark-mode-toggle:hover {
            background: #f9fafb;
            border-color: #8b5cf6;
            color: #8b5cf6;
        }
        
        .dark .dark-mode-toggle:hover {
            background: #374151;
        }
        
        .dark-mode-toggle i {
            font-size: 1rem;
        }
        
        /* Hero Section */
        .hero {
            background: white;
            padding: 140px 2rem 80px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        
        .dark .hero {
            background: #1f2937;
            border-bottom-color: #374151;
        }
        
        .hero-content {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            color: #111827;
            animation: fadeInUp 0.8s ease;
            transition: color 0.3s ease;
        }
        
        .dark .hero h1 {
            color: #f9fafb;
        }
        
        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            color: #4b5563;
            animation: fadeInUp 0.8s ease 0.2s both;
            transition: color 0.3s ease;
        }
        
        .dark .hero p {
            color: #d1d5db;
        }
        
        /* Prize Draw Section */
        .prize-draw {
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .prize-draw::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.3;
            }
        }
        
        .prize-draw-container {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .prize-draw-content {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 3rem 2rem;
            border-radius: 1.5rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .prize-draw-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        
        .prize-draw-icon i {
            font-size: 2.5rem;
            color: #ffd700;
            animation: bounce 2s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        .prize-draw h2 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        
        .prize-amount {
            font-size: 3.5rem;
            font-weight: 900;
            margin: 1rem 0;
            color: #ffd700;
            text-shadow: 0 4px 20px rgba(255, 215, 0, 0.5);
            line-height: 1;
        }
        
        .prize-description {
            font-size: 1.25rem;
            margin: 1.5rem 0;
            opacity: 0.95;
            line-height: 1.6;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .prize-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            margin-top: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-weight: 600;
        }
        
        .prize-badge i {
            color: #ffd700;
        }
        
        @media (max-width: 768px) {
            .prize-draw {
                padding: 3rem 1.5rem;
            }
            
            .prize-draw-content {
                padding: 2rem 1.5rem;
            }
            
            .prize-draw h2 {
                font-size: 1.5rem;
            }
            
            .prize-amount {
                font-size: 2.5rem;
            }
            
            .prize-description {
                font-size: 1.1rem;
            }
        }
        
        /* Contact Form Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .modal-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
        }
        
        .modal-content {
            background: white;
            border-radius: 1.5rem;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            position: relative;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        
        .dark .modal-content {
            background: #1f2937;
        }
        
        .modal-overlay.active .modal-content {
            transform: scale(1);
        }
        
        .modal-header {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            padding: 2rem;
            border-radius: 1.5rem 1.5rem 0 0;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }
        
        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .modal-header h2 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        
        .modal-header p {
            font-size: 1rem;
            opacity: 0.95;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #374151;
        }
        
        .dark .form-label {
            color: #d1d5db;
        }
        
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            color: #111827;
        }
        
        .dark .form-input {
            background: #374151;
            border-color: #4b5563;
            color: #f9fafb;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }
        
        .form-input::placeholder {
            color: #9ca3af;
        }
        
        .dark .form-input::placeholder {
            color: #6b7280;
        }
        
        .btn-submit {
            width: 100%;
            background: #8b5cf6;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-submit:hover {
            background: #7c3aed;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
        }
        
        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .success-message {
            display: none;
            text-align: center;
            padding: 2rem;
        }
        
        .success-message.active {
            display: block;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .success-icon i {
            font-size: 2.5rem;
            color: white;
        }
        
        .success-message h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1rem;
        }
        
        .dark .success-message h3 {
            color: #f9fafb;
        }
        
        .success-message p {
            color: #6b7280;
            font-size: 1rem;
        }
        
        .dark .success-message p {
            color: #9ca3af;
        }
        
        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
            }
            
            .modal-header {
                padding: 1.5rem;
            }
            
            .modal-header h2 {
                font-size: 1.5rem;
            }
            
            .modal-body {
                padding: 1.5rem;
            }
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease 0.4s both;
        }
        
        .btn-primary {
            background: #8b5cf6;
            color: white;
            padding: 1rem 3rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            background: #7c3aed;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.25);
        }
        
        .btn-secondary {
            background: white;
            color: #6b7280;
            padding: 1rem 3rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 2px solid #e5e7eb;
        }
        
        .dark .btn-secondary {
            background: #1f2937;
            color: #d1d5db;
            border-color: #374151;
        }
        
        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #8b5cf6;
            color: #8b5cf6;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }
        
        .dark .btn-secondary:hover {
            background: #374151;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        
        /* Stats Section */
        .stats {
            padding: 4rem 2rem;
            background: #f9fafb;
            transition: background-color 0.3s ease;
        }
        
        .dark .stats {
            background: #111827;
        }
        
        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .dark .stat-card {
            background: #1f2937;
            border-color: #374151;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }
        
        .dark .stat-card:hover {
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.4);
        }
        
        .stat-info h3 {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }
        
        .dark .stat-info h3 {
            color: #9ca3af;
        }
        
        .stat-info p {
            font-size: 2rem;
            font-weight: 800;
            color: #8b5cf6;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            background: #ede9fe;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }
        
        .dark .stat-icon {
            background: rgba(139, 92, 246, 0.2);
        }
        
        .stat-icon i {
            font-size: 1.5rem;
            color: #8b5cf6;
        }
        
        /* Features Section */
        .features {
            padding: 6rem 2rem;
            background: white;
            transition: background-color 0.3s ease;
        }
        
        .dark .features {
            background: #1f2937;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 1rem;
            transition: color 0.3s ease;
        }
        
        .dark .section-title h2 {
            color: #f9fafb;
        }
        
        .section-title p {
            font-size: 1.2rem;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto;
            transition: color 0.3s ease;
        }
        
        .dark .section-title p {
            color: #d1d5db;
        }
        
        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
        }
        
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .dark .feature-card {
            background: #1f2937;
            border-color: #374151;
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            border-color: #8b5cf6;
        }
        
        .dark .feature-card:hover {
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.4);
        }
        
        .feature-icon {
            width: 56px;
            height: 56px;
            background: #ede9fe;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: background-color 0.3s ease;
        }
        
        .dark .feature-icon {
            background: rgba(139, 92, 246, 0.2);
        }
        
        .feature-icon i {
            font-size: 1.75rem;
            color: #8b5cf6;
        }
        
        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #111827;
            transition: color 0.3s ease;
        }
        
        .dark .feature-card h3 {
            color: #f9fafb;
        }
        
        .feature-card p {
            color: #4b5563;
            line-height: 1.7;
            transition: color 0.3s ease;
        }
        
        .dark .feature-card p {
            color: #d1d5db;
        }
        
        /* How It Works */
        .how-it-works {
            padding: 6rem 2rem;
            background: #f9fafb;
            transition: background-color 0.3s ease;
        }
        
        .dark .how-it-works {
            background: #111827;
        }
        
        .steps-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }
        
        .step {
            text-align: center;
            background: white;
            padding: 2.5rem 2rem;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .dark .step {
            background: #1f2937;
            border-color: #374151;
        }
        
        .step:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            border-color: #8b5cf6;
        }
        
        .dark .step:hover {
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.4);
        }
        
        .step-number {
            width: 64px;
            height: 64px;
            background: #8b5cf6;
            color: white;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            font-weight: 800;
            margin: 0 auto 1.5rem;
        }
        
        .step h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            font-weight: 700;
            color: #111827;
            transition: color 0.3s ease;
        }
        
        .dark .step h3 {
            color: #f9fafb;
        }
        
        .step p {
            color: #4b5563;
            line-height: 1.7;
            transition: color 0.3s ease;
        }
        
        .dark .step p {
            color: #d1d5db;
        }
        
        /* Benefits */
        .benefits {
            padding: 6rem 2rem;
            background: white;
            transition: background-color 0.3s ease;
        }
        
        .dark .benefits {
            background: #1f2937;
        }
        
        .benefits-grid {
            max-width: 1200px;
            margin: 3rem auto 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
        }
        
        .benefit-card {
            background: white;
            padding: 2rem;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .dark .benefit-card {
            background: #1f2937;
            border-color: #374151;
        }
        
        .benefit-card:hover {
            transform: translateX(8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            border-color: #8b5cf6;
        }
        
        .dark .benefit-card:hover {
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.4);
        }
        
        .benefit-icon {
            width: 48px;
            height: 48px;
            background: #ede9fe;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background-color 0.3s ease;
        }
        
        .dark .benefit-icon {
            background: rgba(139, 92, 246, 0.2);
        }
        
        .benefit-icon i {
            font-size: 1.5rem;
            color: #8b5cf6;
        }
        
        .benefit-content h4 {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #111827;
            transition: color 0.3s ease;
        }
        
        .dark .benefit-content h4 {
            color: #f9fafb;
        }
        
        .benefit-content p {
            color: #6b7280;
            line-height: 1.6;
            transition: color 0.3s ease;
        }
        
        .dark .benefit-content p {
            color: #d1d5db;
        }
        
        /* CTA Section */
        .cta {
            padding: 6rem 2rem;
            background: #8b5cf6;
            text-align: center;
            color: white;
        }
        
        .cta h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        
        .cta p {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            opacity: 0.95;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta .btn-primary {
            background: white;
            color: #8b5cf6;
        }
        
        .cta .btn-primary:hover {
            background: #f9fafb;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* React home island (premium refresh) */
        .home-modern {
            position: relative;
            overflow-x: clip;
        }

        .home-modern-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 0.5rem;
            position: relative;
            z-index: 1;
        }

        .home-modern-hero {
            position: relative;
            overflow: visible;
            padding: 154px 2rem 104px;
            border-bottom: 1px solid #e5e7eb;
            background:
                radial-gradient(920px 440px at 18% 12%, rgba(139, 92, 246, 0.14), transparent 68%),
                linear-gradient(180deg, #f2f0fc 0%, #eeebfb 100%);
        }

        .dark .home-modern-hero {
            background:
                radial-gradient(900px 420px at 10% 0%, rgba(139, 92, 246, 0.2), transparent 68%),
                radial-gradient(700px 320px at 95% 15%, rgba(59, 130, 246, 0.16), transparent 72%),
                linear-gradient(180deg, #0f172a 0%, #111827 100%);
            border-bottom-color: #374151;
        }

        .home-modern-hero-glow {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(115deg, rgba(255, 255, 255, 0.35), transparent 40%);
        }

        .dark .home-modern-hero-glow {
            background: linear-gradient(115deg, rgba(255, 255, 255, 0.08), transparent 40%);
        }

        .home-modern-hero h1 {
            font-size: 4rem;
            line-height: 1.03;
            font-weight: 900;
            color: #111827;
            margin-bottom: 1.25rem;
            letter-spacing: -0.03em;
        }

        .dark .home-modern-hero h1 {
            color: #f9fafb;
        }

        .home-modern-hero p {
            font-size: 1.1rem;
            line-height: 1.85;
            max-width: 560px;
            margin: 0 0 2.25rem;
            color: #4b5563;
        }

        .dark .home-modern-hero p {
            color: #d1d5db;
        }

        .home-modern-hero-grid {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 3rem;
            align-items: center;
        }

        .home-modern-hero-copy {
            text-align: left;
        }

        .home-modern-kicker {
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            font-size: 0.7rem;
            font-weight: 700;
            color: #7c3aed;
            margin-bottom: 1rem;
        }

        .home-modern-hero .hero-buttons {
            animation: none;
            margin-top: 0.25rem;
            justify-content: flex-start;
        }

        .home-modern-hero .btn-primary {
            border: 0;
            cursor: pointer;
            padding: 0.92rem 1.8rem;
            font-size: 1rem;
        }

        .home-modern-hero .btn-secondary {
            padding: 0.92rem 1.8rem;
            font-size: 1rem;
        }

        .home-modern-inline-stats {
            margin-top: 2.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid #d7d3ed;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            max-width: 560px;
        }

        .home-modern-inline-stat strong {
            display: block;
            font-size: 1.7rem;
            line-height: 1.1;
            color: #111827;
        }

        .home-modern-inline-stat span {
            display: block;
            margin-top: 0.35rem;
            font-size: 0.78rem;
            color: #6b7280;
        }

        .dark .home-modern-inline-stats {
            border-top-color: #334155;
        }

        .dark .home-modern-inline-stat strong {
            color: #f9fafb;
        }

        .dark .home-modern-inline-stat span {
            color: #94a3b8;
        }

        .home-modern-preview {
            width: 100%;
            padding-right: 0.2rem;
        }

        .preview-main-card {
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 1rem;
            box-shadow: 0 18px 48px rgba(55, 65, 81, 0.12);
            backdrop-filter: blur(6px);
            padding: 1.25rem 1.15rem;
        }

        .preview-label {
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-size: 0.62rem;
            color: #94a3b8;
            font-weight: 700;
            margin-bottom: 0.85rem;
        }

        .preview-score {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.85rem;
        }

        .preview-score span {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
        }

        .preview-score small {
            color: #f59e0b;
            letter-spacing: 0.12em;
            font-size: 0.78rem;
        }

        .preview-bars div {
            display: grid;
            grid-template-columns: 14px 1fr;
            align-items: center;
            gap: 0.6rem;
            margin: 0.3rem 0;
        }

        .preview-bars span {
            font-size: 0.68rem;
            color: #94a3b8;
        }

        .preview-bars i {
            display: block;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, #8b5cf6 0%, #a78bfa 100%);
        }

        .preview-mini-grid {
            margin-top: 0.85rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.65rem;
        }

        .preview-mini-card {
            background: rgba(255, 255, 255, 0.82);
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 0.85rem;
            padding: 0.85rem 0.8rem;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
        }

        .preview-mini-card i {
            width: 24px;
            height: 24px;
            border-radius: 0.55rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #7c3aed;
            background: #ede9fe;
            font-size: 0.72rem;
        }

        .preview-mini-card strong {
            display: block;
            font-size: 0.72rem;
            color: #0f172a;
            margin-bottom: 0.2rem;
        }

        .preview-mini-card span {
            display: block;
            font-size: 0.66rem;
            color: #94a3b8;
        }

        .dark .preview-main-card,
        .dark .preview-mini-card {
            background: rgba(17, 24, 39, 0.82);
            border-color: rgba(100, 116, 139, 0.34);
        }

        .dark .preview-score span,
        .dark .preview-mini-card strong {
            color: #f8fafc;
        }

        .dark .preview-label,
        .dark .preview-bars span,
        .dark .preview-mini-card span {
            color: #94a3b8;
        }

        .home-modern .home-modern-card {
            border-radius: 1rem;
            border-color: rgba(139, 92, 246, 0.12);
            box-shadow: 0 10px 30px rgba(17, 24, 39, 0.05);
            backdrop-filter: blur(2px);
        }

        .dark .home-modern .home-modern-card {
            border-color: rgba(139, 92, 246, 0.28);
            box-shadow: 0 14px 32px rgba(0, 0, 0, 0.36);
        }

        .home-modern-stats {
            border-top: 1px solid #e5e7eb;
        }

        .dark .home-modern-stats {
            border-top-color: #374151;
        }

        .home-modern-features,
        .home-modern-benefits {
            position: relative;
        }

        .home-modern-cta {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(500px 300px at 20% 20%, rgba(255, 255, 255, 0.2), transparent 70%),
                linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        }

        .home-modern-cta .btn-primary {
            background: #ffffff;
            color: #6d28d9;
            font-size: 1.15rem;
            padding: 1.05rem 3rem;
            border: none;
            cursor: pointer;
        }

        .home-modern-cta .btn-primary:hover {
            color: #5b21b6;
            background: #f5f3ff;
        }

        @media (prefers-reduced-motion: reduce) {
            .home-modern .home-modern-card,
            .home-modern .btn-primary,
            .home-modern .btn-secondary {
                transition: none !important;
            }
        }

        @media (max-width: 768px) {
            .home-modern-hero {
                padding: 132px 1rem 82px;
            }

            .home-modern-hero-grid {
                grid-template-columns: 1fr;
                gap: 1.8rem;
            }

            .home-modern-hero-copy {
                text-align: center;
            }

            .home-modern-hero .hero-buttons {
                justify-content: center;
            }

            .home-modern-hero h1 {
                font-size: 2.55rem;
            }

            .home-modern-hero p {
                font-size: 1.05rem;
                margin-bottom: 1.5rem;
                margin-left: auto;
                margin-right: auto;
            }

            .home-modern-inline-stats {
                grid-template-columns: 1fr;
                max-width: 100%;
                text-align: left;
            }

            .preview-main-card {
                padding: 1rem;
            }

            .home-modern-preview {
                padding-right: 0;
            }
        }
        
        /* Footer */
        .footer {
            background: #111827;
            color: white;
            padding: 3rem 2rem 1.5rem;
            transition: background-color 0.3s ease;
        }
        
        .dark .footer {
            background: #0f172a;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 2rem;
        }
        
        .footer-section h4 {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .footer-section h4 i {
            color: #8b5cf6;
        }
        
        .footer-section p, .footer-section a {
            color: #9ca3af;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }
        
        .footer-section a:hover {
            color: #8b5cf6;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #374151;
            color: #9ca3af;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .hero-buttons {
                flex-direction: column;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .cta h2 {
                font-size: 2rem;
            }
            
            .header-content {
                padding: 0 1rem;
                flex-wrap: wrap;
                gap: 1rem;
            }
            
            .logo {
                flex: 1 1 100%;
                justify-content: center;
            }
            
            .logo img {
                height: 32px;
            }
            
            .logo-text {
                font-size: 1.25rem;
            }
            
            .header-content > div {
                flex: 1 1 100%;
                justify-content: center;
            }
            
            .language-selector {
                margin-right: 0.5rem;
            }
            
            .language-selector select {
                padding: 0.5rem 1.5rem 0.5rem 0.75rem;
                font-size: 0.8125rem;
            }
            
            .btn-login {
                padding: 0.625rem 1.5rem;
                font-size: 0.875rem;
            }
            
            .features-grid,
            .benefits-grid,
            .steps-container {
                grid-template-columns: 1fr;
            }
            
            .benefit-card {
                flex-direction: column;
                text-align: center;
            }
        }
        
        /* Scroll indicator */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.3);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 92, 246, 0.5);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <img src="{{ asset('assets/images/lopgosDASHBOARD.png') }}" alt="{{ __('app.name') }}">
                <span class="logo-text">{{ __('app.name') }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div class="language-selector">
                    <select id="languageSelector">
                        <option value="pt_BR" {{ app()->getLocale() === 'pt_BR' ? 'selected' : '' }}>🇧🇷 PT</option>
                        <option value="en_US" {{ app()->getLocale() === 'en_US' ? 'selected' : '' }}>🇬🇧 EN</option>
                    </select>
                </div>
                <button id="darkModeToggle" onclick="toggleDarkMode()" class="dark-mode-toggle" title="Toggle Dark Mode">
                    <i id="darkModeIcon" class="fas fa-moon"></i>
                </button>
                <a href="/login" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    {{ __('landing.access_panel') }}
                </a>
            </div>
        </div>
    </header>

    @php
        $homeTranslations = [
            'hero_title' => __('landing.hero_title'),
            'hero_description' => __('landing.hero_description'),
            'hero_kicker' => __('landing.hero_kicker'),
            'hero_control_total' => __('landing.hero_control_total'),
            'hero_preview_title' => __('landing.hero_preview_title'),
            'hero_preview_filter_title' => __('landing.hero_preview_filter_title'),
            'hero_preview_filter_desc' => __('landing.hero_preview_filter_desc'),
            'hero_preview_alert_title' => __('landing.hero_preview_alert_title'),
            'hero_preview_alert_desc' => __('landing.hero_preview_alert_desc'),
            'start_now' => __('landing.start_now'),
            'learn_more' => __('landing.learn_more'),
            'features_title' => __('landing.features_title'),
            'features_description' => __('landing.features_description'),
            'feature_redirect_title' => __('landing.feature_redirect_title'),
            'feature_redirect_desc' => __('landing.feature_redirect_desc'),
            'feature_protection_title' => __('landing.feature_protection_title'),
            'feature_protection_desc' => __('landing.feature_protection_desc'),
            'feature_dashboard_title' => __('landing.feature_dashboard_title'),
            'feature_dashboard_desc' => __('landing.feature_dashboard_desc'),
            'feature_notifications_title' => __('landing.feature_notifications_title'),
            'feature_notifications_desc' => __('landing.feature_notifications_desc'),
            'feature_contacts_title' => __('landing.feature_contacts_title'),
            'feature_contacts_desc' => __('landing.feature_contacts_desc'),
            'feature_multilang_title' => __('landing.feature_multilang_title'),
            'feature_multilang_desc' => __('landing.feature_multilang_desc'),
            'feature_export_title' => __('landing.feature_export_title'),
            'feature_export_desc' => __('landing.feature_export_desc'),
            'feature_customization_title' => __('landing.feature_customization_title'),
            'feature_customization_desc' => __('landing.feature_customization_desc'),
            'feature_darkmode_title' => __('landing.feature_darkmode_title'),
            'feature_darkmode_desc' => __('landing.feature_darkmode_desc'),
            'benefits_title' => __('landing.benefits_title'),
            'benefits_description' => __('landing.benefits_description'),
            'benefit1_title' => __('landing.benefit1_title'),
            'benefit1_desc' => __('landing.benefit1_desc'),
            'benefit2_title' => __('landing.benefit2_title'),
            'benefit2_desc' => __('landing.benefit2_desc'),
            'benefit3_title' => __('landing.benefit3_title'),
            'benefit3_desc' => __('landing.benefit3_desc'),
            'benefit4_title' => __('landing.benefit4_title'),
            'benefit4_desc' => __('landing.benefit4_desc'),
            'benefit5_title' => __('landing.benefit5_title'),
            'benefit5_desc' => __('landing.benefit5_desc'),
            'benefit6_title' => __('landing.benefit6_title'),
            'benefit6_desc' => __('landing.benefit6_desc'),
            'cta_title' => __('landing.cta_title'),
            'cta_description' => __('landing.cta_description'),
            'start_free' => __('landing.start_free'),
        ];

        $homeStats = [
            ['label' => __('landing.reviews_processed'), 'value' => '+10k', 'icon' => 'fas fa-star'],
            ['label' => __('landing.more_google_reviews'), 'value' => __('landing.hero_stat_growth_value'), 'icon' => 'fas fa-chart-line'],
        ];
    @endphp
    <div
        id="homeModernRoot"
        data-translations='@json($homeTranslations)'
        data-stats='@json($homeStats)'
    ></div>

    <!-- Prize Draw Section -->
    <section class="prize-draw">
        <div class="prize-draw-container">
            <div class="prize-draw-content fade-in">
                <div class="prize-draw-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h2>{{ __('landing.prize_draw_title') }}</h2>
                <p class="prize-amount">R$ 10.000,00</p>
                <p class="prize-description">{{ __('landing.prize_draw_description') }}</p>
                <div class="prize-badge">
                    <i class="fas fa-gift"></i>
                    <span>{{ __('landing.prize_draw_badge') }}</span>
                </div>
            </div>
        </div>
    </section>


    <!-- How It Works -->
    <section class="how-it-works" id="como-funciona">
        <div class="section-title">
            <h2>{{ __('landing.how_title') }}</h2>
            <p>{{ __('landing.how_description') }}</p>
        </div>
        <div class="steps-container">
            <div class="step fade-in">
                <div class="step-number">1</div>
                <h3>{{ __('landing.step1_title') }}</h3>
                <p>{{ __('landing.step1_desc') }}</p>
            </div>
            <div class="step fade-in">
                <div class="step-number">2</div>
                <h3>{{ __('landing.step2_title') }}</h3>
                <p>{{ __('landing.step2_desc') }}</p>
            </div>
            <div class="step fade-in">
                <div class="step-number">3</div>
                <h3>{{ __('landing.step3_title') }}</h3>
                <p>{{ __('landing.step3_desc') }}</p>
            </div>
            <div class="step fade-in">
                <div class="step-number">4</div>
                <h3>{{ __('landing.step4_title') }}</h3>
                <p>{{ __('landing.step4_desc') }}</p>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>
                    <i class="fas fa-star"></i>
                    {{ __('app.name') }}
                </h4>
                <p>{{ __('landing.footer_description') }}</p>
                <p style="margin-top: 1rem;">© 2025 {{ __('app.name') }}</p>
                <p>{{ __('landing.all_rights') }}</p>
            </div>
            <div class="footer-section">
                <h4>
                    <i class="fas fa-box"></i>
                    {{ __('landing.product') }}
                </h4>
                <a href="#como-funciona">{{ __('landing.how_works') }}</a>
                <a href="/login">{{ __('landing.control_panel') }}</a>
                <a href="/login">{{ __('landing.create_account') }}</a>
                <a href="#features">{{ __('landing.features') }}</a>
            </div>
            <div class="footer-section">
                <h4>
                    <i class="fas fa-book"></i>
                    {{ __('landing.resources') }}
                </h4>
                <a href="#">{{ __('landing.documentation') }}</a>
                <a href="#">{{ __('landing.help_center') }}</a>
                <a href="#">{{ __('landing.faq') }}</a>
                <a href="#">{{ __('landing.tutorials') }}</a>
            </div>
            <div class="footer-section">
                <h4>
                    <i class="fas fa-envelope"></i>
                    {{ __('landing.contact') }}
                </h4>
                <p><i class="fas fa-at"></i> contato@reviewsplatform.com</p>
                <p><i class="fas fa-phone"></i> (11) 9 9999-9999</p>
                <p><i class="fas fa-headset"></i> {{ __('landing.technical_support') }}</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>{{ __('landing.developed_with') }} <i class="fas fa-heart" style="color: #8b5cf6;"></i> {{ __('landing.by') }} Iago Vilela & Mateus Bittencourt</p>
        </div>
    </footer>

    <!-- Contact Form Modal -->
    <div id="contactModal" class="modal-overlay" onclick="closeModalOnOutsideClick(event)">
        <div class="modal-content">
            <div class="modal-header">
                <button class="modal-close" onclick="closeContactModal()">
                    <i class="fas fa-times"></i>
                </button>
                <h2>{{ __('landing.contact_form_title') }}</h2>
                <p>{{ __('landing.contact_form_subtitle') }}</p>
            </div>
            <div class="modal-body">
                <form id="contactForm" onsubmit="handleContactSubmit(event)">
                    <div class="form-group">
                        <label class="form-label" for="contactName">
                            {{ __('landing.contact_name') }}
                        </label>
                        <input 
                            type="text" 
                            id="contactName" 
                            name="contact_name" 
                            class="form-input" 
                            placeholder="{{ __('landing.contact_name_placeholder') }}"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="companyName">
                            {{ __('landing.company_name') }}
                        </label>
                        <input 
                            type="text" 
                            id="companyName" 
                            name="company_name" 
                            class="form-input" 
                            placeholder="{{ __('landing.company_name_placeholder') }}"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="email">
                            {{ __('landing.email') }}
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            placeholder="{{ __('landing.email_placeholder') }}"
                            required
                        >
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="whatsapp">
                            {{ __('landing.whatsapp') }}
                        </label>
                        <input 
                            type="tel" 
                            id="whatsapp" 
                            name="whatsapp" 
                            class="form-input" 
                            placeholder="{{ __('landing.whatsapp_placeholder') }}"
                            required
                        >
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        {{ __('landing.submit_button') }}
                    </button>
                </form>
                
                <div id="successMessage" class="success-message">
                    <div class="success-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <h3>{{ __('landing.success_title') }}</h3>
                    <p>{{ __('landing.success_message') }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Force favicon reload
        (function() {
            const faviconUrl = '{{ asset("assets/images/lopgosDASHBOARD.png") }}?v=' + Date.now();
            const link = document.createElement('link');
            link.rel = 'icon';
            link.type = 'image/png';
            link.href = faviconUrl;
            
            // Remove existing favicon links
            const existingLinks = document.querySelectorAll('link[rel="icon"]');
            existingLinks.forEach(l => l.remove());
            
            // Add new favicon
            document.head.appendChild(link);
        })();
        
        // Language Selector
        document.getElementById('languageSelector').addEventListener('change', function() {
            const locale = this.value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            fetch('/change-locale', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ locale: locale })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
        
        // Scroll Animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const headerOffset = 80;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Dark Mode Toggle Function
        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            const icon = document.getElementById('darkModeIcon');
            
            // Atualizar ícone
            if (isDark) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
            
            // Salvar preferência
            localStorage.setItem('darkMode', isDark ? 'true' : 'false');
        }
        
        // Inicializar ícone do dark mode
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const icon = document.getElementById('darkModeIcon');
            if (icon) {
                if (isDark) {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                } else {
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                }
            }
        });
        
        // Contact Modal Functions
        function openContactModal() {
            const modal = document.getElementById('contactModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        window.openContactModal = openContactModal;
        
        function closeContactModal() {
            const modal = document.getElementById('contactModal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
            
            // Reset form after closing
            setTimeout(() => {
                document.getElementById('contactForm').reset();
                document.getElementById('contactForm').style.display = 'block';
                document.getElementById('successMessage').classList.remove('active');
            }, 300);
        }
        window.closeContactModal = closeContactModal;
        
        function closeModalOnOutsideClick(event) {
            if (event.target.id === 'contactModal') {
                closeContactModal();
            }
        }
        
        // Handle form submission
        function handleContactSubmit(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = {
                contact_name: formData.get('contact_name'),
                company_name: formData.get('company_name'),
                email: formData.get('email'),
                whatsapp: formData.get('whatsapp')
            };
            
            // Disable submit button
            const submitBtn = event.target.querySelector('.btn-submit');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("landing.sending") }}';
            
            // Send to backend (you'll need to create this route)
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            fetch('/contact-trial', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                // Show success message
                document.getElementById('contactForm').style.display = 'none';
                document.getElementById('successMessage').classList.add('active');
                
                // Close modal after 3 seconds
                setTimeout(() => {
                    closeContactModal();
                }, 3000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __("landing.error_message") }}');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> {{ __("landing.submit_button") }}';
            });
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeContactModal();
            }
        });
    </script>
</body>
</html>
