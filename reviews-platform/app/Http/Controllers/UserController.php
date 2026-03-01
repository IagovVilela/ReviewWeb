<?php

namespace App\Http\Controllers;

use App\Services\TransactionalEmailService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(
        private TransactionalEmailService $emailService
    ) {
    }
    /**
     * Check if current user can manage a specific user based on hierarchy
     */
    private function canManageUser(User $targetUser): bool
    {
        $currentUser = Auth::user();
        
        // Proprietário pode gerenciar todos
        if ($currentUser->role === 'proprietario') {
            return true;
        }
        
        // Admin só pode gerenciar usuários comuns
        if ($currentUser->role === 'admin') {
            return $targetUser->role === 'user';
        }
        
        return false;
    }

    /**
     * Get allowed roles that current user can assign
     */
    private function getAllowedRoles(): array
    {
        $currentUser = Auth::user();
        
        if ($currentUser->role === 'proprietario') {
            return ['proprietario', 'admin', 'user'];
        }
        
        if ($currentUser->role === 'admin') {
            return ['user']; // Admin só pode criar usuários comuns
        }
        
        return [];
    }

    /**
     * Display a listing of users
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        // Proprietário vê todos os usuários
        // Admin vê apenas usuários comuns
        if ($currentUser->role === 'proprietario') {
            $users = User::orderByRaw("CASE role WHEN 'proprietario' THEN 1 WHEN 'admin' THEN 2 WHEN 'user' THEN 3 END")
                         ->orderBy('created_at', 'desc')
                         ->get();
        } else {
            // Admin vê apenas usuários comuns
            $users = User::where('role', 'user')
                         ->orderBy('created_at', 'desc')
                         ->get();
        }
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $allowedRoles = $this->getAllowedRoles();
        $sendWelcomeEmail = $request->boolean('send_welcome_email');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => ['required', Rule::in($allowedRoles)],
            'payment_required' => 'nullable|in:0,1',
        ];

        if ($sendWelcomeEmail) {
            $rules['password'] = 'nullable|string|min:6|confirmed';
        } else {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $request->validate($rules);

        // Se admin tentar criar admin/proprietario, bloquear
        if ($currentUser->role === 'admin' && in_array($request->role, ['admin', 'proprietario'])) {
            return redirect()->route('users.create')
                ->withErrors(['role' => 'Você não tem permissão para criar este tipo de usuário.'])
                ->withInput();
        }

        $password = $sendWelcomeEmail && !$request->filled('password')
            ? Str::random(12)
            : $request->password;

        $paymentRequired = $request->boolean('payment_required');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => $request->role,
            'payment_required' => $paymentRequired,
        ]);

        if ($sendWelcomeEmail) {
            try {
                $this->emailService->sendWelcomeWithTemporaryPassword(
                    $user->email,
                    $user->name,
                    $password
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send welcome email', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
                return redirect()->route('users.index')
                    ->with('success', 'Usuário criado, mas o e-mail de boas-vindas não pôde ser enviado.')
                    ->with('warning', $e->getMessage());
            }
        }

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $targetUser = User::findOrFail($id);
        
        // Verificar permissão para editar
        if (!$this->canManageUser($targetUser)) {
            abort(403, 'Você não tem permissão para editar este usuário.');
        }
        
        return view('admin.users.edit', compact('targetUser'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = Auth::user();
        
        // Verificar permissão para editar
        if (!$this->canManageUser($targetUser)) {
            abort(403, 'Você não tem permissão para editar este usuário.');
        }
        
        $allowedRoles = $this->getAllowedRoles();
        
        // Se admin tentar alterar role para admin/proprietario, não permitir
        $roleValidation = $currentUser->role === 'proprietario' 
            ? ['required', Rule::in(['proprietario', 'admin', 'user'])]
            : ['required', Rule::in(['user'])]; // Admin só pode manter como user
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($targetUser->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => $roleValidation
        ]);

        // Se admin tentar alterar role, bloquear
        if ($currentUser->role === 'admin' && $request->role !== 'user') {
            return redirect()->route('users.edit', $id)
                ->withErrors(['role' => 'Você não tem permissão para alterar para este tipo de usuário.'])
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        
        // Permitir alteração de role apenas se for proprietário ou se manter como 'user'
        if ($currentUser->role === 'proprietario' || $request->role === 'user') {
            $data['role'] = $request->role;
        }

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $targetUser->update($data);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = Auth::user();

        // Prevent deleting yourself
        if ($targetUser->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Você não pode excluir sua própria conta!');
        }

        // Verificar permissão para excluir
        if (!$this->canManageUser($targetUser)) {
            abort(403, 'Você não tem permissão para excluir este usuário.');
        }

        // Prevent deleting the last owner
        if ($targetUser->role === 'proprietario') {
            $ownerCount = User::where('role', 'proprietario')->count();
            if ($ownerCount <= 1) {
                return redirect()->route('users.index')->with('error', 'Não é possível excluir o último proprietário do sistema!');
            }
        }

        // Prevent deleting the last admin (apenas se não houver proprietários)
        if ($targetUser->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            $ownerCount = User::where('role', 'proprietario')->count();
            
            // Se não houver proprietários, não pode excluir o último admin
            if ($ownerCount === 0 && $adminCount <= 1) {
                return redirect()->route('users.index')->with('error', 'Não é possível excluir o último administrador!');
            }
        }

        $targetUser->delete();

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
    }
}

