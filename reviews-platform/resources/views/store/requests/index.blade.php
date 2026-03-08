@extends('layouts.admin')

@section('title', __('store.requests') . ' - ' . __('app.name'))
@section('page-title', __('store.requests'))
@section('page-description', __('store.requests_description'))

@section('header-actions')
    <a href="{{ route('store.products.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 font-medium inline-flex items-center gap-2">
        <i class="fas fa-box"></i>
        {{ __('store.products_title') }}
    </a>
@endsection

@section('content')
<div class="fade-in">
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-800 dark:text-green-200">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        @if($requests->isEmpty())
            <div class="p-8 text-center text-gray-600 dark:text-gray-400">
                <i class="fas fa-inbox text-4xl mb-3 text-gray-400"></i>
                <p>{{ __('store.no_requests') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('store.request_date') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('store.user') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('store.phone') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('store.items') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('store.status') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('store.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($requests as $req)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $req->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $req->user->name ?? '—' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $req->user->email ?? '' }}</p>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if($req->formatted_phone)
                                        <span class="text-gray-700 dark:text-gray-300">{{ $req->formatted_phone }}</span>
                                        @if($req->whatsapp_url)
                                            <a href="{{ $req->whatsapp_url }}" target="_blank" rel="noopener noreferrer" class="ml-2 inline-flex items-center gap-1 px-2 py-1 rounded text-white bg-green-600 hover:bg-green-700 text-xs font-medium" title="{{ __('store.contact_whatsapp') }}">
                                                <i class="fab fa-whatsapp"></i> WhatsApp
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    @foreach($req->items ?? [] as $item)
                                        <span class="block">{{ $item['product_name'] ?? '—' }} × {{ $item['quantity'] ?? 0 }}</span>
                                    @endforeach
                                    @if(!empty($req->message))
                                        <p class="mt-1 text-xs text-gray-500 italic">"{{ Str::limit($req->message, 60) }}"</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <form action="{{ route('store.requests.update_status', $req->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 py-1 px-2">
                                            @foreach(\App\Models\StoreRequest::statusOptions() as $value => $label)
                                                <option value="{{ $value }}" {{ $req->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="px-4 py-3 text-right text-sm">
                                    @if($req->user && $req->user->email)
                                        <a href="mailto:{{ $req->user->email }}" class="text-purple-600 dark:text-purple-400 hover:underline mr-2">
                                            <i class="fas fa-envelope mr-1"></i>{{ __('store.contact') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-4">
        <a href="{{ route('store.products.index') }}" class="text-purple-600 dark:text-purple-400 hover:underline">
            <i class="fas fa-arrow-left mr-1"></i>
            {{ __('store.back_to_products') }}
        </a>
    </div>
</div>
@endsection
