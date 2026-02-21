<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ReviewPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class CompanyController extends Controller
{
    /**
     * Slugs reservados (rotas existentes) que não podem ser usados como URL customizada.
     */
    private function getReservedSlugs(): array
    {
        return [
            'login', 'logout', 'dashboard', 'companies', 'r', 'api', 'create-admin',
            'forgot-password', 'reset-password', 'contact-trial', 'profile', 'support',
            'faqs', 'users', 'reviews', 'password', 'change-locale',
        ];
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Company::with('user')->withCount(['reviews', 'reviewPages']);
        
        // Admin e Proprietário vêem todas as empresas
        if (!in_array($user->role, ['admin', 'proprietario'])) {
            // User comum vê apenas suas empresas
            $query->where('user_id', $user->id);
        }
        
        // Filter by user (company owner) - only for admin and owner
        if ($request->has('user_id') && $request->user_id && in_array($user->role, ['admin', 'proprietario'])) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by visibility (is_active)
        if ($request->has('visibility') && $request->visibility && $request->visibility !== 'all') {
            if ($request->visibility === 'visible') {
                $query->where('is_active', true);
            } elseif ($request->visibility === 'hidden') {
                $query->where('is_active', false);
            }
        }
        
        // Filter by rating limit (positive_score)
        if ($request->has('rating_limit') && $request->rating_limit && $request->rating_limit !== 'all') {
            $query->where('positive_score', $request->rating_limit);
        }
        
        // Filter by reviews count
        if ($request->has('reviews_filter') && $request->reviews_filter && $request->reviews_filter !== 'all') {
            if ($request->reviews_filter === 'with_reviews') {
                $query->has('reviews');
            } elseif ($request->reviews_filter === 'without_reviews') {
                $query->doesntHave('reviews');
            }
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
        
        // Search by name
        if ($request->has('search') && $request->search) {
            $searchTerm = '%' . $request->search . '%';
            $query->where('name', 'LIKE', $searchTerm);
        }
        
        // Order by
        $query->orderBy('status', 'asc')
              ->orderBy('created_at', 'desc');
        
        $companies = $query->paginate(12)->appends($request->query());
        
        // Get users list for filter (only for admin/owner)
        $users = collect(); // Empty collection by default
        if (in_array($user->role, ['admin', 'proprietario'])) {
            $users = \App\Models\User::whereHas('companies')->orderBy('name')->get();
        }
            
        return view('companies', compact('companies', 'users'));
    }

    public function create()
    {
        $user = auth()->user();
        $users = null;
        
        // Se for proprietário ou admin, carregar lista de usuários para atribuição
        if (in_array($user->role, ['proprietario', 'admin'])) {
            if ($user->role === 'proprietario') {
                // Proprietário pode atribuir a qualquer usuário
                $users = \App\Models\User::orderBy('name')->get();
            } else {
                // Admin pode atribuir apenas a usuários comuns
                $users = \App\Models\User::where('role', 'user')->orderBy('name')->get();
            }
        }
        
        return view('companies-create', compact('users'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Usuários agora podem criar quantas empresas/páginas de avaliação quiserem
        \Log::info('CompanyController@store chamado', ['request_data' => $request->all()]);
        
        // Determinar se é rascunho ou publicação
        $isDraft = $request->has('save_as_draft') && $request->save_as_draft === 'true';
        
        // Normalizar URL customizada antes da validação (minúsculas, trim)
        $request->merge([
            'url' => $request->filled('url') ? strtolower(trim($request->input('url'))) : null
        ]);
        
        $urlRules = [
            'nullable',
            'string',
            'max:255',
            Rule::when($request->filled('url'), [
                'regex:/^[a-z0-9\-]+$/',
                Rule::notIn($this->getReservedSlugs()),
                'unique:companies,url',
            ]),
        ];
        
        // Validação diferente para rascunho vs publicação
        if ($isDraft) {
            // Validação para rascunho - campos obrigatórios do banco são exigidos
            $request->validate([
                'name' => 'required|string|max:255',
                'url' => $urlRules,
                'negative_email' => 'required|email',
                'contact_number' => 'nullable|string|max:20',
                'business_website' => 'nullable|string|max:500',
                'business_address' => 'nullable|string|max:500',
                'google_business_url' => 'nullable|string|max:500',
                'positive_score' => 'nullable|integer|min:1|max:5',
                'logo' => 'nullable|file|max:2048',
                'background_image' => 'nullable|file|max:2048',
            ]);
        } else {
            // Validação completa para publicação
            $request->validate([
                'name' => 'required|string|max:255',
                'url' => $urlRules,
                'negative_email' => 'required|email',
                'contact_number' => 'nullable|string|max:20',
                'business_website' => 'nullable|string|max:500',
                'business_address' => 'nullable|string|max:500',
                'google_business_url' => 'nullable|string|max:500',
                'positive_score' => 'required|integer|min:1|max:5',
                'logo' => 'nullable|file|max:2048',
                'background_image' => 'nullable|file|max:2048',
            ]);
        }

        \Log::info('Validação passou com sucesso', ['is_draft' => $isDraft]);
        
        $data = $request->all();
        
        // Remover campos vazios, null ou "null" (string) do array
        $data = array_filter($data, function($value) {
            return $value !== null && $value !== 'null' && $value !== '';
        });
        
        // Para draft, garantir valores padrão para campos obrigatórios se necessário
        if ($isDraft) {
            // positive_score tem default no banco, mas vamos garantir um valor
            if (empty($data['positive_score'])) {
                $data['positive_score'] = 4; // Valor padrão
            }
        }
        
        // Normalizar Google Business URL - adicionar https:// se não tiver protocolo
        if (isset($data['google_business_url']) && !empty($data['google_business_url']) && $data['google_business_url'] !== 'null') {
            $data['google_business_url'] = $this->normalizeGoogleBusinessUrl($data['google_business_url']);
        } else {
            unset($data['google_business_url']); // Remove se for null ou vazio
        }
        
        // Adicionar user_id - permitir atribuição se for proprietário ou admin
        if (in_array($user->role, ['proprietario', 'admin']) && $request->has('assigned_user_id') && $request->assigned_user_id) {
            $assignedUserId = $request->input('assigned_user_id');
            
            // Validar se o usuário pode atribuir a este usuário
            if ($user->role === 'proprietario') {
                // Proprietário pode atribuir a qualquer usuário
                $data['user_id'] = $assignedUserId;
            } elseif ($user->role === 'admin') {
                // Admin só pode atribuir a usuários comuns
                $assignedUser = \App\Models\User::find($assignedUserId);
                if ($assignedUser && $assignedUser->role === 'user') {
                    $data['user_id'] = $assignedUserId;
                } else {
                    $data['user_id'] = $user->id; // Fallback para o próprio admin
                }
            }
        } else {
            // Usuário comum ou sem atribuição específica - usar o próprio usuário
            $data['user_id'] = $user->id;
        }
        
        // Handle file uploads with compression
        // Processar logo_cropped (base64) primeiro, se existir
        if ($request->has('logo_cropped') && !empty($request->input('logo_cropped'))) {
            $logoPath = $this->saveBase64Image($request->input('logo_cropped'), 'logos', 800, 800);
            if ($logoPath) {
                $data['logo'] = $logoPath;
            }
        } elseif ($request->hasFile('logo')) {
            $data['logo'] = $this->compressAndStoreImage($request->file('logo'), 'logos', 800, 800, 85);
        } else {
            unset($data['logo']); // Remove se não houver arquivo
        }
        
        if ($request->hasFile('background_image')) {
            $data['background_image'] = $this->compressAndStoreImage($request->file('background_image'), 'backgrounds', 1920, 1080, 80);
        } else {
            unset($data['background_image']); // Remove se não houver arquivo
        }
        
        // Remover campos que não devem ser salvos
        unset($data['save_as_draft']);
        unset($data['logo_cropped']); // Já foi processado acima
        unset($data['_token']);

        // Definir status
        $data['status'] = $isDraft ? 'draft' : 'published';
        
        // Se for rascunho, automaticamente definir como não visível (sempre)
        if ($isDraft) {
            $data['is_active'] = false;
        } else {
            // Se for publicado, por padrão definir como visível
            // Se vier is_active do request, usar o valor do request
            if (!$request->has('is_active')) {
                $data['is_active'] = true;
            } else {
                $data['is_active'] = $request->boolean('is_active');
            }
        }

        // Create company
        \Log::info('Criando empresa', ['data' => $data]);
        $company = Company::create($data);
        \Log::info('Empresa criada', ['company_id' => $company->id, 'token' => $company->token, 'status' => $company->status]);

        if (!$isDraft) {
            // Create review page apenas para empresas publicadas
            \Log::info('Criando review page');
            $reviewPage = ReviewPage::create([
                'company_id' => $company->id,
                'token' => $company->token,
                'url' => $company->public_url,
            ]);
            \Log::info('Review page criada', ['review_page_id' => $reviewPage->id]);

            \Log::info('Empresa publicada com sucesso', ['token' => $company->token]);
            return redirect()->route('companies.edit', ['id' => $company->id])
                ->with('success', 'Empresa ativada com sucesso! Sua página pública está pronta!')
                ->with('open_review_page', $company->public_url);
        } else {
            // Para rascunhos, redirecionar para página de edição
            \Log::info('Redirecionando para página de edição', ['company_id' => $company->id]);
            return redirect()->route('companies.edit', ['id' => $company->id])
                ->with('success', 'Empresa salva como rascunho! Você pode continuar editando e publicar quando estiver pronto.');
        }
    }

    public function show($token)
    {
        $company = Company::where('token', $token)->firstOrFail();
        $reviews = $company->reviews()->orderBy('created_at', 'desc')->get();
        
        return view('public.review-page', compact('company', 'token', 'reviews'));
    }

    /**
     * Show public review page by custom URL slug (e.g. /befly).
     */
    public function showBySlug($slug)
    {
        $company = Company::where('url', $slug)->where('is_active', true)->first();
        if (!$company) {
            return view('app');
        }
        $token = $company->token;
        $reviews = $company->reviews()->orderBy('created_at', 'desc')->get();
        return view('public.review-page', compact('company', 'token', 'reviews'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $company = Company::findOrFail($id);
        
        // Verificar se o usuário tem permissão para editar
        if ($user->role === 'user' && $company->user_id !== $user->id) {
            return redirect()->route('companies.index')
                ->with('error', 'Você não tem permissão para editar esta empresa.');
        }
        
        // Permitir edição mesmo se publicada
        
        return view('companies-edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        \Log::info('CompanyController@update chamado', ['company_id' => $id, 'request_data' => $request->all()]);
        
        $company = Company::findOrFail($id);
        
        // Verificar se o usuário tem permissão para editar
        if ($user->role === 'user' && $company->user_id !== $user->id) {
            return redirect()->route('companies.index')
                ->with('error', 'Você não tem permissão para editar esta empresa.');
        }
        
        // Permitir edição mesmo se publicada
        
        // Determinar se é rascunho ou publicação
        $isDraft = $request->has('save_as_draft') && $request->save_as_draft === 'true';
        
        // Normalizar URL customizada antes da validação (minúsculas, trim)
        $request->merge([
            'url' => $request->filled('url') ? strtolower(trim($request->input('url'))) : null
        ]);
        
        $urlRulesUpdate = [
            'nullable',
            'string',
            'max:255',
            Rule::when($request->filled('url'), [
                'regex:/^[a-z0-9\-]+$/',
                Rule::notIn($this->getReservedSlugs()),
                Rule::unique('companies', 'url')->ignore($company->id),
            ]),
        ];
        
        // Validação diferente para rascunho vs publicação
        if ($isDraft) {
            // Validação para rascunho - campos obrigatórios do banco são exigidos
            $request->validate([
                'name' => 'required|string|max:255',
                'url' => $urlRulesUpdate,
                'negative_email' => 'required|email',
                'contact_number' => 'nullable|string|max:20',
                'business_website' => 'nullable|string|max:500',
                'business_address' => 'nullable|string|max:500',
                'google_business_url' => 'nullable|string|max:500',
                'positive_score' => 'nullable|integer|min:1|max:5',
                'logo' => 'nullable|file|max:2048',
                'background_image' => 'nullable|file|max:2048',
            ]);
        } else {
            // Validação completa para publicação
            $request->validate([
                'name' => 'required|string|max:255',
                'url' => $urlRulesUpdate,
                'negative_email' => 'required|email',
                'contact_number' => 'nullable|string|max:20',
                'business_website' => 'nullable|string|max:500',
                'business_address' => 'nullable|string|max:500',
                'google_business_url' => 'nullable|string|max:500',
                'positive_score' => 'required|integer|min:1|max:5',
                'logo' => 'nullable|file|max:2048',
                'background_image' => 'nullable|file|max:2048',
            ]);
        }

        \Log::info('Validação passou com sucesso', ['is_draft' => $isDraft]);
        
        $data = $request->all();
        
        // Salvar referências das imagens antigas ANTES de processar
        $oldLogo = $company->logo;
        $oldBackground = $company->background_image;
        
        // Processar arquivos ANTES de filtrar campos vazios
        // Handle logo removal or upload
        if ($request->has('remove_logo') && $request->input('remove_logo') == '1') {
            // Remover logo
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $data['logo'] = null;
        } elseif ($request->has('logo_cropped') && !empty($request->input('logo_cropped'))) {
            // Processar logo_cropped (base64) primeiro, se existir
            // Deletar logo antiga se existir
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            try {
                $logoPath = $this->saveBase64Image($request->input('logo_cropped'), 'logos', 800, 800);
                if ($logoPath) {
                    \Log::info('Logo from crop saved', [
                        'path' => $logoPath, 
                        'file_exists' => Storage::disk('public')->exists($logoPath)
                    ]);
                    $data['logo'] = $logoPath;
                } else {
                    throw new \Exception('Failed to save cropped logo');
                }
            } catch (\Exception $e) {
                \Log::error('Error processing cropped logo: ' . $e->getMessage());
                // Se houver erro, manter a logo antiga
                if ($oldLogo) {
                    $data['logo'] = $oldLogo;
                } else {
                    unset($data['logo']);
                }
            }
        } elseif ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            \Log::info('Logo file received', [
                'originalName' => $logoFile->getClientOriginalName(),
                'mimeType' => $logoFile->getMimeType(),
                'size' => $logoFile->getSize(),
                'isValid' => $logoFile->isValid()
            ]);
            
            // Deletar logo antiga se existir
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            // Salvar nova logo com compressão
            try {
                $logoPath = $this->compressAndStoreImage($logoFile, 'logos', 800, 800, 85);
                \Log::info('Logo path returned', [
                    'path' => $logoPath, 
                    'file_exists' => Storage::disk('public')->exists($logoPath),
                    'full_path' => Storage::disk('public')->path($logoPath)
                ]);
                $data['logo'] = $logoPath;
            } catch (\Exception $e) {
                \Log::error('Error processing logo: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                // Se houver erro, manter a logo antiga
                if ($oldLogo) {
                    $data['logo'] = $oldLogo;
                } else {
                    unset($data['logo']);
                }
            }
        } else {
            \Log::info('No logo file in request, preserving old logo');
            // Preservar logo antiga se não houver novo upload - não incluir no array para não sobrescrever
            unset($data['logo']);
        }
        
        // Handle background removal or upload
        if ($request->has('remove_background') && $request->input('remove_background') == '1') {
            // Remover background
            if ($oldBackground && Storage::disk('public')->exists($oldBackground)) {
                Storage::disk('public')->delete($oldBackground);
            }
            $data['background_image'] = null;
        } elseif ($request->hasFile('background_image')) {
            // Deletar background antigo se existir
            if ($oldBackground && Storage::disk('public')->exists($oldBackground)) {
                Storage::disk('public')->delete($oldBackground);
            }
            // Salvar novo background com compressão
            $data['background_image'] = $this->compressAndStoreImage($request->file('background_image'), 'backgrounds', 1920, 1080, 80);
        } else {
            // Preservar background antigo se não houver novo upload
            unset($data['background_image']);
        }
        
        // Remover campos vazios, null ou "null" (string) do array (APÓS processar arquivos)
        // Mas preservar campos de arquivo processados (logo e background_image)
        $logoPath = isset($data['logo']) ? $data['logo'] : null;
        $backgroundPath = isset($data['background_image']) ? $data['background_image'] : null;
        
        $data = array_filter($data, function($value) {
            return $value !== null && $value !== 'null' && $value !== '';
        });
        
        // Restaurar caminhos de arquivo processados se existirem
        if ($logoPath !== null) {
            $data['logo'] = $logoPath;
        }
        // Se backgroundPath for null (removido), garantir que seja null e não string vazia
        if ($backgroundPath !== null) {
            $data['background_image'] = $backgroundPath;
        } elseif ($request->has('remove_background') && $request->input('remove_background') == '1') {
            // Garantir que seja null quando removido, não string vazia
            $data['background_image'] = null;
        }
        
        // Para draft na edição, se campos obrigatórios não vierem, manter os existentes
        if ($isDraft) {
            // Se name não vier, manter o existente
            if (empty($data['name'])) {
                unset($data['name']);
            }
            
            // Se negative_email não vier, manter o existente
            if (empty($data['negative_email'])) {
                unset($data['negative_email']);
            }
            
            // positive_score tem default no banco, mas vamos garantir um valor
            if (empty($data['positive_score'])) {
                unset($data['positive_score']); // Manter o existente ou usar default do banco
            }
        }
        
        // Normalizar Google Business URL - adicionar https:// se não tiver protocolo
        if (isset($data['google_business_url']) && !empty($data['google_business_url']) && $data['google_business_url'] !== 'null') {
            $data['google_business_url'] = $this->normalizeGoogleBusinessUrl($data['google_business_url']);
        } else {
            unset($data['google_business_url']); // Remove se for null ou vazio
        }
        
        // Remover campos que não devem ser salvos
        unset($data['save_as_draft']);
        unset($data['logo_cropped']); // Este campo é processado separadamente
        unset($data['_token']);

        // Definir status - preservar status atual se empresa já está publicada
        if ($isDraft) {
            // Se explicitamente marcado como draft, definir como draft
            // Mas só mudar para draft se realmente quiser (não preservar se já está publicado)
            // Se a empresa já está publicada e usuário clicou em SAVE, preservar status
            if ($company->status === 'published' && !$request->has('force_draft')) {
                // Preservar status publicado mesmo se save_as_draft foi enviado
                $data['status'] = 'published';
                // Preservar is_active atual
                if (!$request->has('is_active')) {
                    $data['is_active'] = $company->is_active;
                } else {
                    $data['is_active'] = $request->boolean('is_active');
                }
            } else {
                // Mudar para draft apenas se realmente quiser ou se estava como draft
                $data['status'] = 'draft';
                $data['is_active'] = false;
            }
        } else {
            // Se não for draft (botão ACTIVATE foi clicado ou não há save_as_draft)
            // Se a empresa já está publicada, preservar status publicado
            if ($company->status === 'published') {
                $data['status'] = 'published';
                // Preservar is_active atual se não foi especificado no request
                if (!$request->has('is_active')) {
                    $data['is_active'] = $company->is_active;
                } else {
                    $data['is_active'] = $request->boolean('is_active');
                }
            } else {
                // Se estava como draft e não foi marcado como draft, publicar
                $data['status'] = 'published';
                if (!$request->has('is_active')) {
                    $data['is_active'] = true;
                } else {
                    $data['is_active'] = $request->boolean('is_active');
                }
            }
        }

        // Update company
        \Log::info('Atualizando empresa', [
            'data' => $data,
            'has_logo' => isset($data['logo']),
            'logo_path' => $data['logo'] ?? 'NOT SET',
            'has_background' => isset($data['background_image']),
            'background_path' => $data['background_image'] ?? 'NOT SET'
        ]);
        $company->update($data);
        
        // Recarregar a empresa do banco para garantir dados atualizados
        $company->refresh();
        
        \Log::info('Empresa atualizada', [
            'company_id' => $company->id, 
            'status' => $company->status,
            'logo' => $company->logo,
            'logo_url' => $company->logo_url,
            'logo_file_exists' => $company->logo ? Storage::disk('public')->exists($company->logo) : false
        ]);

        if (!$isDraft && $company->status === 'published') {
            // Create review page se não existir
            if (!$company->reviewPages()->exists()) {
                \Log::info('Criando review page');
                $reviewPage = ReviewPage::create([
                    'company_id' => $company->id,
                    'token' => $company->token,
                    'url' => $company->public_url,
                ]);
                \Log::info('Review page criada', ['review_page_id' => $reviewPage->id]);
            }

            \Log::info('Empresa publicada com sucesso', ['token' => $company->token]);
            return redirect()->route('companies.edit', ['id' => $company->id])
                ->with('success', 'Empresa ativada com sucesso! Sua página pública está pronta!')
                ->with('open_review_page', $company->public_url);
        } else {
            // Para rascunhos, redirecionar para página de edição
            \Log::info('Redirecionando para página de edição', ['company_id' => $company->id]);
            return redirect()->route('companies.edit', ['id' => $company->id])
                ->with('success', 'Empresa salva como rascunho! Você pode continuar editando e publicar quando estiver pronto.');
        }
    }

    public function autoSaveMedia(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $user = auth()->user();

        if (!$user) {
            $tokenUser = $this->resolveUserFromMediaToken($request->input('media_token'), $company);
            if ($tokenUser) {
                Auth::onceUsingId($tokenUser->id);
                $user = $tokenUser;
            }
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => __('companies.media_auto_save_unauthorized'),
            ], 401);
        }

        if ($user->role === 'user' && $company->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => __('companies.media_auto_save_unauthorized'),
            ], 403);
        }

        if (!$request->hasFile('logo') && !$request->hasFile('background_image')) {
            return response()->json([
                'success' => false,
                'message' => __('companies.media_auto_save_no_file'),
            ], 422);
        }

        $request->validate([
            'logo' => 'nullable|image|max:2048',
            'background_image' => 'nullable|image|max:4096',
        ]);

        try {
            $response = [
                'success' => true,
                'message' => __('companies.media_auto_save_success'),
            ];

            $updated = false;

            if ($request->hasFile('logo')) {
                $oldLogo = $company->logo;
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }

                $logoPath = $this->compressAndStoreImage($request->file('logo'), 'logos', 800, 800, 85);
                $company->logo = $logoPath;
                $response['logo_url'] = $company->logo_url ?? url('storage/' . $logoPath);
                $updated = true;
            }

            if ($request->hasFile('background_image')) {
                $oldBackground = $company->background_image;
                if ($oldBackground && Storage::disk('public')->exists($oldBackground)) {
                    Storage::disk('public')->delete($oldBackground);
                }

                $backgroundPath = $this->compressAndStoreImage($request->file('background_image'), 'backgrounds', 1920, 1080, 80);
                $company->background_image = $backgroundPath;
                $response['background_url'] = $company->background_image_url ?? url('storage/' . $backgroundPath);
                $updated = true;
            }

            if ($updated) {
                $company->save();
            }

            return response()->json($response);
        } catch (\Exception $exception) {
            \Log::error('Erro no auto save de mídia', [
                'company_id' => $company->id,
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('companies.media_auto_save_error'),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $company = Company::findOrFail($id);
        
        // Verificar se o usuário tem permissão para excluir
        if ($user->role === 'user' && $company->user_id !== $user->id) {
            return redirect()->route('companies.index')
                ->with('error', 'Você não tem permissão para excluir esta empresa.');
        }
        
        $company->delete();
        
        return redirect()->route('companies.index')
            ->with('success', 'Empresa excluída com sucesso!');
    }

    /**
     * Gera e retorna o QR Code da página de avaliação da empresa (download PNG).
     * Não depende de biblioteca no navegador.
     */
    public function downloadQrCode($id)
    {
        $user = auth()->user();
        $company = Company::findOrFail($id);

        if ($user->role === 'user' && $company->user_id !== $user->id) {
            return redirect()->route('companies.index')
                ->with('error', 'Você não tem permissão para acessar esta empresa.');
        }

        $url = $company->public_url;
        $filename = 'qrcode-' . Str::slug($company->name) . '.png';
        $pngData = null;

        if (class_exists(\Endroid\QrCode\Builder\Builder::class)) {
            try {
                $builder = new \Endroid\QrCode\Builder\Builder(
                    data: $url,
                    size: 256,
                    margin: 2
                );
                $result = $builder->build();
                $pngData = $result->getString();
            } catch (\Throwable $e) {
                \Log::warning('QR Code (endroid) failed', ['company_id' => $company->id, 'error' => $e->getMessage()]);
            }
        }

        if ($pngData === null) {
            $apiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=256x256&margin=2&data=' . rawurlencode($url);
            $response = Http::timeout(10)->get($apiUrl);
            if ($response->successful()) {
                $pngData = $response->body();
            }
        }

        if (empty($pngData)) {
            return redirect()->back()
                ->with('error', 'Não foi possível gerar o QR Code. Tente novamente.');
        }

        return response($pngData, 200, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-cache',
        ]);
    }

    protected function resolveUserFromMediaToken(?string $token, Company $company)
    {
        if (!$token) {
            return null;
        }

        try {
            $payload = decrypt($token);

            if (!is_array($payload)) {
                return null;
            }

            $userId = $payload['user_id'] ?? null;
            $companyId = $payload['company_id'] ?? null;
            $expiresAt = $payload['expires_at'] ?? null;

            if (!$userId || !$companyId || !$expiresAt) {
                return null;
            }

            if ((int) $companyId !== (int) $company->id) {
                return null;
            }

            if ($expiresAt < now()->timestamp) {
                return null;
            }

            return \App\Models\User::find($userId);
        } catch (\Throwable $exception) {
            \Log::warning('Invalid media auto-save token', [
                'company_id' => $company->id,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Compress and store image with optimization
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $quality (1-100)
     * @return string
     */
    private function compressAndStoreImage($file, $folder, $maxWidth, $maxHeight, $quality = 85)
    {
        // Ambiente de produção (Railway) estava apresentando problemas com bibliotecas de imagem
        // (Intervention Image / GD), resultando em arquivos em branco ou não legíveis.
        // Para garantir confiabilidade, vamos simplificar e apenas armazenar o arquivo original
        // no disco "public", sem tentativa de compressão. O caminho salvo continua igual.

        try {
            $storedPath = $file->store($folder, 'public');
            // Remover prefixo "public/" se existir
            return str_replace('public/', '', $storedPath);
        } catch (\Exception $e) {
            \Log::error('Error storing image: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Save base64 image to storage
     * 
     * @param string $base64Data
     * @param string $folder
     * @param int $maxWidth
     * @param int $maxHeight
     * @return string|null
     */
    private function saveBase64Image($base64Data, $folder, $maxWidth, $maxHeight)
    {
        try {
            // Remove data URL prefix if present (data:image/png;base64,)
            if (strpos($base64Data, ',') !== false) {
                $base64Data = explode(',', $base64Data)[1];
            }
            
            // Decode base64
            $imageData = base64_decode($base64Data);
            if ($imageData === false) {
                \Log::error('Failed to decode base64 image');
                return null;
            }
            
            // Generate unique filename
            $filename = uniqid($folder . '_') . '.png';
            $path = $folder . '/' . $filename;
            $fullPath = Storage::disk('public')->path($path);
            
            // Ensure directory exists
            $directory = dirname($fullPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Save image
            file_put_contents($fullPath, $imageData);
            
            // Remove prefix "public/" if exists
            return str_replace('public/', '', $path);
        } catch (\Exception $e) {
            \Log::error('Error saving base64 image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Compress image using GD library (fallback)
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $quality
     * @return string
     */
    private function compressWithGD($file, $folder, $maxWidth, $maxHeight, $quality)
    {
        // Check if GD extension is available
        if (!extension_loaded('gd') || !function_exists('imagecreatefromjpeg')) {
            \Log::warning('GD extension not available, storing image without compression');
            $storedPath = $file->store($folder, 'public');
            // Garantir que o caminho não tenha o prefixo do disco
            return str_replace('public/', '', $storedPath);
        }
        
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        
        // Create image resource based on type
        if ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg') {
            $sourceImage = \imagecreatefromjpeg($file->getRealPath());
        } elseif ($mimeType === 'image/png') {
            $sourceImage = \imagecreatefrompng($file->getRealPath());
        } elseif ($mimeType === 'image/gif') {
            $sourceImage = \imagecreatefromgif($file->getRealPath());
        } else {
            // Unsupported format, use original storage
            $storedPath = $file->store($folder, 'public');
            return str_replace('public/', '', $storedPath);
        }
        
        if (!$sourceImage) {
            $storedPath = $file->store($folder, 'public');
            return str_replace('public/', '', $storedPath);
        }
        
        // Get original dimensions
        $originalWidth = \imagesx($sourceImage);
        $originalHeight = \imagesy($sourceImage);
        
        // Calculate new dimensions maintaining aspect ratio
        // Only resize if image is larger than max dimensions
        if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight) {
            // Image is already smaller than max - keep original size
            $newWidth = $originalWidth;
            $newHeight = $originalHeight;
        } else {
            // Image is larger - resize it down
            $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
            $newWidth = (int)($originalWidth * $ratio);
            $newHeight = (int)($originalHeight * $ratio);
        }
        
        // Create new image
        $newImage = \imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG
        if ($extension === 'png') {
            \imagealphablending($newImage, false);
            \imagesavealpha($newImage, true);
            $transparent = \imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            \imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Resize image
        \imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
        
        // Generate filename
        $filename = uniqid($folder . '_') . '.jpg'; // Always save as JPG for better compression
        $path = $folder . '/' . $filename;
        $fullPath = Storage::disk('public')->path($path);
        
        // Ensure directory exists
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Save compressed image as JPEG
        \imagejpeg($newImage, $fullPath, $quality);
        
        // Free memory
        \imagedestroy($sourceImage);
        \imagedestroy($newImage);
        
        return $path;
    }

    /**
     * Normalize Google Business URL - add https:// if protocol is missing
     * 
     * @param string $url
     * @return string
     */
    private function normalizeGoogleBusinessUrl($url)
    {
        if (empty($url)) {
            return $url;
        }

        // Remove espaços em branco
        $url = trim($url);
        
        // Convert to lowercase for consistency
        $url = strtolower($url);

        // Se já tiver protocolo (http:// ou https://), retorna como está
        if (preg_match('/^https?:\/\//i', $url)) {
            return $url;
        }

        // Se começar com www., adiciona https://
        if (preg_match('/^www\./i', $url)) {
            return 'https://' . $url;
        }

        // Para outros casos, adiciona https://
        return 'https://' . $url;
    }
}
