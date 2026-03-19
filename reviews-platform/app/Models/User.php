<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /** Hierarquia de cargos (maior = mais alto): proprietario > admin > user */
    public const ROLE_PROPRIETARIO = 'proprietario';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'photo',
        'created_by',
        'stripe_customer_id',
        'stripe_subscription_id',
        'subscription_status',
        'payment_required',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'payment_required' => 'boolean',
    ];

    /**
     * Usuário que criou este usuário (admin ou proprietário).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuários criados por este usuário (para admin: só esses podem ser adicionados à empresa).
     */
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    /**
     * Whether the user has an active subscription (and can access dashboard when payment_required).
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === 'active';
    }

    /**
     * Whether the user must pay before accessing the full dashboard.
     */
    public function requiresPayment(): bool
    {
        return (bool) $this->payment_required;
    }

    /**
     * Proprietário = cargo mais alto do sistema (acesso total, pode gerenciar todos os usuários).
     */
    public function isProprietario(): bool
    {
        return strtolower((string) $this->role) === self::ROLE_PROPRIETARIO;
    }

    /**
     * Verifica se é admin (case-insensitive para compatibilidade com dados legados).
     */
    public function isAdmin(): bool
    {
        return strtolower((string) $this->role) === self::ROLE_ADMIN;
    }

    /**
     * Verifica se tem pelo menos nível administrador (admin ou proprietário).
     */
    public function isAtLeastAdmin(): bool
    {
        $role = strtolower((string) $this->role);
        return in_array($role, [self::ROLE_PROPRIETARIO, self::ROLE_ADMIN], true);
    }

    /**
     * Nível do cargo para ordenação (3 = proprietário, 2 = admin, 1 = user).
     */
    public function roleLevel(): int
    {
        return match ($this->role) {
            self::ROLE_PROPRIETARIO => 3,
            self::ROLE_ADMIN => 2,
            default => 1,
        };
    }

    /**
     * Get the companies owned by the user
     */
    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    /**
     * Empresas às quais o usuário tem acesso como membro (não proprietário)
     */
    public function companiesAsMember()
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->withTimestamps();
    }

    /**
     * Verifica se o usuário tem acesso à empresa (proprietário ou membro)
     */
    public function hasAccessTo(Company $company): bool
    {
        if ($company->user_id === $this->id) {
            return true;
        }
        return $company->members()->where('user_id', $this->id)->exists();
    }

    /**
     * Get the photo URL (Cloudinary or local storage)
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo) {
            return null;
        }
        if (str_starts_with($this->photo, 'http')) {
            return $this->photo;
        }
        return asset('storage/' . ltrim(str_replace('storage/', '', $this->photo), '/'));
    }
}
