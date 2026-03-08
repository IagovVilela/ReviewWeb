@extends('layouts.admin')

@section('title', __('store.add_product') . ' - ' . __('app.name'))
@section('page-title', __('store.add_product'))
@section('page-description', __('store.products_description'))

@section('content')
<div class="fade-in max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <form action="{{ route('store.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('store.product_name') }} *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('store.description') }}</label>
                    <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('store.product_image') }}</label>
                    <input type="file" name="image" id="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded file:border-0 file:bg-purple-50 file:text-purple-700 dark:file:bg-gray-700 dark:file:text-gray-200">
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('store.price') }} ({{ __('store.optional') }})</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300">
                    <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">{{ __('store.is_active') }}</label>
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('store.sort_order') }}</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                </div>
                <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-600">
                    <label for="store_notification_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('store.notification_email_label') }}</label>
                    <input type="email" name="store_notification_email" id="store_notification_email" value="{{ old('store_notification_email', $notificationEmail ?? '') }}" placeholder="{{ __('store.notification_email_placeholder') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('store.notification_email_help') }}</p>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i>{{ __('store.save') }}
                </button>
                <a href="{{ route('store.products.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500">{{ __('store.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
