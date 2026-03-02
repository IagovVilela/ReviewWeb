@extends('layouts.admin')

@section('title', __('store.manage_store') . ' - ' . __('store.notification_email_label') . ' - ' . __('app.name'))
@section('page-title', __('store.notification_email_label'))
@section('page-description', __('store.notification_email_help'))

@section('content')
<div class="fade-in max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <form action="{{ route('store.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <label for="store_notification_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('store.notification_email_label') }}</label>
                <input type="email" name="store_notification_email" id="store_notification_email" value="{{ old('store_notification_email', $notificationEmail ?? '') }}" placeholder="{{ __('store.notification_email_placeholder') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('store_notification_email') border-red-500 @enderror">
                @error('store_notification_email')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i>{{ __('store.save') }}
                </button>
                <a href="{{ route('store.products.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500">{{ __('store.cancel') }}</a>
            </div>
        </form>
    </div>
    <div class="mt-4">
        <a href="{{ route('store.products.index') }}" class="text-purple-600 dark:text-purple-400 hover:underline">
            <i class="fas fa-arrow-left mr-1"></i>
            {{ __('store.back_to_products') }}
        </a>
    </div>
</div>
@endsection
