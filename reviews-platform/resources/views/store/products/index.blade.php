@extends('layouts.admin')

@section('title', __('store.products_title') . ' - ' . __('app.name'))
@section('page-title', __('store.products_title'))
@section('page-description', __('store.products_description'))

@section('header-actions')
    <a href="{{ route('store.products.create') }}" class="btn-primary px-4 py-2 rounded-lg text-white font-medium inline-flex items-center gap-2">
        <i class="fas fa-plus"></i>
        {{ __('store.add_product') }}
    </a>
    <a href="{{ route('store.requests.index') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium inline-flex items-center gap-2">
        <i class="fas fa-inbox"></i>
        {{ __('store.requests') }} ({{ \App\Models\StoreRequest::count() }})
    </a>
@endsection

@section('content')
<div class="fade-in">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-800 dark:text-green-200">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(isset($notificationEmail) && $notificationEmail)
        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg text-blue-800 dark:text-blue-200 flex flex-wrap items-center gap-2">
            <i class="fas fa-envelope"></i>
            <span>{{ __('store.requests_sent_to') }}: <strong>{{ $notificationEmail }}</strong></span>
            <a href="{{ route('store.settings') }}" class="ml-2 text-sm underline">{{ __('store.change_email') }}</a>
        </div>
    @else
        <div class="mb-4 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg text-amber-800 dark:text-amber-200 flex flex-wrap items-center gap-2">
            <i class="fas fa-info-circle"></i>
            <span>{{ __('store.notification_email_help') }}</span>
            <a href="{{ route('store.settings') }}" class="ml-2 text-sm font-medium underline">{{ __('store.set_notification_email') }}</a>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        @if($products->isEmpty())
            <div class="p-8 text-center text-gray-600 dark:text-gray-400">
                <i class="fas fa-box-open text-4xl mb-3 text-gray-400"></i>
                <p>{{ __('store.no_products') }}</p>
                <a href="{{ route('store.products.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                    <i class="fas fa-plus"></i>
                    {{ __('store.add_product') }}
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('store.product_image') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('store.product_name') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('store.price') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('store.status') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('store.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-4 py-3">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="" class="w-12 h-12 object-cover rounded-lg">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    @if($product->price !== null)
                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($product->is_active)
                                        <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">{{ __('store.active') }}</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300">{{ __('store.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('store.products.edit', $product->id) }}" class="text-purple-600 hover:text-purple-700 dark:text-purple-400 mr-3">{{ __('store.edit') }}</a>
                                    <form action="{{ route('store.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('store.confirm_delete_product') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 dark:text-red-400">{{ __('store.delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-4">
        <a href="{{ route('store') }}" class="text-purple-600 dark:text-purple-400 hover:underline">
            <i class="fas fa-arrow-left mr-1"></i>
            {{ __('store.back_to_store') }}
        </a>
    </div>
</div>
@endsection
