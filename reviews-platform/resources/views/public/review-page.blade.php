<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('public.how_was_experience') }} - {{ $company->name ?? 'Nossa Empresa' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .hero-gradient {
            background: #667eea; /* Fallback color if image fails to load */
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .review-card {
            transition: all 0.3s ease;
        }
        
        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .google-button {
            background: #4285F4;
            transition: all 0.3s ease;
        }
        
        .google-button:hover {
            background: #3367d6;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(66, 133, 244, 0.3);
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }
        
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        /* Logo enhancement styles */
        .company-logo {
            transition: all 0.3s ease;
            filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.15));
            border-radius: 20px;
            display: block;
            width: auto;
            height: auto;
        }
        
        .company-logo:hover {
            transform: scale(1.08);
            filter: drop-shadow(0 12px 24px rgba(0, 0, 0, 0.25));
        }
        
        .company-name-large {
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);
            letter-spacing: -0.02em;
            font-weight: 800;
        }
        
        /* Map icon clickable styles */
        .map-icon-clickable {
            transition: all 0.2s ease;
        }
        
        .map-icon-clickable:hover {
            transform: scale(1.1);
            filter: drop-shadow(0 2px 4px rgba(59, 130, 246, 0.3));
        }
        
        .slide-in-right {
            animation: slideInRight 0.8s ease-out;
        }
        
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        /* Mobile-specific styles */
        @media (max-width: 768px) {
            .hero-gradient {
                padding: 2rem 1rem;
            }
            
            .company-logo {
                max-width: 120px !important;
                max-height: 120px !important;
            }
            
            .company-name-large {
                font-size: 2.5rem;
                line-height: 1.2;
            }
            
            .floating-shapes .shape {
                display: none;
            }
            
            .review-card {
                margin: 0.5rem;
                padding: 1rem;
            }
            
            .form-input {
                font-size: 16px; /* Prevents zoom on iOS */
            }
            
            .btn-mobile {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
                min-height: 44px; /* iOS touch target */
            }
            
            .contact-info-mobile {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .contact-info-mobile .flex {
                margin-bottom: 0.5rem;
            }
        }
        
        @media (max-width: 480px) {
            .company-logo {
                max-width: 100px !important;
                max-height: 100px !important;
            }
            
            .company-name-large {
                font-size: 2rem;
            }
            
            .hero-gradient {
                padding: 1.5rem 0.5rem;
            }
            
            .review-card {
                margin: 0.25rem;
                padding: 0.75rem;
            }
        }
        
        /* Touch-friendly styles */
        .touch-target {
            min-height: 44px;
            min-width: 44px;
        }
        
        /* Prevent zoom on input focus (iOS) */
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            font-size: 16px;
        }
        
        /* Smooth scrolling for mobile */
        html {
            scroll-behavior: smooth;
        }
        
        /* Mobile navigation improvements */
        @media (max-width: 768px) {
            .mobile-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                border-top: 1px solid #e5e7eb;
                padding: 0.5rem;
                z-index: 50;
            }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Success Message -->
    @if(session('success'))
    <div class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg animate-slide-in">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <div>
                <h4 class="font-semibold">Sucesso!</h4>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif
    <!-- Hero Section -->
    <div class="relative overflow-hidden hero-gradient" @if($company->background_image_url) style="background-image: url('{{ $company->background_image_url }}'); background-size: cover; background-position: center;" @else style="background-image: url('{{ asset('assets/images/Backpadrao.jpg') }}'); background-size: cover; background-position: center;" @endif>
        <!-- Overlay para melhorar legibilidade quando há imagem de fundo -->
        @if($company->background_image_url)
        <div class="absolute inset-0 bg-black/30"></div>
        @else
        <div class="absolute inset-0 bg-black/20"></div>
        @endif
        
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        
        <div class="relative z-10 px-4 py-8 sm:py-16 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Company Logo/Name Section -->
                <div class="mb-6 sm:mb-8 fade-in">
                    @if($company->logo_url)
                        <!-- Logo exists - show logo without circle background -->
                        <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="company-logo mx-auto mb-4 sm:mb-8" loading="lazy" style="max-width: 160px; max-height: 160px; width: auto; height: auto; display: block;" onerror="this.style.display='none';">
                        <!-- Company Name below logo -->
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4 sm:mb-8 fade-in company-name-large">
                            {{ $company->name ?? 'Nossa Empresa' }}
                        </h1>
                    @else
                        <!-- No logo - show only company name, much larger -->
                        <h1 class="text-4xl sm:text-6xl md:text-8xl font-bold text-white mb-4 sm:mb-8 fade-in company-name-large">
                            {{ $company->name ?? 'Nossa Empresa' }}
                        </h1>
                    @endif
                </div>
                
                <!-- Prize Promotion Banner -->
                <div class="mb-4 sm:mb-6 fade-in pulse-animation">
                    <div class="inline-block bg-green-500 rounded-lg px-4 sm:px-5 py-2.5 sm:py-3 shadow-lg">
                        <div class="flex items-center justify-center gap-2 sm:gap-3">
                            <i class="fas fa-gift text-white text-sm sm:text-base"></i>
                            <p class="text-sm sm:text-base font-bold text-white">
                                {{ __('public.prize_hero_text') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-16">
        <!-- Review Form -->
        <div class="max-w-2xl mx-auto mb-16">
            <div class="bg-white rounded-2xl p-4 sm:p-8 shadow-lg border border-gray-100 fade-in">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 text-center">{{ __('public.how_was_experience') }}</h2>
                
                <!-- Rating Stars -->
                <div class="text-center mb-6 sm:mb-8">
                    <div class="flex justify-center space-x-1 sm:space-x-2 mb-4" id="ratingStars">
                        <i class="fas fa-star text-3xl sm:text-4xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors touch-target" data-rating="1"></i>
                        <i class="fas fa-star text-3xl sm:text-4xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors touch-target" data-rating="2"></i>
                        <i class="fas fa-star text-3xl sm:text-4xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors touch-target" data-rating="3"></i>
                        <i class="fas fa-star text-3xl sm:text-4xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors touch-target" data-rating="4"></i>
                        <i class="fas fa-star text-3xl sm:text-4xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors touch-target" data-rating="5"></i>
                    </div>
                </div>
                
                <!-- Review Form -->
                <form id="reviewForm" class="space-y-4 sm:space-y-6">
                    @csrf
                    <input type="hidden" id="rating" name="rating" value="">
                    <input type="hidden" id="company_token" name="company_token" value="{{ $token }}">
                    
                    <!-- WhatsApp - Always visible below stars -->
                    <div id="whatsappSection">
                        <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                            {{ __('public.whatsapp_number') }}
                        </label>
                        <input 
                            type="tel" 
                            id="whatsapp" 
                            name="whatsapp"
                            required
                            class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent form-input"
                            placeholder="{{ __('public.whatsapp_placeholder') }}"
                            maxlength="15"
                        >
                        <p class="text-xs text-gray-500 mt-1 italic">{{ __('public.competition_internal_use') }}</p>
                        <p class="text-sm text-gray-600 mt-3 text-center font-medium hidden" id="confirmText"></p>
                    </div>
                    
                    <!-- Comment - Only shown for negative reviews -->
                    <div id="commentSection" class="hidden">
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-comment text-blue-500 mr-2"></i>
                            {{ __('public.comment_optional') }}
                        </label>
                        <textarea 
                            id="comment" 
                            name="comment"
                            rows="4"
                            class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent form-input resize-none"
                            placeholder="{{ __('public.comment_placeholder') }}"
                        ></textarea>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submitBtn"
                        disabled
                        class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 sm:py-4 rounded-xl font-semibold text-base sm:text-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 btn-mobile touch-target hidden"
                    >
                        <span id="submitBtnText">{{ __('public.send_review') }}</span>
                    </button>
                </form>
                
                <!-- Loading State -->
                <div id="loadingState" class="hidden text-center py-8">
                    <div class="bg-green-100 border border-green-300 rounded-lg p-4 mb-4" id="loadingStateContent">
                        <h5 class="text-lg font-semibold text-green-800 mb-2" id="loadingTitle">{{ __('public.redirecting_to_google') }}</h5>
                        <p class="text-green-700 mb-3" id="loadingDescription">{{ __('public.redirecting_google_desc') }}</p>
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-2">
                            <i class="fas fa-spinner fa-spin text-green-600 text-2xl"></i>
                        </div>
                        <p class="text-green-600 text-sm" id="loadingCountdown">{{ __('public.redirecting_in_seconds') }}</p>
                    </div>
                    <!-- Generic loading state for negative reviews -->
                    <div class="bg-blue-100 border border-blue-300 rounded-lg p-4 mb-4 hidden" id="genericLoadingState">
                        <h5 class="text-lg font-semibold text-blue-800 mb-2">{{ __('public.processing_review') }}</h5>
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-2">
                            <i class="fas fa-spinner fa-spin text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Success State -->
                <div id="successState" class="hidden text-center py-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                        <i class="fas fa-check text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ __('public.review_sent') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('public.thanks_for_feedback') }}</p>
                    <div id="nextAction" class="mt-6">
                        <!-- Content will be dynamically inserted here -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8 sm:mt-16 fade-in">
            <p class="text-gray-500 text-xs sm:text-sm">
                {{ __('public.powered_by') }}
            </p>
        </div>
    </div>
    
    <script>
        // Translations for public review page
        const translations = {
            pt_BR: {
                rating_1: 'Péssimo',
                rating_2: 'Ruim',
                rating_3: 'Regular',
                rating_4: 'Bom',
                rating_5: 'Excelente',
                redirecting_to_google: 'Redirecionando para o Google...',
                redirecting_google_desc: 'Por favor, complete sua avaliação pública no Google para ajudar outros a nos escolherem.',
                redirecting_in_seconds: 'Redirecionando em 2 segundos...',
                thanks_for_negative_feedback: 'Obrigado pelo feedback!',
                how_can_we_improve: 'O que podemos melhorar?',
                feedback_placeholder: 'Conte-nos o que aconteceu para que possamos melhorar nosso atendimento...',
                how_prefer_contact: 'Como você prefere ser contatado?',
                contact_whatsapp: 'WhatsApp (mesmo número informado)',
                contact_email: 'E-mail',
                contact_phone: 'Telefone',
                contact_no: 'Não desejo ser contatado',
                contact_detail_label: 'E-mail ou Telefone',
                contact_detail_email_placeholder: 'Digite seu e-mail',
                contact_detail_phone_placeholder: '(11) 99999-9999',
                contact_detail_required: 'Este campo será obrigatório',
                send_private_feedback: 'Enviar Feedback Privado',
                skip: 'Pular',
                private_feedback_sent: 'Feedback enviado!',
                private_feedback_sent_desc: 'Obrigado pelo seu feedback detalhado. Nossa equipe entrará em contato em breve.',
                private_feedback_success: 'Feedback privado enviado com sucesso!',
                review_registered: 'Sua avaliação foi registrada. Entraremos em contato se necessário.',
                review_registered_success: 'Avaliação registrada com sucesso!',
                please_tell_us: 'Por favor, conte-nos o que podemos melhorar.',
                please_enter_email: 'Por favor, informe um e-mail de contato.',
                please_enter_phone: 'Por favor, informe um telefone de contato.',
                error_sending_feedback: 'Erro ao enviar feedback. Tente novamente.'
            },
            en_US: {
                rating_1: 'Terrible',
                rating_2: 'Poor',
                rating_3: 'Average',
                rating_4: 'Good',
                rating_5: 'Excellent',
                redirecting_to_google: 'Redirecting to Google...',
                redirecting_google_desc: 'Please complete your public review on Google to help others choose us.',
                redirecting_in_seconds: 'Redirecting in 2 seconds...',
                thanks_for_negative_feedback: 'Thanks for the feedback!',
                how_can_we_improve: 'How can we improve?',
                feedback_placeholder: 'Tell us what happened so we can improve our service...',
                how_prefer_contact: 'How would you like to be contacted?',
                contact_whatsapp: 'WhatsApp (same number provided)',
                contact_email: 'E-mail',
                contact_phone: 'Phone',
                contact_no: 'I do not wish to be contacted',
                contact_detail_label: 'Email or Phone',
                contact_detail_email_placeholder: 'Enter your email',
                contact_detail_phone_placeholder: '(11) 99999-9999',
                contact_detail_required: 'This field will be required',
                send_private_feedback: 'Send Private Feedback',
                skip: 'Skip',
                private_feedback_sent: 'Feedback sent!',
                private_feedback_sent_desc: 'Thank you for your detailed feedback. Our team will contact you shortly.',
                private_feedback_success: 'Private feedback sent successfully!',
                review_registered: 'Your review has been registered. We will contact you if necessary.',
                review_registered_success: 'Review registered successfully!',
                please_tell_us: 'Please tell us how we can improve.',
                please_enter_email: 'Please provide a contact email.',
                please_enter_phone: 'Please provide a contact phone.',
                error_sending_feedback: 'Error sending feedback. Please try again.'
            }
        };
        
        const currentLang = '{{ app()->getLocale() }}';
        const t = translations[currentLang] || translations.pt_BR;
        
        // Phone number formatting function
        function formatPhoneNumber(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            if (value.length > 10) {
                input.value = '(' + value.substring(0, 2) + ') ' + value.substring(2, 7) + '-' + value.substring(7);
            } else if (value.length > 6) {
                input.value = '(' + value.substring(0, 2) + ') ' + value.substring(2, 6) + '-' + value.substring(6);
            } else if (value.length > 2) {
                input.value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
            } else if (value.length > 0) {
                input.value = '(' + value;
            }
        }
        
        // Review System
        class ReviewSystem {
            constructor() {
                this.selectedRating = 0;
                this.companyData = @json($company);
                this.token = '{{ $token }}';
                this.positiveThreshold = this.companyData.positive_score || 4;
                this.init();
            }
            
            init() {
                this.bindStarEvents();
                this.bindFormEvents();
            }
            
            bindStarEvents() {
                const stars = document.querySelectorAll('#ratingStars i');
                
                stars.forEach((star, index) => {
                    // Click event
                    star.addEventListener('click', () => {
                        this.selectRating(index + 1);
                    });
                    
                    // Touch event for mobile
                    star.addEventListener('touchend', (e) => {
                        e.preventDefault();
                        this.selectRating(index + 1);
                    });
                    
                    // Hover effects (desktop only)
                    star.addEventListener('mouseenter', () => {
                        if (window.innerWidth > 768) {
                            this.highlightStars(index + 1);
                        }
                    });
                });
                
                // Reset hover on mouse leave
                document.getElementById('ratingStars').addEventListener('mouseleave', () => {
                    if (window.innerWidth > 768) {
                        this.highlightStars(this.selectedRating);
                    }
                });
            }
            
            selectRating(rating) {
                this.selectedRating = rating;
                this.highlightStars(rating);
                document.getElementById('rating').value = rating;
                
                // Show submit button when rating is selected
                const submitBtn = document.getElementById('submitBtn');
                const confirmText = document.getElementById('confirmText');
                
                submitBtn.classList.remove('hidden');
                submitBtn.disabled = false;
                
                // Update confirm text
                const isPositive = rating >= this.positiveThreshold;
                if (isPositive) {
                    confirmText.classList.remove('hidden');
                    confirmText.textContent = '{{ __('public.confirm_whatsapp_and_review') }}';
                    document.getElementById('submitBtnText').textContent = '{{ __('public.submit') }}';
                } else {
                    confirmText.classList.remove('hidden');
                    confirmText.textContent = '{{ __('public.confirm_whatsapp_and_review') }}';
                    document.getElementById('submitBtnText').textContent = '{{ __('public.submit') }}';
                }
            }
            
            highlightStars(rating) {
                const stars = document.querySelectorAll('#ratingStars i');
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.remove('text-gray-300');
                        star.classList.add('text-yellow-400');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            }
            
            // updateRatingText function removed - no text labels below stars
            
            
            bindFormEvents() {
                document.getElementById('reviewForm').addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.submitReview();
                });
            }
            
            async submitReview() {
                const formData = new FormData(document.getElementById('reviewForm'));
                const commentSection = document.getElementById('commentSection');
                const rating = parseInt(document.getElementById('rating').value);
                const isNegativeReview = rating < this.positiveThreshold;
                const commentSectionVisible = !commentSection.classList.contains('hidden');

                // Negative review: first step = only show comment section (do NOT call API yet)
                if (isNegativeReview && !commentSectionVisible) {
                    this.showCommentSectionForNegative();
                    return;
                }

                // From here: positive review (call API once) OR negative review second step (call API once with comment)
                if (isNegativeReview) {
                    this.showGenericLoadingState();
                } else {
                    this.showLoadingState();
                }

                try {
                    const response = await fetch('/api/reviews', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        const isNegativeWithComment = isNegativeReview && commentSectionVisible;
                        if (isNegativeWithComment) {
                            // Hide all loading states immediately
                            document.getElementById('loadingState').classList.add('hidden');
                            document.getElementById('genericLoadingState').classList.add('hidden');
                            document.getElementById('loadingStateContent').classList.add('hidden');
                            document.getElementById('commentSection').classList.add('hidden');
                            document.getElementById('submitBtn').classList.add('hidden');
                            // Show success state directly
                            document.getElementById('successState').classList.remove('hidden');
                            document.getElementById('nextAction').innerHTML = `
                                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                                    <h4 class="text-lg font-semibold text-green-800 mb-2">{{ __('public.review_sent') }}</h4>
                                    <p class="text-green-600">{{ __('public.thanks_for_feedback') }}</p>
                                </div>
                            `;
                        } else {
                            // Store current review ID for private feedback
                            window.currentReviewId = result.data.review_id;
                            this.showSuccessState(result);
                        }
                    } else {
                        this.showErrorState(result.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.showErrorState('Erro ao enviar avaliação. Tente novamente.');
                }
            }

            /**
             * Avaliação negativa: primeiro clique em Enviar só mostra a seção de comentário.
             * NÃO chama a API — a avaliação é criada só quando o usuário enviar de novo (com ou sem comentário).
             */
            showCommentSectionForNegative() {
                document.getElementById('reviewForm').classList.remove('hidden');
                document.getElementById('ratingStars').parentElement.classList.add('hidden');
                const whatsappSection = document.getElementById('whatsappSection');
                if (whatsappSection) whatsappSection.classList.add('hidden');
                document.getElementById('commentSection').classList.remove('hidden');
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.classList.remove('hidden');
                submitBtn.disabled = false;
                document.getElementById('submitBtnText').textContent = '{{ __('public.send_review') }}';
            }
            
            showLoadingState() {
                document.getElementById('reviewForm').classList.add('hidden');
                document.getElementById('loadingState').classList.remove('hidden');
                // Show Google redirect content for positive reviews
                document.getElementById('loadingStateContent').classList.remove('hidden');
                document.getElementById('genericLoadingState').classList.add('hidden');
            }
            
            showGenericLoadingState() {
                document.getElementById('reviewForm').classList.add('hidden');
                document.getElementById('loadingState').classList.remove('hidden');
                // Show generic loading for negative reviews (no Google redirect)
                document.getElementById('loadingStateContent').classList.add('hidden');
                document.getElementById('genericLoadingState').classList.remove('hidden');
            }
            
            showSuccessState(result) {
                console.log('Result from API:', result);
                
                // Check if result has data property, otherwise use the old logic
                const isPositive = result.data ? result.data.is_positive : (this.selectedRating >= this.positiveThreshold);
                const googleUrl = result.data ? result.data.google_business_url : this.companyData.google_business_url;
                const negativeEmail = result.data ? result.data.negative_email : this.companyData.negative_email;
                
                console.log('Review result:', {
                    isPositive,
                    googleUrl,
                    resultData: result.data
                });
                
                // For negative reviews, hide ALL loading states immediately and show comment form
                if (!isPositive) {
                    // Negative review - hide ALL loading states completely (no redirect message)
                    const loadingState = document.getElementById('loadingState');
                    if (loadingState) {
                        loadingState.classList.add('hidden');
                    }
                    const genericLoadingState = document.getElementById('genericLoadingState');
                    if (genericLoadingState) {
                        genericLoadingState.classList.add('hidden');
                    }
                    const loadingStateContent = document.getElementById('loadingStateContent');
                    if (loadingStateContent) {
                        loadingStateContent.classList.add('hidden');
                    }
                    
                    // First, show the form again (it was hidden during loading)
                    const reviewForm = document.getElementById('reviewForm');
                    if (reviewForm) {
                        reviewForm.classList.remove('hidden');
                    }
                    
                    // Hide stars and WhatsApp
                    const ratingStars = document.getElementById('ratingStars');
                    if (ratingStars && ratingStars.parentElement) {
                        ratingStars.parentElement.classList.add('hidden');
                    }
                    const whatsappSection = document.getElementById('whatsappSection');
                    if (whatsappSection) {
                        whatsappSection.classList.add('hidden');
                    }
                    
                    // Show comment section
                    const commentSection = document.getElementById('commentSection');
                    if (commentSection) {
                        commentSection.classList.remove('hidden');
                    }
                    
                    // Show submit button again with comment
                    const submitBtn = document.getElementById('submitBtn');
                    if (submitBtn) {
                        submitBtn.classList.remove('hidden');
                        submitBtn.disabled = false;
                    }
                    const submitBtnText = document.getElementById('submitBtnText');
                    if (submitBtnText) {
                        submitBtnText.textContent = '{{ __('public.send_review') }}';
                    }
                    
                    // Don't show success state yet - wait for comment submission
                    const successState = document.getElementById('successState');
                    if (successState) {
                        successState.classList.add('hidden');
                    }
                    return; // Exit early for negative reviews - no redirect message should appear
                }
                
                if (isPositive) {
                    // Positive review - continue showing loading with countdown, then redirect
                    if (googleUrl && googleUrl.trim() !== '') {
                        // Normalize URL - ensure it has protocol
                        let normalizedUrl = googleUrl.trim();
                        
                        // Convert to lowercase
                        normalizedUrl = normalizedUrl.toLowerCase();
                        
                        // Add https:// if protocol is missing
                        if (!normalizedUrl.match(/^https?:\/\//i)) {
                            if (normalizedUrl.startsWith('www.')) {
                                normalizedUrl = 'https://' + normalizedUrl;
                            } else {
                                normalizedUrl = 'https://' + normalizedUrl;
                            }
                        }
                        
                        // Update loading state with countdown
                        let countdown = 2;
                        const loadingState = document.getElementById('loadingState');
                        // Find or create countdown text element
                        let countdownTextEl = loadingState.querySelector('p.text-green-600.text-sm');
                        if (!countdownTextEl) {
                            // If element doesn't exist, create it
                            const loadingContent = loadingState.querySelector('.bg-green-100');
                            if (loadingContent) {
                                countdownTextEl = loadingContent.querySelector('p.text-green-600.text-sm');
                            }
                        }
                        
                        // Update countdown every second
                        const countdownInterval = setInterval(() => {
                            countdown--;
                            if (countdownTextEl) {
                                if (countdown > 0) {
                                    // Use translation for countdown
                                    const locale = '{{ app()->getLocale() }}';
                                    if (locale === 'pt_BR') {
                                        countdownTextEl.textContent = `Redirecionando em ${countdown} segundos...`;
                                    } else {
                                        countdownTextEl.textContent = `Redirecting in ${countdown} seconds...`;
                                    }
                                } else {
                                    const locale = '{{ app()->getLocale() }}';
                                    if (locale === 'pt_BR') {
                                        countdownTextEl.textContent = 'Redirecionando agora...';
                                    } else {
                                        countdownTextEl.textContent = 'Redirecting now...';
                                    }
                                }
                            }
                            
                            if (countdown <= 0) {
                                clearInterval(countdownInterval);
                                console.log('Redirecting to Google:', normalizedUrl);
                                // Redirect in the same window
                                window.location.href = normalizedUrl;
                            }
                        }, 1000);
                        
                        // Keep loading state visible, don't show success state
                        return; // Exit early since we're redirecting
                    } else {
                        console.warn('No Google URL available for redirect');
                        // Fallback: Show success message if no Google URL
                        document.getElementById('loadingState').classList.add('hidden');
                        document.getElementById('successState').classList.remove('hidden');
                    }
                } else {
                    // Negative review - hide stars/WhatsApp, show comment box
                    // Hide loading state completely (no redirect message)
                    document.getElementById('loadingState').classList.add('hidden');
                    
                    // First, show the form again (it was hidden during loading)
                    document.getElementById('reviewForm').classList.remove('hidden');
                    
                    // Hide stars and WhatsApp
                    document.getElementById('ratingStars').parentElement.classList.add('hidden');
                    document.getElementById('whatsappSection').classList.add('hidden');
                    
                    // Show comment section
                    const commentSection = document.getElementById('commentSection');
                    commentSection.classList.remove('hidden');
                    
                    // Show submit button again with comment
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.classList.remove('hidden');
                    submitBtn.disabled = false;
                    document.getElementById('submitBtnText').textContent = '{{ __('public.send_review') }}';
                    
                    // Don't show success state yet - wait for comment submission
                    document.getElementById('successState').classList.add('hidden');
                }
            }
            
            showErrorState(message) {
                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('reviewForm').classList.remove('hidden');
                
                // Show error notification
                this.showNotification(message, 'error');
            }
            
            showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `notification notification-${type}`;
                notification.textContent = message;
                
                Object.assign(notification.style, {
                    position: 'fixed',
                    top: '20px',
                    right: '20px',
                    padding: '1rem 1.5rem',
                    borderRadius: '12px',
                    color: 'white',
                    fontWeight: '500',
                    zIndex: '1000',
                    transform: 'translateX(100%)',
                    transition: 'transform 0.3s ease',
                    background: type === 'error' ? '#ef4444' : '#10b981',
                    maxWidth: '400px',
                    boxShadow: '0 10px 25px rgba(0, 0, 0, 0.1)'
                });
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.transform = 'translateX(0)';
                }, 100);
                
                setTimeout(() => {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (document.body.contains(notification)) {
                            document.body.removeChild(notification);
                        }
                    }, 300);
                }, 5000);
            }
        }
        
        // Global functions for private feedback
        async function submitPrivateFeedback(event) {
            const feedback = document.getElementById('privateFeedback').value;
            const contactPreference = document.getElementById('contactPreference').value;
            const contactDetailField = document.getElementById('contactDetailField');
            const contactDetail = document.getElementById('contactDetail').value;
            
            if (!feedback.trim()) {
                alert(t.please_tell_us);
                return;
            }
            
            // Validar campo de contato detalhado se necessário
            if (contactPreference === 'email' || contactPreference === 'phone') {
                if (!contactDetail.trim()) {
                    alert(contactPreference === 'email' ? t.please_enter_email : t.please_enter_phone);
                    return;
                }
            }
            
            try {
                // Show loading - safely get button
                const button = event && event.target ? event.target : document.querySelector('#privateFeedbackForm button[type="button"]');
                if (!button) return;
                
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
                button.disabled = true;
                
                // Send private feedback
                const response = await fetch('/api/reviews/private-feedback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        review_id: window.currentReviewId,
                        feedback: feedback,
                        contact_preference: contactPreference,
                        contact_detail: contactDetail
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success message
                    document.getElementById('nextAction').innerHTML = `
                        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-green-800 mb-2">${t.private_feedback_sent}</h4>
                            <p class="text-green-600 mb-4">${t.private_feedback_sent_desc}</p>
                            <div class="flex items-center justify-center space-x-2">
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span class="text-green-600">${t.private_feedback_success}</span>
                            </div>
                        </div>
                    `;
                } else {
                    alert(t.error_sending_feedback);
                }
            } catch (error) {
                console.error('Erro ao enviar feedback:', error);
                alert('Erro ao enviar feedback. Tente novamente.');
            } finally {
                // Restore button safely
                if (button && originalText) {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            }
        }
        
        function skipPrivateFeedback() {
            document.getElementById('nextAction').innerHTML = `
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ __('public.thanks_for_feedback') }}</h4>
                    <p class="text-gray-600 mb-4">${t.review_registered}</p>
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fas fa-check-circle text-gray-600"></i>
                        <span class="text-gray-600">${t.review_registered_success}</span>
                    </div>
                </div>
            `;
        }
        
        // Função para mostrar/ocultar campo de contato detalhado
        function toggleContactField(select) {
            const contactField = document.getElementById('contactDetailField');
            const contactInput = document.getElementById('contactDetail');
            const contactLabel = document.getElementById('contactDetailLabel');
            
            const value = select.value;
            
            if (value === 'email' || value === 'phone') {
                // Mostrar campo
                contactField.classList.remove('hidden');
                
                // Atualizar label baseado na seleção
                if (value === 'email') {
                    contactLabel.textContent = t.contact_email;
                    contactInput.type = 'email';
                    contactInput.placeholder = t.contact_detail_email_placeholder;
                    contactInput.required = true;
                } else if (value === 'phone') {
                    contactLabel.textContent = t.contact_phone;
                    contactInput.type = 'tel';
                    contactInput.placeholder = t.contact_detail_phone_placeholder;
                    contactInput.required = true;
                }
                
                // Limpar e focar no campo
                contactInput.value = '';
                contactInput.focus();
            } else {
                // Ocultar campo
                contactField.classList.add('hidden');
                contactInput.required = false;
                contactInput.value = '';
            }
        }
        
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            new ReviewSystem();
            
            // Formatação de telefone WhatsApp
            const whatsappInput = document.getElementById('whatsapp');
            if (whatsappInput) {
                whatsappInput.addEventListener('input', function(e) {
                    formatPhoneNumber(e.target);
                });
            }
        });
    </script>
</body>
</html>
