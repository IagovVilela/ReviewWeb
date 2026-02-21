<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;

// Public routes
Route::get('/', function () {
    return view('app');
});

// Contact trial form submission
Route::post('/contact-trial', [ContactController::class, 'submitTrialRequest'])->name('contact.trial');

// Change locale route
Route::post('/change-locale', function (Request $request) {
    $locale = $request->input('locale', 'pt_BR');
    
    if (in_array($locale, ['pt_BR', 'en_US'])) {
        session(['locale' => $locale]);
        return response()->json(['success' => true, 'locale' => $locale]);
    }
    
    return response()->json(['success' => false], 400);
})->name('change-locale');

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

// Password Reset routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('password.send-code');
Route::get('/reset-password/code', [AuthController::class, 'showResetCodeForm'])->name('password.reset.code');
Route::post('/reset-password/verify', [AuthController::class, 'verifyResetCode'])->name('password.verify-code');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

// Create admin user (for initial setup)
Route::get('/create-admin', [AuthController::class, 'createAdmin']);

// Dashboard route - accessible to all authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            // Dashboard de administrador - com visual moderno igual aos usuários
            $allReviews = \App\Models\Review::all();
            $allCompanies = \App\Models\Company::orderBy('created_at', 'desc')->get();
            
            $stats = [
                'total_reviews' => $allReviews->count(),
                'positive_reviews' => $allReviews->where('is_positive', true)->count(),
                'negative_reviews' => $allReviews->where('is_positive', false)->count(),
                'average_rating' => $allReviews->count() > 0 ? round($allReviews->avg('rating'), 1) : 0,
            ];
            
            $negativeCount = \App\Models\Review::where('is_positive', false)
                ->where(function($query) {
                    $query->where('is_processed', false)
                          ->orWhereNull('is_processed');
                })
                ->count();
            
            return view('dashboard', compact('negativeCount', 'stats', 'allCompanies'));
        } else {
            // Dashboard de usuário comum
            $companies = $user->companies()->orderBy('created_at', 'desc')->get();
            $hasCompany = $companies->count() > 0;
            
            // Get selected company from query parameter
            // If user has only one company, default to that company; otherwise default to 'all'
            $defaultView = $companies->count() === 1 ? $companies->first()->id : 'all';
            $selectedCompanyId = request()->get('company_id', $defaultView);
            $selectedCompany = null;
            $stats = [
                'total_reviews' => 0,
                'positive_reviews' => 0,
                'negative_reviews' => 0,
                'average_rating' => 0,
            ];
            
            if ($hasCompany) {
                if ($selectedCompanyId === 'all') {
                    // Aggregate stats from all companies
                    $allReviews = \App\Models\Review::whereIn('company_id', $companies->pluck('id'))->get();
                    $stats = [
                        'total_reviews' => $allReviews->count(),
                        'positive_reviews' => $allReviews->where('is_positive', true)->count(),
                        'negative_reviews' => $allReviews->where('is_positive', false)->count(),
                        'average_rating' => $allReviews->count() > 0 ? round($allReviews->avg('rating'), 1) : 0,
                    ];
                } else {
                    // Single company stats
                    $selectedCompany = $companies->find($selectedCompanyId) ?? $companies->first();
                    if ($selectedCompany) {
                        $stats = [
                            'total_reviews' => $selectedCompany->reviews()->count(),
                            'positive_reviews' => $selectedCompany->reviews()->where('is_positive', true)->count(),
                            'negative_reviews' => $selectedCompany->reviews()->where('is_positive', false)->count(),
                            'average_rating' => round($selectedCompany->reviews()->avg('rating'), 1) ?? 0,
                        ];
                    }
                }
            }
            
            // If no company selected and has companies, use first as default
            if (!$selectedCompany && $hasCompany && $selectedCompanyId !== 'all') {
                $selectedCompany = $companies->first();
                if ($selectedCompany) {
                    $stats = [
                        'total_reviews' => $selectedCompany->reviews()->count(),
                        'positive_reviews' => $selectedCompany->reviews()->where('is_positive', true)->count(),
                        'negative_reviews' => $selectedCompany->reviews()->where('is_positive', false)->count(),
                        'average_rating' => round($selectedCompany->reviews()->avg('rating'), 1) ?? 0,
                    ];
                }
            }
            
            return view('dashboard-user', compact('companies', 'selectedCompany', 'hasCompany', 'stats', 'selectedCompanyId'));
        }
    })->name('dashboard');
});

