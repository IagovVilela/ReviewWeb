<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Retorna os IDs de todas as empresas acessíveis pelo usuário autenticado
     * (empresas que ele criou + empresas das quais é membro).
     */
    private function getAccessibleCompanyIds(): \Illuminate\Support\Collection
    {
        $user = Auth::user();
        $owned   = $user->companies()->pluck('id');
        $member  = $user->companiesAsMember()->pluck('companies.id');
        return $owned->merge($member)->unique()->values();
    }

    /**
     * Verifica se o admin autenticado pode gerenciar o usuário-alvo.
     */
    private function canManageUser(User $targetUser): bool
    {
        $currentUser = Auth::user();

        if ($currentUser->role === 'proprietario') {
            return true;
        }

        if ($currentUser->role === 'admin') {
            if ($targetUser->role !== 'user') {
                return false;
            }

            // Criou diretamente
            if ($targetUser->created_by === $currentUser->id) {
                return true;
            }

            // Pertence a uma das empresas do admin
            $companyIds = $this->getAccessibleCompanyIds();
            if ($companyIds->isNotEmpty()) {
                return $targetUser->companiesAsMember()
                    ->whereIn('companies.id', $companyIds)
                    ->exists();
            }

            return false;
        }

        return false;
    }

    /**
     * Roles que o usuário autenticado pode atribuir.
     */
    private function getAllowedRoles(): array
    {
        $currentUser = Auth::user();

        if ($currentUser->role === 'proprietario') {
            return ['proprietario', 'admin', 'user'];
        }

        if ($currentUser->role === 'admin') {
            return ['user'];
        }

        return [];
    }

    /**
     * Listagem de usuários com escopo por permissão.
     */
    public function index()
    {
        $currentUser = Auth::user();

        if ($currentUser->role === 'proprietario') {
            $users = User::orderByRaw("CASE role WHEN 'proprietario' THEN 1 WHEN 'admin' THEN 2 WHEN 'user' THEN 3 END")
                         ->orderBy('created_at', 'desc')
                         ->get();
        } else {
            // Admin: somente usuários 'user' que ele criou OU que pertencem às suas empresas
            $companyIds = $this->getAccessibleCompanyIds();

            $users = User::where('role', 'user')
                ->where(function ($q) use ($currentUser, $companyIds) {
                    $q->where('created_by', $currentUser->id);
                    if ($companyIds->isNotEmpty()) {
                        $q->orWhereHas('companiesAsMember', function ($inner) use ($companyIds) {
                            $inner->whereIn('companies.id', $companyIds);
                        });
                    }
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * Formulário de criação.
     */
    public function create()
    {
        $currentUser = Auth::user();

        if ($currentUser->role === 'proprietario') {
            $companies = Company::orderBy('name')->get();
        } else {
            $companyIds = $this->getAccessibleCompanyIds();
            $companies  = Company::whereIn('id', $companyIds)->orderBy('name')->get();
        }

        return view('admin.users.create', compact('companies'));
    }

    /**
     * Persistir novo usuário.
     */
    public function store(Request $request)
    {
        $currentUser  = Auth::user();
        $allowedRoles = $this->getAllowedRoles();

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:6|confirmed',
            'role'       => ['required', Rule::in($allowedRoles)],
            'company_id' => 'nullable|exists:companies,id',
        ]);

        if ($currentUser->role === 'admin' && in_array($request->role, ['admin', 'proprietario'])) {
            return redirect()->route('users.create')
                ->withErrors(['role' => 'Você não tem permissão para criar este tipo de usuário.'])
                ->withInput();
        }

        // Valida se a empresa escolhida pertence ao admin
        if ($currentUser->role === 'admin' && $request->filled('company_id')) {
            $companyIds = $this->getAccessibleCompanyIds();
            if (!$companyIds->contains((int) $request->company_id)) {
                return redirect()->route('users.create')
                    ->withErrors(['company_id' => 'Você não tem acesso a esta empresa.'])
                    ->withInput();
            }
        }

        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'created_by' => $currentUser->id,
        ]);

        if ($request->filled('company_id')) {
            $user->companiesAsMember()->attach($request->company_id);
        }

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Formulário de edição.
     */
    public function edit($id)
    {
        $targetUser = User::findOrFail($id);

        if (!$this->canManageUser($targetUser)) {
            abort(403, 'Você não tem permissão para editar este usuário.');
        }

        $currentUser = Auth::user();

        if ($currentUser->role === 'proprietario') {
            $companies = Company::orderBy('name')->get();
        } else {
            $companyIds = $this->getAccessibleCompanyIds();
            $companies  = Company::whereIn('id', $companyIds)->orderBy('name')->get();
        }

        // Empresa atualmente atribuída ao usuário (primeira encontrada dentro do escopo)
        $assignedCompanyId = $targetUser->companiesAsMember()
            ->when($currentUser->role === 'admin', function ($q) {
                $q->whereIn('companies.id', $this->getAccessibleCompanyIds());
            })
            ->value('companies.id');

        return view('admin.users.edit', compact('targetUser', 'companies', 'assignedCompanyId'));
    }

    /**
     * Atualizar usuário.
     */
    public function update(Request $request, $id)
    {
        $targetUser  = User::findOrFail($id);
        $currentUser = Auth::user();

        if (!$this->canManageUser($targetUser)) {
            abort(403, 'Você não tem permissão para editar este usuário.');
        }

        $roleValidation = $currentUser->role === 'proprietario'
            ? ['required', Rule::in(['proprietario', 'admin', 'user'])]
            : ['required', Rule::in(['user'])];

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($targetUser->id)],
            'password'   => 'nullable|string|min:6|confirmed',
            'role'       => $roleValidation,
            'company_id' => 'nullable|exists:companies,id',
        ]);

        if ($currentUser->role === 'admin' && $request->role !== 'user') {
            return redirect()->route('users.edit', $id)
                ->withErrors(['role' => 'Você não tem permissão para alterar para este tipo de usuário.'])
                ->withInput();
        }

        // Valida se a empresa escolhida pertence ao admin
        if ($currentUser->role === 'admin' && $request->filled('company_id')) {
            $companyIds = $this->getAccessibleCompanyIds();
            if (!$companyIds->contains((int) $request->company_id)) {
                return redirect()->route('users.edit', $id)
                    ->withErrors(['company_id' => 'Você não tem acesso a esta empresa.'])
                    ->withInput();
            }
        }

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($currentUser->role === 'proprietario' || $request->role === 'user') {
            $data['role'] = $request->role;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $targetUser->update($data);

        // Sincronizar empresa (apenas dentro do escopo do admin)
        if ($currentUser->role === 'admin') {
            $companyIds = $this->getAccessibleCompanyIds();
            // Remove associações antigas dentro do escopo do admin
            $targetUser->companiesAsMember()->detach($companyIds->toArray());
            if ($request->filled('company_id') && $companyIds->contains((int) $request->company_id)) {
                $targetUser->companiesAsMember()->attach($request->company_id);
            }
        } elseif ($currentUser->role === 'proprietario') {
            // Proprietário pode reatribuir livremente
            if ($request->has('company_id')) {
                if ($request->filled('company_id')) {
                    $targetUser->companiesAsMember()->syncWithoutDetaching([$request->company_id]);
                }
            }
        }

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Excluir usuário.
     */
    public function destroy($id)
    {
        $targetUser  = User::findOrFail($id);
        $currentUser = Auth::user();

        if ($targetUser->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Você não pode excluir sua própria conta!');
        }

        if (!$this->canManageUser($targetUser)) {
            abort(403, 'Você não tem permissão para excluir este usuário.');
        }

        if ($targetUser->role === 'proprietario') {
            $ownerCount = User::where('role', 'proprietario')->count();
            if ($ownerCount <= 1) {
                return redirect()->route('users.index')->with('error', 'Não é possível excluir o último proprietário do sistema!');
            }
        }

        if ($targetUser->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            $ownerCount = User::where('role', 'proprietario')->count();
            if ($ownerCount === 0 && $adminCount <= 1) {
                return redirect()->route('users.index')->with('error', 'Não é possível excluir o último administrador!');
            }
        }

        $targetUser->delete();

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
    }
}
