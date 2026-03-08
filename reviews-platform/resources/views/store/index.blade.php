@extends('layouts.admin')

@section('title', __('app.store') . ' - ' . __('app.name'))
@section('page-title', __('app.store'))
@section('page-description', __('store.page_description'))

@section('content')
<div class="fade-in max-w-4xl mx-auto">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg flex items-center gap-2 text-green-800 dark:text-green-200">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg flex items-center gap-2 text-red-800 dark:text-red-200">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-6">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-store text-blue-600 dark:text-blue-400 text-3xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('store.catalog_title') }}</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('store.catalog_subtitle') }}</p>
        </div>

        @if($products->isNotEmpty())
            <form action="{{ route('store.request', [], false) }}" method="POST" id="store-request-form">
                @csrf
                <div class="grid sm:grid-cols-2 gap-4 mb-6">
                    @foreach($products as $product)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 flex gap-4">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                            @else
                                <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-box text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</h3>
                                @if($product->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ $product->description }}</p>
                                @endif
                                @if($product->price !== null)
                                    <p class="text-sm font-semibold text-purple-600 dark:text-purple-400 mt-1">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                                @endif
                                <div class="mt-2 flex items-center gap-2">
                                    <label for="qty-{{ $product->id }}" class="text-sm text-gray-600 dark:text-gray-400">{{ __('store.quantity') }}:</label>
                                    <input type="number" name="items[{{ $loop->index }}][quantity]" id="qty-{{ $product->id }}" min="0" max="99" value="0" class="w-16 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                                    <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $product->id }}">
                                    <input type="hidden" name="items[{{ $loop->index }}][product_name]" value="{{ $product->name }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mb-4">
                    <label for="request-phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('store.phone_label') }}</label>
                    <input type="text" name="phone" id="request-phone" value="{{ old('phone') }}" maxlength="20" autocomplete="tel"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('phone') border-red-500 @enderror"
                           placeholder="{{ __('store.phone_placeholder') }}">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="request-message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('store.message_optional') }}</label>
                    <textarea name="message" id="request-message" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="{{ __('store.message_placeholder') }}"></textarea>
                </div>
                <button type="submit" id="store-submit-btn" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium inline-flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i>
                    {{ __('store.submit_request') }}
                </button>
            </form>
            <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                <i class="fas fa-info-circle mr-1"></i>
                {{ __('store.request_info') }}
            </p>
        @elseif(!empty($storeUrl))
            <a href="{{ $storeUrl }}" target="_blank" rel="noopener noreferrer"
               class="w-full block text-center px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium inline-flex items-center justify-center gap-2">
                <i class="fas fa-external-link-alt"></i>
                {{ __('store.view_external_store') }}
            </a>
        @else
            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg text-amber-800 dark:text-amber-200 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                {{ __('store.no_products_yet') }}
            </div>
        @endif
    </div>
</div>

@if($products->isNotEmpty())
<script>
(function() {
    var form = document.getElementById('store-request-form');
    var phoneInput = document.getElementById('request-phone');

    function maskPhone(value) {
        var d = value.replace(/\D/g, '').substring(0, 11);
        if (d.length <= 2) return d ? '(' + d : '';
        if (d.length <= 6) return '(' + d.substring(0, 2) + ') ' + d.substring(2);
        return '(' + d.substring(0, 2) + ') ' + d.substring(2, 7) + '-' + d.substring(7);
    }

    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = maskPhone(this.value);
        });
    }

    form.addEventListener('submit', function(e) {
        var inputs = document.querySelectorAll('input[name^="items"][name$="[quantity]"]');
        var hasQty = Array.from(inputs).some(function(inp) { return parseInt(inp.value, 10) > 0; });
        if (!hasQty) {
            e.preventDefault();
            alert('{{ __("store.select_at_least_one") }}');
            return false;
        }
    });
})();
</script>
@endif
@endsection
