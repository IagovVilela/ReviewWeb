<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'url',
        'slug',
        'token',
        'logo',
        'background_image',
        'negative_email',
        'contact_number',
        'business_website',
        'business_address',
        'google_business_url',
        'positive_score',
        'is_active',
        'status'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'positive_score' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name);
            }
            if (empty($company->token)) {
                $company->token = 'review_' . Str::random(20);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function reviewPages()
    {
        return $this->hasMany(ReviewPage::class);
    }

    public function getPublicUrlAttribute()
    {
        $url = $this->url ? trim($this->url) : '';
        if ($url !== '') {
            return url('/' . $url);
        }
        return url('/r/' . $this->token);
    }

    public function getPositiveReviewsCountAttribute()
    {
        return $this->reviews()->where('is_positive', true)->count();
    }

    public function getNegativeReviewsCountAttribute()
    {
        return $this->reviews()->where('is_positive', false)->count();
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return null;
        }
        
        // Limpar o caminho se tiver barras duplas ou prefixos desnecessários
        $logoPath = ltrim($this->logo, '/');
        $logoPath = str_replace('storage/', '', $logoPath);
        
        // Retorna URL usando asset() que é mais confiável
        return asset('storage/' . $logoPath);
    }
    
    /**
     * Retorna URL completa da logo incluindo APP_URL
     * Garante HTTPS em produção
     */
    public function getFullLogoUrlAttribute()
    {
        if (!$this->logo) {
            return null;
        }
        
        $appUrl = rtrim(config('app.url'), '/');
        
        // Garantir HTTPS em produção (Railway)
        if (config('app.env') === 'production' && 
            str_starts_with($appUrl, 'http://') && 
            !str_contains($appUrl, 'localhost')) {
            $appUrl = str_replace('http://', 'https://', $appUrl);
        }
        
        return $appUrl . '/storage/' . $this->logo;
    }

    public function getBackgroundImageUrlAttribute()
    {
        // Verificar se background_image é null, vazio ou string "null"
        if (!$this->background_image || $this->background_image === '' || $this->background_image === 'null') {
            return null;
        }
        
        // Limpar o caminho se tiver barras duplas ou prefixos desnecessários
        $bgPath = ltrim($this->background_image, '/');
        $bgPath = str_replace('storage/', '', $bgPath);
        
        // Verificar se o arquivo realmente existe antes de retornar a URL
        if (!Storage::disk('public')->exists($bgPath)) {
            return null;
        }
        
        // Retorna URL usando asset() que é mais confiável
        return asset('storage/' . $bgPath);
    }

    public function getGoogleMapsUrlAttribute()
    {
        if (!$this->business_address) {
            return null;
        }
        
        $address = urlencode($this->business_address);
        return "https://www.google.com/maps/search/?api=1&query={$address}";
    }
}
