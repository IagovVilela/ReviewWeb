@extends('layouts.admin')

@section('title', __('reviews.title') . ' - ' . __('app.name'))

@section('page-title', __('reviews.dashboard_title'))
@section('page-description', __('reviews.dashboard_description'))

@section('header-actions')
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
        <button onclick="exportContacts(this)" class="bg-green-500 text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm sm:text-base font-medium hover:bg-green-600 transition-colors min-h-[36px] sm:min-h-[44px] flex items-center justify-center flex-1 sm:flex-none">
            <i class="fas fa-download mr-1.5 sm:mr-2 text-xs sm:text-sm"></i>
            <span class="hidden sm:inline">{{ __('reviews.export_contacts') }}</span>
            <span class="sm:hidden uppercase text-xs">{{ strtoupper(__('reviews.export_contacts')) }}</span>
        </button>
        <button onclick="refreshReviews()" class="btn-primary text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm sm:text-base font-medium min-h-[36px] sm:min-h-[44px] flex items-center justify-center flex-1 sm:flex-none">
            <i class="fas fa-sync-alt mr-1.5 sm:mr-2 text-xs sm:text-sm"></i>
            <span class="hidden sm:inline">{{ __('reviews.update') }}</span>
            <span class="sm:hidden uppercase text-xs">{{ strtoupper(__('reviews.update')) }}</span>
        </button>
    </div>
@endsection

@section('styles')
    <style>
        .rating-positive {
            background: #10b981;
        }
        
        .rating-negative {
            background: #ef4444;
        }
        
        .pulse-alert {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            opacity: 0;
            animation: chartFadeIn 0.8s ease-out forwards;
            width: 100%;
            overflow: hidden;
        }
        
        @keyframes chartFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .chart-container canvas {
            animation: chartScale 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            animation-delay: 0.2s;
            transform: scale(0.95);
            max-width: 100%;
            height: auto !important;
        }
        
        @keyframes chartScale {
            to {
                transform: scale(1);
            }
        }
        
        .table-container {
            max-height: 500px;
            overflow-y: auto;
        }
        
        /* Melhorias mobile - Elementos compactos */
        @media (max-width: 640px) {
            /* Cards mais compactos */
            .bg-white.rounded-xl.shadow-sm,
            .dark .bg-gray-800.rounded-xl.shadow-sm {
                padding: 1rem !important;
            }
            
            /* Títulos menores */
            h2.text-xl,
            h3.text-lg {
                font-size: 1.125rem !important;
                margin-bottom: 0.75rem !important;
            }
            
            /* Botões de ação mais compactos */
            button[onclick="exportContacts(this)"],
            button[onclick="refreshReviews()"] {
                font-size: 0.75rem !important;
                padding: 0.5rem 0.75rem !important;
                min-height: 36px !important;
            }
            
            /* Labels e textos menores */
            label.block.text-sm,
            .text-sm {
                font-size: 0.8125rem !important;
            }
            
            /* Espaçamento reduzido */
            .space-y-4 > * + *,
            .space-y-6 > * + * {
                margin-top: 0.75rem !important;
            }
            
            /* Botões de filtro mais compactos */
            button[onclick="applyFilters()"],
            button[onclick="clearFilters()"] {
                font-size: 0.75rem !important;
                padding: 0.5rem 0.75rem !important;
                min-height: 36px !important;
            }
        }
        
        /* Responsive table wrapper */
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table-responsive table {
                min-width: 600px;
            }
            
            /* Charts mobile */
            .chart-container {
                height: 250px !important;
                max-width: 100%;
                overflow: hidden;
                padding: 0.5rem !important;
            }
            
            .chart-container canvas {
                max-width: 100% !important;
                width: 100% !important;
                height: 100% !important;
            }
            
            /* Chart cards mobile */
            .bg-white.rounded-xl.p-6.shadow-sm,
            .dark .bg-gray-800.rounded-xl.p-6.shadow-sm {
                padding: 1rem !important;
                overflow: hidden;
                max-width: 100%;
            }
            
            /* Chart header mobile */
            .flex.items-center.justify-between.mb-4 {
                flex-wrap: wrap;
                gap: 0.75rem;
            }
            
            /* Chart period buttons mobile */
            .chart-period-btn {
                min-height: 32px;
                padding: 0.5rem 0.75rem !important;
                font-size: 0.75rem;
            }
            
            /* Prevent horizontal scroll on charts */
            .grid.grid-cols-1.lg\\:grid-cols-2 {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            
            /* Reviews container mobile */
            #reviewsContainer {
                overflow-x: hidden;
                padding-bottom: 1rem;
            }
            
            /* Review cards mobile */
            #reviewsContainer > div {
                overflow: visible !important;
            }
            
            /* Pagination mobile */
            #paginationContainer {
                padding-bottom: 2rem !important;
                margin-bottom: 1rem;
            }
            
            #paginationContainer .flex.items-center.justify-between {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            
            #paginationContainer .flex.space-x-2 {
                justify-content: center;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            #paginationContainer button {
                min-height: 44px;
                padding: 0.75rem 1rem;
            }
            
            /* Company search input - prevent icon overlap */
            #companySearchInput {
                font-size: 16px !important;
                min-height: 44px;
                padding-left: 2.75rem !important;
                padding-right: 1rem !important;
                padding-top: 0.75rem !important;
                padding-bottom: 0.75rem !important;
                width: 100%;
                box-sizing: border-box;
            }
            
            /* Company search icon positioning */
            #companySearchInput + .fa-search {
                left: 0.875rem !important;
                z-index: 10;
                pointer-events: none;
            }
            
            /* All select inputs mobile */
            select {
                font-size: 16px !important;
                min-height: 44px;
                padding: 0.75rem 1rem !important;
            }
            
            /* All text inputs mobile */
            input[type="text"],
            input[type="email"],
            input[type="number"] {
                font-size: 16px !important;
                min-height: 44px;
                padding: 0.75rem 1rem !important;
            }
        }
        
        .chart-period-btn {
            transition: var(--transition-smooth);
        }
        
        .chart-period-btn.active {
            background: var(--primary-color);
            color: white;
            transform: scale(1.05);
        }
        
        .chart-period-btn:hover:not(.active) {
            background: rgba(139, 92, 246, 0.1);
            color: var(--primary-color);
        }
        
        .trend-up {
            color: #10b981;
        }
        
        .trend-down {
            color: #ef4444;
        }
        
            /* Filtros mais compactos no mobile */
            @media (max-width: 767px) {
                #reviewsFiltersForm {
                    gap: 0.75rem !important;
                }
                #reviewsFiltersForm > div {
                    margin-bottom: 0.5rem;
                }
                #reviewsFiltersForm label {
                    font-size: 0.75rem !important;
                    margin-bottom: 0.375rem !important;
                    line-height: 1.2;
                }
                #reviewsFiltersForm input,
                #reviewsFiltersForm select {
                    font-size: 14px !important;
                    padding-top: 0.5rem !important;
                    padding-bottom: 0.5rem !important;
                    min-height: 36px !important;
                    height: 36px !important;
                }
                #reviewsFiltersForm button {
                    font-size: 0.875rem !important;
                    padding-top: 0.5rem !important;
                    padding-bottom: 0.5rem !important;
                    min-height: 36px !important;
                }
                
                /* Container de filtros mais compacto */
                #filtersContainer {
                    padding: 0.75rem !important;
                    margin-bottom: 1rem !important;
                }
                #filtersContainer > div {
                    padding: 0.75rem !important;
                }
            }
    </style>
