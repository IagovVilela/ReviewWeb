<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reviews Platform - {{ __('landing.hero_title') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon - Logo da Plataforma -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/lopgosDASHBOARD.png') }}?v=2">
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
        
        .language-selector select:hover {
            border-color: #8b5cf6;
            background: #f9fafb;
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
        
        /* Hero Section */
        .hero {
            background: white;
            padding: 140px 2rem 80px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
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
        }
        
        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            color: #4b5563;
            animation: fadeInUp 0.8s ease 0.2s both;
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
        
        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #8b5cf6;
            color: #8b5cf6;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }
        
        /* Stats Section */
        .stats {
            padding: 4rem 2rem;
            background: #f9fafb;
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
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }
        
        .stat-info h3 {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 0.5rem;
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
        }
        
        .stat-icon i {
            font-size: 1.5rem;
            color: #8b5cf6;
        }
        
        /* Features Section */
        .features {
            padding: 6rem 2rem;
            background: white;
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
        }
        
        .section-title p {
            font-size: 1.2rem;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto;
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
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            border-color: #8b5cf6;
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
        }
        
        .feature-card p {
            color: #4b5563;
            line-height: 1.7;
        }
        
        /* How It Works */
        .how-it-works {
            padding: 6rem 2rem;
            background: #f9fafb;
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
        
        .step:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            border-color: #8b5cf6;
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
        }
        
        .step p {
            color: #4b5563;
            line-height: 1.7;
        }
        
        /* Benefits */
        .benefits {
            padding: 6rem 2rem;
            background: white;
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
        
        .benefit-card:hover {
            transform: translateX(8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            border-color: #8b5cf6;
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
        }
        
        .benefit-content p {
            color: #6b7280;
            line-height: 1.6;
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
        
        /* Footer */
        .footer {
            background: #111827;
            color: white;
            padding: 3rem 2rem 1.5rem;
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
                <img src="{{ asset('assets/images/lopgosDASHBOARD.png') }}" alt="Reviews Platform">
                <span class="logo-text">Reviews Platform</span>
            </div>
            <div style="display: flex; align-items: center;">
                <a href="/login" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    {{ __('landing.access_panel') }}
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>{{ __('landing.hero_title') }}</h1>
            <p>{{ __('landing.hero_description') }}</p>
            <div class="hero-buttons">
                <a href="/login" class="btn-primary">
                    <i class="fas fa-rocket"></i>
                    {{ __('landing.start_now') }}
                </a>
                <a href="#como-funciona" class="btn-secondary">
                    <i class="fas fa-play-circle"></i>
                    {{ __('landing.learn_more') }}
                </a>
            </div>
                                </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-card fade-in">
                <div class="stat-info">
                    <h3>{{ __('landing.satisfaction_rate') }}</h3>
                    <p>95%</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
            </div>
            <div class="stat-card fade-in">
                <div class="stat-info">
                    <h3>{{ __('landing.reviews_processed') }}</h3>
                    <p>+10k</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
            </div>
            <div class="stat-card fade-in">
                <div class="stat-info">
                    <h3>{{ __('landing.more_google_reviews') }}</h3>
                    <p>3x</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-card fade-in">
                <div class="stat-info">
                    <h3>{{ __('landing.active_companies') }}</h3>
                    <p>500+</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="section-title">
            <h2>{{ __('landing.features_title') }}</h2>
            <p>{{ __('landing.features_description') }}</p>
        </div>
        <div class="features-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-crosshairs"></i>
                </div>
                <h3>{{ __('landing.feature_redirect_title') }}</h3>
                <p>{{ __('landing.feature_redirect_desc') }}</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>{{ __('landing.feature_protection_title') }}</h3>
                <p>{{ __('landing.feature_protection_desc') }}</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>{{ __('landing.feature_dashboard_title') }}</h3>
                <p>{{ __('landing.feature_dashboard_desc') }}</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h3>{{ __('landing.feature_notifications_title') }}</h3>
                <p>{{ __('landing.feature_notifications_desc') }}</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>{{ __('landing.feature_contacts_title') }}</h3>
                <p>{{ __('landing.feature_contacts_desc') }}</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h3>{{ __('landing.feature_multilang_title') }}</h3>
                <p>{{ __('landing.feature_multilang_desc') }}</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-download"></i>
                </div>
                <h3>{{ __('landing.feature_export_title') }}</h3>
                <p>{{ __('landing.feature_export_desc') }}</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <h3>{{ __('landing.feature_customization_title') }}</h3>
                <p>{{ __('landing.feature_customization_desc') }}</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">
                    <i class="fas fa-moon"></i>
                </div>
                <h3>{{ __('landing.feature_darkmode_title') }}</h3>
                <p>{{ __('landing.feature_darkmode_desc') }}</p>
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

    <!-- Benefits -->
    <section class="benefits">
        <div class="section-title">
            <h2>{{ __('landing.benefits_title') }}</h2>
            <p>{{ __('landing.benefits_description') }}</p>
        </div>
        <div class="benefits-grid">
            <div class="benefit-card fade-in">
                <div class="benefit-icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div class="benefit-content">
                    <h4>{{ __('landing.benefit1_title') }}</h4>
                    <p>{{ __('landing.benefit1_desc') }}</p>
                </div>
            </div>
            <div class="benefit-card fade-in">
                <div class="benefit-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="benefit-content">
                    <h4>{{ __('landing.benefit2_title') }}</h4>
                    <p>{{ __('landing.benefit2_desc') }}</p>
                </div>
            </div>
            <div class="benefit-card fade-in">
                <div class="benefit-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="benefit-content">
                    <h4>{{ __('landing.benefit3_title') }}</h4>
                    <p>{{ __('landing.benefit3_desc') }}</p>
                </div>
            </div>
            <div class="benefit-card fade-in">
                <div class="benefit-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="benefit-content">
                    <h4>{{ __('landing.benefit4_title') }}</h4>
                    <p>{{ __('landing.benefit4_desc') }}</p>
                </div>
            </div>
            <div class="benefit-card fade-in">
                <div class="benefit-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="benefit-content">
                    <h4>{{ __('landing.benefit5_title') }}</h4>
                    <p>{{ __('landing.benefit5_desc') }}</p>
                </div>
            </div>
            <div class="benefit-card fade-in">
                <div class="benefit-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <div class="benefit-content">
                    <h4>{{ __('landing.benefit6_title') }}</h4>
                    <p>{{ __('landing.benefit6_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <h2>{{ __('landing.cta_title') }}</h2>
        <p>{{ __('landing.cta_description') }}</p>
        <a href="/login" class="btn-primary" style="font-size: 1.2rem; padding: 1.2rem 3.5rem;">
            <i class="fas fa-star"></i>
            {{ __('landing.start_free') }}
        </a>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>
                    <i class="fas fa-star"></i>
                    Reviews Platform
                </h4>
                <p>{{ __('landing.footer_description') }}</p>
                <p style="margin-top: 1rem;">© 2025 Reviews Platform</p>
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
                <p><i class="fas fa-at"></i> contact@reviewsplatform.com</p>
                <p><i class="fas fa-phone"></i> (11) 9 9999-9999</p>
                <p><i class="fas fa-headset"></i> {{ __('landing.technical_support') }}</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>{{ __('landing.developed_with') }} <i class="fas fa-heart" style="color: #8b5cf6;"></i> {{ __('landing.by') }} Iago Vilela & Mateus Bittencourt</p>
        </div>
    </footer>

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
    </script>
</body>
</html>
