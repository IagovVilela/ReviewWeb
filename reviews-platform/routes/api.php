<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/healthcheck', function () {
    $checks = [];

    $checks['php_version'] = PHP_VERSION;
    $checks['app_key_set'] = !empty(config('app.key'));
    $checks['app_env'] = config('app.env');
    $checks['session_driver'] = config('session.driver');
    $checks['db_connection'] = config('database.default');

    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $checks['db_status'] = 'OK';
    } catch (\Throwable $e) {
        $checks['db_status'] = 'FAIL: ' . $e->getMessage();
    }

    try {
        $hasTable = \Illuminate\Support\Facades\Schema::hasTable('sessions');
        $checks['sessions_table'] = $hasTable ? 'EXISTS' : 'MISSING';
    } catch (\Throwable $e) {
        $checks['sessions_table'] = 'ERROR: ' . $e->getMessage();
    }

    $checks['pdo_drivers'] = implode(', ', \PDO::getAvailableDrivers());

    $extensions = ['pdo_mysql', 'pdo_pgsql', 'mbstring', 'openssl', 'gd', 'bcmath'];
    foreach ($extensions as $ext) {
        $checks['ext_' . $ext] = extension_loaded($ext) ? 'loaded' : 'NOT loaded';
    }

    try {
        $webMiddleware = app(\Illuminate\Session\Middleware\StartSession::class);
        $checks['start_session_class'] = 'OK';
    } catch (\Throwable $e) {
        $checks['start_session_class'] = 'FAIL: ' . $e->getMessage();
    }

    try {
        $encrypter = app('encrypter');
        $test = $encrypter->encrypt('test');
        $checks['encryption'] = 'OK';
    } catch (\Throwable $e) {
        $checks['encryption'] = 'FAIL: ' . $e->getMessage();
    }

    return response()->json($checks, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Review API Routes (Public - for review submission from public pages)
Route::post('/reviews', [ReviewController::class, 'store']);
Route::post('/reviews/private-feedback', [ReviewController::class, 'storePrivateFeedback']);

/*
 | NOTE:
 | As rotas GET usadas pelo painel autenticado foram movidas para routes/web.php
 | (grupo 'auth' + 'web') para garantir sessão/cookies nas requisições fetch.
 | Mantemos em api.php apenas as rotas públicas consumidas pela página pública.
 */