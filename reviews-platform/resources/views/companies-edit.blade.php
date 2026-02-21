@extends('layouts.admin')

@section('title', __('companies.edit') . ' - ' . __('app.name'))

@section('page-title', __('companies.edit') . ': ' . $company->name)

@section('header-actions')
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-4 w-full sm:w-auto">
        <!-- Mobile: Layout compacto e profissional -->
        <div class="flex items-center justify-between sm:justify-start gap-2 sm:gap-4 w-full sm:w-auto">
            <a href="/companies" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors text-sm sm:text-base px-2 py-1.5 sm:px-0 sm:py-0 flex items-center">
                <i class="fas fa-arrow-left mr-1.5 sm:mr-2 text-sm"></i>
                <span class="hidden sm:inline">{{ __('companies.back') }}</span>
                <span class="sm:hidden">{{ __('app.back') }}</span>
            </a>
            <span class="px-2 py-1 text-xs sm:text-sm font-medium rounded-full {{ $company->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }} whitespace-nowrap">
                {{ $company->status === 'published' ? __('companies.active') : __('companies.draft') }}
            </span>
        </div>
        <!-- Mobile: Botões em linha compacta -->
        <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto">
            <button type="button" onclick="submitForm()" class="btn-primary text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm sm:text-base font-medium min-h-[36px] sm:min-h-[44px] flex items-center justify-center flex-1 sm:flex-none">
                <i class="fas fa-upload mr-1.5 sm:mr-2 text-xs sm:text-sm"></i>
                <span class="hidden sm:inline">{{ __('companies.activate') }}</span>
                <span class="sm:hidden uppercase text-xs">{{ strtoupper(__('companies.activate')) }}</span>
            </button>
            <button type="button" onclick="saveForm()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm sm:text-base font-medium min-h-[36px] sm:min-h-[44px] flex items-center justify-center flex-1 sm:flex-none">
                <i class="fas fa-save mr-1.5 sm:mr-2 text-xs sm:text-sm"></i>
                <span class="hidden sm:inline">{{ __('companies.save') }}</span>
                <span class="sm:hidden uppercase text-xs">{{ strtoupper(__('app.save')) }}</span>
            </button>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .upload-area {
        border: 2px dashed #d1d5db;
        transition: all 0.3s ease;
    }
    .upload-area:hover {
        border-color: var(--primary-color);
        background-color: rgba(139, 92, 246, 0.05);
    }
    
    /* Cropper Modal Styles */
    #cropModal {
        display: none !important;
        position: fixed;
        z-index: 99999 !important;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        overflow-y: auto;
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        /* Garantir que cubra toda a tela no iPhone */
        inset: 0;
        /* Suporte para safe-area no iPhone */
        padding-top: env(safe-area-inset-top);
        padding-bottom: env(safe-area-inset-bottom);
        padding-left: env(safe-area-inset-left);
        padding-right: env(safe-area-inset-right);
    }
    
    #cropModal.show {
        display: flex !important;
        align-items: center;
        justify-content: center;
        padding: 20px;
        padding-top: calc(20px + env(safe-area-inset-top));
        padding-bottom: calc(20px + env(safe-area-inset-bottom));
        visibility: visible;
        opacity: 1;
    }
    
    /* Estilos específicos para iPhone/Safari */
    @supports (-webkit-touch-callout: none) {
        #cropModal {
            position: fixed !important;
            z-index: 99999 !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            height: -webkit-fill-available !important;
        }
        
        #cropModal.show {
            display: flex !important;
        }
    }
    
    .crop-modal-content {
        background-color: white;
        padding: 1rem;
        border-radius: 16px;
        width: 80vw;
        height: auto;
        max-width: 800px;
        overflow-y: auto;
        position: relative;
        z-index: 100000 !important;
        margin: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        pointer-events: auto !important;
        display: flex;
        flex-direction: column;
        max-height: 90vh;
        max-height: calc(90vh - env(safe-area-inset-top) - env(safe-area-inset-bottom));
    }
    
    #cropImageContainer {
        flex-shrink: 0;
    }
    
    @media (min-width: 640px) {
        .crop-modal-content {
            padding: 1.5rem;
        }
    }
    
    @media (max-width: 639px) {
        .crop-modal-content {
            width: 95%;
            padding: 1rem;
        }
    }
    
    .crop-modal-header {
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
        flex-shrink: 0;
        position: relative;
    }
    
    .crop-modal-header h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }
    
    .crop-modal-header p {
        margin: 0.5rem 0 0 0;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .crop-modal-close {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        background: transparent;
        border: none;
        color: #6b7280;
        font-size: 1.5rem;
        line-height: 1;
        padding: 0.25rem 0.5rem;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .crop-modal-close:hover {
        color: #111827;
    }

    .crop-modal-close:focus {
        outline: 2px solid #6366f1;
        outline-offset: 2px;
    }
    
    #cropImageContainer {
        width: 100%;
        height: 420px;
        background-color: #f3f4f6;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        touch-action: none;
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }
    
    @media (max-width: 639px) {
        #cropImageContainer {
            height: 300px;
            margin-bottom: 1rem;
        }
    }

    #cropperImage {
        display: none;
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 12px;
        user-select: none;
        visibility: visible;
        opacity: 1;
        transition: transform 0.1s ease-out;
        transform-origin: center center;
        will-change: transform;
    }
    
    #cropImageContainer #cropperImage[style*="display: block"] {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    /* Zoom controls */
    .zoom-controls {
        position: absolute;
        bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 0.5rem;
        z-index: 1000;
        background: rgba(0, 0, 0, 0.7);
        padding: 0.5rem;
        border-radius: 0.5rem;
        backdrop-filter: blur(10px);
    }
    
    .zoom-btn {
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 0.375rem;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #374151;
    }
    
    .zoom-btn:hover {
        background: white;
        transform: scale(1.1);
    }
    
    .zoom-btn:active {
        transform: scale(0.95);
    }
    
    .zoom-indicator {
        background: rgba(255, 255, 255, 0.9);
        color: #374151;
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        min-width: 60px;
        justify-content: center;
    }

    .crop-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none;
        display: none;
    }

    .crop-overlay.active {
        display: block;
    }

    #cropBox {
        position: absolute;
        border: 2px solid rgba(47, 128, 237, 0.95);
        box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.55);
        border-radius: 8px;
        background: rgba(47, 128, 237, 0.12);
        cursor: move;
        pointer-events: auto;
        display: none;
    }

    #cropBox.active {
        display: block;
    }

    #cropBox::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 6px;
        background-image:
            linear-gradient(rgba(255,255,255,0.35) 0, rgba(255,255,255,0.35) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.35) 0, rgba(255,255,255,0.35) 1px, transparent 1px);
        background-size: 100% calc(100%/3), calc(100%/3) 100%;
        background-position: 0 calc(100%/3), calc(100%/3) 0;
        pointer-events: none;
    }

    .crop-handle {
        position: absolute;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: #2f80ed;
        border: 2px solid #ffffff;
        touch-action: none;
        pointer-events: auto;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }
    
    @media (max-width: 639px) {
        .crop-handle {
            width: 24px;
            height: 24px;
            border-width: 3px;
        }
    }

    .crop-handle[data-handle="nw"] {
        top: -7px;
        left: -7px;
        cursor: nwse-resize;
    }

    .crop-handle[data-handle="ne"] {
        top: -7px;
        right: -7px;
        cursor: nesw-resize;
    }

    .crop-handle[data-handle="sw"] {
        bottom: -7px;
        left: -7px;
        cursor: nesw-resize;
    }

    .crop-handle[data-handle="se"] {
        bottom: -7px;
        right: -7px;
        cursor: nwse-resize;
    }

    .crop-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
        flex-shrink: 0;
        position: sticky;
        bottom: 0;
        background-color: white;
        z-index: 10001;
    }

    .crop-modal-actions button {
        z-index: 10002;
        position: relative;
        flex-shrink: 0;
    }
    
    .slider {
        -webkit-appearance: none;
        appearance: none;
        background: #e5e7eb;
        outline: none;
        border-radius: 8px;
        height: 8px;
        width: 100%;
    }
    
    @media (max-width: 768px) {
        .slider {
            height: 10px;
        }
        
        .slider::-webkit-slider-thumb {
            width: 24px;
            height: 24px;
        }
        
        .slider::-moz-range-thumb {
            width: 24px;
            height: 24px;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="url"] {
            font-size: 16px !important;
            min-height: 44px;
            padding: 0.75rem 1rem !important;
        }
        
        /* Garantir que o final da página seja visível */
        form#companyForm {
            padding-bottom: 6rem !important;
            margin-bottom: 2rem !important;
        }
        
        .max-w-4xl {
            padding-bottom: 4rem !important;
            margin-bottom: 2rem !important;
        }
        
        /* Última seção precisa de mais espaço */
        .bg-white.rounded-xl.shadow-sm:last-child,
        .dark .bg-gray-800.rounded-xl.shadow-sm:last-child {
            margin-bottom: 3rem !important;
            padding-bottom: 2rem !important;
        }
        
        /* Melhorias mobile - Header Actions */
        @media (max-width: 640px) {
            /* Reduzir padding dos cards */
            .bg-white.rounded-xl.shadow-sm,
            .dark .bg-gray-800.rounded-xl.shadow-sm {
                padding: 1rem !important;
            }
            
            /* Títulos menores */
            h2.text-xl {
                font-size: 1.125rem !important;
                margin-bottom: 1rem !important;
            }
            
            /* Labels menores */
            label.block.text-sm {
                font-size: 0.8125rem !important;
                margin-bottom: 0.5rem !important;
            }
            
            /* Inputs mais compactos mas ainda acessíveis */
            input[type="text"],
            input[type="email"],
            input[type="url"] {
                padding: 0.625rem 0.875rem !important;
                font-size: 16px !important;
                min-height: 42px;
            }
            
            /* Espaçamento entre seções reduzido */
            .space-y-4 > * + *,
            .space-y-6 > * + * {
                margin-top: 1rem !important;
            }
            
            /* Botões de ação mais compactos */
            button[onclick="submitForm()"],
            button[onclick="saveForm()"] {
                font-size: 0.75rem !important;
                padding: 0.5rem 0.75rem !important;
                min-height: 36px !important;
            }
            
            /* Status badge menor */
            span.px-2.py-1 {
                font-size: 0.6875rem !important;
                padding: 0.375rem 0.625rem !important;
            }
            
            /* Link voltar mais compacto */
            a[href="/companies"] {
                font-size: 0.8125rem !important;
                padding: 0.375rem 0.5rem !important;
            }
            
            /* Ícones menores */
            .fa-arrow-left,
            .fa-upload,
            .fa-save {
                font-size: 0.75rem !important;
            }
        }
    }
    
    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        background: #8b5cf6;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(139, 92, 246, 0.3);
        transition: all 0.3s ease;
        border: 2px solid #ffffff;
    }
    
    .slider::-webkit-slider-thumb:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
    }
    
    .slider::-moz-range-thumb {
        width: 20px;
        height: 20px;
        background: #8b5cf6;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 6px rgba(139, 92, 246, 0.3);
        transition: all 0.3s ease;
    }
    
    .slider::-moz-range-thumb:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
    }
    
    /* Dark mode support */
    .dark .slider {
        background: #374151;
    }
    
    .dark .slider::-webkit-slider-thumb {
        background: #8b5cf6;
        border-color: #1f2937;
    }
    
    .dark .slider::-moz-range-thumb {
        background: #8b5cf6;
        border-color: #1f2937;
    }
