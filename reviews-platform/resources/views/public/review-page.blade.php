<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('public.how_was_experience') }} — {{ $company->name ?? 'Company' }}</title>
    @vite(['resources/css/marketing.css', 'resources/js/review-entry.jsx'])
    <style>
        .review-hero {
            background: #0a0a0a;
        }
        .company-logo {
            filter: drop-shadow(0 8px 24px rgba(0,0,0,.25));
            border-radius: 1rem;
        }
        .company-name-large {
            font-family: 'Instrument Serif', Georgia, serif;
            letter-spacing: -0.03em;
            text-shadow: 0 2px 20px rgba(0,0,0,.35);
        }
        input[type="text"], input[type="email"], input[type="tel"], textarea {
            font-size: 16px;
        }
        .google-button {
            background: #0a0a0a;
            transition: opacity .2s ease;
        }
        .google-button:hover { opacity: .9; }
    </style>
</head>
<body class="min-h-screen bg-surface text-ink antialiased">
    <!-- Success Message -->
    @if(session('success'))
    <div class="fixed top-4 right-4 z-50 rounded-xl border border-green-200 bg-green-50 px-6 py-4 text-green-800 shadow-editorial">
        <div>
            <h4 class="font-semibold">{{ __('public.review_sent') }}</h4>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    </div>
    @endif
    <!-- Hero Section -->
    <div class="relative overflow-hidden review-hero" @if($company->background_image_url) style="background-image: url('{{ $company->background_image_url }}'); background-size: cover; background-position: center;" @else style="background-image: url('{{ asset('assets/images/Backpadrao.jpg') }}'); background-size: cover; background-position: center;" @endif>
        <div class="absolute inset-0 bg-gradient-to-b from-black/55 via-black/45 to-black/60"></div>
        
        <div class="relative z-10 px-4 py-12 sm:py-20 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <div class="mb-6 sm:mb-8">
                    @if($company->logo_url)
                        <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="company-logo mx-auto mb-6" loading="lazy" style="max-width: 140px; max-height: 140px; width: auto; height: auto;" onerror="this.style.display='none';">
                        <h1 class="text-3xl sm:text-4xl md:text-5xl text-white mb-4 company-name-large">
                            {{ $company->name ?? 'Company' }}
                        </h1>
                    @else
                        <h1 class="text-4xl sm:text-5xl md:text-6xl text-white mb-4 company-name-large">
                            {{ $company->name ?? 'Company' }}
                        </h1>
                    @endif
                </div>
                
                <div class="mb-4 sm:mb-6">
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-2.5 backdrop-blur-sm">
                        <span class="text-sm font-medium text-white">{{ __('public.prize_hero_text') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="editorial-container py-12 md:py-16">
        <div class="max-w-xl mx-auto mb-16">
            <div class="editorial-card shadow-editorial p-6 sm:p-8">
                <h2 class="editorial-display text-2xl sm:text-3xl text-center mb-6">{{ __('public.how_was_experience') }}</h2>
                
                <div class="text-center mb-8" id="reviewStarsRoot"></div>
                
                <!-- Review Form -->
                <form id="reviewForm" class="space-y-4 sm:space-y-6">
                    @csrf
                    <input type="hidden" id="rating" name="rating" value="">
                    <input type="hidden" id="company_token" name="company_token" value="{{ $token }}">
                    
                    <!-- WhatsApp - Always visible below stars -->
                    <div id="whatsappSection">
                        <label for="whatsapp" class="editorial-label">
                            {{ __('public.whatsapp_number') }}
                        </label>
                        <input 
                            type="tel" 
                            id="whatsapp" 
                            name="whatsapp"
                            required
                            class="editorial-input"
                            placeholder="{{ __('public.whatsapp_placeholder') }}"
                            maxlength="15"
                        >
                        <p class="mt-1 text-xs italic text-ink-subtle">{{ __('public.competition_internal_use') }}</p>
                        <p class="mt-3 hidden text-center text-sm font-medium text-ink-muted" id="confirmText"></p>
                    </div>
                    
                    <!-- Comment - Only shown for negative reviews -->
                    <div id="commentSection" class="hidden">
                        <label for="comment" class="editorial-label">
                            {{ __('public.comment_optional') }}
                        </label>
                        <textarea 
                            id="comment" 
                            name="comment"
                            rows="4"
                            class="editorial-input resize-none"
                            placeholder="{{ __('public.comment_placeholder') }}"
                        ></textarea>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submitBtn"
                        disabled
                        class="editorial-btn-primary w-full py-4 disabled:cursor-not-allowed disabled:opacity-50 hidden"
                    >
                        <span id="submitBtnText">{{ __('public.send_review') }}</span>
                    </button>
                </form>
                
                <!-- Loading State -->
                <div id="loadingState" class="hidden text-center py-8">
                    <div class="editorial-card mb-4 border-green-200 bg-green-50/80" id="loadingStateContent">
                        <h5 class="text-lg font-semibold text-ink mb-2" id="loadingTitle">{{ __('public.redirecting_to_google') }}</h5>
                        <p class="text-ink-muted mb-3" id="loadingDescription">{{ __('public.redirecting_google_desc') }}</p>
                        <div class="mx-auto mb-2 h-10 w-10 animate-spin rounded-full border-2 border-ink/20 border-t-ink"></div>
                        <p class="text-sm text-ink-muted" id="loadingCountdown">{{ __('public.redirecting_in_seconds') }}</p>
                    </div>
                    <div class="editorial-card mb-4 hidden" id="genericLoadingState">
                        <h5 class="text-lg font-semibold text-ink mb-2">{{ __('public.processing_review') }}</h5>
                        <div class="mx-auto h-10 w-10 animate-spin rounded-full border-2 border-ink/20 border-t-ink"></div>
                    </div>
                </div>
                
                <!-- Success State -->
                <div id="successState" class="hidden text-center py-8">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-green-200 bg-green-50 text-green-700">
                        <span class="text-2xl">✓</span>
                    </div>
                    <h3 class="editorial-display text-xl text-ink mb-2">{{ __('public.review_sent') }}</h3>
                    <p class="text-ink-muted mb-4">{{ __('public.thanks_for_feedback') }}</p>
                    <div id="nextAction" class="mt-6">
                        <!-- Content will be dynamically inserted here -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8 sm:mt-16">
            <p class="text-xs sm:text-sm text-ink-subtle">
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
                window.reviewSystemInstance = this;
                this.init();
            }
            
            init() {
                this.bindStarEvents();
                this.bindFormEvents();
            }
            
            bindStarEvents() {
                const container = document.getElementById('ratingStars');
                if (!container) return;
                const stars = container.querySelectorAll('i');
                
                stars.forEach((star, index) => {
                    star.addEventListener('click', () => this.selectRating(index + 1));
                    star.addEventListener('touchend', (e) => {
                        e.preventDefault();
                        this.selectRating(index + 1);
                    });
                    star.addEventListener('mouseenter', () => {
                        if (window.innerWidth > 768) this.highlightStars(index + 1);
                    });
                });
                
                container.addEventListener('mouseleave', () => {
                    if (window.innerWidth > 768) this.highlightStars(this.selectedRating);
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
                const container = document.getElementById('ratingStars');
                if (!container) return;
                const stars = container.querySelectorAll('i');
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
                const starsRoot = document.getElementById('reviewStarsRoot');
                if (starsRoot) starsRoot.classList.add('hidden');
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
                    const starsRoot = document.getElementById('reviewStarsRoot');
                    if (starsRoot) starsRoot.classList.add('hidden');
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
                        let countdownTextEl = document.getElementById('loadingCountdown');
                        
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
                    const starsRoot = document.getElementById('reviewStarsRoot');
                if (starsRoot) starsRoot.classList.add('hidden');
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
                button.innerHTML = 'Sending…';
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
                        <div class="editorial-card border-green-200 bg-green-50/80 p-6">
                            <h4 class="text-lg font-semibold text-ink mb-2">${t.private_feedback_sent}</h4>
                            <p class="text-ink-muted mb-4">${t.private_feedback_sent_desc}</p>
                            <p class="text-green-700">${t.private_feedback_success}</p>
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
                <div class="editorial-card p-6">
                    <h4 class="text-lg font-semibold text-ink mb-2">{{ __('public.thanks_for_feedback') }}</h4>
                    <p class="text-ink-muted mb-4">${t.review_registered}</p>
                    <p class="text-ink-muted">${t.review_registered_success}</p>
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
