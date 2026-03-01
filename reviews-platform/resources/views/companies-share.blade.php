@extends('layouts.admin')

@section('title', __('companies.share_title') . ' - ' . __('app.name'))
@section('page-title', __('companies.share_title'))
@section('page-description', __('companies.share_subtitle'))

@section('content')
<div class="fade-in max-w-3xl mx-auto">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg flex items-center gap-2 text-green-800 dark:text-green-200">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <p class="text-gray-600 dark:text-gray-400 mb-6">{{ __('companies.share_subtitle') }}</p>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Digital -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 flex flex-col">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-link text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('companies.share_digital') }}</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 flex-1">{{ __('companies.share_digital_desc') }}</p>
            <div class="space-y-3">
                <div class="flex gap-2">
                    <input type="text" id="sharePublicUrl" readonly value="{{ $company->public_url }}"
                           class="flex-1 px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-800 dark:text-gray-200 text-sm">
                    <button type="button" onclick="copyShareLink()" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium inline-flex items-center gap-2">
                        <i class="fas fa-copy"></i>
                        {{ __('companies.share_copy') }}
                    </button>
                </div>
                <a href="{{ route('companies.qrcode', $company->id) }}" class="block w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium text-center inline-flex items-center justify-center gap-2">
                    <i class="fas fa-qrcode"></i>
                    {{ __('companies.share_download_qr') }}
                </a>
            </div>
        </div>

        <!-- Physical -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 flex flex-col">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-store text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('companies.share_physical') }}</h3>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 flex-1">{{ __('companies.share_physical_desc') }}</p>
            <a href="{{ route('store') }}" class="mt-auto px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium inline-flex items-center justify-center gap-2">
                <i class="fas fa-shopping-bag"></i>
                {{ __('companies.share_go_to_store') }}
            </a>
        </div>
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('dashboard') }}" class="text-purple-600 dark:text-purple-400 hover:underline font-medium inline-flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            {{ __('companies.share_continue') }}
        </a>
    </div>
</div>

<script>
function copyShareLink() {
    const input = document.getElementById('sharePublicUrl');
    if (input) {
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value).then(function() {
            const btn = event.target.closest('button');
            if (btn) {
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
                setTimeout(function() { btn.innerHTML = orig; }, 2000);
            }
        });
    }
}
</script>
@endsection
