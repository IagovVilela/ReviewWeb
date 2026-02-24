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
        $base = rtrim(config('app.public_url', config('app.url')), '/');
        $url = $this->url ? trim($this->url) : '';
        if ($url !== '') {
            return $base . '/' . $url;
        }
        return $base . '/r/' . $this->token;
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
        if (str_starts_with($this->logo, 'http')) {
            return $this->logo;
        }
        $logoPath = ltrim($this->logo, '/');
        $logoPath = str_replace('storage/', '', $logoPath);
        return asset('storage/' . $logoPath);
    }

    public function getFullLogoUrlAttribute()
    {
        if (!$this->logo) {
            return null;
        }
        if (str_starts_with($this->logo, 'http')) {
            return $this->logo;
        }
        $appUrl = rtrim(config('app.url'), '/');
        if (config('app.env') === 'production' && str_starts_with($appUrl, 'http://') && !str_contains($appUrl, 'localhost')) {
            $appUrl = str_replace('http://', 'https://', $appUrl);
        }
        return $appUrl . '/storage/' . $this->logo;
    }

    public function getBackgroundImageUrlAttribute()
    {
        if (!$this->background_image || $this->background_image === '' || $this->background_image === 'null') {
            return null;
        }
        if (str_starts_with($this->background_image, 'http')) {
            return $this->background_image;
        }
        $bgPath = ltrim($this->background_image, '/');
        $bgPath = str_replace('storage/', '', $bgPath);
        if (!Storage::disk('public')->exists($bgPath)) {
            return null;
        }
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