</style>
@endsection

@section('content')
@php
    $mediaAutoSaveToken = encrypt([
        'user_id' => auth()->id(),
        'company_id' => $company->id,
        'expires_at' => now()->addMinutes(60)->timestamp,
    ]);
@endphp
<div class="max-w-4xl mx-auto pb-6 sm:pb-8">
    <form method="POST" action="{{ route('companies.update', $company->id) }}" enctype="multipart/form-data" id="companyForm" class="space-y-3 sm:space-y-6 pb-12 sm:pb-6" data-media-save-url="{{ route('companies.auto-save-media', $company->id, false) }}" data-media-save-token="{{ $mediaAutoSaveToken }}">
        @csrf
        @method('PUT')

        <!-- Dados Básicos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-3 sm:p-6">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-100 mb-3 sm:mb-4">{{ __('companies.basic_info') }}</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">{{ __('companies.name') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $company->name) }}" placeholder="{{ __('companies.name_placeholder') }}" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base" style="font-size: 16px; min-height: 44px;" required>
                </div>

                <div>
                    @php
                        $urlHost = preg_replace('#^https?://#', '', rtrim(config('app.url'), '/'));
                    @endphp
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">{{ __('companies.url') }}</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-lg">{{ $urlHost }}/</span>
                        <input type="text" name="url" value="{{ old('url', $company->url) }}" placeholder="{{ __('companies.url_placeholder') }}" class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-r-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base" style="font-size: 16px; min-height: 44px;">
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('companies.url_hint') }}</p>
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">{{ __('companies.email') }} *</label>
                    <input type="email" name="negative_email" value="{{ old('negative_email', $company->negative_email) }}" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base" style="font-size: 16px; min-height: 44px;" required>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('companies.negative_email_desc') }}</p>
                </div>
            </div>
        </div>

        @if($company->status === 'published')
        <!-- Link da página de avaliação -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-3 sm:p-6">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">{{ __('companies.review_page_link') }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ __('companies.review_page_link_desc') }}</p>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <input type="text" value="{{ $company->public_url }}" readonly id="editPublicUrl" class="flex-1 px-3 sm:px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-800 dark:text-gray-200 text-sm">
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="navigator.clipboard && navigator.clipboard.writeText(document.getElementById('editPublicUrl').value); alert('{{ __("dashboard.link_copied") }}');" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm inline-flex items-center gap-2">
                        <i class="fas fa-copy"></i> {{ __('dashboard.copy') }}
                    </button>
                    <a href="{{ $company->public_url }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm inline-flex items-center gap-2">
                        <i class="fas fa-external-link-alt"></i> {{ __('dashboard.view') }}
                    </a>
                    <a href="{{ route('companies.qrcode', $company->id) }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm inline-flex items-center gap-2">
                        <i class="fas fa-qrcode"></i> {{ __('companies.download_qr') }}
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Upload de Imagens -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-3 sm:p-6">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-100 mb-3 sm:mb-4">{{ __('companies.visual_customization') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">{{ __('companies.logo') }}</label>
                    <div class="upload-area rounded-lg p-2 sm:p-4 text-center cursor-pointer" id="logoUploadArea">
                        <div id="logoPreviewContainer">
                            @if($company->logo_url)
                                <img src="{{ $company->logo_url }}?t={{ time() }}" alt="{{ __('companies.logo') }}" class="max-w-xs mx-auto mb-2 rounded-lg" id="logoPreviewImg" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div id="logoPlaceholder" class="py-2 sm:py-4" style="display: none;">
                                    <i class="fas fa-image text-2xl sm:text-4xl text-gray-400 dark:text-gray-500 mb-1 sm:mb-2"></i>
                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">{{ __('companies.upload_click') }}</p>
                                </div>
                            @else
                                <div id="logoPlaceholder" class="py-2 sm:py-4">
                                    <i class="fas fa-image text-2xl sm:text-4xl text-gray-400 dark:text-gray-500 mb-1 sm:mb-2"></i>
                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">{{ __('companies.upload_click') }}</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" name="logo" accept="image/png,image/jpeg,image/jpg,image/gif,image/webp" class="hidden" id="logoInput" onchange="handleLogoUpload(this)">
                        <input type="hidden" name="remove_logo" id="removeLogoFlag" value="0">
                        <div class="flex flex-col sm:flex-row gap-1.5 sm:gap-2 justify-center mt-1 sm:mt-2">
                            <label for="logoInput" class="text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 cursor-pointer inline-block text-xs sm:text-sm" style="touch-action: manipulation; -webkit-tap-highlight-color: transparent;">
                                <i class="fas fa-upload mr-1.5 sm:mr-2"></i>{{ __('companies.logo_change') }}
                            </label>
                            @if($company->logo_url)
                            <button type="button" onclick="removeLogo()" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-xs sm:text-sm" style="touch-action: manipulation; -webkit-tap-highlight-color: transparent;">
                                <i class="fas fa-trash mr-1.5 sm:mr-2"></i>{{ __('companies.remove_logo') }}
                            </button>
                            @endif
                        </div>
                        <div id="logoAutoSaveStatus" class="mt-1 sm:mt-2 text-xs text-gray-500 hidden flex items-center gap-1 justify-center" aria-live="polite"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">{{ __('companies.background_image') }}</label>
                    <div class="upload-area rounded-lg p-2 sm:p-4 text-center cursor-pointer" id="backgroundUploadArea">
                        <div id="backgroundPreviewContainer">
                            @if($company->background_image_url)
                                <img src="{{ $company->background_image_url }}" alt="{{ __('companies.background_image') }}" class="max-w-xs mx-auto mb-2 rounded-lg" id="backgroundPreviewImg">
                            @else
                                <div id="backgroundPlaceholder" class="py-2 sm:py-4">
                                    <i class="fas fa-image text-2xl sm:text-4xl text-gray-400 dark:text-gray-500 mb-1 sm:mb-2"></i>
                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">{{ __('companies.upload_click') }}</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" name="background_image" accept="image/png,image/jpeg,image/jpg,image/gif,image/webp" class="hidden" id="backgroundInput" onchange="handleBackgroundUpload(this)">
                        <input type="hidden" name="remove_background" id="removeBackgroundFlag" value="0">
                        <div class="flex flex-col sm:flex-row gap-1.5 sm:gap-2 justify-center mt-1 sm:mt-2">
                            <label for="backgroundInput" class="text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 cursor-pointer inline-block text-xs sm:text-sm" style="touch-action: manipulation; -webkit-tap-highlight-color: transparent;">
                                <i class="fas fa-upload mr-1.5 sm:mr-2"></i>{{ __('companies.background_change') }}
                            </label>
                            <button type="button" onclick="openStockImageModal()" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-xs sm:text-sm" style="touch-action: manipulation; -webkit-tap-highlight-color: transparent;">
                                <i class="fas fa-search mr-1.5 sm:mr-2"></i>{{ __('companies.browse_free_images') }}
                            </button>
                            @if($company->background_image_url)
                            <button type="button" onclick="removeBackground()" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-xs sm:text-sm" style="touch-action: manipulation; -webkit-tap-highlight-color: transparent;">
                                <i class="fas fa-trash mr-1.5 sm:mr-2"></i>{{ __('companies.remove_background') }}
                            </button>
                            @endif
                        </div>
                        <div id="backgroundAutoSaveStatus" class="mt-1 sm:mt-2 text-xs text-gray-500 hidden flex items-center gap-1 justify-center" aria-live="polite"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações de Contato -->
        {{-- Seção comentada - pode ser reativada no futuro se necessário --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-3 sm:p-6">
            {{-- <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">{{ __('companies.company_details') }}</h2> --}}
            
            <div class="space-y-3 sm:space-y-4">
                {{-- Campos comentados - podem ser reativados no futuro se necessário --}}
                {{-- 
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('companies.phone') }}</label>
                    <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number', $company->contact_number) }}" placeholder="{{ __('companies.phone_placeholder') }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base" style="font-size: 16px; min-height: 44px;" maxlength="15">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('companies.website') }}</label>
                    <input type="url" name="business_website" value="{{ old('business_website', $company->business_website) }}" placeholder="{{ __('companies.website_placeholder') }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base" style="font-size: 16px; min-height: 44px;">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('companies.address') }}</label>
                    <input type="text" name="business_address" value="{{ old('business_address', $company->business_address) }}" placeholder="{{ __('companies.address_placeholder') }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base" style="font-size: 16px; min-height: 44px;">
                </div>
                --}}

                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">{{ __('companies.google_business_url') }}</label>
                    <input type="text" name="google_business_url" value="{{ old('google_business_url', $company->google_business_url) }}" placeholder="{{ __('companies.google_business_url_placeholder') }}" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base" style="font-size: 16px; min-height: 44px;">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('companies.google_business_url_desc') }}</p>
                </div>
            </div>
        </div>

        <!-- Configurações -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-3 sm:p-6 mb-16 sm:mb-0" style="margin-bottom: 4rem;">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-100 mb-3 sm:mb-4">{{ __('companies.positive_score') }}</h2>
            
            <div>
                <label for="positive_score" class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">
                    {{ __('companies.positive_score_label') }} *
                </label>
                <div class="flex items-center space-x-4">
                    <input 
                        type="range" 
                        name="positive_score" 
                        id="positive_score"
                        min="1" 
                        max="5" 
                        value="{{ old('positive_score', $company->positive_score ?? 4) }}" 
                        class="flex-1 slider"
                        oninput="if(typeof updateStarDisplay === 'function') updateStarDisplay(this.value)"
                        required
                    >
                    <div class="flex items-center space-x-2 min-w-[120px]">
                        <span id="starCount" class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ old('positive_score', $company->positive_score ?? 4) }}</span>
                        <div class="flex text-yellow-400" id="starIcons">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= (old('positive_score', $company->positive_score ?? 4)))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <span>{{ __('companies.very_restrictive') }} (1)</span>
                    <span>{{ __('companies.very_permissive') }} (5)</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 mb-4">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ __('companies.positive_score_desc') }}
                </p>
            </div>
        </div>
        
        <!-- Espaçamento adicional para mobile -->
        <div class="h-12 sm:h-0"></div>
    </form>
