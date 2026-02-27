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
    ];

    /**
     * Quem criou este usuário
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuários criados por este usuário
     */
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
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
