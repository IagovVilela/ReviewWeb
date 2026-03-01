@extends('layouts.admin')

@section('title', __('app.store') . ' - ' . __('app.name'))
@section('page-title', __('app.store'))
@section('page-description', 'Material de coleta de avaliações')

@section('content')
<div class="fade-in max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-store text-blue-600 dark:text-blue-400 text-3xl"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Material de coleta de avaliações</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Suportes de mesa, adesivos e outros materiais para divulgar sua página de avaliações no estabelecimento.
            </p>
        </div>

        @if(!empty($storeUrl))
            <a href="{{ $storeUrl }}" target="_blank" rel="noopener noreferrer"
               class="w-full block text-center px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium inline-flex items-center justify-center gap-2">
                <i class="fas fa-external-link-alt"></i>
                Ver loja
            </a>
        @else
            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg text-amber-800 dark:text-amber-200 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                A loja está em configuração. Em breve você poderá acessar o material de coleta aqui.
            </div>
        @endif
    </div>
</div>
@endsection
