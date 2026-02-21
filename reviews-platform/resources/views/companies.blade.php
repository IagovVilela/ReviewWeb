@extends('layouts.admin')

@section('title', __('companies.title') . ' - ' . __('app.name'))

@section('page-title', __('companies.title'))
@section('page-description', __('dashboard.companies_count') . ' • ' . $companies->where('status', 'published')->count() . ' ' . __('companies.count_active') . ' • ' . $companies->where('status', 'draft')->count() . ' ' . __('companies.count_draft'))

@section('header-actions')
    <a href="/companies/create" class="btn-primary text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm sm:text-base font-medium min-h-[36px] sm:min-h-[44px] flex items-center justify-center">
        <i class="fas fa-plus mr-1.5 sm:mr-2 text-xs sm:text-sm"></i>
        <span class="hidden sm:inline">{{ __('companies.create') }}</span>
        <span class="sm:hidden uppercase text-xs">{{ strtoupper(__('companies.create')) }}</span>
    </a>
@endsection

@section('content')
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            @if(session('company_url'))
                <div class="mt-2">
                    <a href="{{ session('company_url') }}" target="_blank" class="text-green-600 hover:underline font-medium">
                        <i class="fas fa-external-link-alt mr-1"></i>
                        Ver página pública
                    </a>
                </div>
            @endif
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <div id="filtersContainer" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-3 md:p-6 mb-4 md:mb-6">
        <div class="flex items-center justify-between mb-3 md:mb-4">
            <h3 class="text-sm md:text-lg font-semibold text-gray-800 dark:text-gray-100">{{ __('companies.filters') }}</h3>
            <button type="button" id="toggleFiltersBtn" class="md:hidden text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors" onclick="toggleFilters()">
                <i class="fas fa-chevron-down" id="toggleFiltersIcon"></i>
            </button>
        </div>
        <form id="filtersForm" method="GET" action="{{ route('companies.index') }}" class="hidden md:flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2 md:gap-3 lg:gap-4">
            <!-- User Filter (Admin/Proprietario only) -->
            @if(in_array(Auth::user()->role, ['admin', 'proprietario']) && $users && $users->count() > 0)
            <div class="flex-1 min-w-[200px] w-full md:w-auto">
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('companies.filter_by_user') }}</label>
                <select name="user_id" id="userFilter" class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent" style="min-height: 36px; font-size: 14px;">
                    <option value="">{{ __('companies.all_users') }}</option>
                    @foreach($users as $userOption)
                        <option value="{{ $userOption->id }}" {{ request('user_id') == $userOption->id ? 'selected' : '' }}>
                            {{ $userOption->name }} ({{ $userOption->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            
            <!-- Search by Name -->
            <div class="flex-1 min-w-[200px] w-full md:w-auto relative">
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('companies.search_company') }}</label>
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        id="searchFilter" 
                        value="{{ request('search') }}"
                        placeholder="{{ __('companies.search_company_placeholder') }}"
                        class="w-full px-2 md:px-3 py-1.5 md:py-2 pl-8 md:pl-10 text-sm md:text-base bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        style="min-height: 36px; font-size: 14px; padding-left: 2rem;"
                    >
                    <i class="fas fa-search absolute left-2 md:left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none text-sm"></i>
                </div>
            </div>
            
            <!-- Status Filter -->
            <div class="flex-1 min-w-[150px] w-full md:w-auto">
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('companies.filter_by_status') }}</label>
                <select name="status" id="statusFilter" class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent" style="min-height: 36px; font-size: 14px;">
                    <option value="all">{{ __('companies.all_statuses') }}</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>{{ __('companies.active') }}</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('companies.draft') }}</option>
                </select>
            </div>
            
            <!-- Visibility Filter -->
            <div class="flex-1 min-w-[150px] w-full md:w-auto">
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('companies.filter_by_visibility') }}</label>
                <select name="visibility" id="visibilityFilter" class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent" style="min-height: 36px; font-size: 14px;">
                    <option value="all">{{ __('companies.all_visibilities') }}</option>
                    <option value="visible" {{ request('visibility') == 'visible' ? 'selected' : '' }}>{{ __('companies.visible') }}</option>
                    <option value="hidden" {{ request('visibility') == 'hidden' ? 'selected' : '' }}>{{ __('companies.hidden') }}</option>
                </select>
            </div>
            
            <!-- Rating Limit Filter -->
            <div class="flex-1 min-w-[150px] w-full md:w-auto">
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('companies.filter_by_rating_limit') }}</label>
                <select name="rating_limit" id="ratingLimitFilter" class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent" style="min-height: 36px; font-size: 14px;">
                    <option value="all">{{ __('companies.all_ratings') }}</option>
                    <option value="5" {{ request('rating_limit') == '5' ? 'selected' : '' }}>5 estrelas</option>
                    <option value="4" {{ request('rating_limit') == '4' ? 'selected' : '' }}>4 estrelas</option>
                    <option value="3" {{ request('rating_limit') == '3' ? 'selected' : '' }}>3 estrelas</option>
                    <option value="2" {{ request('rating_limit') == '2' ? 'selected' : '' }}>2 estrelas</option>
                    <option value="1" {{ request('rating_limit') == '1' ? 'selected' : '' }}>1 estrela</option>
                </select>
            </div>
            
            <!-- Reviews Filter -->
            <div class="flex-1 min-w-[150px] w-full md:w-auto">
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('companies.filter_by_reviews') }}</label>
                <select name="reviews_filter" id="reviewsFilter" class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent" style="min-height: 36px; font-size: 14px;">
                    <option value="all">{{ __('companies.all_reviews') }}</option>
                    <option value="with_reviews" {{ request('reviews_filter') == 'with_reviews' ? 'selected' : '' }}>{{ __('companies.with_reviews') }}</option>
                    <option value="without_reviews" {{ request('reviews_filter') == 'without_reviews' ? 'selected' : '' }}>{{ __('companies.without_reviews') }}</option>
                </select>
            </div>
            
            <!-- Period Filter -->
            <div class="flex-1 min-w-[150px] w-full md:w-auto">
                <label class="block text-xs md:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('companies.filter_by_period') }}</label>
                <select name="period" id="periodFilter" class="w-full px-2 md:px-3 py-1.5 md:py-2 text-sm md:text-base bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent" style="min-height: 36px; font-size: 14px;">
                    <option value="all">{{ __('companies.all_periods') }}</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>{{ __('companies.today') }}</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>{{ __('companies.this_week') }}</option>
                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>{{ __('companies.this_month') }}</option>
                </select>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-2 w-full sm:w-auto">
                <button type="submit" class="btn-primary text-white px-3 md:px-4 py-1.5 md:py-2 text-sm md:text-base rounded-lg font-medium w-full sm:w-auto" style="min-height: 36px;">
                    <i class="fas fa-filter mr-1.5 md:mr-2"></i>
                    {{ __('companies.apply') }}
                </button>
                <button type="button" onclick="clearFilters()" class="btn-secondary text-white px-3 md:px-4 py-1.5 md:py-2 text-sm md:text-base rounded-lg font-medium w-full sm:w-auto" style="min-height: 36px;">
                    <i class="fas fa-times mr-1.5 md:mr-2"></i>
                    {{ __('companies.clear') }}
                </button>
            </div>
        </form>
    </div>

    @if($companies->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @foreach($companies as $company)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover stagger-item">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            @if($company->logo)
                                <div class="image-placeholder w-12 h-12">
                                    <img src="{{ $company->logo_url }}?v={{ time() }}" 
                                         alt="{{ $company->name }}" 
                                         loading="lazy"
                                         class="w-12 h-12 rounded-lg object-cover"
                                         onload="var parent = this.parentElement; parent.classList.add('loaded'); this.style.opacity = '1'; if(parent.querySelector('.placeholder-shimmer')) parent.querySelector('.placeholder-shimmer').style.display = 'none';"
                                         onerror="console.error('Erro ao carregar imagem:', this.src); this.style.display='none'; var next = this.nextElementSibling; if(next) next.style.display='block';"
                                         style="opacity: 0;">
                                    <div class="placeholder-shimmer"></div>
                                </div>
                            @else
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-building text-purple-600"></i>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $company->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $company->created_at ? $company->created_at->format('d/m/Y') : '' }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $company->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $company->status === 'published' ? __('companies.active') : __('companies.draft') }}
                            </span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $company->is_active ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $company->is_active ? __('companies.visible') : __('companies.hidden') }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3 mb-4">
                        @if($company->user && (auth()->user()->role === 'admin' || auth()->user()->role === 'proprietario'))
                            <div class="flex items-center text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">
                                <i class="fas fa-user w-4 mr-2 text-purple-600"></i>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-gray-800 truncate">{{ $company->user->name }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ $company->user->email }}</div>
                                    <div class="text-xs mt-1">
                                        @if($company->user->role === 'proprietario')
                                            <span class="px-1.5 py-0.5 rounded bg-yellow-100 text-yellow-800 text-xs font-medium">Proprietário</span>
                                        @elseif($company->user->role === 'admin')
                                            <span class="px-1.5 py-0.5 rounded bg-purple-100 text-purple-800 text-xs font-medium">Admin</span>
                                        @else
                                            <span class="px-1.5 py-0.5 rounded bg-gray-100 text-gray-800 text-xs font-medium">Usuário</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-envelope w-4 mr-2"></i>
                            <span class="truncate">{{ $company->negative_email }}</span>
                        </div>
                        @if($company->contact_number)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-phone w-4 mr-2"></i>
                                <span>{{ $company->contact_number }}</span>
                            </div>
                        @endif
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-star w-4 mr-2"></i>
                            <span>{{ __('companies.rating_limit', ['score' => $company->positive_score]) }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4 py-3 border-t border-b border-gray-100">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt mr-1"></i>
                            <span>{{ $company->review_pages_count }} {{ __('companies.pages') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-comment mr-1"></i>
                            <span>{{ $company->reviews_count }} {{ __('companies.reviews_count') }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:space-x-2">
                        @if($company->status === 'published')
                            <a href="{{ $company->public_url }}" target="_blank" class="flex-1 bg-blue-50 text-blue-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors text-center">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                <span class="hidden sm:inline">{{ __('companies.view_page') }}</span>
                                <span class="sm:hidden">{{ __('companies.view') }}</span>
                            </a>
                            <button type="button" onclick="downloadQRCode('{{ $company->public_url }}', 'qrcode-{{ Str::slug($company->name) }}.png')" class="flex-1 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-center">
                                <i class="fas fa-qrcode mr-1"></i>
                                {{ __('companies.download_qr') }}
                            </button>
                        @endif
                        <a href="{{ route('companies.edit', $company->id) }}" class="flex-1 bg-purple-50 text-purple-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-purple-100 transition-colors text-center">
                            <i class="fas fa-edit mr-1"></i>
                            <span class="hidden sm:inline">{{ __('companies.edit_company') }}</span>
                            <span class="sm:hidden">{{ __('companies.edit') }}</span>
                        </a>
                        <form method="POST" action="{{ route('companies.destroy', $company->id) }}" class="inline sm:flex-shrink-0 delete-company-form" data-company-id="{{ $company->id }}" data-company-name="{{ $company->name }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="w-full sm:w-auto bg-red-50 text-red-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors delete-company-btn">
                                <i class="fas fa-trash mr-1 sm:mr-0"></i>
                                <span class="sm:hidden">{{ __('companies.delete') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $companies->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-purple-100 rounded-full mb-6">
                <i class="fas fa-building text-purple-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ __('companies.no_companies') }}</h3>
            <p class="text-gray-600 mb-6">{{ __('companies.no_companies_desc') }}</p>
            <a href="/companies/create" class="btn-primary text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                {{ __('companies.create_first') }}
            </a>
        </div>
    @endif

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteCompanyModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="backdrop-filter: blur(4px);">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" id="modalOverlay"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all scale-95 opacity-0" id="modalContent">
            <div class="p-6">
                <!-- Ícone de Alerta -->
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 dark:bg-red-900/30 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-3xl"></i>
                </div>
                
                <!-- Título -->
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 text-center mb-2">
                    {{ __('companies.delete_company') }}
                </h3>
                
                <!-- Mensagem -->
                <p class="text-gray-600 dark:text-gray-300 text-center mb-1">
                    {{ __('companies.confirm_delete') }}
                </p>
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 text-center mb-6" id="companyNameDisplay">
                    <!-- Nome da empresa será inserido aqui -->
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center mb-6">
                    {{ __('companies.delete_warning') }}
                </p>
                
                <!-- Botões -->
                <div class="flex flex-col sm:flex-row gap-2">
                    <button type="button" id="cancelDeleteBtn" class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-times mr-1.5"></i>
                        {{ __('companies.cancel') }}
                    </button>
                    <button type="button" id="confirmDeleteBtn" class="flex-1 px-3 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors shadow-md hover:shadow-lg">
                        <i class="fas fa-trash mr-1.5"></i>
                        {{ __('companies.confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Enhanced animations and interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add ripple effect to buttons
            document.querySelectorAll('.btn-primary, .btn-secondary').forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        top: ${y}px;
                        left: ${x}px;
                        background: rgba(255, 255, 255, 0.5);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s ease-out;
                        pointer-events: none;
                    `;
                    
                    this.appendChild(ripple);
                    setTimeout(() => ripple.remove(), 600);
                });
            });
            
            // Add hover parallax effect to cards (disabled to prevent button issues)
            // Parallax effect can interfere with button clicks and hover states
            document.querySelectorAll('.card-hover').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    // Simple hover effect without transforms
                    this.style.transition = 'box-shadow 0.3s ease, transform 0.3s ease';
                });
                
                card.addEventListener('mouseleave', function() {
                    // Ensure transition remains
                    this.style.transition = 'box-shadow 0.3s ease, transform 0.3s ease';
                });
            });

            // Modal de Confirmação de Exclusão
            let deleteForm = null;
            const modal = document.getElementById('deleteCompanyModal');
            const modalContent = document.getElementById('modalContent');
            const modalOverlay = document.getElementById('modalOverlay');
            const cancelBtn = document.getElementById('cancelDeleteBtn');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            const companyNameDisplay = document.getElementById('companyNameDisplay');

            // Abrir modal ao clicar no botão de deletar
            document.querySelectorAll('.delete-company-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    deleteForm = this.closest('.delete-company-form');
                    const companyName = deleteForm.getAttribute('data-company-name');
                    
                    companyNameDisplay.textContent = `"${companyName}"`;
                    modal.classList.add('show');
                    modalContent.style.opacity = '1';
                    modalContent.style.transform = 'scale(1)';
                    document.body.style.overflow = 'hidden';
                });
            });

            // Fechar modal ao clicar em cancelar
            if (cancelBtn) {
                cancelBtn.addEventListener('click', closeModal);
            }

            // Fechar modal ao clicar no overlay
            if (modalOverlay) {
                modalOverlay.addEventListener('click', closeModal);
            }

            // Fechar modal com ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal && modal.classList.contains('show')) {
                    closeModal();
                }
            });

            // Confirmar exclusão
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    if (deleteForm) {
                        deleteForm.submit();
                    }
                });
            }

            function closeModal() {
                if (modal) {
                    modal.classList.remove('show');
                    if (modalContent) {
                        modalContent.style.opacity = '0';
                        modalContent.style.transform = 'scale(0.95)';
                    }
                    document.body.style.overflow = '';
                    deleteForm = null;
                }
            }
        });
        
        // Toggle filters on mobile
        function toggleFilters() {
            const form = document.getElementById('filtersForm');
            const icon = document.getElementById('toggleFiltersIcon');
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
        
        // Clear filters function
        function clearFilters() {
            const form = document.getElementById('filtersForm');
            if (form) {
                // Reset all form fields
                form.querySelectorAll('input[type="text"], select').forEach(field => {
                    if (field.name === 'search') {
                        field.value = '';
                    } else if (field.name) {
                        // For selects, set to 'all' or empty
                        if (field.tagName === 'SELECT') {
                            const allOption = field.querySelector('option[value="all"], option[value=""]');
                            if (allOption) {
                                field.value = allOption.value;
                            }
                        }
                    }
                });
                
                // Submit form to reload page without filters
                window.location.href = '{{ route("companies.index") }}';
            }
        }
        
        // Add ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            @keyframes modalFadeIn {
                from {
                    opacity: 0;
                    transform: scale(0.95);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }
            #deleteCompanyModal.show #modalContent {
                animation: modalFadeIn 0.3s ease-out forwards;
            }
            #deleteCompanyModal.show {
                display: flex !important;
            }
            
            /* Filtros mais compactos no mobile */
            @media (max-width: 767px) {
                #filtersForm {
                    gap: 0.75rem !important;
                }
                
                #filtersForm > div {
                    margin-bottom: 0.5rem !important;
                }
                
                #filtersForm label {
                    font-size: 0.75rem !important;
                    margin-bottom: 0.375rem !important;
                    line-height: 1.2;
                }
                
                #filtersForm input,
                #filtersForm select {
                    font-size: 14px !important;
                    padding-top: 0.5rem !important;
                    padding-bottom: 0.5rem !important;
                    padding-left: 0.5rem !important;
                    padding-right: 0.5rem !important;
                    min-height: 36px !important;
                    height: 36px !important;
                }
                
                #filtersForm button {
                    font-size: 0.875rem !important;
                    padding-top: 0.5rem !important;
                    padding-bottom: 0.5rem !important;
                    min-height: 36px !important;
                    height: 36px !important;
                }
                
                /* Container de filtros mais compacto */
                #filtersContainer {
                    padding: 0.75rem !important;
                    margin-bottom: 1rem !important;
                }
                
                #filtersForm {
                    margin-top: 0.5rem;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
