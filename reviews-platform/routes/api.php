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