// Companies routes - accessible to all authenticated users (with controller-level restrictions)
Route::middleware(['auth'])->group(function () {
    Route::get('/companies', [App\Http\Controllers\CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [App\Http\Controllers\CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [App\Http\Controllers\CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{id}/edit', [App\Http\Controllers\CompanyController::class, 'edit'])->name('companies.edit');
    Route::get('/companies/{id}/qrcode', [App\Http\Controllers\CompanyController::class, 'downloadQrCode'])->name('companies.qrcode');
    Route::put('/companies/{id}', [App\Http\Controllers\CompanyController::class, 'update'])->name('companies.update');
    Route::post('/companies/{id}/auto-save-media', [App\Http\Controllers\CompanyController::class, 'autoSaveMedia'])
        ->name('companies.auto-save-media')
        ->withoutMiddleware(['auth']);
    Route::delete('/companies/{id}', [App\Http\Controllers\CompanyController::class, 'destroy'])->name('companies.destroy');

    // Reviews routes for ALL authenticated users (not just admin)
    Route::get('/reviews', function () {
        return view('admin.reviews.index');
    })->name('reviews.index');
    
    Route::get('/reviews/negative', function () {
        return view('admin.reviews.negative');
    })->name('reviews.negative');

    // API Routes for Reviews (accessible to all authenticated users)
    // These routes use the 'web' middleware from the parent group which includes session support
    Route::prefix('api')->group(function () {
        // Get reviews (filtered by user's companies if not admin)
        Route::get('/reviews', function (\Illuminate\Http\Request $request) {
            return (new \App\Http\Controllers\ReviewController)->index($request);
        });
        
        Route::get('/reviews/negative', function (\Illuminate\Http\Request $request) {
            return (new \App\Http\Controllers\ReviewController)->negativeReviews($request);
        });
        
        Route::get('/companies/{companyId}/contacts', function ($companyId) {
            return (new \App\Http\Controllers\ReviewController)->exportContacts(request(), $companyId);
        });
        
        // Get companies (only user's companies if not admin)
        Route::get('/companies', function () {
            try {
                $user = auth()->user();
                
                if (!$user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Não autenticado'
                    ], 401);
                }
                
                if ($user->role === 'admin') {
                    // Admin sees all companies
                    $companies = \App\Models\Company::select('id', 'name', 'token')->get();
                } else {
                    // Regular users see only their companies
                    $companies = \App\Models\Company::select('id', 'name', 'token')
                        ->where('user_id', $user->id)
                        ->get();
                }
                
                return response()->json([
                    'success' => true,
                    'data' => $companies
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao carregar empresas: ' . $e->getMessage()
                ], 500);
            }
        });
    });
});

// Protected admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    // User Management Routes (Admin Only)
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
});

// Profile Routes (All authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/photo', [App\Http\Controllers\ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    
    // Support Routes
    Route::get('/support', function () {
        return view('support.help-center');
    })->name('support.help-center');
    
    Route::get('/faqs', function () {
        return view('support.faqs');
    })->name('support.faqs');
});

// Public review page (no auth required)
Route::get('/r/{token}', [App\Http\Controllers\CompanyController::class, 'show'])->name('public.review-page');

// Public review page by custom URL (e.g. /befly)
Route::get('/{slug}', [App\Http\Controllers\CompanyController::class, 'showBySlug'])
    ->where('slug', '[a-z0-9\-]+')
    ->name('public.review-page.slug');

// API Routes
Route::post('/api/reviews', [App\Http\Controllers\ReviewController::class, 'store']);

// Rota catch-all para SPA (Single Page Application) - APENAS para rotas que não começam com 'api'
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*');