</div>

    <!-- Stock Image Search Modal -->
    <div id="stockImageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center" style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col m-4">
            <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ __('companies.browse_free_images') }}</h3>
                    <button onclick="closeStockImageModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <div class="flex gap-2">
                    <input type="text" id="stockImageSearch" placeholder="{{ __('companies.search_images_placeholder') }}" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <button onclick="searchStockImages()" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        <i class="fas fa-search mr-2"></i>{{ __('companies.search') }}
                    </button>
                </div>
            </div>
            <div id="stockImagesGrid" class="flex-1 overflow-y-auto p-4 sm:p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-8">
                    <i class="fas fa-image text-4xl mb-2"></i>
                    <p>{{ __('companies.search_images_hint') }}</p>
                </div>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                    {{ __('companies.images_provided_by') }} <a href="https://loremflickr.com" target="_blank" class="text-blue-600 hover:underline">LoremFlickr</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Crop Modal -->
    <div id="cropModal">
    <div class="crop-modal-content">
        <div class="crop-modal-header">
            <h3>{{ __('companies.crop_logo') }}</h3>
            <p>{{ __('companies.crop_logo_desc') }}</p>
            <button type="button" class="crop-modal-close" onclick="cancelCrop(true)" aria-label="{{ __('companies.cancel') }}">
                &times;
            </button>
        </div>
            <div id="cropImageContainer">
                <img id="cropperImage" src="" alt="Crop">
                <div id="cropOverlay" class="crop-overlay">
                    <div id="cropBox">
                        <span class="crop-handle" data-handle="nw"></span>
                        <span class="crop-handle" data-handle="ne"></span>
                        <span class="crop-handle" data-handle="sw"></span>
                        <span class="crop-handle" data-handle="se"></span>
                    </div>
                </div>
                <!-- Zoom Controls -->
                <div class="zoom-controls">
                    <button type="button" class="zoom-btn" onclick="zoomOut()" title="Zoom Out">
                        <i class="fas fa-minus"></i>
                    </button>
                    <div class="zoom-indicator" id="zoomIndicator">100%</div>
                    <button type="button" class="zoom-btn" onclick="zoomIn()" title="Zoom In">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button type="button" class="zoom-btn" onclick="resetZoom()" title="Reset Zoom">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
            </div>
        <div class="crop-modal-actions">
            <button type="button" id="cancelCropBtn" class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200 cursor-pointer font-medium shadow-sm hover:shadow" style="z-index: 10001; position: relative; min-width: 120px;">
                <i class="fas fa-times mr-2"></i>
                {{ __('companies.cancel') }}
            </button>
            <button type="button" id="applyCropBtn" class="px-6 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-200 cursor-pointer font-medium shadow-md hover:shadow-lg" style="z-index: 10001; position: relative; min-width: 120px;">
                <i class="fas fa-check mr-2"></i>
                {{ __('companies.apply_crop') }}
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
        // Define remove functions globally first
        function removeLogo() {
            if (confirm('{{ __('companies.confirm_remove_logo') }}')) {
                const removeLogoInput = document.getElementById('removeLogoFlag');
                if (removeLogoInput) {
                    removeLogoInput.value = '1';
                }
                const logoPreviewImg = document.getElementById('logoPreviewImg');
                const logoPlaceholder = document.getElementById('logoPlaceholder');
                const logoInput = document.getElementById('logoInput');
                
                if (logoPreviewImg) {
                    logoPreviewImg.style.display = 'none';
                }
                if (logoPlaceholder) {
                    logoPlaceholder.style.display = 'block';
                }
                if (logoInput) {
                    logoInput.value = '';
                }
            }
        }

        function removeBackground() {
            if (confirm('{{ __('companies.confirm_remove_background') }}')) {
                const removeBackgroundInput = document.getElementById('removeBackgroundFlag');
                if (removeBackgroundInput) {
                    removeBackgroundInput.value = '1';
                }
                const backgroundPreviewImg = document.getElementById('backgroundPreviewImg');
                const backgroundPlaceholder = document.getElementById('backgroundPlaceholder');
                const backgroundInput = document.getElementById('backgroundInput');
                
                if (backgroundPreviewImg) {
                    backgroundPreviewImg.style.display = 'none';
                }
                if (backgroundPlaceholder) {
                    backgroundPlaceholder.style.display = 'block';
                }
                if (backgroundInput) {
                    backgroundInput.value = '';
                }
            }
        }

        const cropElements = {
            modal: null,
            container: null,
            image: null,
            overlay: null,
            box: null
        };

        const cropState = {
            originalImage: null,
            naturalWidth: 0,
            naturalHeight: 0,
            imageRect: null,
            cropBox: { x: 0, y: 0, width: 0, height: 0 },
            ratios: null,
            isDragging: false,
            mode: null,
            handle: null,
            startX: 0,
            startY: 0,
            startCrop: null,
            fileName: 'logo.png',
            freeCrop: true, // Allow free crop (non-square) by default
            maintainAspectRatio: false, // Allow independent width/height adjustment
            zoom: 1.0, // Zoom level (1.0 = 100%)
            minZoom: 0.5, // Minimum zoom (50%)
            maxZoom: 5.0, // Maximum zoom (500%)
            panX: 0, // Pan offset X
            panY: 0, // Pan offset Y
            isZooming: false, // Track if currently zooming
            initialDistance: 0, // Initial distance between two touches for pinch
            lastTouchCenter: { x: 0, y: 0 } // Center point of pinch gesture
        };

        function ensureCropElements() {
            if (!cropElements.modal) {
                cropElements.modal = document.getElementById('cropModal');
                cropElements.container = document.getElementById('cropImageContainer');
                cropElements.image = document.getElementById('cropperImage');
                cropElements.overlay = document.getElementById('cropOverlay');
                cropElements.box = document.getElementById('cropBox');
            }
        }

