@extends('layouts.admin')

@section('title', __('reviews.negative_title') . ' - ' . __('app.name'))

@section('page-title', __('reviews.negative_title'))
@section('page-description', __('reviews.negative_description'))

@section('header-actions')
    <button onclick="refreshNegativeReviews()" class="bg-red-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-600 transition-colors">
        <i class="fas fa-sync-alt mr-2"></i>
        {{ __('reviews.refresh') }}
    </button>
@endsection

@section('styles')
    <style>
        .alert-card {
            background: #fef2f2;
            border: 2px solid #fecaca;
            transition: var(--transition-smooth);
            overflow: hidden;
        }
        
        .dark .alert-card {
            background: #7f1d1d;
            border: 2px solid #b91c1c;
        }
        
        .alert-card:hover {
            border-color: #fca5a5;
            box-shadow: 0 8px 16px rgba(239, 68, 68, 0.15);
        }
        
        .dark .alert-card:hover {
            border-color: #dc2626;
            box-shadow: 0 8px 16px rgba(239, 68, 68, 0.25);
        }
        
        .stars-negative {
            color: #dc2626;
        }
        
        .priority-high {
            animation: pulseRed 2s infinite;
        }
        
        @keyframes pulseRed {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(220, 38, 38, 0);
            }
        }
        
        .review-action-btn {
            transition: var(--transition-smooth);
        }
        
        .review-action-btn:hover {
            transform: translateY(-2px);
        }
        
        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            /* Reduzir espaçamentos gerais */
            main {
                padding: 0.5rem !important;
            }
            
            /* Alert banner mobile - mais compacto */
            .bg-red-50,
            .bg-red-50 > div {
                padding: 0.75rem !important;
                margin-bottom: 0.75rem !important;
                margin-left: -0.5rem;
                margin-right: -0.5rem;
                width: calc(100% + 1rem);
                max-width: calc(100% + 1rem);
            }
            
            .bg-red-50 .text-xl {
                font-size: 1rem !important;
            }
            
            .bg-red-50 .text-sm {
                font-size: 0.75rem !important;
                line-height: 1.4;
            }
            
            /* Filters section mobile - mais compacto */
            .bg-white.rounded-xl,
            .dark .bg-gray-800.rounded-xl {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
                width: calc(100% + 1rem);
                max-width: calc(100% + 1rem);
                border-radius: 8px !important;
                margin-bottom: 0.75rem !important;
            }
            
            .bg-white.rounded-xl > div,
            .dark .bg-gray-800.rounded-xl > div {
                padding: 0.75rem !important;
            }
            
            #filtersContainer {
                padding: 0.5rem !important;
                margin-bottom: 0.75rem !important;
            }
            
            #filtersContainer > div {
                padding: 0.5rem !important;
            }
            
            #filtersContainer h2 {
                font-size: 0.875rem !important;
                margin-bottom: 0.5rem !important;
            }
            
            /* Filtros mais compactos */
            #negativeFiltersForm {
                gap: 0.5rem !important;
            }
            
            #negativeFiltersForm > div {
                margin-bottom: 0.5rem;
            }
            
            #negativeFiltersForm label {
                font-size: 0.7rem !important;
                margin-bottom: 0.25rem !important;
                line-height: 1.2;
            }
            
            #negativeFiltersForm input,
            #negativeFiltersForm select {
                font-size: 13px !important;
                padding: 0.4rem 0.75rem !important;
                min-height: 36px !important;
                height: 36px !important;
            }
            
            #searchFilter {
                font-size: 13px !important;
                padding: 0.4rem 0.75rem !important;
            }
            
            #companySearchInput {
                font-size: 13px !important;
                padding: 0.4rem 0.75rem 0.4rem 2rem !important;
            }
            
            /* List header mobile - mais compacto */
            .bg-white.rounded-xl .p-4,
            .bg-white.rounded-xl .p-6 {
                padding: 0.75rem !important;
            }
            
            .bg-white.rounded-xl h2 {
                font-size: 0.875rem !important;
            }
            
            .bg-white.rounded-xl .text-sm {
                font-size: 0.7rem !important;
            }
            
            #sortFilter {
                width: 100% !important;
                min-height: 36px !important;
                height: 36px !important;
                font-size: 13px !important;
                padding: 0.4rem 0.75rem !important;
            }
            
            /* Cards mobile - muito mais compactos */
            .alert-card {
                padding: 0.75rem !important;
                margin: 0;
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
                border-radius: 8px !important;
            }
            
            /* Card header - mais compacto */
            .alert-card > div:first-child {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 0.5rem !important;
                margin-bottom: 0.75rem !important;
            }
            
            /* Ícone menor */
            .alert-card .w-12 {
                width: 2.5rem !important;
                height: 2.5rem !important;
            }
            
            .alert-card .w-12 i {
                font-size: 0.875rem !important;
            }
            
            /* Nome da empresa menor */
            .alert-card h3 {
                font-size: 0.875rem !important;
                line-height: 1.3;
                margin-bottom: 0.25rem !important;
            }
            
            /* Badges e datas menores */
            .alert-card .text-sm {
                font-size: 0.7rem !important;
            }
            
            .alert-card .text-xs {
                font-size: 0.65rem !important;
                padding: 0.25rem 0.5rem !important;
            }
            
            /* Rating section mobile - mais compacto */
            .alert-card .text-right {
                width: 100% !important;
                text-align: left !important;
                margin-top: 0.5rem !important;
                padding-top: 0.5rem !important;
                border-top: 1px solid rgba(220, 38, 38, 0.2);
            }
            
            .alert-card .text-2xl,
            .alert-card .text-3xl {
                font-size: 1.25rem !important;
            }
            
            .alert-card .text-lg,
            .alert-card .text-xl {
                font-size: 0.875rem !important;
            }
            
            /* WhatsApp section - mais compacto */
            .alert-card .flex.items-center.space-x-3 {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
                width: 100%;
                margin-bottom: 0.75rem !important;
            }
            
            .alert-card .bg-green-50 {
                padding: 0.5rem 0.75rem !important;
                font-size: 0.75rem !important;
            }
            
            .alert-card .btn-primary {
                padding: 0.5rem 0.75rem !important;
                font-size: 0.75rem !important;
                min-height: 36px !important;
            }
            
            /* Comment boxes mobile - mais compactos */
            .alert-card .bg-white,
            .alert-card .bg-orange-50 {
                padding: 0.5rem 0.75rem !important;
                margin-bottom: 0.75rem !important;
                font-size: 0.75rem !important;
            }
            
            .alert-card .bg-white p,
            .alert-card .bg-orange-50 p {
                font-size: 0.7rem !important;
                margin-bottom: 0.25rem !important;
            }
            
            /* Action buttons mobile - mais compactos */
            .alert-card .flex.flex-wrap.gap-2 {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }
            
            .alert-card .flex.flex-wrap.gap-2 > button {
                width: 100% !important;
                min-height: 36px !important;
                padding: 0.5rem 0.75rem !important;
                font-size: 0.75rem !important;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            /* Espaçamento entre cards */
            #reviewsContainer > div {
                padding: 0.5rem !important;
                margin-bottom: 0.5rem !important;
            }
            
            /* Prevent horizontal scroll */
            body, html {
                overflow-x: hidden;
                max-width: 100vw;
            }
            
            main {
                overflow-x: hidden;
                max-width: 100%;
            }
            
            /* Company name mobile */
            .alert-card h3 {
                word-break: break-word;
                overflow-wrap: break-word;
                max-width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Alert Banner -->
    <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-400 dark:border-red-600 p-3 md:p-4 rounded-lg mb-4 md:mb-6 fade-in overflow-hidden">
        <div class="flex flex-col sm:flex-row">
            <div class="flex-shrink-0 mb-1 sm:mb-0">
                <i class="fas fa-exclamation-triangle text-red-400 dark:text-red-500 text-base md:text-xl"></i>
            </div>
            <div class="ml-0 sm:ml-3 flex-1">
                <p class="text-xs md:text-sm text-red-700 dark:text-red-300 break-words">
                    <strong>{{ __('reviews.alert_attention') }}</strong> {{ __('reviews.alert_message') }}
                </p>
            </div>
        </div>
    </div>
    
    <!-- Smart Filters Section -->
    <div id="filtersContainer" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-4 md:mb-6 fade-in">
        <div class="p-2 md:p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-2 md:mb-4">
                <h2 class="text-xs md:text-lg font-semibold text-gray-800 dark:text-gray-100">
                    <i class="fas fa-filter mr-2 text-red-500"></i>
                    {{ __('reviews.smart_filters') }}
                </h2>
                <div class="flex items-center gap-2">
                    <button id="clearFiltersBtn" class="hidden text-xs md:text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium">
                        <i class="fas fa-times mr-1"></i>
                        <span class="hidden sm:inline">{{ __('reviews.clear_all_filters') }}</span>
                    </button>
                    <button type="button" id="toggleFiltersBtn" class="md:hidden text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors" onclick="toggleNegativeFilters()">
                        <i class="fas fa-chevron-down" id="toggleNegativeFiltersIcon"></i>
                    </button>
                </div>
            </div>
            
            <!-- Active Filters Display -->
            <div id="activeFilters" class="hidden flex flex-wrap gap-2 mb-3 md:mb-4 pb-3 md:pb-4 border-b border-gray-200 dark:border-gray-700">
                <span class="text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('reviews.active_filters') }}:</span>
                <!-- Active filter chips will be inserted here -->
            </div>
            
            <div id="negativeFiltersForm" class="hidden md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                <!-- Search Filter -->
                <div class="lg:col-span-2">
                    <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 md:mb-2">
                        <i class="fas fa-search mr-1"></i>
                        {{ __('reviews.search_placeholder') }}
                    </label>
                    <input 
                        type="text" 
                        id="searchFilter" 
                        placeholder="{{ __('reviews.search_placeholder') }}"
                        class="w-full px-2 md:px-4 py-1.5 md:py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        style="font-size: 14px; min-height: 36px;"
                    >
                </div>
                
                <!-- Company Filter (with search) -->
                <div>
                    <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 md:mb-2">
                        <i class="fas fa-building mr-1"></i>
                        {{ __('reviews.filter_by_company') }}
                    </label>
                    <div class="relative" id="companyFilterWrapper">
                        <div class="relative">
                            <input 
                                type="text" 
                                id="companySearchInput" 
                                placeholder="{{ __('reviews.search_company_placeholder') }}"
                                class="w-full px-2 md:px-4 py-1.5 md:py-2 pl-8 md:pl-10 pr-4 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                style="font-size: 14px; min-height: 36px; padding-left: 2rem;"
                                autocomplete="off"
                            >
                            <i class="fas fa-search absolute left-2 md:left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none z-10"></i>
                            <input type="hidden" id="companyFilter" value="all">
                        </div>
                        <div id="companyDropdown" class="hidden absolute z-50 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <div id="companyOptions" class="py-1">
                                <!-- Options will be loaded dynamically -->
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- User Filter (apenas proprietário) -->
                @if(Auth::user()->role === 'proprietario')
                <div>
                    <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 md:mb-2">
                        <i class="fas fa-user mr-1"></i>
                        {{ __('reviews.filter_by_user') }}
                    </label>
                    <select id="userFilter" class="w-full px-2 md:px-4 py-1.5 md:py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-red-500" style="font-size: 14px; min-height: 36px;">
                        <option value="all">{{ __('reviews.all_users') }}</option>
                        <!-- Users will be loaded dynamically -->
                    </select>
                </div>
                @endif
                
                <!-- Status Filter -->
                <div>
                    <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 md:mb-2">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ __('reviews.filter_by_status') }}
                    </label>
                    <select id="statusFilter" class="w-full px-2 md:px-4 py-1.5 md:py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-red-500" style="font-size: 14px; min-height: 36px;">
                        <option value="all">{{ __('reviews.all_status') }}</option>
                        <option value="unprocessed">{{ __('reviews.unprocessed') }}</option>
                        <option value="processed">{{ __('reviews.processed') }}</option>
                    </select>
                </div>
                
                <!-- Period Filter -->
                <div>
                    <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 md:mb-2">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ __('reviews.filter_by_period') }}
                    </label>
                    <select id="periodFilter" class="w-full px-2 md:px-4 py-1.5 md:py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-red-500" style="font-size: 14px; min-height: 36px;">
                        <option value="all">{{ __('reviews.all_periods_filter') }}</option>
                        <option value="today">{{ __('reviews.today') }}</option>
                        <option value="week">{{ __('reviews.last_week') }}</option>
                        <option value="month">{{ __('reviews.last_month') }}</option>
                    </select>
                </div>
                
                <!-- Rating Filter -->
                <div>
                    <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 md:mb-2">
                        <i class="fas fa-star mr-1"></i>
                        {{ __('reviews.filter_by_rating') }}
                    </label>
                    <select id="ratingFilter" class="w-full px-2 md:px-4 py-1.5 md:py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-red-500" style="font-size: 14px; min-height: 36px;">
                        <option value="all">{{ __('reviews.all_ratings_filter') }}</option>
                        <option value="1">{{ __('reviews.one_star') }}</option>
                        <option value="2">{{ __('reviews.two_stars') }}</option>
                        <option value="3">{{ __('reviews.three_stars') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Negative Reviews List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-2 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-4">
                <div class="flex-1 min-w-0">
                    <h2 class="text-sm md:text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('reviews.negative_list_title') }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-xs md:text-sm">
                        <span id="resultsCount" class="font-medium text-red-600 dark:text-red-400">0</span> {{ __('reviews.results_count') }}
                    </p>
                </div>
                <div class="w-full sm:w-auto">
                    <select id="sortFilter" class="w-full sm:w-auto px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-red-500 min-h-[44px] font-size-16">
                        <option value="recent">{{ __('reviews.sort_most_recent') }}</option>
                        <option value="oldest">{{ __('reviews.sort_oldest') }}</option>
                        <option value="lowest">{{ __('reviews.sort_lowest_rating') }}</option>
                        <option value="highest">{{ __('reviews.sort_lowest_rating') }} ({{ __('reviews.rating') }} maior primeiro)</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Loading State - Skeleton Screens -->
        <div id="loadingState" class="space-y-4 p-6">
            <!-- Skeleton Negative Review Card 1 -->
            <div class="skeleton-card bg-red-50/50 dark:bg-red-900/10">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center flex-1">
                        <div class="skeleton-avatar bg-red-200 dark:bg-red-900/30"></div>
                        <div class="flex-1">
                            <div class="skeleton-line w-1-2 bg-red-200 dark:bg-red-900/30"></div>
                            <div class="skeleton-line w-1-4 bg-red-200 dark:bg-red-900/30"></div>
                        </div>
                    </div>
                    <div class="w-20">
                        <div class="skeleton-line w-full bg-red-200 dark:bg-red-900/30"></div>
                    </div>
                </div>
                <div class="skeleton-line w-full bg-red-200 dark:bg-red-900/30"></div>
                <div class="skeleton-line w-3-4 bg-red-200 dark:bg-red-900/30"></div>
            </div>
            
            <!-- Skeleton Negative Review Card 2 -->
            <div class="skeleton-card bg-red-50/50 dark:bg-red-900/10">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center flex-1">
                        <div class="skeleton-avatar bg-red-200 dark:bg-red-900/30"></div>
                        <div class="flex-1">
                            <div class="skeleton-line w-1-2 bg-red-200 dark:bg-red-900/30"></div>
                            <div class="skeleton-line w-1-4 bg-red-200 dark:bg-red-900/30"></div>
                        </div>
                    </div>
                    <div class="w-20">
                        <div class="skeleton-line w-full bg-red-200 dark:bg-red-900/30"></div>
                    </div>
                </div>
                <div class="skeleton-line w-full bg-red-200 dark:bg-red-900/30"></div>
                <div class="skeleton-line w-1-2 bg-red-200 dark:bg-red-900/30"></div>
            </div>
        </div>
        
        <!-- Reviews Container -->
        <div id="reviewsContainer" class="hidden">
            <!-- Reviews will be loaded here -->
        </div>
        
        <!-- Empty State -->
        <div id="emptyState" class="hidden p-12 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full mb-6 scale-in">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ __('reviews.empty_title') }}</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __('reviews.empty_description') }}</p>
            <div class="inline-flex items-center text-green-600 dark:text-green-400 text-sm font-medium">
                <i class="fas fa-trophy mr-2"></i>
                {{ __('reviews.empty_excellent_work') }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Format date function
        function formatDate(dateString) {
            if (!dateString) return '';
            
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return dateString;
            
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            
            return `${day}/${month}/${year} ${hours}:${minutes}`;
        }
        
        // Translations for JavaScript
        const translations = {
            pt_BR: {
                error_loading_negative: '{{ __('reviews.error_loading_negative') }}',
                updating_reviews: '{{ __('reviews.updating_reviews') }}',
                opening_whatsapp: '{{ __('reviews.opening_whatsapp') }}',
                contact_now: '{{ __('reviews.contact_now') }}',
                client_comment: '{{ __('reviews.client_comment') }}',
                private_feedback: '{{ __('reviews.private_feedback') }}',
                mark_as_processed: '{{ __('reviews.mark_as_processed') }}',
                confirm_mark_processed: '{{ __('reviews.confirm_mark_processed') }}',
                processed_success: '{{ __('reviews.processed_success') }}',
                error_processing: '{{ __('reviews.error_processing') }}',
                send_followup: '{{ __('reviews.send_followup') }}',
                followup_message_prompt: '{{ __('reviews.followup_message_prompt') }}',
                sending_followup: '{{ __('reviews.sending_followup') }}',
                followup_success: '{{ __('reviews.followup_success') }}',
                error_sending_followup: '{{ __('reviews.error_sending_followup') }}',
                add_note: '{{ __('reviews.add_note') }}',
                note_prompt: '{{ __('reviews.note_prompt') }}',
                saving_note: '{{ __('reviews.saving_note') }}',
                note_success: '{{ __('reviews.note_success') }}',
                error_saving_note: '{{ __('reviews.error_saving_note') }}',
                today_badge: '{{ __('reviews.today_badge') }}',
                unprocessed: '{{ __('reviews.unprocessed') }}',
                negative_title: '{{ __('reviews.negative_title') }}',
                processed: '{{ __('reviews.processed') }}',
                last_week: '{{ __('reviews.last_week') }}',
                last_month: '{{ __('reviews.last_month') }}',
                one_star: '{{ __('reviews.one_star') }}',
                two_stars: '{{ __('reviews.two_stars') }}',
                three_stars: '{{ __('reviews.three_stars') }}',
                no_results: '{{ __('reviews.no_results') }}',
                results_count: '{{ __('reviews.results_count') }}'
            },
            en_US: {
                error_loading_negative: '{{ __('reviews.error_loading_negative') }}',
                updating_reviews: '{{ __('reviews.updating_reviews') }}',
                opening_whatsapp: '{{ __('reviews.opening_whatsapp') }}',
                contact_now: '{{ __('reviews.contact_now') }}',
                client_comment: '{{ __('reviews.client_comment') }}',
                private_feedback: '{{ __('reviews.private_feedback') }}',
                mark_as_processed: '{{ __('reviews.mark_as_processed') }}',
                confirm_mark_processed: '{{ __('reviews.confirm_mark_processed') }}',
                processed_success: '{{ __('reviews.processed_success') }}',
                error_processing: '{{ __('reviews.error_processing') }}',
                send_followup: '{{ __('reviews.send_followup') }}',
                followup_message_prompt: '{{ __('reviews.followup_message_prompt') }}',
                sending_followup: '{{ __('reviews.sending_followup') }}',
                followup_success: '{{ __('reviews.followup_success') }}',
                error_sending_followup: '{{ __('reviews.error_sending_followup') }}',
                add_note: '{{ __('reviews.add_note') }}',
                note_prompt: '{{ __('reviews.note_prompt') }}',
                saving_note: '{{ __('reviews.saving_note') }}',
                note_success: '{{ __('reviews.note_success') }}',
                error_saving_note: '{{ __('reviews.error_saving_note') }}',
                today_badge: '{{ __('reviews.today_badge') }}',
                unprocessed: '{{ __('reviews.unprocessed') }}',
                negative_title: '{{ __('reviews.negative_title') }}',
                processed: '{{ __('reviews.processed') }}',
                last_week: '{{ __('reviews.last_week') }}',
                last_month: '{{ __('reviews.last_month') }}',
                one_star: '{{ __('reviews.one_star') }}',
                two_stars: '{{ __('reviews.two_stars') }}',
                three_stars: '{{ __('reviews.three_stars') }}',
                no_results: '{{ __('reviews.no_results') }}',
                results_count: '{{ __('reviews.results_count') }}'
            }
        };
        
        const currentLang = '{{ app()->getLocale() }}';
        const t = translations[currentLang] || translations.pt_BR;
        
        class NegativeReviewsPanel {
            constructor() {
                this.currentPage = 1;
                this.sortBy = 'recent';
                this.filters = {
                    company_id: 'all',
                    user_id: 'all',
                    status: 'all',
                    period: 'all',
                    rating: 'all',
                    search: ''
                };
                this.searchTimeout = null;
                this.companies = [];
                this.users = [];
                this.selectedCompany = null;
                this.companySearchTerm = '';
                this.init();
            }
            
            async init() {
                await Promise.all([
                    this.loadCompanies(),
                    this.loadUsers()
                ]);
                await this.loadNegativeReviews();
                this.initCompanySearch();
                this.bindEvents();
                this.loadFiltersFromURL();
            }
            
            async loadCompanies() {
                try {
                    const response = await fetch('/api/companies', {
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        this.companies = result.data;
                        this.renderCompanyOptions();
                    }
                } catch (error) {
                    console.error('Erro ao carregar empresas:', error);
                }
            }
            
            async loadUsers() {
                try {
                    @if(Auth::user()->role === 'proprietario')
                    const response = await fetch('/api/users/with-companies', {
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        this.users = result.data;
                        this.populateUserFilter();
                    }
                    @endif
                } catch (error) {
                    console.error('Erro ao carregar usuários:', error);
                }
            }
            
            populateUserFilter() {
                const select = document.getElementById('userFilter');
                if (!select) return;
                
                this.users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = `${user.name} (${user.companies_count} ${user.companies_count === 1 ? 'empresa' : 'empresas'})`;
                    select.appendChild(option);
                });
            }
            
            initCompanySearch() {
                const searchInput = document.getElementById('companySearchInput');
                const dropdown = document.getElementById('companyDropdown');
                const wrapper = document.getElementById('companyFilterWrapper');
                
                if (!searchInput) return;
                
                // Show dropdown on focus
                searchInput.addEventListener('focus', () => {
                    if (this.companies.length > 0) {
                        dropdown.classList.remove('hidden');
                        this.renderCompanyOptions();
                    }
                });
                
                // Search functionality
                searchInput.addEventListener('input', (e) => {
                    this.companySearchTerm = e.target.value.toLowerCase();
                    this.renderCompanyOptions();
                    dropdown.classList.remove('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!wrapper.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
                
                // Set selected company text
                this.updateCompanyInput();
            }
            
            renderCompanyOptions() {
                const optionsDiv = document.getElementById('companyOptions');
                if (!optionsDiv) return;
                
                const filtered = this.companies.filter(company => {
                    if (!this.companySearchTerm) return true;
                    const name = company.name ? company.name.toLowerCase() : '';
                    const owner = company.user_name ? company.user_name.toLowerCase() : '';
                    return name.includes(this.companySearchTerm) || owner.includes(this.companySearchTerm);
                });
                
                if (filtered.length === 0) {
                    optionsDiv.innerHTML = `<div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">${t.no_companies_found || 'Nenhuma empresa encontrada'}</div>`;
                    return;
                }
                
                // Add "All" option
                let html = `
                    <div class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 ${this.filters.company_id === 'all' ? 'bg-red-50 dark:bg-red-900/30' : ''}" 
                         onclick="negativeReviewsPanel.selectCompany(null)">
                        <div class="font-medium text-gray-900 dark:text-gray-100">${t.all_companies_filter || 'Todas as Empresas'}</div>
                    </div>
                `;
                
                filtered.forEach(company => {
                    const isSelected = this.filters.company_id == company.id;
                    html += `
                        <div class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 ${isSelected ? 'bg-red-50 dark:bg-red-900/30' : ''}" 
                             onclick="negativeReviewsPanel.selectCompany(${company.id}, '${company.name.replace(/'/g, "\\'")}')">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${company.name}</div>
                            ${company.user_name ? `<div class="text-xs text-gray-500 dark:text-gray-400">${t.company_owner || 'Proprietário'}: ${company.user_name}</div>` : ''}
                        </div>
                    `;
                });
                
                optionsDiv.innerHTML = html;
            }
            
            selectCompany(companyId, companyName) {
                this.filters.company_id = companyId || 'all';
                document.getElementById('companyFilter').value = companyId || 'all';
                
                const searchInput = document.getElementById('companySearchInput');
                if (searchInput) {
                    searchInput.value = companyName || '';
                }
                
                document.getElementById('companyDropdown').classList.add('hidden');
                this.updateCompanyInput();
                this.applyFilters();
            }
            
            updateCompanyInput() {
                const searchInput = document.getElementById('companySearchInput');
                if (!searchInput) return;
                
                if (this.filters.company_id === 'all' || !this.filters.company_id) {
                    searchInput.value = '';
                    searchInput.placeholder = t.search_company_placeholder || 'Buscar empresa...';
                } else {
                    const company = this.companies.find(c => c.id == this.filters.company_id);
                    if (company) {
                        searchInput.value = company.name;
                    }
                }
            }
            
            bindEvents() {
                // Sort filter
                document.getElementById('sortFilter').addEventListener('change', (e) => {
                    this.sortBy = e.target.value;
                    this.loadNegativeReviews();
                });
                
                // User filter
                const userFilter = document.getElementById('userFilter');
                if (userFilter) {
                    userFilter.addEventListener('change', (e) => {
                        this.filters.user_id = e.target.value;
                        this.applyFilters();
                    });
                }
                
                // Status filter
                document.getElementById('statusFilter').addEventListener('change', (e) => {
                    this.filters.status = e.target.value;
                    this.applyFilters();
                });
                
                // Period filter
                document.getElementById('periodFilter').addEventListener('change', (e) => {
                    this.filters.period = e.target.value;
                    this.applyFilters();
                });
                
                // Rating filter
                document.getElementById('ratingFilter').addEventListener('change', (e) => {
                    this.filters.rating = e.target.value;
                    this.applyFilters();
                });
                
                // Search filter with debounce
                document.getElementById('searchFilter').addEventListener('input', (e) => {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.filters.search = e.target.value.trim();
                        this.applyFilters();
                    }, 500);
                });
                
                // Clear filters button
                document.getElementById('clearFiltersBtn').addEventListener('click', () => {
                    this.clearAllFilters();
                });
            }
            
            applyFilters() {
                this.currentPage = 1;
                this.updateURL();
                this.updateActiveFilters();
                this.loadNegativeReviews();
            }
            
            clearAllFilters() {
                this.filters = {
                    company_id: 'all',
                    status: 'all',
                    period: 'all',
                    rating: 'all',
                    search: ''
                };
                
                document.getElementById('companyFilter').value = 'all';
                document.getElementById('statusFilter').value = 'all';
                document.getElementById('periodFilter').value = 'all';
                document.getElementById('ratingFilter').value = 'all';
                document.getElementById('searchFilter').value = '';
                
                this.updateURL();
                this.updateActiveFilters();
                this.loadNegativeReviews();
            }
            
            updateActiveFilters() {
                const activeFiltersDiv = document.getElementById('activeFilters');
                const clearBtn = document.getElementById('clearFiltersBtn');
                const activeFilters = [];
                
                if (this.filters.company_id !== 'all' && this.filters.company_id) {
                    const company = this.companies.find(c => c.id == this.filters.company_id);
                    activeFilters.push({
                        key: 'company',
                        label: company ? company.name : 'Empresa',
                        value: this.filters.company_id
                    });
                }
                
                if (this.filters.user_id !== 'all' && this.filters.user_id) {
                    const user = this.users.find(u => u.id == this.filters.user_id);
                    activeFilters.push({
                        key: 'user',
                        label: user ? user.name : 'Usuário',
                        value: this.filters.user_id
                    });
                }
                
                if (this.filters.status !== 'all') {
                    activeFilters.push({
                        key: 'status',
                        label: this.filters.status === 'processed' ? t.processed || 'Processadas' : t.unprocessed || 'Não Processadas',
                        value: this.filters.status
                    });
                }
                
                if (this.filters.period !== 'all') {
                    const periodLabels = {
                        today: t.today || 'Hoje',
                        week: t.last_week || 'Última Semana',
                        month: t.last_month || 'Último Mês'
                    };
                    activeFilters.push({
                        key: 'period',
                        label: periodLabels[this.filters.period] || this.filters.period,
                        value: this.filters.period
                    });
                }
                
                if (this.filters.rating !== 'all') {
                    const ratingLabels = {
                        '1': t.one_star || '1 Estrela',
                        '2': t.two_stars || '2 Estrelas',
                        '3': t.three_stars || '3 Estrelas'
                    };
                    activeFilters.push({
                        key: 'rating',
                        label: ratingLabels[this.filters.rating] || this.filters.rating + ' Estrelas',
                        value: this.filters.rating
                    });
                }
                
                if (this.filters.search) {
                    activeFilters.push({
                        key: 'search',
                        label: `"${this.filters.search}"`,
                        value: this.filters.search
                    });
                }
                
                if (activeFilters.length > 0) {
                    activeFiltersDiv.classList.remove('hidden');
                    clearBtn.classList.remove('hidden');
                    
                    activeFiltersDiv.innerHTML = `
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('reviews.active_filters') }}:</span>
                        ${activeFilters.map(filter => `
                            <span class="inline-flex items-center px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 rounded-full text-xs font-medium">
                                ${filter.label}
                                <button onclick="negativeReviewsPanel.removeFilter('${filter.key}')" class="ml-2 hover:text-red-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        `).join('')}
                    `;
                } else {
                    activeFiltersDiv.classList.add('hidden');
                    clearBtn.classList.add('hidden');
                }
            }
            
            removeFilter(key) {
                if (key === 'company') {
                    this.filters.company_id = 'all';
                    document.getElementById('companyFilter').value = 'all';
                    this.updateCompanyInput();
                } else if (key === 'user') {
                    this.filters.user_id = 'all';
                    const userFilter = document.getElementById('userFilter');
                    if (userFilter) userFilter.value = 'all';
                } else if (key === 'status') {
                    this.filters.status = 'all';
                    document.getElementById('statusFilter').value = 'all';
                } else if (key === 'period') {
                    this.filters.period = 'all';
                    document.getElementById('periodFilter').value = 'all';
                } else if (key === 'rating') {
                    this.filters.rating = 'all';
                    document.getElementById('ratingFilter').value = 'all';
                } else if (key === 'search') {
                    this.filters.search = '';
                    document.getElementById('searchFilter').value = '';
                }
                
                this.applyFilters();
            }
            
            updateURL() {
                const params = new URLSearchParams();
                
                if (this.filters.company_id !== 'all' && this.filters.company_id) params.set('company_id', this.filters.company_id);
                if (this.filters.user_id !== 'all' && this.filters.user_id) params.set('user_id', this.filters.user_id);
                if (this.filters.status !== 'all') params.set('status', this.filters.status);
                if (this.filters.period !== 'all') params.set('period', this.filters.period);
                if (this.filters.rating !== 'all') params.set('rating', this.filters.rating);
                if (this.filters.search) params.set('search', this.filters.search);
                if (this.sortBy !== 'recent') params.set('sort', this.sortBy);
                
                const newURL = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                window.history.replaceState({}, '', newURL);
            }
            
            loadFiltersFromURL() {
                const params = new URLSearchParams(window.location.search);
                
                if (params.has('company_id')) this.filters.company_id = params.get('company_id');
                if (params.has('status')) this.filters.status = params.get('status');
                if (params.has('period')) this.filters.period = params.get('period');
                if (params.has('rating')) this.filters.rating = params.get('rating');
                if (params.has('search')) this.filters.search = params.get('search');
                if (params.has('sort')) this.sortBy = params.get('sort');
                
                // Update UI
                document.getElementById('companyFilter').value = this.filters.company_id;
                document.getElementById('statusFilter').value = this.filters.status;
                document.getElementById('periodFilter').value = this.filters.period;
                document.getElementById('ratingFilter').value = this.filters.rating;
                document.getElementById('searchFilter').value = this.filters.search;                
                document.getElementById('sortFilter').value = this.sortBy;
                
                this.updateActiveFilters();
            }
            
            async loadNegativeReviews() {
                try {
                    this.showLoading();
                    
                    const params = new URLSearchParams({
                        sort: this.sortBy,
                        page: this.currentPage
                    });
                    
                    // Add filters to params
                    if (this.filters.company_id !== 'all' && this.filters.company_id) {
                        params.set('company_id', this.filters.company_id);
                    }
                    if (this.filters.user_id !== 'all' && this.filters.user_id) {
                        params.set('user_id', this.filters.user_id);
                    }
                    if (this.filters.status !== 'all') {
                        params.set('status', this.filters.status);
                    }
                    if (this.filters.period !== 'all') {
                        params.set('period', this.filters.period);
                    }
                    if (this.filters.rating !== 'all') {
                        params.set('rating', this.filters.rating);
                    }
                    if (this.filters.search) {
                        params.set('search', this.filters.search);
                    }
                    
                    const response = await fetch(`/api/reviews/negative?${params}`, {
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        this.displayNegativeReviews(result.data);
                    } else {
                        this.showError(t.error_loading_negative);
                    }
                } catch (error) {
                    console.error('Erro ao carregar avaliações negativas:', error);
                    this.showError(t.error_loading_negative);
                }
            }
            
            displayNegativeReviews(data) {
                const container = document.getElementById('reviewsContainer');
                const loadingState = document.getElementById('loadingState');
                const emptyState = document.getElementById('emptyState');
                
                loadingState.classList.add('hidden');
                
                const reviews = data.data || data;
                
                if (reviews.length === 0) {
                    emptyState.classList.remove('hidden');
                    container.classList.add('hidden');
                    const emptyMessage = emptyState.querySelector('h3');
                    if (emptyMessage) {
                        emptyMessage.textContent = t.no_results || 'Nenhuma avaliação encontrada com os filtros aplicados';
                    }
                    return;
                }
                
                emptyState.classList.add('hidden');
                container.classList.remove('hidden');
                
                container.innerHTML = reviews.map((review, index) => 
                    this.createNegativeReviewCard(review, index)
                ).join('');
            }
            
            createNegativeReviewCard(review, index) {
                const today = new Date().toDateString();
                const reviewDate = new Date(review.created_at).toDateString();
                const isToday = today === reviewDate;
                
                return `
                    <div class="p-2 sm:p-6 border-b border-gray-200 dark:border-gray-700 hover:bg-red-50 dark:hover:bg-gray-700/50 transition-all stagger-item" style="animation-delay: ${index * 0.05}s">
                        <div class="alert-card rounded-xl p-3 sm:p-6">
                            <div class="flex items-start justify-between mb-3 flex-col sm:flex-row">
                                <div class="flex items-center flex-1 w-full sm:w-auto">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-500 rounded-lg flex items-center justify-center mr-3 sm:mr-4 shadow-lg flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-white text-sm sm:text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-red-800 dark:text-red-300 text-sm sm:text-lg break-words">${review.company.name}</h3>
                                        <div class="flex items-center mt-1 flex-wrap gap-2">
                                            <span class="text-sm text-red-600 dark:text-red-400">
                                                ${isToday ? '<span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold animate-pulse">🚨 ' + t.today_badge + '</span>' : `<i class="far fa-clock mr-1"></i>${formatDate(review.created_at)}`}
                                            </span>
                                            ${!review.is_processed ? '<span class="bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300 px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap">' + t.unprocessed + '</span>' : ''}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right mt-2 sm:mt-0 w-full sm:w-auto border-t sm:border-t-0 border-red-200 dark:border-red-800 pt-2 sm:pt-0">
                                    <div class="text-xl sm:text-3xl font-bold text-red-600 dark:text-red-400 whitespace-nowrap">${review.rating}/5</div>
                                    <div class="stars-negative text-sm sm:text-xl mt-1">
                                        ${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center mb-3 gap-2">
                                <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium flex items-center justify-center sm:justify-start break-all">
                                    <i class="fab fa-whatsapp mr-1.5 flex-shrink-0 text-xs sm:text-sm"></i>
                                    <span class="break-all">${review.whatsapp}</span>
                                </div>
                                <button onclick="contactWhatsApp('${review.whatsapp}')" class="btn-primary text-white px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium review-action-btn w-full sm:w-auto flex items-center justify-center min-h-[36px] sm:min-h-[44px]">
                                    <i class="fab fa-whatsapp mr-2"></i>
                                    ${t.contact_now}
                                </button>
                            </div>
                            
                            ${review.comment ? `
                                <div class="bg-white dark:bg-gray-900/50 p-2 sm:p-4 rounded-lg border-2 border-red-200 dark:border-red-800 mb-3">
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-1">${t.client_comment}</p>
                                    <p class="text-xs sm:text-sm text-gray-800 dark:text-gray-200 italic">"${review.comment}"</p>
                                </div>
                            ` : ''}
                            
                            ${review.private_feedback ? `
                                <div class="bg-orange-50 dark:bg-orange-900/30 p-2 sm:p-4 rounded-lg border-2 border-orange-200 dark:border-orange-700 mb-3">
                                    <p class="text-xs sm:text-sm text-orange-700 dark:text-orange-400 font-medium mb-1">
                                        <i class="fas fa-lock mr-1 text-xs"></i> ${t.private_feedback}
                                    </p>
                                    <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">${review.private_feedback}</p>
                                </div>
                            ` : ''}
                            
                            <div class="flex flex-col sm:flex-row flex-wrap gap-2">
                                <button onclick="markAsProcessed(${review.id})" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition-all review-action-btn w-full sm:w-auto flex items-center justify-center min-h-[36px] sm:min-h-[44px]">
                                    <i class="fas fa-check mr-1.5 text-xs"></i>
                                    ${t.mark_as_processed}
                                </button>
                                <button onclick="sendFollowUp(${review.id})" class="bg-purple-500 hover:bg-purple-600 dark:bg-purple-600 dark:hover:bg-purple-700 text-white px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition-all review-action-btn w-full sm:w-auto flex items-center justify-center min-h-[36px] sm:min-h-[44px]">
                                    <i class="fas fa-envelope mr-1.5 text-xs"></i>
                                    ${t.send_followup}
                                </button>
                                <button onclick="addNote(${review.id})" class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium transition-all review-action-btn w-full sm:w-auto flex items-center justify-center min-h-[36px] sm:min-h-[44px]">
                                    <i class="fas fa-sticky-note mr-2"></i>
                                    ${t.add_note}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            updateStats(data) {
                // Stats cards were removed, only update results count if element exists
                const reviews = data.data || data;
                const total = data.total || reviews.length;
                
                // Update results count only if element exists
                const resultsCountEl = document.getElementById('resultsCount');
                if (resultsCountEl) {
                    resultsCountEl.textContent = total;
                }
            }
            
            showLoading() {
                document.getElementById('loadingState').classList.remove('hidden');
                document.getElementById('reviewsContainer').classList.add('hidden');
                document.getElementById('emptyState').classList.add('hidden');
            }
            
            showError(message) {
                document.getElementById('loadingState').classList.add('hidden');
                showNotification(message, 'error');
            }
        }
        
        // Global functions
        let negativeReviewsPanel;
        
        function refreshNegativeReviews() {
            negativeReviewsPanel.loadNegativeReviews();
            showNotification(t.updating_reviews, 'info');
        }
        
        function applyFilters() {
            negativeReviewsPanel.filters.company_id = document.getElementById('companyFilter').value;
            negativeReviewsPanel.filters.status = document.getElementById('statusFilter').value;
            negativeReviewsPanel.filters.rating = document.getElementById('ratingFilter').value;
            negativeReviewsPanel.filters.period = document.getElementById('periodFilter').value;
            negativeReviewsPanel.loadNegativeReviews();
        }
        
        function clearFilters() {
            document.getElementById('companyFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('ratingFilter').value = '';
            document.getElementById('periodFilter').value = '';
            document.getElementById('whatsappSearch').value = '';
            
            negativeReviewsPanel.filters = {
                company_id: '',
                status: '',
                rating: '',
                period: '',
                whatsapp: ''
            };
            
            negativeReviewsPanel.loadNegativeReviews();
        }
        
        function searchByWhatsApp() {
            const whatsapp = document.getElementById('whatsappSearch').value;
            if (whatsapp.trim()) {
                negativeReviewsPanel.filters.whatsapp = whatsapp;
                negativeReviewsPanel.loadNegativeReviews();
            }
        }
        
        function formatPhoneNumber(input) {
            // Remove all non-numeric characters
            let value = input.value.replace(/\D/g, '');
            
            // Limit to 11 digits (DDD + 9 digits)
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            // Format based on length
            if (value.length <= 2) {
                // Just DDD: (11
                input.value = value.length > 0 ? `(${value}` : '';
            } else if (value.length <= 6) {
                // DDD + first part: (11) 9999
                input.value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
            } else if (value.length <= 10) {
                // DDD + first part + second part: (11) 9999-9999
                input.value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
            } else {
                // DDD + first part + second part: (11) 99999-9999
                input.value = `(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}`;
            }
        }
        
        function contactWhatsApp(whatsapp) {
            const cleanNumber = whatsapp.replace(/[^0-9]/g, '');
            window.open(`https://wa.me/${cleanNumber}`, '_blank');
            showNotification(t.opening_whatsapp, 'success');
        }
        
        async function markAsProcessed(reviewId) {
            if (window.showConfirmModal) {
                window.showConfirmModal({
                    title: t.mark_as_processed || 'Marcar como Processado',
                    message: t.confirm_mark_processed,
                    warning: '',
                    confirmText: t.confirm || 'Confirmar',
                    cancelText: t.cancel || 'Cancelar',
                    confirmColor: 'blue',
                    onConfirm: async () => {
                        try {
                            const loader = showLoading('{{ __('app.loading') ?? 'Loading...' }}');
                            
                            // Simular API call
                            await new Promise(resolve => setTimeout(resolve, 1000));
                            
                            hideLoading();
                            showNotification(t.processed_success, 'success');
                            negativeReviewsPanel.loadNegativeReviews();
                        } catch (error) {
                            hideLoading();
                            showNotification(t.error_processing, 'error');
                        }
                    }
                });
            }
        }
        
        async function sendFollowUp(reviewId) {
            const message = prompt(t.followup_message_prompt);
            
            if (message !== null) {
                try {
                    const loader = showLoading(t.sending_followup);
                    
                    // Simular API call
                    await new Promise(resolve => setTimeout(resolve, 1000));
                    
                    hideLoading();
                    showNotification(t.followup_success, 'success');
                } catch (error) {
                    hideLoading();
                    showNotification(t.error_sending_followup, 'error');
                }
            }
        }
        
        async function addNote(reviewId) {
            const note = prompt(t.note_prompt);
            
            if (note && note.trim()) {
                try {
                    const loader = showLoading(t.saving_note);
                    
                    // Simular API call
                    await new Promise(resolve => setTimeout(resolve, 500));
                    
                    hideLoading();
                    showNotification(t.note_success, 'success');
                } catch (error) {
                    hideLoading();
                    showNotification(t.error_saving_note, 'error');
                }
            }
        }
        
        // Toggle filters on mobile
        function toggleNegativeFilters() {
            const form = document.getElementById('negativeFiltersForm');
            const icon = document.getElementById('toggleNegativeFiltersIcon');
            if (form && icon) {
                const isHidden = form.classList.contains('hidden');
                if (isHidden) {
                    form.classList.remove('hidden');
                    form.classList.add('grid', 'grid-cols-1');
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    form.classList.add('hidden');
                    form.classList.remove('grid', 'grid-cols-1');
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            }
        }
        
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            negativeReviewsPanel = new NegativeReviewsPanel();
        });
    </script>
@endsection
