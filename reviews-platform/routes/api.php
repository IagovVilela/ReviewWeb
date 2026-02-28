<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CompanyController;

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

// Rotas que exigem autenticação (sessão ou token) - mesmo domínio usa sessão via Sanctum stateful
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/companies/{companyId}/members', [CompanyController::class, 'addMember'])->name('api.companies.members.add');
    Route::delete('/companies/{companyId}/members/{memberId}', [CompanyController::class, 'removeMember'])->name('api.companies.members.remove');
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