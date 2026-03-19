@extends('layouts.admin')

@section('title', __('billing.subscribe_title') . ' - ' . __('app.name'))
@section('page-title', __('billing.subscribe_title'))
@section('page-description', __('billing.subscribe_subtitle'))

@section('content')
<div class="fade-in max-w-xl mx-auto">
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg flex items-center gap-2 text-red-800 dark:text-red-200">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if(session('info'))
        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg flex items-center gap-2 text-blue-800 dark:text-blue-200">
            <i class="fas fa-info-circle"></i>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <div class="text-center mb-6">
            <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-credit-card text-purple-600 dark:text-purple-400 text-2xl"></i>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 text-xs font-semibold mb-3">
                <i class="fas fa-gift"></i>
                {{ __('billing.subscribe_trial_badge') }}
            </div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('billing.subscribe_title') }}</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('billing.subscribe_subtitle') }}</p>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">{{ __('billing.subscribe_trial_note') }}</p>
            <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ __('billing.subscribe_cancel_note') }}</p>
        </div>

        <form method="POST" action="{{ route('billing.checkout') }}">
            @csrf
            <button type="submit" class="w-full btn-primary text-white px-4 py-3 rounded-lg font-medium inline-flex items-center justify-center gap-2">
                <i class="fas fa-lock"></i>
                {{ __('billing.subscribe_button') }}
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
            <i class="fas fa-shield-alt mr-1"></i>
            {{ __('billing.secure_payment') }}
        </p>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('logout') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400">
            <i class="fas fa-sign-out-alt mr-1"></i> Sair
        </a>
    </div>
</div>
@endsection