// Make updateStarDisplay available globally before DOMContentLoaded
window.updateStarDisplay = function(value) {
    const starCount = document.getElementById('starCount');
    if (starCount) {
        starCount.textContent = value;
    }
    const starIcons = document.getElementById('starIcons');
    if (starIcons) {
        starIcons.innerHTML = '';
        for (let i = 0; i < value; i++) {
            starIcons.innerHTML += '<i class="fas fa-star"></i>';
        }
        for (let i = value; i < 5; i++) {
            starIcons.innerHTML += '<i class="far fa-star"></i>';
        }
    }
};


document.addEventListener('DOMContentLoaded', function() {
            ensureCropElements();

            if (cropElements.box) {
                cropElements.box.addEventListener('pointerdown', startCropInteraction);
            }
            
            // Add zoom support with mouse wheel
            if (cropElements.container) {
                cropElements.container.addEventListener('wheel', function(e) {
                    if (cropElements.modal && cropElements.modal.classList.contains('show')) {
                        e.preventDefault();
                        const delta = e.deltaY > 0 ? 0.9 : 1.1;
                        cropState.zoom = Math.max(cropState.minZoom, Math.min(cropState.maxZoom, cropState.zoom * delta));
                        applyZoom();
                    }
                }, { passive: false });
                
                // Pinch-to-zoom support (touch)
                let touches = [];
                let initialZoom = 1.0;
                let initialPanX = 0;
                let initialPanY = 0;
                let gestureCenter = { x: 0, y: 0 };
                
                cropElements.container.addEventListener('touchstart', function(e) {
                    if (e.touches.length === 2) {
                        e.preventDefault();
                        touches = Array.from(e.touches);
                        cropState.isZooming = true;
                        
                        // Store initial state
                        initialZoom = cropState.zoom;
                        initialPanX = cropState.panX;
                        initialPanY = cropState.panY;
                        
                        // Calculate initial distance and center
                        cropState.initialDistance = getDistance(touches[0], touches[1]);
                        gestureCenter = getTouchCenter(touches[0], touches[1]);
                        
                        // Get container bounds for center calculation
                        const containerRect = cropElements.container.getBoundingClientRect();
                        gestureCenter.x -= containerRect.left;
                        gestureCenter.y -= containerRect.top;
                    }
                }, { passive: false });
                
                cropElements.container.addEventListener('touchmove', function(e) {
                    if (e.touches.length === 2 && cropState.isZooming) {
                        e.preventDefault();
                        touches = Array.from(e.touches);
                        
                        // Calculate current distance
                        const currentDistance = getDistance(touches[0], touches[1]);
                        const scale = currentDistance / cropState.initialDistance;
                        
                        // Calculate new zoom
                        const newZoom = Math.max(cropState.minZoom, Math.min(cropState.maxZoom, initialZoom * scale));
                        
                        // Calculate zoom change
                        const zoomChange = newZoom / initialZoom;
                        
                        // Adjust pan to keep gesture center fixed
                        // When zooming, we need to adjust pan so the point under the gesture center stays in place
                        const containerRect = cropElements.container.getBoundingClientRect();
                        const currentCenter = getTouchCenter(touches[0], touches[1]);
                        const centerX = currentCenter.x - containerRect.left;
                        const centerY = currentCenter.y - containerRect.top;
                        
                        // Calculate how much the image needs to shift to keep the gesture center point fixed
                        const offsetX = (gestureCenter.x - centerX) * (zoomChange - 1);
                        const offsetY = (gestureCenter.y - centerY) * (zoomChange - 1);
                        
                        // Update zoom and pan
                        cropState.zoom = newZoom;
                        cropState.panX = initialPanX - offsetX;
                        cropState.panY = initialPanY - offsetY;
                        
                        applyZoom();
                    }
                }, { passive: false });
                
                cropElements.container.addEventListener('touchend', function(e) {
                    if (e.touches.length < 2) {
                        cropState.isZooming = false;
                        initialZoom = 1.0;
                        initialPanX = 0;
                        initialPanY = 0;
                        gestureCenter = { x: 0, y: 0 };
                    }
                });
            }

            const applyBtn = document.getElementById('applyCropBtn');
            if (applyBtn) applyBtn.addEventListener('click', applyCrop);
            const cancelBtn = document.getElementById('cancelCropBtn');
            if (cancelBtn) cancelBtn.addEventListener('click', () => cancelCrop());

            window.addEventListener('resize', handleResizeWhileOpen);

            // Star Rating Display - Function is already defined globally above
    
            // Código comentado - referente ao campo de telefone que foi desabilitado
            // Pode ser reativado no futuro se necessário
            /*
    const phoneInput = document.getElementById('contact_number');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) {
                        value = value.slice(0, 11);
            }
            
            if (value.length > 10) {
                        e.target.value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
            } else if (value.length > 6) {
                        e.target.value = `(${value.slice(0, 2)}) ${value.slice(2, 6)}-${value.slice(6)}`;
            } else if (value.length > 2) {
                        e.target.value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
            } else if (value.length > 0) {
                        e.target.value = `(${value}`;
            }
        });
    }
            */
        });

        function handleLogoUpload(input) {
            ensureCropElements();
            const file = input.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                alert('Por favor, selecione uma imagem válida.');
                input.value = '';
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('A imagem é muito grande. Por favor, selecione uma imagem menor que 5MB.');
                input.value = '';
                return;
            }

            cropState.fileName = file.name ? `${file.name.replace(/\.[^/.]+$/, '') || 'logo'}.png` : 'logo.png';
            cropState.originalImage = new Image();

            const reader = new FileReader();
            reader.onload = function(event) {
                try {
                    if (!cropState.originalImage) {
                        cropState.originalImage = new Image();
                    }
                    cropState.originalImage.onload = function() {
                        cropState.naturalWidth = cropState.originalImage.naturalWidth;
                        cropState.naturalHeight = cropState.originalImage.naturalHeight;
                        cropState.ratios = null;
                        showCropModal(event.target.result);
                    };
                    cropState.originalImage.onerror = function() {
                        console.error('Error loading image:', file.name);
                        alert('Erro ao carregar a imagem. Tente novamente.');
                        input.value = '';
                    };
                    cropState.originalImage.src = event.target.result;
                } catch (error) {
                    console.error('Error processing image:', error);
                    alert('Erro ao processar a imagem. Tente novamente.');
                    input.value = '';
                }
            };
            reader.onerror = function(error) {
                console.error('FileReader error:', error);
                alert('Erro ao ler o arquivo. Tente novamente.');
                input.value = '';
            };
            reader.onabort = function() {
                console.warn('FileReader aborted');
                input.value = '';
            };
            try {
                reader.readAsDataURL(file);
            } catch (error) {
                console.error('Error reading file:', error);
                alert('Erro ao ler o arquivo. Verifique se o arquivo é uma imagem válida.');
                input.value = '';
            }
        }

        function showCropModal(imageSrc) {
            ensureCropElements();
            if (!cropElements.image || !cropElements.modal) {
                console.error('Crop elements not found:', {
                    image: !!cropElements.image,
                    modal: !!cropElements.modal
                });
                return;
            }

            console.log('Showing crop modal with image:', imageSrc.substring(0, 50) + '...');
            
            // Reset zoom and pan when opening modal
            cropState.zoom = 1.0;
            cropState.panX = 0;
            cropState.panY = 0;
            cropState.isZooming = false;
            
            // Clear previous event handlers
            cropElements.image.onload = null;
            cropElements.image.onerror = null;
            
            let imageLoaded = false;
            
            cropElements.image.onload = function() {
                if (imageLoaded) return; // Prevent multiple calls
                imageLoaded = true;
                console.log('Image loaded in modal');
                cropElements.image.onerror = null; // Remove error handler after successful load
                applyZoom(); // Apply initial zoom
                prepareCropUI();
            };
            
            cropElements.image.onerror = function() {
                if (imageLoaded) return; // Don't show error if already loaded
                console.error('Error loading image in modal');
                alert('Erro ao carregar a imagem no modal. Tente novamente.');
            };
            
            cropElements.image.src = imageSrc;
            cropElements.image.style.display = 'block';
            cropElements.image.style.visibility = 'visible';
            cropElements.image.style.opacity = '1';
            
            // Garantir z-index máximo no iPhone
            cropElements.modal.style.zIndex = '99999';
            cropElements.modal.style.position = 'fixed';
            cropElements.modal.style.top = '0';
            cropElements.modal.style.left = '0';
            cropElements.modal.style.right = '0';
            cropElements.modal.style.bottom = '0';
            cropElements.modal.style.width = '100%';
            cropElements.modal.style.height = '100%';
            
            cropElements.modal.classList.add('show');
            // Prevenir scroll do body quando modal aberto
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.width = '100%';

            // If image is already loaded (cached), trigger onload manually
            if (cropElements.image.complete && cropElements.image.naturalWidth > 0) {
                console.log('Image already complete, preparing UI');
                applyZoom();
                if (cropElements.image.onload) {
                    cropElements.image.onload();
                } else {
                    prepareCropUI();
                }
            }
        }

        function prepareCropUI() {
            ensureCropElements();
            if (!cropElements.image || !cropElements.container) {
                console.error('Missing crop elements in prepareCropUI');
                return;
            }
            
            // Ensure image is visible
            cropElements.image.style.display = 'block';
            cropElements.image.style.visibility = 'visible';
            cropElements.image.style.opacity = '1';
            
            if (!cropElements.image.complete || cropElements.image.naturalWidth === 0) {
                console.log('Image not complete yet, retrying...');
                setTimeout(prepareCropUI, 50);
                return;
            }

            console.log('Preparing crop UI, image dimensions:', cropElements.image.naturalWidth, 'x', cropElements.image.naturalHeight);
            
            updateImageRect();

            if (!cropState.imageRect || !cropState.imageRect.width || !cropState.imageRect.height) {
                console.log('Image rect not ready, retrying...');
                setTimeout(prepareCropUI, 50);
                return;
            }

            cropElements.overlay.classList.add('active');
            cropElements.box.classList.add('active');

            if (cropState.ratios) {
                applyRatiosToCropBox();
            } else {
                initializeCropBox();
            }

            updateCropBoxPosition();
            console.log('Crop UI prepared successfully');
        }

        function updateImageRect() {
            ensureCropElements();
            if (!cropElements.container || !cropElements.image || !cropState.originalImage) return;

            const containerRect = cropElements.container.getBoundingClientRect();
            const imageRect = cropElements.image.getBoundingClientRect();

            // Get the actual displayed image dimensions from getBoundingClientRect
            // This already accounts for the transform (zoom and pan)
            const displayedWidth = imageRect.width;
            const displayedHeight = imageRect.height;
            
            // Calculate position relative to container
            const imageLeft = imageRect.left - containerRect.left;
            const imageTop = imageRect.top - containerRect.top;
            
            cropState.imageRect = {
                left: imageLeft,
                top: imageTop,
                width: displayedWidth,
                height: displayedHeight
            };
        }
        
        function applyZoom() {
            ensureCropElements();
            if (!cropElements.image || !cropState.originalImage) return;
            
            // Apply zoom and pan transform
            const transform = `translate(${cropState.panX}px, ${cropState.panY}px) scale(${cropState.zoom})`;
            cropElements.image.style.transform = transform;
            cropElements.image.style.transformOrigin = 'center center';
            
            // Update zoom indicator
            const zoomIndicator = document.getElementById('zoomIndicator');
            if (zoomIndicator) {
                zoomIndicator.textContent = Math.round(cropState.zoom * 100) + '%';
            }
            
            // Update image rect after zoom
            requestAnimationFrame(() => {
                updateImageRect();
                if (cropState.cropBox.width > 0 && cropState.cropBox.height > 0) {
                    updateCropBoxPosition();
                }
            });
        }
        
        function zoomIn() {
            cropState.zoom = Math.min(cropState.zoom * 1.2, cropState.maxZoom);
            applyZoom();
        }
        
        function zoomOut() {
            cropState.zoom = Math.max(cropState.zoom / 1.2, cropState.minZoom);
            applyZoom();
        }
        
        function resetZoom() {
            cropState.zoom = 1.0;
            cropState.panX = 0;
            cropState.panY = 0;
            applyZoom();
        }
        
        function getDistance(touch1, touch2) {
            const dx = touch1.clientX - touch2.clientX;
            const dy = touch1.clientY - touch2.clientY;
            return Math.sqrt(dx * dx + dy * dy);
        }
        
        function getTouchCenter(touch1, touch2) {
            return {
                x: (touch1.clientX + touch2.clientX) / 2,
                y: (touch1.clientY + touch2.clientY) / 2
            };
        }

        function initializeCropBox() {
            if (!cropState.imageRect) return;
            const { left, top, width, height } = cropState.imageRect;
            
            // Initialize with 80% of image dimensions, allowing free crop
            const cropWidth = Math.max(60, width * 0.8);
            const cropHeight = Math.max(60, height * 0.8);
            
            cropState.cropBox = {
                x: left + (width - cropWidth) / 2,
                y: top + (height - cropHeight) / 2,
                width: cropWidth,
                height: cropHeight
            };
        }

        function applyRatiosToCropBox() {
            if (!cropState.ratios || !cropState.imageRect) {
                initializeCropBox();
                return;
            }

            const { left, top, width, height } = cropState.imageRect;
            
            // If ratios have size (old format), convert to width/height
            if (cropState.ratios.size) {
                const minDimension = Math.min(width, height);
                const desiredSize = cropState.ratios.size * minDimension;
                const minSize = Math.max(60, minDimension * 0.15);
                const size = clamp(desiredSize, minSize, minDimension);
                let x = left + cropState.ratios.x * width;
                let y = top + cropState.ratios.y * height;
                x = clamp(x, left, left + width - size);
                y = clamp(y, top, top + height - size);
                cropState.cropBox = { x, y, width: size, height: size };
            } else if (cropState.ratios.width && cropState.ratios.height) {
                // New format with width and height
                let x = left + cropState.ratios.x * width;
                let y = top + cropState.ratios.y * height;
                const cropWidth = Math.min(cropState.ratios.width * width, width - (x - left));
                const cropHeight = Math.min(cropState.ratios.height * height, height - (y - top));
                x = clamp(x, left, left + width - cropWidth);
                y = clamp(y, top, top + height - cropHeight);
                cropState.cropBox = { x, y, width: cropWidth, height: cropHeight };
            } else {
                initializeCropBox();
            }
        }

        function updateCropBoxPosition() {
            ensureCropElements();
            if (!cropElements.box || !cropState.imageRect) return;
            const { x, y, width, height } = cropState.cropBox;
            cropElements.box.style.left = `${x}px`;
            cropElements.box.style.top = `${y}px`;
            cropElements.box.style.width = `${width}px`;
            cropElements.box.style.height = `${height}px`;
            cropElements.overlay.classList.add('active');
            cropElements.box.classList.add('active');
            updateRatios();
        }

        function updateRatios() {
            if (!cropState.imageRect) return;
            const { left, top, width, height } = cropState.imageRect;
            const { x, y, width: cropWidth, height: cropHeight } = cropState.cropBox;

            cropState.ratios = {
                x: (x - left) / width,
                y: (y - top) / height,
                width: cropWidth / width,
                height: cropHeight / height
            };
        }

        function startCropInteraction(event) {
            ensureCropElements();
            if (!cropState.imageRect) return;

            event.preventDefault();
            const handle = event.target.dataset.handle || null;
            cropState.mode = handle ? 'resize' : 'move';
            cropState.handle = handle;
            cropState.isDragging = true;
            cropState.startX = event.clientX;
            cropState.startY = event.clientY;
            cropState.startCrop = { ...cropState.cropBox };

            window.addEventListener('pointermove', onCropPointerMove);
            window.addEventListener('pointerup', endCropInteraction);
        }

        function onCropPointerMove(event) {
            if (!cropState.isDragging) return;
            event.preventDefault();
            const dx = event.clientX - cropState.startX;
            const dy = event.clientY - cropState.startY;

            if (cropState.mode === 'move') {
                moveCropBox(dx, dy);
            } else {
                resizeCropBox(dx, dy);
            }

            updateCropBoxPosition();
        }

        function endCropInteraction() {
            cropState.isDragging = false;
            window.removeEventListener('pointermove', onCropPointerMove);
            window.removeEventListener('pointerup', endCropInteraction);
        }

        function moveCropBox(dx, dy) {
            const limits = getLimits();
            const { width, height } = cropState.startCrop;
            const maxX = limits.right - width;
            const maxY = limits.bottom - height;

            cropState.cropBox.x = clamp(cropState.startCrop.x + dx, limits.left, maxX);
            cropState.cropBox.y = clamp(cropState.startCrop.y + dy, limits.top, maxY);
            cropState.cropBox.width = width;
            cropState.cropBox.height = height;
        }

        function resizeCropBox(dx, dy) {
            const limits = getLimits();
            const minDimension = Math.min(cropState.imageRect.width, cropState.imageRect.height);
            const minWidth = Math.max(60, minDimension * 0.15);
            const minHeight = Math.max(60, minDimension * 0.15);
            const start = cropState.startCrop;
            const startRight = start.x + start.width;
            const startBottom = start.y + start.height;
            let newWidth = start.width;
            let newHeight = start.height;
            let newX = start.x;
            let newY = start.y;

            switch (cropState.handle) {
                case 'nw': {
                    // Northwest handle - adjust top and left
                    const newLeft = clamp(start.x + dx, limits.left, startRight - minWidth);
                    const newTop = clamp(start.y + dy, limits.top, startBottom - minHeight);
                    newWidth = startRight - newLeft;
                    newHeight = startBottom - newTop;
                    newWidth = clamp(newWidth, minWidth, limits.right - limits.left);
                    newHeight = clamp(newHeight, minHeight, limits.bottom - limits.top);
                    newX = startRight - newWidth;
                    newY = startBottom - newHeight;
                    break;
                }
                case 'ne': {
                    // Northeast handle - adjust top and right
                    const newRight = clamp(startRight + dx, start.x + minWidth, limits.right);
                    const newTop = clamp(start.y + dy, limits.top, startBottom - minHeight);
                    newWidth = newRight - start.x;
                    newHeight = startBottom - newTop;
                    newWidth = clamp(newWidth, minWidth, limits.right - start.x);
                    newHeight = clamp(newHeight, minHeight, limits.bottom - limits.top);
                    newX = start.x;
                    newY = startBottom - newHeight;
                    break;
                }
                case 'sw': {
                    // Southwest handle - adjust bottom and left
                    const newLeft = clamp(start.x + dx, limits.left, startRight - minWidth);
                    const newBottom = clamp(startBottom + dy, start.y + minHeight, limits.bottom);
                    newWidth = startRight - newLeft;
                    newHeight = newBottom - start.y;
                    newWidth = clamp(newWidth, minWidth, limits.right - limits.left);
                    newHeight = clamp(newHeight, minHeight, limits.bottom - start.y);
                    newX = startRight - newWidth;
                    newY = start.y;
                    break;
                }
                case 'se':
                default: {
                    // Southeast handle - adjust bottom and right (default)
                    const newRight = clamp(startRight + dx, start.x + minWidth, limits.right);
                    const newBottom = clamp(startBottom + dy, start.y + minHeight, limits.bottom);
                    newWidth = newRight - start.x;
                    newHeight = newBottom - start.y;
                    newWidth = clamp(newWidth, minWidth, limits.right - start.x);
                    newHeight = clamp(newHeight, minHeight, limits.bottom - start.y);
                    newX = start.x;
                    newY = start.y;
                    break;
                }
            }

            // If maintaining aspect ratio, adjust accordingly
            if (cropState.maintainAspectRatio && cropState.startCrop.width > 0 && cropState.startCrop.height > 0) {
                const aspectRatio = cropState.startCrop.width / cropState.startCrop.height;
                if (Math.abs(dx) > Math.abs(dy)) {
                    // Width changed more, adjust height
                    newHeight = newWidth / aspectRatio;
                    if (newY + newHeight > limits.bottom) {
                        newHeight = limits.bottom - newY;
                        newWidth = newHeight * aspectRatio;
                    }
                } else {
                    // Height changed more, adjust width
                    newWidth = newHeight * aspectRatio;
                    if (newX + newWidth > limits.right) {
                        newWidth = limits.right - newX;
                        newHeight = newWidth / aspectRatio;
                    }
                }
            }

            const maxX = limits.right - newWidth;
            const maxY = limits.bottom - newHeight;
            cropState.cropBox.x = clamp(newX, limits.left, maxX);
            cropState.cropBox.y = clamp(newY, limits.top, maxY);
            cropState.cropBox.width = newWidth;
            cropState.cropBox.height = newHeight;
        }

        function getLimits() {
            if (!cropState.imageRect) {
                return { left: 0, top: 0, right: 0, bottom: 0 };
            }
            const { left, top, width, height } = cropState.imageRect;
            return {
                left,
                top,
                right: left + width,
                bottom: top + height
            };
        }

        function handleResizeWhileOpen() {
            ensureCropElements();
            if (!cropElements.modal || !cropElements.modal.classList.contains('show')) return;
            if (!cropElements.image || !cropElements.image.src) return;

            updateImageRect();
            if (!cropState.imageRect) return;

            if (cropState.ratios) {
                applyRatiosToCropBox();
            } else {
                initializeCropBox();
            }

            updateCropBoxPosition();
        }

        function applyCrop() {
            ensureCropElements();
            if (!cropState.originalImage || !cropState.imageRect) {
                alert('Carregue uma imagem para recortar.');
                return;
            }

            const { left, top, width, height } = cropState.imageRect;
            const { x, y, width: cropWidth, height: cropHeight } = cropState.cropBox;
            
            // Account for zoom and pan in calculations
            const relativeX = (x - left) / cropState.zoom;
            const relativeY = (y - top) / cropState.zoom;
            const relativeWidth = cropWidth / cropState.zoom;
            const relativeHeight = cropHeight / cropState.zoom;
            
            // Get the actual displayed image dimensions (accounting for zoom)
            const displayedWidth = width * cropState.zoom;
            const displayedHeight = height * cropState.zoom;
            
            const scaleX = cropState.naturalWidth / displayedWidth;
            const scaleY = cropState.naturalHeight / displayedHeight;
            
            // Calculate source coordinates and dimensions
            let sourceX = relativeX * scaleX;
            let sourceY = relativeY * scaleY;
            let sourceWidth = relativeWidth * scaleX;
            let sourceHeight = relativeHeight * scaleY;

            // Clamp to image boundaries
            sourceX = clamp(sourceX, 0, cropState.naturalWidth - 1);
            sourceY = clamp(sourceY, 0, cropState.naturalHeight - 1);
            sourceWidth = Math.min(sourceWidth, cropState.naturalWidth - sourceX);
            sourceHeight = Math.min(sourceHeight, cropState.naturalHeight - sourceY);
            sourceWidth = Math.max(1, sourceWidth);
            sourceHeight = Math.max(1, sourceHeight);

            // Calculate output dimensions (max 800px, maintain aspect ratio)
            const maxOutputSize = 800;
            let outputWidth = sourceWidth;
            let outputHeight = sourceHeight;
            
            if (outputWidth > maxOutputSize || outputHeight > maxOutputSize) {
                const scale = Math.min(maxOutputSize / outputWidth, maxOutputSize / outputHeight);
                outputWidth = Math.round(outputWidth * scale);
                outputHeight = Math.round(outputHeight * scale);
            }

            // Create canvas with actual crop dimensions (not forced square)
            const canvas = document.createElement('canvas');
            canvas.width = outputWidth;
            canvas.height = outputHeight;
            const ctx = canvas.getContext('2d');
            
            // Enable transparency support
            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';
            
            // Draw the cropped image
            ctx.drawImage(
                cropState.originalImage,
                sourceX,
                sourceY,
                sourceWidth,
                sourceHeight,
                0,
                0,
                outputWidth,
                outputHeight
            );

            canvas.toBlob(function(blob) {
                if (!blob) {
                    alert('Erro ao processar a imagem. Tente novamente.');
                    return;
                }

                const file = new File([blob], cropState.fileName, { type: 'image/png' });
                
                // Detectar iOS/Safari
                const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) || 
                             (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
                
                const logoInput = document.getElementById('logoInput');
                if (logoInput) {
                    // Método mais confiável para iOS
                    if (isIOS) {
                        // No iOS, usar DataTransfer pode não funcionar imediatamente
                        // Vamos tentar uma abordagem alternativa
                        try {
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            logoInput.files = dataTransfer.files;
                            
                            // Verificar se o arquivo foi atribuído corretamente
                            if (!logoInput.files || logoInput.files.length === 0) {
                                console.warn('iOS: DataTransfer não funcionou, tentando método alternativo');
                                // Método alternativo: criar um novo input temporário
                                const tempInput = document.createElement('input');
                                tempInput.type = 'file';
                                tempInput.accept = 'image/png';
                                const formData = new FormData();
                                formData.append('logo', file);
                                // Armazenar o blob para uso posterior
                                logoInput._blob = blob;
                                logoInput._file = file;
                            }
                        } catch (e) {
                            console.error('Erro ao atribuir arquivo no iOS:', e);
                            // Armazenar o blob para uso posterior
                            logoInput._blob = blob;
                            logoInput._file = file;
                        }
                    } else {
                        // Método padrão para outros navegadores
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        logoInput.files = dataTransfer.files;
                    }
                    
                    console.log('File added to input:', {
                        fileName: file.name,
                        fileSize: file.size,
                        fileType: file.type,
                        inputFilesLength: logoInput.files ? logoInput.files.length : 0,
                        isIOS: isIOS
                    });
                } else {
                    console.error('logoInput not found!');
                }

                const base64Data = canvas.toDataURL('image/png');
                const logoPreviewContainer = document.getElementById('logoPreviewContainer');
                const logoPreviewImg = document.getElementById('logoPreviewImg');
                const logoPlaceholder = document.getElementById('logoPlaceholder');

                // Hide placeholder if exists
                if (logoPlaceholder) {
                    logoPlaceholder.style.display = 'none';
                }

                // Update or create preview image
                if (logoPreviewImg) {
                    logoPreviewImg.src = base64Data;
                    logoPreviewImg.style.display = 'block';
                } else if (logoPreviewContainer) {
                    // Create preview image if it doesn't exist
                    const newImg = document.createElement('img');
                    newImg.id = 'logoPreviewImg';
                    newImg.src = base64Data;
                    newImg.alt = '{{ __('companies.logo') }}';
                    newImg.className = 'max-w-xs mx-auto mb-2 rounded-lg';
                    logoPreviewContainer.insertBefore(newImg, logoPreviewContainer.firstChild);
                }

                // No iOS, adicionar um pequeno delay para garantir que o arquivo esteja disponível
                if (isIOS) {
                    setTimeout(function() {
                        // Verificar novamente se o arquivo está disponível
                        if (logoInput && logoInput.files && logoInput.files.length > 0) {
                            autoSaveMedia('logo');
                        } else if (logoInput && logoInput._file) {
                            // Se o arquivo não foi atribuído via DataTransfer, usar o método alternativo
                            console.log('iOS: Usando método alternativo para auto-save');
                            // Criar um FormData manualmente
                            const form = document.getElementById('companyForm');
                            if (form) {
                                const mediaSaveUrl = form.dataset.mediaSaveUrl;
                                const mediaSaveToken = form.dataset.mediaSaveToken || '';
                                if (mediaSaveUrl) {
                                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                                    if (csrfMeta) {
                                        const formData = new FormData();
                                        formData.append('_token', csrfMeta.getAttribute('content'));
                                        formData.append('media_type', 'logo');
                                        formData.append('logo', logoInput._file);
                                        if (mediaSaveToken) {
                                            formData.append('media_token', mediaSaveToken);
                                        }
                                        
                                        let requestUrl = mediaSaveUrl;
                                        try {
                                            const absoluteUrl = new URL(mediaSaveUrl, window.location.href);
                                            absoluteUrl.protocol = window.location.protocol;
                                            absoluteUrl.host = window.location.host;
                                            requestUrl = absoluteUrl.pathname + absoluteUrl.search;
                                        } catch (urlError) {
                                            console.warn('autoSaveMedia: unable to normalize URL', urlError);
                                        }
                                        
                                        setMediaStatus('logo', 'saving');
                                        
                                        fetch(requestUrl, {
                                            method: 'POST',
                                            body: formData,
                                            credentials: 'include',
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest',
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': csrfMeta.getAttribute('content')
                                            }
                                        })
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error('Auto-save request failed');
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            if (data.logo_url) {
                                                const previewImg = document.getElementById('logoPreviewImg');
                                                if (previewImg) {
                                                    previewImg.src = `${data.logo_url}?t=${Date.now()}`;
                                                }
                                            }
                                            setMediaStatus('logo', 'saved');
                                        })
                                        .catch(error => {
                                            console.error('Media auto-save error (iOS):', error);
                                            setMediaStatus('logo', 'error');
                                        });
                                    }
                                }
                            }
                        }
                    }, 300); // Delay de 300ms para iOS
                } else {
                    // Para outros navegadores, chamar imediatamente
                    autoSaveMedia('logo');
                }

                cancelCrop(false);
                setTimeout(() => {
                    const modal = document.getElementById('cropModal');
                    if (modal) {
                        modal.classList.remove('show');
                    }
                }, 0);
            }, 'image/png', 0.92);
        }

        function cancelCrop(resetInput = true) {
            // Restaurar scroll do body
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.body.style.width = '';
            ensureCropElements();
            endCropInteraction();

            if (cropElements.modal) {
                cropElements.modal.classList.remove('show');
            }
            if (cropElements.overlay) {
                cropElements.overlay.classList.remove('active');
            }
            if (cropElements.box) {
                cropElements.box.classList.remove('active');
                cropElements.box.style.width = '';
                cropElements.box.style.height = '';
                cropElements.box.style.left = '';
                cropElements.box.style.top = '';
            }
            if (cropElements.image) {
                cropElements.image.style.display = 'none';
                cropElements.image.src = '';
            }

            cropState.originalImage = null;
            cropState.imageRect = null;
            cropState.cropBox = { x: 0, y: 0, size: 0 };
            cropState.ratios = null;
            cropState.isDragging = false;

            if (resetInput) {
                const logoInput = document.getElementById('logoInput');
                if (logoInput) {
                    logoInput.value = '';
                }
            }
        }

        function clamp(value, min, max) {
            return Math.min(Math.max(value, min), max);
        }

