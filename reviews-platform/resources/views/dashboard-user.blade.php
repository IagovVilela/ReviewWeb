@extends('layouts.admin')

@section('title', __('dashboard.my_dashboard'))
@section('page-title', __('dashboard.my_dashboard'))
@section('page-description', __('dashboard.company_overview'))

@section('content')
<div class="fade-in">
    @if(!$hasCompany)
    <!-- No Company Alert -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-2xl"></i>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-300 mb-2">{{ __('dashboard.welcome_message') }}</h3>
                <p class="text-blue-800 dark:text-blue-400 mb-4">
                    {{ __('dashboard.no_company_message') }}
                </p>
                <a href="{{ route('companies.create') }}" class="btn-primary px-3 py-1.5 sm:px-6 sm:py-3 rounded-lg text-sm sm:text-base text-white font-medium shadow-md hover:shadow-lg transition-all inline-flex items-center gap-1.5 sm:gap-2 min-h-[36px] sm:min-h-[44px]">
                    <i class="fas fa-plus text-xs sm:text-sm"></i>
                    <span class="hidden sm:inline">{{ __('dashboard.create_my_company') }}</span>
                    <span class="sm:hidden uppercase text-xs">{{ strtoupper(__('companies.create')) }}</span>
                </a>
            </div>
        </div>
    </div>
    @else
    <!-- Company Selector (if multiple companies) -->
    @if($companies->count() > 1)
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-4 lg:p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-building text-purple-600 dark:text-purple-400 text-lg sm:text-xl"></i>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('dashboard.view_dashboard') }}</label>
            </div>
            <select id="companySelector" onchange="changeCompany(this.value)" class="w-full sm:w-auto px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent sm:min-w-[250px] text-sm sm:text-base">
                <option value="all" {{ $selectedCompanyId === 'all' ? 'selected' : '' }}>📊 {{ __('dashboard.all_companies_overview') }}</option>
                @foreach($companies as $comp)
                    <option value="{{ $comp->id }}" {{ $selectedCompanyId == $comp->id ? 'selected' : '' }}>
                        {{ $comp->name }}
                        @if($comp->status === 'published')
                            <span class="text-green-600">✓</span>
                        @else
                            <span class="text-yellow-600">⏳</span>
                        @endif
                    </option>
                @endforeach
            </select>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
            <i class="fas fa-info-circle mr-1"></i>
            {{ __('dashboard.you_have_companies', [
                'count' => $companies->count(),
                'company' => $companies->count() === 1 ? __('dashboard.company') : __('dashboard.companies_plural')
            ]) }}
        </p>
    </div>
    @endif
    
    <!-- Company Info Card -->
    @if($selectedCompanyId === 'all')
    <!-- All Companies Overview Card -->
    <div class="bg-gradient-to-r from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20 rounded-xl border border-purple-200 dark:border-purple-800 shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-th-large text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('dashboard.all_my_companies') }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ $companies->count() }} {{ $companies->count() === 1 ? __('dashboard.registered_company') : __('dashboard.registered_companies') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-4 py-2 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 rounded-lg font-semibold">
                    <i class="fas fa-chart-pie mr-1"></i> {{ __('dashboard.overview_badge') }}
                </span>
            </div>
        </div>
    </div>
    @elseif($selectedCompany)
    <!-- Single Company Info Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-4 sm:p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3 sm:space-x-4 flex-1 min-w-0">
                @if($selectedCompany->logo)
                    <img src="{{ $selectedCompany->logo_url }}" 
                         alt="{{ $selectedCompany->name }}" 
                         class="w-12 h-12 sm:w-16 sm:h-16 rounded-lg object-cover border-2 border-purple-200 flex-shrink-0">
                @else
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-building text-white text-xl sm:text-2xl"></i>
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <h2 class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-gray-100 truncate">{{ $selectedCompany->name }}</h2>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 truncate break-all sm:break-normal">{{ $selectedCompany->negative_email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                @if($selectedCompany->status === 'published')
                    <span class="px-3 sm:px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-lg font-semibold text-sm sm:text-base whitespace-nowrap">
                        <i class="fas fa-check-circle mr-1"></i> {{ __('dashboard.active') }}
                    </span>
                @else
                    <span class="px-3 sm:px-4 py-2 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 rounded-lg font-semibold text-sm sm:text-base whitespace-nowrap">
                        <i class="fas fa-clock mr-1"></i> {{ __('dashboard.draft') }}
                    </span>
                @endif
                @if($selectedCompany->status === 'draft')
                    <a href="{{ route('companies.edit', $selectedCompany->id) }}" class="btn-primary px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm sm:text-base text-white inline-flex items-center gap-1.5 sm:gap-2 min-h-[36px] sm:min-h-[44px]">
                        <i class="fas fa-edit"></i>
                        <span class="hidden sm:inline">{{ __('app.edit') }}</span>
                        <span class="sm:hidden">{{ __('app.edit') }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Reviews -->
        <div id="userStatCardTotal" onclick="filterUserReviews('all')" class="bg-white dark:bg-gray-800 rounded-xl border-2 border-gray-200 dark:border-gray-700 p-6 card-hover cursor-pointer transition-all hover:shadow-lg hover:border-purple-500 relative group" style="cursor: pointer;" title="👆 Clique para ver todas as avaliações">
            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                <span class="text-xs bg-purple-500 text-white px-2 py-1 rounded-full font-medium">
                    <i class="fas fa-hand-pointer mr-1"></i>Clique
                </span>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('dashboard.total_reviews_full') }}</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['total_reviews'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-comment-alt text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Positive Reviews -->
        <div id="userStatCardPositive" onclick="filterUserReviews('positive')" class="bg-white dark:bg-gray-800 rounded-xl border-2 border-gray-200 dark:border-gray-700 p-6 card-hover cursor-pointer transition-all hover:shadow-lg hover:border-green-500 relative group" style="cursor: pointer;" title="👆 Clique para ver apenas avaliações positivas">
            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                <span class="text-xs bg-green-500 text-white px-2 py-1 rounded-full font-medium">
                    <i class="fas fa-hand-pointer mr-1"></i>Clique
                </span>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('dashboard.positive_reviews_full') }}</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['positive_reviews'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-smile text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Negative Reviews -->
        <div id="userStatCardNegative" onclick="filterUserReviews('negative')" class="bg-white dark:bg-gray-800 rounded-xl border-2 border-gray-200 dark:border-gray-700 p-6 card-hover cursor-pointer transition-all hover:shadow-lg hover:border-red-500 relative group" style="cursor: pointer;" title="👆 Clique para ver apenas avaliações negativas">
            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                <span class="text-xs bg-red-500 text-white px-2 py-1 rounded-full font-medium">
                    <i class="fas fa-hand-pointer mr-1"></i>Clique
                </span>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('dashboard.negative_reviews_full') }}</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['negative_reviews'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-frown text-red-600 dark:text-red-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div id="userStatCardAverage" onclick="scrollToRecentReviews()" class="bg-white dark:bg-gray-800 rounded-xl border-2 border-gray-200 dark:border-gray-700 p-6 card-hover cursor-pointer transition-all hover:shadow-lg hover:border-blue-500 relative group" style="cursor: pointer;" title="👆 Clique para ver as avaliações recentes">
            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                <span class="text-xs bg-blue-500 text-white px-2 py-1 rounded-full font-medium">
                    <i class="fas fa-hand-pointer mr-1"></i>Clique
                </span>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('dashboard.average_rating_full') }}</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['average_rating'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Public Link Card -->
    @if($selectedCompany && $selectedCompany->status === 'published')
    <div class="bg-gradient-to-r from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20 rounded-xl border border-purple-200 dark:border-purple-800 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    <i class="fas fa-link mr-2"></i>
                    {{ __('dashboard.public_link_of', ['name' => $selectedCompany->name]) }}
                </h3>
                <p class="text-gray-700 dark:text-gray-300 mb-3">{{ __('dashboard.share_link_message') }}</p>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <input type="text" 
                           value="{{ $selectedCompany->public_url }}" 
                           id="publicUrl"
                           readonly 
                           class="flex-1 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-800 dark:text-gray-200 text-sm sm:text-base">
                    <div class="flex flex-wrap items-stretch sm:items-center gap-2">
                        <button onclick="copyLink()" class="flex-1 sm:flex-none px-4 sm:px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors inline-flex items-center justify-center gap-2 text-sm sm:text-base">
                            <i class="fas fa-copy"></i>
                            <span>{{ __('dashboard.copy') }}</span>
                        </button>
                        <a href="{{ $selectedCompany->public_url }}" target="_blank" class="flex-1 sm:flex-none px-4 sm:px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors inline-flex items-center justify-center gap-2 text-sm sm:text-base">
                            <i class="fas fa-external-link-alt"></i>
                            <span>{{ __('dashboard.view') }}</span>
                        </a>
                        <button type="button" onclick="downloadQRCode('{{ $selectedCompany->public_url }}', 'qrcode-{{ Str::slug($selectedCompany->name) }}.png')" class="flex-1 sm:flex-none px-4 sm:px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors inline-flex items-center justify-center gap-2 text-sm sm:text-base">
                            <i class="fas fa-qrcode"></i>
                            <span>{{ __('dashboard.download_qr') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif($selectedCompanyId === 'all' && $companies->where('status', 'published')->count() > 0)
    <!-- All Companies Links -->
    <div class="bg-gradient-to-r from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20 rounded-xl border border-purple-200 dark:border-purple-800 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            <i class="fas fa-link mr-2"></i>
            {{ __('dashboard.public_links_companies') }}
        </h3>
        <div class="space-y-3">
            @foreach($companies->where('status', 'published') as $comp)
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-purple-200 dark:border-purple-700">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 dark:text-gray-100 mb-1">{{ $comp->name }}</p>
                        <input type="text" 
                               value="{{ $comp->public_url }}" 
                               id="publicUrl{{ $comp->id }}"
                               readonly 
                               class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-800 dark:text-gray-200">
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button onclick="copyLinkById('publicUrl{{ $comp->id }}')" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors inline-flex items-center gap-2 text-sm">
                            <i class="fas fa-copy"></i>
                            {{ __('dashboard.copy') }}
                        </button>
                        <a href="{{ $comp->public_url }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors inline-flex items-center gap-2 text-sm">
                            <i class="fas fa-external-link-alt"></i>
                            {{ __('dashboard.see') }}
                        </a>
                        <button type="button" onclick="downloadQRCode('{{ $comp->public_url }}', 'qrcode-{{ Str::slug($comp->name) }}.png')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors inline-flex items-center gap-2 text-sm">
                            <i class="fas fa-qrcode"></i>
                            {{ __('dashboard.download_qr') }}
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recent Reviews -->
    <div id="recentReviewsSection" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                <i class="fas fa-history mr-2"></i>
                {{ __('dashboard.recent_reviews') }}
                @if($selectedCompanyId === 'all')
                    <span class="text-sm font-normal text-gray-500">{{ __('dashboard.from_all_companies') }}</span>
                @elseif($selectedCompany)
                    <span class="text-sm font-normal text-gray-500">{{ __('dashboard.from_company', ['name' => $selectedCompany->name]) }}</span>
                @endif
            </h2>
        </div>
        <div class="p-6">
            @php
                if ($selectedCompanyId === 'all') {
                    $recentReviews = \App\Models\Review::whereIn('company_id', $companies->pluck('id'))
                        ->with('company')
                        ->latest()
                        ->take(10)
                        ->get();
                } else {
                    $recentReviews = $selectedCompany ? $selectedCompany->reviews()->latest()->take(10)->get() : collect();
                }
            @endphp
            
            @if($recentReviews->isEmpty())
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comment-slash text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('dashboard.no_reviews_yet') }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        @if($selectedCompanyId === 'all')
                            {{ __('dashboard.no_reviews_all') }}
                        @else
                            {{ __('dashboard.share_link_start') }}
                        @endif
                    </p>
                </div>
            @else
                <div class="space-y-4" id="reviewsList">
                    @foreach($recentReviews as $review)
                    <div class="review-item p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg" data-rating="{{ $review->rating }}" data-is-positive="{{ $review->is_positive ? '1' : '0' }}">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-{{ $i <= $review->rating ? 'yellow' : 'gray' }}-400"></i>
                                    @endfor
                                    <span class="ml-2 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $review->rating }}/5</span>
                                </div>
                                @if($selectedCompanyId === 'all' && isset($review->company))
                                    <span class="px-2 py-1 text-xs bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 rounded-full">
                                        {{ $review->company->name }}
                                    </span>
                                @endif
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-800 dark:text-gray-200">{{ $review->comment ?? __('dashboard.no_comment') }}</p>
                        @if($review->customer_phone)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                <i class="fas fa-phone mr-1"></i> {{ $review->customer_phone }}
                            </p>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    /* Stat card clickable styles */
    [id^="userStatCard"] {
        transition: all 0.3s ease;
        user-select: none;
        position: relative;
    }
    
    [id^="userStatCard"]:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    [id^="userStatCard"]:active {
        transform: translateY(-2px) scale(0.98);
    }
    
    [id^="userStatCard"].active {
        border-color: #667eea !important;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2) !important;
    }
    
    [id^="userStatCard"] > * {
        position: relative;
        z-index: 2;
    }
    
    .review-item.hidden {
        display: none;
    }
</style>
@endsection

@section('scripts')
<script>
    let currentReviewFilter = 'all';
    
    function copyLink() {
        const input = document.getElementById('publicUrl');
        if (input) {
            input.select();
            document.execCommand('copy');
            showNotification(@json(__('dashboard.link_copied')), 'success');
        }
    }
    
    function copyLinkById(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.select();
            document.execCommand('copy');
            showNotification(@json(__('dashboard.link_copied')), 'success');
        }
    }
    
    function changeCompany(companyId) {
        const url = new URL(window.location);
        url.searchParams.set('company_id', companyId);
        window.location.href = url.toString();
    }
    
    function filterUserReviews(filterType) {
        // Remove active state from all cards
        document.querySelectorAll('[id^="userStatCard"]').forEach(card => {
            card.classList.remove('active');
        });
        
        // Get all review items
        const reviewItems = document.querySelectorAll('.review-item');
        
        if (filterType === 'all') {
            // Show all reviews
            reviewItems.forEach(item => {
                item.classList.remove('hidden');
            });
            // Highlight total card
            const totalCard = document.getElementById('userStatCardTotal');
            if (totalCard) totalCard.classList.add('active');
            currentReviewFilter = 'all';
        } else if (filterType === 'positive') {
            // Show only positive reviews (rating >= 4)
            reviewItems.forEach(item => {
                const isPositive = item.getAttribute('data-is-positive') === '1';
                if (isPositive) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
            // Highlight positive card
            const positiveCard = document.getElementById('userStatCardPositive');
            if (positiveCard) positiveCard.classList.add('active');
            currentReviewFilter = 'positive';
        } else if (filterType === 'negative') {
            // Show only negative reviews (rating < 4)
            reviewItems.forEach(item => {
                const isPositive = item.getAttribute('data-is-positive') === '1';
                if (!isPositive) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
            // Highlight negative card
            const negativeCard = document.getElementById('userStatCardNegative');
            if (negativeCard) negativeCard.classList.add('active');
            currentReviewFilter = 'negative';
        }
        
        // Scroll to reviews section
        setTimeout(() => {
            const reviewsSection = document.getElementById('recentReviewsSection');
            if (reviewsSection) {
                reviewsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 100);
    }
    
    function scrollToRecentReviews() {
        const reviewsSection = document.getElementById('recentReviewsSection');
        if (reviewsSection) {
            reviewsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
    
    // Helper function for notifications (if not already defined)
    function showNotification(message, type) {
        // Simple notification - you can enhance this with a toast library
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
@endsection

