<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Company;
use App\Services\TransactionalEmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class ReviewController extends Controller
{
    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        try {
            Log::info('ReviewController@store chamado', ['request_data' => $request->all()]);

            // Validate the request
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'whatsapp' => 'required|string|max:20',
                'comment' => 'nullable|string|max:1000',
                'company_token' => 'required|string|exists:companies,token'
            ]);

            // Find the company by token
            $company = Company::where('token', $request->company_token)->first();
            
            if (!$company) {
                Log::error('Empresa não encontrada', ['token' => $request->company_token]);
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa não encontrada'
                ], 404);
            }

            // Determine if review is positive or negative
            $isPositive = $request->rating >= $company->positive_score;

            // Create the review
            $review = Review::create([
                'company_id' => $company->id,
                'rating' => $request->rating,
                'whatsapp' => $request->whatsapp,
                'comment' => $request->comment,
                'is_positive' => $isPositive,
                'is_processed' => false
            ]);

            Log::info('Avaliação criada', [
                'review_id' => $review->id,
                'company_id' => $company->id,
                'rating' => $request->rating,
                'is_positive' => $isPositive
            ]);

            // Send email notification
            $this->sendEmailNotification($company, $review);

            // Update review page statistics
            $this->updateReviewPageStats($company);

            return response()->json([
                'success' => true,
                'message' => 'Avaliação enviada com sucesso!',
                'data' => [
                    'review_id' => $review->id,
                    'rating' => $review->rating,
                    'is_positive' => $isPositive,
                    'google_business_url' => $company->google_business_url,
                    'negative_email' => $company->negative_email
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao criar avaliação', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Send email notification to company owner.
     * Uses SendGrid first; if it fails, falls back to Resend.
     */
    private function sendEmailNotification($company, $review)
    {
        if ($review->is_positive) {
            $subject = 'Nova Avaliação Positiva - ' . $company->name;
            $view = 'emails.new-review';
        } else {
            $subject = '🚨 ALERTA: Avaliação Negativa - ' . $company->name;
            $view = 'emails.negative-review-alert';
        }

        $htmlContent = View::make($view, [
            'company' => $company,
            'review' => $review,
            'isPositive' => $review->is_positive,
        ])->render();

        try {
            app(TransactionalEmailService::class)->send(
                $company->negative_email,
                $subject,
                $htmlContent
            );
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de avaliação', [
                'error' => $e->getMessage(),
                'company_id' => $company->id,
                'review_id' => $review->id,
            ]);
        }
    }

    /**
     * Update review page statistics
     */
    private function updateReviewPageStats($company)
    {
        try {
            $reviewPage = $company->reviewPages()->first();
            if ($reviewPage) {
                $reviewPage->increment('reviews_count');
                Log::info('Estatísticas da página atualizadas', [
                    'company_id' => $company->id,
                    'reviews_count' => $reviewPage->reviews_count
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar estatísticas', [
                'message' => $e->getMessage(),
                'company_id' => $company->id
            ]);
        }
    }

    /**
     * Get reviews for admin panel
     */
    public function index(Request $request)
    {
        try {
            Log::info('ReviewController@index chamado', ['request' => $request->all()]);
            
            $user = auth()->user();
            $query = Review::with('company');

            // Apenas proprietário vê todas; admin e user filtram pelas suas empresas
            if ($user->role !== 'proprietario') {
                $userCompanyIds = \App\Models\Company::accessibleBy($user->id)->pluck('id');
                $query->whereIn('company_id', $userCompanyIds);
            }

            // Filter by company
            if ($request->has('company_id') && $request->company_id) {
                $query->where('company_id', $request->company_id);
            }

            // Filter by user (company owner) - only for proprietário
            if ($request->has('user_id') && $request->user_id && $user->role === 'proprietario') {
                $companyIds = \App\Models\Company::where('user_id', $request->user_id)->pluck('id');
                if ($companyIds->isNotEmpty()) {
                    $query->whereIn('company_id', $companyIds);
                } else {
                    // No companies for this user, return empty result
                    $query->whereRaw('1 = 0');
                }
            }

            // Filter by rating type
            if ($request->has('type') && $request->type) {
                if ($request->type === 'positive') {
                    $query->where('is_positive', true);
                } elseif ($request->type === 'negative') {
                    $query->where('is_positive', false);
                }
            }

            // Filter by rating
            if ($request->has('rating') && $request->rating) {
                $query->where('rating', $request->rating);
            }

            // Sort by created_at desc
            $query->orderBy('created_at', 'desc');

            // Allow custom per_page for charts (load all reviews)
            $perPage = $request->get('per_page', 20);
            $reviews = $query->paginate($perPage);

            Log::info('Reviews carregadas', ['total' => $reviews->total(), 'count' => $reviews->count()]);

            return response()->json([
                'success' => true,
                'data' => $reviews
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao carregar avaliações', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar avaliações'
            ], 500);
        }
    }

    /**
     * Get negative reviews for admin panel
     */
    public function negativeReviews(Request $request)
    {
        try {
            Log::info('ReviewController@negativeReviews chamado', ['filters' => $request->all()]);
            
            $user = auth()->user();
            $query = Review::with('company')->where('is_positive', false);

            // Apenas proprietário vê todas; admin e user filtram pelas suas empresas
            if ($user->role !== 'proprietario') {
                $userCompanyIds = \App\Models\Company::accessibleBy($user->id)->pluck('id');
                $query->whereIn('company_id', $userCompanyIds);
            }

            // Filter by company
            if ($request->has('company_id') && $request->company_id && $request->company_id !== 'all') {
                $query->where('company_id', $request->company_id);
            }

            // Filter by user (company owner) - only for proprietário
            if ($request->has('user_id') && $request->user_id && $request->user_id !== 'all' && $user->role === 'proprietario') {
                $companyIds = \App\Models\Company::where('user_id', $request->user_id)->pluck('id');
                if ($companyIds->isNotEmpty()) {
                    $query->whereIn('company_id', $companyIds);
                } else {
                    // No companies for this user, return empty result
                    $query->whereRaw('1 = 0');
                }
            }

            // Filter by status (processed/unprocessed)
            if ($request->has('status') && $request->status && $request->status !== 'all') {
                if ($request->status === 'processed') {
                    $query->where('is_processed', true);
                } elseif ($request->status === 'unprocessed') {
                    $query->where('is_processed', false);
                }
            }

            // Filter by rating
            if ($request->has('rating') && $request->rating && $request->rating !== 'all') {
                $query->where('rating', $request->rating);
            }

            // Filter by date period
            if ($request->has('period') && $request->period && $request->period !== 'all') {
                $now = now();
                switch ($request->period) {
                    case 'today':
                        $query->whereDate('created_at', $now->toDateString());
                        break;
                    case 'week':
                        $query->where('created_at', '>=', $now->copy()->subWeek());
                        break;
                    case 'month':
                        $query->where('created_at', '>=', $now->copy()->subMonth());
                        break;
                }
            }

            // Filter by date range
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Search filter (comment, private_feedback, whatsapp)
            if ($request->has('search') && $request->search) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function($q) use ($searchTerm) {
                    $q->where('comment', 'LIKE', $searchTerm)
                      ->orWhere('private_feedback', 'LIKE', $searchTerm)
                      ->orWhere('whatsapp', 'LIKE', $searchTerm);
                });
            }

            // Sort
            $sortBy = $request->get('sort', 'recent');
            switch ($sortBy) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'lowest':
                    $query->orderBy('rating', 'asc')->orderBy('created_at', 'desc');
                    break;
                case 'highest':
                    $query->orderBy('rating', 'desc')->orderBy('created_at', 'desc');
                    break;
                case 'recent':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $reviews = $query->paginate(20);

            Log::info('Avaliações negativas carregadas', ['total' => $reviews->total()]);

            return response()->json([
                'success' => true,
                'data' => $reviews
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao carregar avaliações negativas', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar avaliações negativas'
            ], 500);
        }
    }

    /**
     * Store private feedback for negative reviews
     */
    public function storePrivateFeedback(Request $request)
    {
        try {
            $request->validate([
                'review_id' => 'required|exists:reviews,id',
                'feedback' => 'required|string|max:1000',
                'contact_preference' => 'required|in:whatsapp,email,phone,no_contact',
                'contact_detail' => 'nullable|string|max:255'
            ]);

            $review = Review::findOrFail($request->review_id);
            
            // Prepare data to update
            $updateData = [
                'private_feedback' => $request->feedback,
                'contact_preference' => $request->contact_preference,
                'has_private_feedback' => true
            ];
            
            // Add contact detail if provided
            if ($request->contact_preference === 'email' || $request->contact_preference === 'phone') {
                if ($request->contact_detail) {
                    $updateData['contact_detail'] = $request->contact_detail;
                }
            }
            
            // Update review with private feedback
            $review->update($updateData);

            Log::info('Feedback privado enviado', [
                'review_id' => $review->id,
                'company_id' => $review->company_id,
                'contact_preference' => $request->contact_preference,
                'has_contact_detail' => !empty($request->contact_detail)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Feedback privado enviado com sucesso!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar feedback privado', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Export contacts for a company
     */
    public function exportContacts(Request $request, $companyId)
    {
        try {
            $company = Company::findOrFail($companyId);
            $user = auth()->user();
            if ($user->role !== 'proprietario' && !$user->hasAccessTo($company)) {
                return response()->json(['success' => false, 'message' => 'Sem permissão para exportar contatos desta empresa.'], 403);
            }
            $reviews = $company->reviews()->get();

            $contacts = $reviews->map(function ($review) {
                return [
                    'whatsapp' => $review->whatsapp,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->format('d/m/Y H:i')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'company' => $company->name,
                    'total_contacts' => $contacts->count(),
                    'contacts' => $contacts
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao exportar contatos', [
                'message' => $e->getMessage(),
                'company_id' => $companyId
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erro ao exportar contatos'
            ], 500);
        }
    }
}