function saveForm() {
    const form = document.getElementById('companyForm');
    if (!form) {
        console.error('{{ __('companies.form_not_found') }}');
        showNotification('{{ __('companies.error_form_not_found') }}', 'error');
        return;
    }
    
    // Verificar se há arquivo no input de logo antes de submeter
    const logoInput = document.getElementById('logoInput');
    if (logoInput && logoInput.files && logoInput.files.length > 0) {
        console.log('Logo file ready for submission:', {
            fileName: logoInput.files[0].name,
            fileSize: logoInput.files[0].size,
            fileType: logoInput.files[0].type
        });
    } else {
        console.log('No logo file in input');
    }
    
    // Remove any existing save_as_draft input
    const existingDraftInput = form.querySelector('input[name="save_as_draft"]');
    if (existingDraftInput) {
        existingDraftInput.remove();
    }
    
    // Add save_as_draft input with value 'true'
    const draftInput = document.createElement('input');
    draftInput.type = 'hidden';
    draftInput.name = 'save_as_draft';
    draftInput.value = 'true';
    form.appendChild(draftInput);
    
    const saveBtn = document.querySelector('button[onclick="saveForm()"]');
    const submitBtn = document.querySelector('button[onclick="submitForm()"]');
    if (saveBtn && !saveBtn.dataset.originalText) {
        saveBtn.dataset.originalText = saveBtn.innerHTML;
    }
    if (saveBtn) {
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> {{ __('companies.saving') }}';
        saveBtn.disabled = true;
    }
    if (submitBtn) {
        submitBtn.disabled = true;
    }
    
    // Submit form (with save_as_draft = true, so it will be saved as draft)
    form.submit();
}

