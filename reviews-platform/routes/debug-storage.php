<?php

// Rota temporária para diagnosticar o problema de storage
// Remover após resolver o problema

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/debug-storage', function () {
    // Verificar se está autenticado (segurança básica)
    if (!auth()->check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $info = [
        'storage_path' => storage_path(),
        'storage_app_public' => storage_path('app/public'),
        'storage_app_public_exists' => file_exists(storage_path('app/public')),
        'storage_app_public_writable' => is_writable(storage_path('app/public')),
        'public_storage_link' => public_path('storage'),
        'public_storage_link_exists' => file_exists(public_path('storage')),
        'public_storage_is_link' => is_link(public_path('storage')),
        'public_storage_link_target' => is_link(public_path('storage')) ? readlink(public_path('storage')) : null,
        'base_path' => base_path(),
        'app_path' => app_path(),
        'root_directory' => getcwd(),
    ];
    
    // Verificar diretórios de imagens
    $directories = [
        'logos' => storage_path('app/public/logos'),
        'backgrounds' => storage_path('app/public/backgrounds'),
        'photos' => storage_path('app/public/photos'),
    ];
    
    foreach ($directories as $name => $path) {
        $info["directory_{$name}_exists"] = file_exists($path);
        $info["directory_{$name}_writable"] = is_writable($path);
        if (file_exists($path)) {
            $files = glob($path . '/*');
            $info["directory_{$name}_files_count"] = count($files);
            $info["directory_{$name}_files"] = array_slice($files, 0, 5); // Primeiros 5 arquivos
        }
    }
    
    // Verificar se há arquivos no storage
    try {
        $allFiles = Storage::disk('public')->allFiles();
        $info['total_files_in_storage'] = count($allFiles);
        $info['sample_files'] = array_slice($allFiles, 0, 10);
    } catch (\Exception $e) {
        $info['storage_error'] = $e->getMessage();
    }
    
    return response()->json($info, 200, [], JSON_PRETTY_PRINT);
})->middleware('web');