@endsection

@section('content')
    <!-- Filters -->
    <div id="filtersContainer" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-3 md:p-6 mb-4 md:mb-6">
        <div class="flex items-center justify-between mb-3 md:mb-4">
            <h3 class="text-sm md:text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('reviews.filters') }}</h3>
            <button type="button" id="toggleFiltersBtn" class="md:hidden text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors" onclick="toggleReviewsFilters()">
                <i class="fas fa-chevron-down" id="toggleReviewsFiltersIcon"></i>
            </button>
        </div>
        <div id="reviewsFiltersForm" class="hidden md:flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2 md:gap-3 lg:gap-4">
            <!-- Company Filter (with search) -->
            <div class="flex-1 min-w-[200px] w-full md:w-auto">
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('reviews.company') }}</label>
                <div class="relative" id="companyFilterWrapper">
                    <div class="relative">
                        <input 
                            type="text" 
                            id="companySearchInput" 
                            placeholder="{{ __('reviews.search_company_placeholder') }}"
                            class="w-full px-2 md:px-3 py-1.5 md:py-2 pl-8 md:pl-10 pr-4 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            style="font-size: 14px; min-height: 36px; padding-left: 2rem;"
                            autocomplete="off"
                        >
                        <i class="fas fa-search absolute left-2 md:left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none z-10"></i>
                        <input type="hidden" id="companyFilter" value="">
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
            <div class="flex-1 min-w-[150px] w-full md:w-auto">
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('reviews.filter_by_user') }}</label>
                <select id="userFilter" class="w-full px-2 md:px-3 py-1.5 md:py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent" style="font-size: 14px; min-height: 36px;">
                    <option value="">{{ __('reviews.all_users') }}</option>
                    <!-- Users will be loaded dynamically -->
                </select>
            </div>
            @endif
            
            <!-- Type Filter -->
            <div class="flex-1 min-w-[150px] w-full md:w-auto">
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('reviews.type') }}</label>
                <select id="typeFilter" class="w-full px-2 md:px-3 py-1.5 md:py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent" style="font-size: 14px; min-height: 36px;">
                    <option value="">{{ __('reviews.all_types') }}</option>
                    <option value="positive">{{ __('reviews.positive_ratings') }}</option>
                    <option value="negative">{{ __('reviews.negative_ratings') }}</option>
                </select>
            </div>
            
            <!-- Rating Filter -->
            <div class="flex-1 min-w-[150px] w-full md:w-auto">
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('reviews.rating') }}</label>
                <select id="ratingFilter" class="w-full px-2 md:px-3 py-1.5 md:py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent" style="font-size: 14px; min-height: 36px;">
                    <option value="">{{ __('reviews.all_ratings') }}</option>
                    <option value="5">5 {{ __('reviews.rating_label') }}</option>
                    <option value="4">4 {{ __('reviews.rating_label') }}</option>
                    <option value="3">3 {{ __('reviews.rating_label') }}</option>
                    <option value="2">2 {{ __('reviews.rating_label') }}</option>
                    <option value="1">1 {{ __('reviews.rating_label_singular') }}</option>
                </select>
            </div>
            
            <!-- Date Filter -->
            <div class="flex-1 min-w-[150px] w-full md:w-auto">
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('reviews.period') }}</label>
                <select id="dateFilter" class="w-full px-2 md:px-3 py-1.5 md:py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent" style="font-size: 14px; min-height: 36px;">
                    <option value="">{{ __('reviews.all_periods') }}</option>
                    <option value="today">{{ __('reviews.today') }}</option>
                    <option value="week">{{ __('reviews.this_week') }}</option>
                    <option value="month">{{ __('reviews.this_month') }}</option>
                </select>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-end space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                <button onclick="applyFilters()" class="btn-primary text-white px-3 md:px-4 py-1.5 md:py-2 rounded-lg font-medium w-full sm:w-auto text-sm md:text-base" style="min-height: 36px;">
                    <i class="fas fa-filter mr-2"></i>
                    {{ __('reviews.apply') }}
                </button>
                <button onclick="clearFilters()" class="btn-secondary text-white px-3 md:px-4 py-1.5 md:py-2 rounded-lg font-medium w-full sm:w-auto text-sm md:text-base" style="min-height: 36px;">
                    <i class="fas fa-times mr-2"></i>
                    {{ __('reviews.clear') }}
                </button>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
        <!-- Reviews Over Time Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 card-hover">
                <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('reviews.reviews_over_time') }}</h3>
                <div class="flex space-x-2">
                    <button onclick="updateChartPeriod('7d', this)" class="chart-period-btn active px-3 py-1 text-xs rounded-full bg-purple-500 text-white">{{ __('reviews.days_7') }}</button>
                    <button onclick="updateChartPeriod('30d', this)" class="chart-period-btn px-3 py-1 text-xs rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ __('reviews.days_30') }}</button>
                    <button onclick="updateChartPeriod('90d', this)" class="chart-period-btn px-3 py-1 text-xs rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ __('reviews.days_90') }}</button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="reviewsOverTimeChart"></canvas>
            </div>
            <div id="reviewsOverTimeNoData" class="hidden flex flex-col items-center justify-center h-full min-h-[300px] text-center p-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                    <i class="fas fa-chart-line text-gray-400 dark:text-gray-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ __('reviews.chart_no_data') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('reviews.chart_no_data_desc') }}</p>
            </div>
        </div>
        
        <!-- Rating Distribution Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 card-hover">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('reviews.rating_distribution') }}</h3>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('reviews.positivas') }}</span>
                    <div class="w-3 h-3 bg-red-500 rounded-full ml-4"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('reviews.negativas') }}</span>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="ratingDistributionChart"></canvas>
            </div>
            <div id="ratingDistributionNoData" class="hidden flex flex-col items-center justify-center h-full min-h-[300px] text-center p-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                    <i class="fas fa-chart-pie text-gray-400 dark:text-gray-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ __('reviews.chart_no_data') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('reviews.chart_no_data_desc') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Company Performance Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 card-hover mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('reviews.company_performance') }}</h3>
            <button onclick="exportCompanyData()" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-600 transition-colors">
                <i class="fas fa-download mr-2"></i>
                {{ __('reviews.export_data') }}
            </button>
        </div>
        <div class="table-container table-responsive">
            <table class="w-full text-sm min-w-full">
                <thead class="bg-gray-50 dark:bg-gray-900 sticky top-0">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">{{ __('reviews.empresa') }}</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">{{ __('reviews.total') }}</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">{{ __('reviews.positivas') }}</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">{{ __('reviews.negativas') }}</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">{{ __('reviews.media') }}</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">{{ __('reviews.ultima') }}</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">{{ __('reviews.acoes') }}</th>
                    </tr>
                </thead>
                <tbody id="companyPerformanceTable">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Reviews List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('reviews.reviews_list') }}</h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('reviews.last_reviews') }}</p>
        </div>
        
        <!-- Loading State - Skeleton Screens -->
        <div id="loadingState" class="space-y-4">
            <!-- Skeleton Review Card 1 -->
            <div class="skeleton-card">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center flex-1">
                        <div class="skeleton-avatar"></div>
                        <div class="flex-1">
                            <div class="skeleton-line w-1-2"></div>
                            <div class="skeleton-line w-1-4"></div>
                        </div>
                    </div>
                    <div class="w-24">
                        <div class="skeleton-line w-full"></div>
                    </div>
                </div>
                <div class="skeleton-line w-full"></div>
                <div class="skeleton-line w-3-4"></div>
            </div>
            
            <!-- Skeleton Review Card 2 -->
            <div class="skeleton-card">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center flex-1">
                        <div class="skeleton-avatar"></div>
                        <div class="flex-1">
                            <div class="skeleton-line w-1-2"></div>
                            <div class="skeleton-line w-1-4"></div>
                        </div>
                    </div>
                    <div class="w-24">
                        <div class="skeleton-line w-full"></div>
                    </div>
                </div>
                <div class="skeleton-line w-full"></div>
                <div class="skeleton-line w-1-2"></div>
            </div>
            
            <!-- Skeleton Review Card 3 -->
            <div class="skeleton-card">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center flex-1">
                        <div class="skeleton-avatar"></div>
                        <div class="flex-1">
                            <div class="skeleton-line w-1-2"></div>
                            <div class="skeleton-line w-1-4"></div>
                        </div>
                    </div>
                    <div class="w-24">
                        <div class="skeleton-line w-full"></div>
                    </div>
                </div>
                <div class="skeleton-line w-full"></div>
                <div class="skeleton-line w-3-4"></div>
                <div class="skeleton-line w-1-2"></div>
            </div>
            <p class="text-gray-600 dark:text-gray-400">{{ __('reviews.loading') }}</p>
        </div>
        
        <!-- Reviews Container -->
        <div id="reviewsContainer" class="hidden">
            <!-- Reviews will be loaded here -->
        </div>
        
        <!-- Empty State -->
        <div id="emptyState" class="hidden p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                <i class="fas fa-star text-gray-400 dark:text-gray-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ __('reviews.no_reviews') }}</h3>
            <p class="text-gray-600 dark:text-gray-400">{{ __('reviews.no_reviews_desc') }}</p>
        </div>
        
        <!-- Pagination -->
        <div id="paginationContainer" class="hidden p-4 sm:p-6 border-t border-gray-200 dark:border-gray-700 pb-6 sm:pb-6">
            <!-- Pagination will be loaded here -->
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        // Translations for JavaScript
        const translations = {
            pt_BR: {
                monday: 'Seg', tuesday: 'Ter', wednesday: 'Qua', thursday: 'Qui', 
                friday: 'Sex', saturday: 'Sáb', sunday: 'Dom',
                positivas: 'Positivas', negativas: 'Negativas',
                all_companies_filter: 'Todas as Empresas',
                search_company_placeholder: 'Buscar empresa...',
                no_companies_found: 'Nenhuma empresa encontrada',
                company_owner: 'Proprietário',
                positive: @json(__('reviews.positive')),
                negative: @json(__('reviews.negative')),
                contact: @json(__('reviews.contact')),
                process: @json(__('reviews.process')),
                delete: @json(__('reviews.delete')),
                view: @json(__('reviews.view'))
            },
            en_US: {
                monday: 'Mon', tuesday: 'Tue', wednesday: 'Wed', thursday: 'Thu',
                friday: 'Fri', saturday: 'Sat', sunday: 'Sun',
                positivas: 'Positive', negativas: 'Negative',
                all_companies_filter: 'All Companies',
                search_company_placeholder: 'Search company...',
                no_companies_found: 'No companies found',
                company_owner: 'Owner',
                positive: @json(__('reviews.positive')),
                negative: @json(__('reviews.negative')),
                contact: @json(__('reviews.contact')),
                process: @json(__('reviews.process')),
                delete: @json(__('reviews.delete')),
                view: @json(__('reviews.view'))
            }
        };
        
        const currentLang = '{{ app()->getLocale() }}';
        const t = translations[currentLang] || translations.pt_BR;
        
        // Função para formatar data
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
        
        class ReviewsPanel {
            constructor() {
                this.currentPage = 1;
                this.filters = {
                    company_id: '',
                    user_id: '',
                    type: '',
                    rating: '',
                    date: ''
                };
                this.companies = [];
                this.users = [];
                this.companySearchTerm = '';
                this.charts = {};
                this.chartPeriod = 7; // Default to 7 days
                this.allReviews = []; // Store all reviews for chart updates
                this.init();
            }
            
            async init() {
                await Promise.all([
                    this.loadCompanies(),
                    this.loadUsers()
                ]);
                this.initializeCharts();
                await this.loadReviews();
                this.initCompanySearch();
                this.bindEvents();
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
                
                searchInput.addEventListener('focus', () => {
                    if (this.companies.length > 0) {
                        dropdown.classList.remove('hidden');
                        this.renderCompanyOptions();
                    }
                });
                
                searchInput.addEventListener('input', (e) => {
                    this.companySearchTerm = e.target.value.toLowerCase();
                    this.renderCompanyOptions();
                    dropdown.classList.remove('hidden');
                });
                
                document.addEventListener('click', (e) => {
                    if (!wrapper.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
                
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
                
                let html = `
                    <div class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 ${!this.filters.company_id ? 'bg-purple-50 dark:bg-purple-900/30' : ''}" 
                         onclick="reviewsPanel.selectCompany(null)">
                        <div class="font-medium text-gray-900 dark:text-gray-100">${t.all_companies_filter || 'Todas as Empresas'}</div>
                    </div>
                `;
                
                filtered.forEach(company => {
                    const isSelected = this.filters.company_id == company.id;
                    html += `
                        <div class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 ${isSelected ? 'bg-purple-50 dark:bg-purple-900/30' : ''}" 
                             onclick="reviewsPanel.selectCompany(${company.id}, '${company.name.replace(/'/g, "\\'")}')">
                            <div class="font-medium text-gray-900 dark:text-gray-100">${company.name}</div>
                            ${company.user_name ? `<div class="text-xs text-gray-500 dark:text-gray-400">${t.company_owner || 'Proprietário'}: ${company.user_name}</div>` : ''}
                        </div>
                    `;
                });
                
                optionsDiv.innerHTML = html;
            }
            
            selectCompany(companyId, companyName) {
                this.filters.company_id = companyId || '';
                document.getElementById('companyFilter').value = companyId || '';
                
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
                
                if (!this.filters.company_id) {
                    searchInput.value = '';
                    searchInput.placeholder = t.search_company_placeholder || 'Buscar empresa...';
                } else {
                    const company = this.companies.find(c => c.id == this.filters.company_id);
                    if (company) {
                        searchInput.value = company.name;
                    }
                }
            }
            
            async loadAllReviewsForChart() {
                try {
                    const params = new URLSearchParams({
                        per_page: 10000, // Carregar muitos reviews de uma vez para o gráfico
                        page: 1
                    });
                    
                    // Só adicionar filtros se tiverem valores (não vazios)
                    // Quando não há filtros, o backend retorna todos os reviews de todas as empresas
                    // Usar comparações explícitas para evitar tratar '0' como falsy
                    if (this.filters.company_id !== '' && this.filters.company_id !== 'all' && this.filters.company_id !== null && this.filters.company_id !== undefined) {
                        params.set('company_id', this.filters.company_id);
                    }
                    if (this.filters.user_id !== '' && this.filters.user_id !== 'all' && this.filters.user_id !== null && this.filters.user_id !== undefined) {
                        params.set('user_id', this.filters.user_id);
                    }
                    if (this.filters.type !== '' && this.filters.type !== 'all' && this.filters.type !== null && this.filters.type !== undefined) {
                        params.set('type', this.filters.type);
                    }
                    if (this.filters.rating !== '' && this.filters.rating !== 'all' && this.filters.rating !== null && this.filters.rating !== undefined) {
                        params.set('rating', this.filters.rating);
                    }
                    if (this.filters.date !== '' && this.filters.date !== 'all' && this.filters.date !== null && this.filters.date !== undefined) {
                        params.set('date', this.filters.date);
                    }
                    
                    const response = await fetch(`/api/reviews?${params}`, {
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    const result = await response.json();
                    
                    if (result.success && result.data && result.data.data) {
                        this.allReviews = result.data.data;
                        this.updateChartsWithRealData(this.allReviews);
                        return this.allReviews;
                    } else {
                        // Se não retornou dados, tentar carregar todas as páginas
                        let allReviews = [];
                        let currentPage = 1;
                        let hasMore = true;
                        
                        while (hasMore) {
                            params.set('page', currentPage);
                            params.set('per_page', 100); // Usar 100 por página para não sobrecarregar
                            
                            const pageResponse = await fetch(`/api/reviews?${params}`, {
                                credentials: 'include',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            });
                            const pageResult = await pageResponse.json();
                            
                            if (pageResult.success && pageResult.data && pageResult.data.data) {
                                allReviews = allReviews.concat(pageResult.data.data);
                                
                                // Verificar se há mais páginas
                                const totalPages = pageResult.data.last_page || 1;
                                hasMore = currentPage < totalPages;
                                currentPage++;
                            } else {
                                hasMore = false;
                            }
                        }
                        
                        this.allReviews = allReviews;
                        this.updateChartsWithRealData(allReviews);
                        return allReviews;
                    }
                } catch (error) {
                    console.error('Erro ao carregar todos os reviews para o gráfico:', error);
                    return [];
                }
            }
            
            async loadReviews() {
                try {
                    this.showLoading();
                    
                    const params = new URLSearchParams({
                        page: this.currentPage
                    });
                    
                    // Só adicionar filtros se tiverem valores (não vazios)
                    // Quando não há filtros, o backend retorna todos os reviews de todas as empresas
                    if (this.filters.company_id && this.filters.company_id !== '' && this.filters.company_id !== 'all') {
                        params.set('company_id', this.filters.company_id);
                    }
                    if (this.filters.user_id && this.filters.user_id !== '' && this.filters.user_id !== 'all') {
                        params.set('user_id', this.filters.user_id);
                    }
                    if (this.filters.type && this.filters.type !== '' && this.filters.type !== 'all') {
                        params.set('type', this.filters.type);
                    }
                    if (this.filters.rating && this.filters.rating !== '' && this.filters.rating !== 'all') {
                        params.set('rating', this.filters.rating);
                    }
                    if (this.filters.date && this.filters.date !== '' && this.filters.date !== 'all') {
                        params.set('date', this.filters.date);
                    }
                    
                    const response = await fetch(`/api/reviews?${params}`, {
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        // Store all reviews for chart updates - result.data is paginated, result.data.data has the reviews
                        const reviews = result.data.data || result.data;
                        
                        this.displayReviews(result.data);
                        this.updateCompanyPerformanceTable(result.data);
                        
                        // Carregar todos os reviews para o gráfico (sem paginação)
                        await this.loadAllReviewsForChart();
                    } else {
                        this.showError('Erro ao carregar avaliações');
                    }
                } catch (error) {
                    console.error('Erro ao carregar avaliações:', error);
                    this.showError('Erro ao carregar avaliações');
                }
            }
            
            initializeCharts() {
                // Reviews Over Time Chart - inicializado com dados vazios
                const reviewsOverTimeCtx = document.getElementById('reviewsOverTimeChart').getContext('2d');
                this.charts.reviewsOverTime = new Chart(reviewsOverTimeCtx, {
                    type: 'line',
                    data: {
                        labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                        datasets: [{
                            label: 'Positivas',
                            data: [0, 0, 0, 0, 0, 0, 0], // Dados vazios
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }, {
                            label: 'Negativas',
                            data: [0, 0, 0, 0, 0, 0, 0], // Dados vazios
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#ef4444',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        resizeDelay: 0,
                        layout: {
                            padding: {
                                left: 5,
                                right: 5,
                                top: 5,
                                bottom: 5
                            }
                        },
                        animation: {
                            duration: 1500,
                            easing: 'easeInOutQuart',
                            delay: (context) => {
                                let delay = 0;
                                if (context.type === 'data' && context.mode === 'default') {
                                    delay = context.dataIndex * 100;
                                }
                                return delay;
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: window.innerWidth < 768 ? 8 : 15,
                                    font: {
                                        size: window.innerWidth < 768 ? 11 : 13
                                    },
                                    boxWidth: window.innerWidth < 768 ? 10 : 12,
                                    boxHeight: window.innerWidth < 768 ? 10 : 12
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: window.innerWidth < 768 ? 90 : 45,
                                    minRotation: window.innerWidth < 768 ? 90 : 0,
                                    maxTicksLimit: window.innerWidth < 768 ? 7 : 12,
                                    font: {
                                        size: window.innerWidth < 768 ? 10 : 12
                                    },
                                    padding: window.innerWidth < 768 ? 3 : 8
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    precision: 0,
                                    font: {
                                        size: window.innerWidth < 768 ? 10 : 12
                                    },
                                    padding: window.innerWidth < 768 ? 3 : 8
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });
                
                // Rating Distribution Chart - inicializado com dados vazios
                const ratingDistributionCtx = document.getElementById('ratingDistributionChart').getContext('2d');
                this.charts.ratingDistribution = new Chart(ratingDistributionCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['5★', '4★', '3★', '2★', '1★'],
                        datasets: [{
                            data: [0, 0, 0, 0, 0], // Dados vazios
                            backgroundColor: [
                                '#10b981',
                                '#34d399',
                                '#fbbf24',
                                '#f59e0b',
                                '#ef4444'
                            ],
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        resizeDelay: 0,
                        layout: {
                            padding: {
                                left: 5,
                                right: 5,
                                top: 5,
                                bottom: 5
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: window.innerWidth < 768 ? 8 : 15,
                                    usePointStyle: true,
                                    font: {
                                        size: window.innerWidth < 768 ? 11 : 13
                                    },
                                    boxWidth: window.innerWidth < 768 ? 10 : 12,
                                    boxHeight: window.innerWidth < 768 ? 10 : 12
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += context.parsed + ' avaliações';
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
            
            updateChartsWithRealData(reviews) {
                console.log('updateChartsWithRealData chamado', reviews);
                
                // Verificar se há dados
                const hasData = reviews && reviews.length > 0;
                
                if (!hasData) {
                    console.log('Sem reviews para atualizar gráficos');
                    // Esconder gráficos e mostrar mensagens
                    this.showChartNoData('reviewsOverTime');
                    this.showChartNoData('ratingDistribution');
                    return;
                }
                
                // Mostrar gráficos e esconder mensagens
                this.hideChartNoData('reviewsOverTime');
                this.hideChartNoData('ratingDistribution');
                
                // Update rating distribution chart
                const ratingCounts = [0, 0, 0, 0, 0];
                reviews.forEach(review => {
                    if (review.rating >= 1 && review.rating <= 5) {
                        ratingCounts[5 - review.rating]++;
                    }
                });
                
                // Verificar se há pelo menos uma avaliação com rating válido
                const hasRatingData = ratingCounts.some(count => count > 0);
                
                if (hasRatingData && this.charts.ratingDistribution) {
                    this.charts.ratingDistribution.data.datasets[0].data = ratingCounts;
                    this.charts.ratingDistribution.update();
                } else {
                    this.showChartNoData('ratingDistribution');
                }
                
                // Update reviews over time chart
                this.updateReviewsOverTimeChart(reviews);
            }
            
            showChartNoData(chartName) {
                const chartContainer = document.querySelector(`#${chartName}Chart`)?.parentElement;
                const noDataDiv = document.getElementById(`${chartName}NoData`);
                
                if (chartContainer) {
                    chartContainer.style.display = 'none';
                }
                if (noDataDiv) {
                    noDataDiv.classList.remove('hidden');
                }
            }
            
            hideChartNoData(chartName) {
                const chartContainer = document.querySelector(`#${chartName}Chart`)?.parentElement;
                const noDataDiv = document.getElementById(`${chartName}NoData`);
                
                if (chartContainer) {
                    chartContainer.style.display = 'block';
                }
                if (noDataDiv) {
                    noDataDiv.classList.add('hidden');
                }
            }
            
            updateReviewsOverTimeChart(reviews) {
                if (!reviews || reviews.length === 0 || !this.charts.reviewsOverTime) {
                    this.showChartNoData('reviewsOverTime');
                    return;
                }
                
                const today = new Date();
                const dateRanges = [];
                const labels = [];
                const positiveData = [];
                const negativeData = [];
                
                // Create array of dates based on period
                const period = this.chartPeriod;
                
                if (period === 7) {
                    // Last 7 days - show day names
                    for (let i = period - 1; i >= 0; i--) {
                        const date = new Date(today);
                        date.setDate(date.getDate() - i);
                        date.setHours(0, 0, 0, 0);
                        dateRanges.push(date);
                        
                        const dayNames = [
                            t.sunday, t.monday, t.tuesday, t.wednesday, t.thursday, t.friday, t.saturday
                        ];
                        labels.push(dayNames[date.getDay()]);
                    }
                } else if (period === 30) {
                    // Last 30 days - show every 5 days
                    for (let i = period - 1; i >= 0; i -= 5) {
                        const date = new Date(today);
                        date.setDate(date.getDate() - i);
                        date.setHours(0, 0, 0, 0);
                        dateRanges.push(date);
                        
                        labels.push(`${date.getDate()}/${date.getMonth() + 1}`);
                    }
                } else if (period === 90) {
                    // Last 90 days - show every 15 days
                    for (let i = period - 1; i >= 0; i -= 15) {
                        const date = new Date(today);
                        date.setDate(date.getDate() - i);
                        date.setHours(0, 0, 0, 0);
                        dateRanges.push(date);
                        
                        labels.push(`${date.getDate()}/${date.getMonth() + 1}`);
                    }
                }
                
                // Count reviews for each date range
                dateRanges.forEach((date, index) => {
                    let positiveCount = 0;
                    let negativeCount = 0;
                    
                    // Get the range for this data point
                    const nextDate = dateRanges[index + 1] || new Date(today.getTime() + 86400000);
                    
                    reviews.forEach(review => {
                        try {
                            const reviewDate = new Date(review.created_at);
                            if (isNaN(reviewDate.getTime())) {
                                console.error('Data inválida:', review.created_at);
                                return;
                            }
                            reviewDate.setHours(0, 0, 0, 0);
                            
                            // For 7 days, match exact day. For others, match range
                            if (period === 7) {
                                // Compare by date string to avoid timezone issues
                                const reviewDateStr = reviewDate.toISOString().split('T')[0];
                                const rangeDateStr = date.toISOString().split('T')[0];
                                
                                if (reviewDateStr === rangeDateStr) {
                                    if (review.is_positive) {
                                        positiveCount++;
                                    } else {
                                        negativeCount++;
                                    }
                                }
                            } else {
                                if (reviewDate >= date && reviewDate < nextDate) {
                                    if (review.is_positive) {
                                        positiveCount++;
                                    } else {
                                        negativeCount++;
                                    }
                                }
                            }
                        } catch (error) {
                            console.error('Erro ao processar review:', error, review);
                        }
                    });
                    
                    positiveData.push(positiveCount);
                    negativeData.push(negativeCount);
                });
                
                // Verificar se há dados para exibir
                const hasData = positiveData.some(count => count > 0) || negativeData.some(count => count > 0);
                
                if (!hasData) {
                    this.showChartNoData('reviewsOverTime');
                    return;
                }
                
                // Mostrar gráfico e esconder mensagem
                this.hideChartNoData('reviewsOverTime');
                
                // Update chart
                this.charts.reviewsOverTime.data.labels = labels;
                this.charts.reviewsOverTime.data.datasets[0].data = positiveData;
                this.charts.reviewsOverTime.data.datasets[1].data = negativeData;
                this.charts.reviewsOverTime.update('active');
            }
            
            changeChartPeriod(period) {
                this.chartPeriod = period;
                // Recarregar todos os reviews para garantir que o gráfico tenha todos os dados
                if (this.allReviews.length === 0) {
                    this.loadAllReviewsForChart().then(() => {
                        this.updateReviewsOverTimeChart(this.allReviews);
                    });
                } else {
                this.updateReviewsOverTimeChart(this.allReviews);
                }
            }
            
            displayReviews(data) {
                const container = document.getElementById('reviewsContainer');
                const loadingState = document.getElementById('loadingState');
                const emptyState = document.getElementById('emptyState');
                
                loadingState.classList.add('hidden');
                const reviews = data.data || data;
                
                if (reviews.length === 0) {
                    emptyState.classList.remove('hidden');
                    container.classList.add('hidden');
                    return;
                }
                
                emptyState.classList.add('hidden');
                container.classList.remove('hidden');
                
                container.innerHTML = reviews.map(review => this.createReviewCard(review)).join('');
                
                if (data.last_page) {
                    this.updatePagination(data);
                }
            }
            
            createReviewCard(review) {
                const isPositive = review.is_positive;
                const ratingClass = isPositive ? 'rating-positive' : 'rating-negative';
                const typeText = isPositive ? t.positive : t.negative;
                const typeIcon = isPositive ? 'fa-thumbs-up' : 'fa-exclamation-triangle';
                
                return `
                    <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors fade-in overflow-hidden">
                        <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
                            <div class="flex-1 w-full min-w-0">
                                <div class="flex items-center mb-3">
                                    <div class="w-12 h-12 ${ratingClass} rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                        <i class="fas ${typeIcon} text-white text-lg"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 truncate">${review.company.name}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">${typeText} • ${formatDate(review.created_at)}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center mb-3">
                                    <div class="flex text-yellow-400 mr-3 flex-shrink-0">
                                        ${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}
                                    </div>
                                    <span class="text-lg font-semibold text-gray-800 dark:text-gray-100">${review.rating}/5</span>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 mb-3">
                                    <div class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-3 py-2 rounded-full text-sm font-medium flex items-center break-all sm:break-normal">
                                        <i class="fab fa-whatsapp mr-2 flex-shrink-0"></i>
                                        <span class="truncate">${review.whatsapp}</span>
                                    </div>
                                    <button onclick="contactWhatsApp('${review.whatsapp}')" class="bg-green-500 text-white px-4 py-2 rounded-full text-sm hover:bg-green-600 transition-colors flex items-center justify-center min-h-[44px] flex-shrink-0">
                                        <i class="fab fa-whatsapp mr-2"></i>
                                        ${t.contact}
                                    </button>
                                </div>
                                
                                ${review.comment ? `
                                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg mb-3">
                                        <p class="text-gray-700 dark:text-gray-300 italic break-words">"${review.comment}"</p>
                                    </div>
                                ` : ''}
                            </div>
                            
                            <div class="flex flex-row sm:flex-col space-x-2 sm:space-x-0 sm:space-y-2 w-full sm:w-auto sm:ml-4 flex-shrink-0">
                                <button onclick="markAsProcessed(${review.id})" class="flex-1 sm:flex-none bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600 transition-colors flex items-center justify-center min-h-[44px]">
                                    <i class="fas fa-check mr-2"></i>
                                    ${t.process}
                                </button>
                                <button onclick="deleteReview(${review.id})" class="flex-1 sm:flex-none bg-red-500 text-white px-4 py-2 rounded text-sm hover:bg-red-600 transition-colors flex items-center justify-center min-h-[44px]">
                                    <i class="fas fa-trash mr-2"></i>
                                    ${t.delete}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            updateCompanyPerformanceTable(data) {
                const tbody = document.getElementById('companyPerformanceTable');
                const reviews = data.data || data;
                
                const companyStats = {};
                reviews.forEach(review => {
                    if (!companyStats[review.company.id]) {
                        companyStats[review.company.id] = {
                            name: review.company.name,
                            total: 0,
                            positive: 0,
                            negative: 0,
                            ratings: [],
                            lastReview: null
                        };
                    }
                    
                    companyStats[review.company.id].total++;
                    if (review.is_positive) {
                        companyStats[review.company.id].positive++;
                    } else {
                        companyStats[review.company.id].negative++;
                    }
                    companyStats[review.company.id].ratings.push(review.rating);
                    
                    if (!companyStats[review.company.id].lastReview || 
                        new Date(review.created_at) > new Date(companyStats[review.company.id].lastReview)) {
                        companyStats[review.company.id].lastReview = review.created_at;
                    }
                });
                
                tbody.innerHTML = Object.entries(companyStats).map(([companyId, company]) => {
                    const average = company.ratings.length > 0 ? 
                        (company.ratings.reduce((sum, rating) => sum + rating, 0) / company.ratings.length).toFixed(1) : '0.0';
                    
                    return `
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">${company.name}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">${company.total}</td>
                            <td class="px-4 py-3 text-green-600 dark:text-green-400 font-medium">${company.positive}</td>
                            <td class="px-4 py-3 text-red-600 dark:text-red-400 font-medium">${company.negative}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">${average}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-sm">${company.lastReview ? formatDate(company.lastReview) : 'N/A'}</td>
                            <td class="px-4 py-3">
                                <button onclick="viewCompanyDetails('${company.name}', ${companyId})" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm">
                                    <i class="fas fa-eye mr-1"></i>${t.view}
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');
            }
            
            updatePagination(data) {
                const container = document.getElementById('paginationContainer');
                if (data.last_page <= 1) {
                    container.classList.add('hidden');
                    return;
                }
                
                container.classList.remove('hidden');
                
                let pagination = '<div class="flex flex-col sm:flex-row items-center justify-between gap-4">';
                pagination += `<span class="text-sm text-gray-600 dark:text-gray-400 text-center sm:text-left">Mostrando ${data.from || 0} a ${data.to || 0} de ${data.total} avaliações</span>`;
                
                pagination += '<div class="flex flex-wrap items-center justify-center gap-2">';
                
                if (data.current_page > 1) {
                    pagination += `<button onclick="reviewsPanel.goToPage(${data.current_page - 1})" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors min-h-[44px]">Anterior</button>`;
                }
                
                for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
                    const activeClass = i === data.current_page ? 'bg-purple-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600';
                    pagination += `<button onclick="reviewsPanel.goToPage(${i})" class="px-4 py-2 ${activeClass} rounded transition-colors min-h-[44px] min-w-[44px]">${i}</button>`;
                }
                
                if (data.current_page < data.last_page) {
                    pagination += `<button onclick="reviewsPanel.goToPage(${data.current_page + 1})" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors min-h-[44px]">Próximo</button>`;
                }
                
                pagination += '</div></div>';
                container.innerHTML = pagination;
            }
            
            goToPage(page) {
                this.currentPage = page;
                this.loadReviews();
            }
            
            bindEvents() {
                const userFilter = document.getElementById('userFilter');
                if (userFilter) {
                    userFilter.addEventListener('change', () => {
                        this.filters.user_id = userFilter.value;
                        this.applyFilters();
                    });
                }
                document.getElementById('typeFilter').addEventListener('change', () => this.applyFilters());
                document.getElementById('ratingFilter').addEventListener('change', () => this.applyFilters());
                document.getElementById('dateFilter').addEventListener('change', () => this.applyFilters());
            }
            
            applyFilters() {
                // Obter valores dos filtros e normalizar (tratar strings vazias e 'all' como sem filtro)
                const companyId = document.getElementById('companyFilter').value;
                const userId = document.getElementById('userFilter') ? document.getElementById('userFilter').value : '';
                const type = document.getElementById('typeFilter').value;
                const rating = document.getElementById('ratingFilter').value;
                const date = document.getElementById('dateFilter').value;
                
                this.filters = {
                    company_id: (companyId !== '' && companyId !== 'all' && companyId !== null && companyId !== undefined) ? companyId : '',
                    user_id: (userId !== '' && userId !== 'all' && userId !== null && userId !== undefined) ? userId : '',
                    type: (type !== '' && type !== 'all' && type !== null && type !== undefined) ? type : '',
                    rating: (rating !== '' && rating !== 'all' && rating !== null && rating !== undefined) ? rating : '',
                    date: (date !== '' && date !== 'all' && date !== null && date !== undefined) ? date : ''
                };
                
                this.currentPage = 1;
                this.loadReviews();
            }
            
            clearFilters() {
                document.getElementById('companyFilter').value = '';
                const companySearchInput = document.getElementById('companySearchInput');
                if (companySearchInput) companySearchInput.value = '';
                const userFilter = document.getElementById('userFilter');
                if (userFilter) userFilter.value = '';
                document.getElementById('typeFilter').value = '';
                document.getElementById('ratingFilter').value = '';
                document.getElementById('dateFilter').value = '';
                
                this.filters = {
                    company_id: '',
                    user_id: '',
                    type: '',
                    rating: '',
                    date: ''
                };
                
                this.updateCompanyInput();
                this.currentPage = 1;
                this.loadReviews();
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
        let reviewsPanel;
        
        function applyFilters() {
            reviewsPanel.applyFilters();
        }
        
        function clearFilters() {
            reviewsPanel.clearFilters();
        }
        
        function refreshReviews() {
            reviewsPanel.loadReviews();
        }
        
        
        function contactWhatsApp(whatsapp) {
            const cleanNumber = whatsapp.replace(/[^0-9]/g, '');
            window.open(`https://wa.me/${cleanNumber}`, '_blank');
        }
        
        // Função auxiliar para escapar valores CSV
        function escapeCsvValue(value) {
            if (value === null || value === undefined) {
                return '';
            }
            const stringValue = String(value);
            // Se contém vírgula, aspas duplas ou quebra de linha, precisa ser envolvido em aspas
            if (stringValue.includes(',') || stringValue.includes('"') || stringValue.includes('\n') || stringValue.includes('\r')) {
                // Escapar aspas duplas duplicando-as
                return '"' + stringValue.replace(/"/g, '""') + '"';
            }
            return stringValue;
        }
        
        // Função auxiliar para criar linha CSV
        function createCsvRow(values) {
            return values.map(escapeCsvValue).join(',');
        }
        
        async function exportContacts(button) {
            if (!button) return;
            
            const companyId = document.getElementById('companyFilter').value;
            if (!companyId) {
                showNotification('Selecione uma empresa para exportar os contatos', 'warning');
                return;
            }
            
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exportando...';
            button.disabled = true;
            
            try {
                const response = await fetch(`/api/companies/${companyId}/contacts`);
                const result = await response.json();
                
                if (result.success && result.data.contacts.length > 0) {
                    // BOM UTF-8 para Excel reconhecer encoding correto
                    const BOM = '\uFEFF';
                    
                    // Cabeçalhos
                    const headers = ['WhatsApp', 'Nota', 'Comentário', 'Data'];
                    
                    // Criar linhas CSV formatadas corretamente
                    const csvRows = [
                        createCsvRow(headers),
                        ...result.data.contacts.map(contact => 
                            createCsvRow([
                                contact.whatsapp || '',
                                contact.rating || '',
                                (contact.comment || '').replace(/\r\n/g, ' ').replace(/\n/g, ' ').replace(/\r/g, ' '),
                                formatDate(contact.created_at) || ''
                            ])
                        )
                    ];
                    
                    const csvContent = BOM + csvRows.join('\r\n');
                    
                    // Criar blob com MIME type correto para CSV
                    const blob = new Blob([csvContent], { 
                        type: 'text/csv;charset=utf-8;' 
                    });
                    
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `contatos_${result.data.company.replace(/[^a-zA-Z0-9]/g, '_')}_${new Date().toISOString().split('T')[0]}.csv`;
                    // Permitir preview abrindo em nova aba
                    a.target = '_blank';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    // Não revogar imediatamente para permitir preview - usar timeout maior para garantir download
                    setTimeout(() => {
                        // Verificar se o link ainda está sendo usado antes de revogar
                        try {
                    window.URL.revokeObjectURL(url);
                        } catch (e) {
                            console.warn('Erro ao revogar URL:', e);
                        }
                    }, 5000); // 5 segundos para garantir que o download foi iniciado
                    
                    showNotification(`Arquivo exportado com sucesso! ${result.data.contacts.length} contato(s).`, 'success');
                } else {
                    showNotification('Nenhum contato encontrado para esta empresa', 'warning');
                }
            } catch (error) {
                console.error('Erro:', error);
                showNotification('Erro ao exportar contatos', 'error');
            } finally {
                // Safely restore button state
                if (button && originalText) {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            }
        }
        
        function markAsProcessed(reviewId) {
            showNotification('Avaliação marcada como processada!', 'success');
        }
        
        function deleteReview(reviewId) {
            if (window.showConfirmModal) {
                window.showConfirmModal({
                    title: 'Excluir Avaliação',
                    message: 'Tem certeza que deseja excluir esta avaliação?',
                    warning: 'Esta ação não pode ser desfeita.',
                    confirmText: 'Confirmar Exclusão',
                    cancelText: 'Cancelar',
                    confirmColor: 'red',
                    onConfirm: () => {
                        showNotification('Avaliação excluída!', 'success');
                        // Aqui você pode adicionar a lógica para realmente deletar a avaliação
                    }
                });
            }
        }
        
        async function exportCompanyData() {
            try {
                // Obter dados da tabela de performance
                const table = document.querySelector('.table-container table');
                if (!table) {
                    showNotification('Nenhum dado disponível para exportar', 'warning');
                    return;
                }
                
                // Extrair dados da tabela
                const rows = Array.from(table.querySelectorAll('tbody tr'));
                if (rows.length === 0) {
                    showNotification('Nenhum dado disponível para exportar', 'warning');
                    return;
                }
                
                // BOM UTF-8 para Excel reconhecer encoding correto
                const BOM = '\uFEFF';
                
                // Cabeçalhos
                const headers = ['Empresa', 'Total', 'Positivas', 'Negativas', 'Média', 'Última Avaliação'];
                
                // Criar linhas CSV formatadas corretamente
                const csvRows = [
                    createCsvRow(headers),
                    ...rows.map(row => {
                        const cells = row.querySelectorAll('td');
                        return createCsvRow([
                            cells[0]?.textContent.trim() || '',
                            cells[1]?.textContent.trim() || '',
                            cells[2]?.textContent.trim() || '',
                            cells[3]?.textContent.trim() || '',
                            cells[4]?.textContent.trim() || '',
                            cells[5]?.textContent.trim() || ''
                        ]);
                    })
                ];
                
                const csvContent = BOM + csvRows.join('\r\n');
                
                // Criar e baixar arquivo - usar CSV MIME type correto
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                
                // Gerar nome do arquivo com data
                const now = new Date();
                const dateStr = now.toISOString().split('T')[0];
                a.download = `performance_empresas_${dateStr}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                showNotification('Dados exportados com sucesso!', 'success');
            } catch (error) {
                console.error('Erro ao exportar dados:', error);
                showNotification('Erro ao exportar dados', 'error');
            }
        }
        
        function viewCompanyDetails(companyName, companyId) {
            document.getElementById('companyFilter').value = companyId;
            reviewsPanel.applyFilters();
            showNotification(`Mostrando avaliações de ${companyName}`, 'success');
        }
        
        function updateChartPeriod(periodStr, element) {
            if (!element || !reviewsPanel) return;
            
            // Parse period string (e.g., '7d' -> 7)
            const period = parseInt(periodStr);
            
            // Update button styles
            document.querySelectorAll('.chart-period-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-purple-500', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-600');
            });
            
            element.classList.add('active', 'bg-purple-500', 'text-white');
            element.classList.remove('bg-gray-200', 'text-gray-600');
            
            // Update chart with real data
            reviewsPanel.changeChartPeriod(period);
        }
        
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            reviewsPanel = new ReviewsPanel();
            
            // Redimensionar gráficos quando a tela mudar de tamanho
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (reviewsPanel && reviewsPanel.charts) {
                        if (reviewsPanel.charts.reviewsOverTime) {
                            reviewsPanel.charts.reviewsOverTime.resize();
                        }
                        if (reviewsPanel.charts.ratingDistribution) {
                            reviewsPanel.charts.ratingDistribution.resize();
                        }
                    }
                }, 250);
            });
        });
        // Toggle filters on mobile
        function toggleReviewsFilters() {
            const form = document.getElementById('reviewsFiltersForm');
            const icon = document.getElementById('toggleReviewsFiltersIcon');
            if (form && icon) {
                const isHidden = form.classList.contains('hidden');
                if (isHidden) {
                    form.classList.remove('hidden');
                    form.classList.add('flex', 'flex-col');
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    form.classList.add('hidden');
                    form.classList.remove('flex', 'flex-col');
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            }
        }
    </script>
@endsection