function submitForm() {
    const form = document.getElementById('companyForm');
    if (!form) return;
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Remove any existing save_as_draft input (to ensure it's published)
    const existingDraftInput = form.querySelector('input[name="save_as_draft"]');
    if (existingDraftInput) {
        existingDraftInput.remove();
    }
    
    const submitBtn = document.querySelector('button[onclick="submitForm()"]');
    const saveBtn = document.querySelector('button[onclick="saveForm()"]');
    if (submitBtn && !submitBtn.dataset.originalText) {
        submitBtn.dataset.originalText = submitBtn.innerHTML;
    }
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> {{ __('companies.activating') }}';
        submitBtn.disabled = true;
    }
    if (saveBtn) {
        saveBtn.disabled = true;
    }
    
    // Submit form (without save_as_draft, so it will be published)
    form.submit();
}

function showNotification(message, type) {
    alert(message);
}

function handleBackgroundUpload(input) {
    // Verificar se há arquivo disponível (método padrão ou alternativo para iOS)
    let file = null;
    if (input.files && input.files[0]) {
        file = input.files[0];
    } else if (input._file) {
        // Método alternativo para iOS quando DataTransfer não funcionou
        file = input._file;
        console.log('iOS: Usando arquivo alternativo para background upload');
    }
    
    if (!file) {
        console.warn('Nenhum arquivo disponível para background upload');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const previewContainer = document.getElementById('backgroundPreviewContainer');
        const placeholder = document.getElementById('backgroundPlaceholder');
        
        if (placeholder) {
            placeholder.remove();
        }
        
        let previewImg = document.getElementById('backgroundPreviewImg');
        if (!previewImg && previewContainer) {
            previewImg = document.createElement('img');
            previewImg.id = 'backgroundPreviewImg';
            previewImg.className = 'max-w-xs mx-auto mb-2 rounded-lg';
            previewImg.alt = '{{ __('companies.background_image') }}';
            previewContainer.appendChild(previewImg);
        }
        
        if (previewImg) {
            previewImg.src = e.target.result;
        }
        
        // No iOS, adicionar um pequeno delay antes do auto-save
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) || 
                     (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        
        if (isIOS) {
            setTimeout(function() {
                autoSaveMedia('background');
            }, 200);
        } else {
            autoSaveMedia('background');
        }
    };
    
    reader.onerror = function(error) {
        console.error('FileReader error (background):', error);
    };
    
    reader.readAsDataURL(file);
}

        document.querySelectorAll('.upload-area').forEach(area => {
            area.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            area.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            area.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const input = this.querySelector('input[type="file"]');
                    if (input) {
                        input.files = files;
                        if (input.id === 'logoInput') {
                            handleLogoUpload(input);
                        } else if (input.id === 'backgroundInput') {
                            handleBackgroundUpload(input);
                        }
                    }
                }
            });
        });

        // Stock Image Search Functions
        function openStockImageModal() {
            const modal = document.getElementById('stockImageModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            // Load popular images on open
            searchStockImages('business');
        }

        function closeStockImageModal() {
            const modal = document.getElementById('stockImageModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }

        async function searchStockImages(query = 'business') {
            const searchInput = document.getElementById('stockImageSearch');
            const searchTerm = searchInput ? searchInput.value.trim() || query : query;
            const grid = document.getElementById('stockImagesGrid');
            
            if (!grid) return;
            
            grid.innerHTML = '<div class="col-span-full text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-purple-600"></i><p class="mt-2">{{ __('companies.loading_images') }}</p></div>';
            
            // Using LoremFlickr directly - no CORS issues and deterministic with lock parameter
            loadStockImagesFromDirectUrls(searchTerm);
        }

        function loadStockImagesFromDirectUrls(searchTerm) {
            const grid = document.getElementById('stockImagesGrid');
            if (!grid) return;

            grid.innerHTML = '';

            const images = [];
            const baseSeed = Date.now();
            const termRaw = searchTerm && searchTerm.trim() ? searchTerm.trim() : 'business';
            const sanitizedTerm = termRaw.toLowerCase()
                .replace(/[^a-z0-9\s,]/g, ' ')
                .trim()
                .replace(/\s+/g, ',') || 'business';

            for (let i = 1; i <= 20; i++) {
                const lock = baseSeed + i;
                images.push({
                    seed: lock,
                    term: sanitizedTerm,
                    displayTerm: termRaw,
                    thumbUrl: `https://loremflickr.com/400/300/${sanitizedTerm}?lock=${lock}`
                });
            }

            displayStockImages(images);
        }

        function displayStockImages(images) {
            const grid = document.getElementById('stockImagesGrid');
            if (!grid) return;

            if (!images || images.length === 0) {
                grid.innerHTML = '<div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-8"><i class="fas fa-image text-4xl mb-2"></i><p>{{ __('companies.no_images_found') }}</p></div>';
                return;
            }

            grid.innerHTML = images.map(image => `
                <div class="relative group cursor-pointer" onclick="selectStockImage('${image.seed}', '${image.term}')">
                    <div class="aspect-video bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                        <img src="${image.thumbUrl}" alt="${image.displayTerm || 'Stock image'}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy" onerror="this.parentElement.innerHTML='<div class=\\'w-full h-full flex items-center justify-center text-gray-400\\'><i class=\\'fas fa-image text-2xl\\'></i></div>'">
                    </div>
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-opacity rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </div>
                </div>
            `).join('');
        }

        async function selectStockImage(seed, term) {
            try {
                const grid = document.getElementById('stockImagesGrid');
                if (grid) {
                    grid.innerHTML = '<div class="col-span-full text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-purple-600"></i><p class="mt-2">{{ __('companies.downloading_image') }}</p></div>';
                }

                const fetchUrl = `https://loremflickr.com/1920/1080/${term}?lock=${seed}`;

                let response = await fetch(fetchUrl, { mode: 'cors' });

                if (!response.ok) {
                    const proxyUrl = `https://api.allorigins.win/raw?url=${encodeURIComponent(fetchUrl)}`;
                    response = await fetch(proxyUrl);
                }

                const blob = await response.blob();
                const file = new File([blob], `stock-${seed}.jpg`, { type: 'image/jpeg' });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);

                const backgroundInput = document.getElementById('backgroundInput');
                if (backgroundInput) {
                    backgroundInput.files = dataTransfer.files;
                    handleBackgroundUpload(backgroundInput);
                }

                closeStockImageModal();
            } catch (error) {
                console.error('Error selecting image:', error);
                alert('{{ __('companies.error_downloading_image') }}');
            }
        }

        const mediaAutoSaveMessages = {
            saving: @json(__('companies.media_auto_save_in_progress')),
            saved: @json(__('companies.media_auto_save_success')),
            error: @json(__('companies.media_auto_save_error'))
        };

        const mediaStatusIcons = {
            saving: '<i class="fas fa-spinner fa-spin mr-1"></i>',
            saved: '<i class="fas fa-check-circle text-green-500 mr-1"></i>',
            error: '<i class="fas fa-exclamation-triangle text-red-500 mr-1"></i>'
        };

        const mediaStatusTimers = {};
        const mediaAutoSaveState = {
            logo: { controller: null },
            background: { controller: null }
        };

        function autoSaveMedia(type) {
            const form = document.getElementById('companyForm');
            if (!form) return;
            const mediaSaveUrl = form.dataset.mediaSaveUrl;
            const mediaSaveToken = form.dataset.mediaSaveToken || '';
            if (!mediaSaveUrl) return;

            const input = getMediaInputByType(type);
            if (!input) return;
            
            // Verificar se há arquivo disponível (método padrão ou alternativo para iOS)
            let file = null;
            if (input.files && input.files.length > 0) {
                file = input.files[0];
            } else if (input._file) {
                // Método alternativo para iOS quando DataTransfer não funcionou
                file = input._file;
                console.log('iOS: Usando arquivo alternativo para auto-save');
            }
            
            if (!file) {
                console.warn('Nenhum arquivo disponível para auto-save:', type);
                return;
            }

            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfMeta) {
                console.warn('CSRF token not found. Skipping media auto-save.');
                return;
            }

            const state = mediaAutoSaveState[type] || {};
            if (state.controller) {
                state.controller.abort();
            }
            state.controller = new AbortController();
            mediaAutoSaveState[type] = state;

            let requestUrl = mediaSaveUrl;
            try {
                const absoluteUrl = new URL(mediaSaveUrl, window.location.href);
                absoluteUrl.protocol = window.location.protocol;
                absoluteUrl.host = window.location.host;
                requestUrl = absoluteUrl.pathname + absoluteUrl.search;
            } catch (urlError) {
                console.warn('autoSaveMedia: unable to normalize URL', urlError);
            }

            const formData = new FormData();
            formData.append('_token', csrfMeta.getAttribute('content'));
            formData.append('media_type', type);
            if (mediaSaveToken) {
                formData.append('media_token', mediaSaveToken);
            }

            if (type === 'logo') {
                formData.append('logo', file);
            } else {
                formData.append('background_image', file);
            }

            setMediaStatus(type, 'saving');

            fetch(requestUrl, {
                method: 'POST',
                body: formData,
                credentials: 'include',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfMeta.getAttribute('content')
                },
                signal: state.controller.signal
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Auto-save request failed');
                }
                return response.json();
            })
            .then(data => {
                if (type === 'logo' && data.logo_url) {
                    const previewImg = document.getElementById('logoPreviewImg');
                    if (previewImg) {
                        previewImg.src = `${data.logo_url}?t=${Date.now()}`;
                    }
                }
                if (type === 'background' && data.background_url) {
                    const previewImg = document.getElementById('backgroundPreviewImg');
                    if (previewImg) {
                        previewImg.src = `${data.background_url}?t=${Date.now()}`;
                    }
                }
                setMediaStatus(type, 'saved');
            })
            .catch(error => {
                if (error.name === 'AbortError') {
                    return;
                }
                console.error('Media auto-save error:', error);
                setMediaStatus(type, 'error');
            })
            .finally(() => {
                state.controller = null;
            });
        }

        function getMediaInputByType(type) {
            if (type === 'logo') {
                return document.getElementById('logoInput');
            }
            return document.getElementById('backgroundInput') || document.getElementById('background_image');
        }

        function setMediaStatus(type, state) {
            const indicator = document.getElementById(`${type}AutoSaveStatus`);
            if (!indicator) {
                return;
            }

            if (mediaStatusTimers[type]) {
                clearTimeout(mediaStatusTimers[type]);
                mediaStatusTimers[type] = null;
            }

            indicator.classList.remove('hidden', 'text-red-500', 'text-green-600', 'text-gray-500');

            if (state === 'saving') {
                indicator.classList.add('text-gray-500');
            } else if (state === 'saved') {
                indicator.classList.add('text-green-600');
            } else if (state === 'error') {
                indicator.classList.add('text-red-500');
            }

            const icon = mediaStatusIcons[state] || '';
            const message = mediaAutoSaveMessages[state] || '';
            indicator.innerHTML = icon + message;

            if (state === 'saved') {
                mediaStatusTimers[type] = setTimeout(() => {
                    indicator.classList.add('hidden');
                }, 4000);
            } else if (state === 'error') {
                mediaStatusTimers[type] = setTimeout(() => {
                    indicator.classList.add('hidden');
                }, 6000);
            }
        }

        // Allow Enter key to search
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('stockImageSearch');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        searchStockImages();
                    }
                });
            }
        });
        
        @if(session('open_review_page'))
            window.addEventListener('load', function() {
                const reviewPageUrl = @json(session('open_review_page'));
                if (reviewPageUrl) {
                    window.open(reviewPageUrl, '_blank');
                }
            });
        @endif
</script>
@endsection
