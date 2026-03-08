<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'items',
        'message',
        'phone',
        'status',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retorna apenas os dígitos do telefone (para armazenamento e WhatsApp).
     */
    public static function phoneToDigits(?string $phone): ?string
    {
        if ($phone === null || $phone === '') {
            return null;
        }
        $digits = preg_replace('/\D/', '', $phone);
        return $digits !== '' ? $digits : null;
    }

    /**
     * Formata o telefone para exibição: (11) 98765-4321
     */
    public function getFormattedPhoneAttribute(): ?string
    {
        $digits = self::phoneToDigits($this->phone);
        if ($digits === null || strlen($digits) < 10) {
            return $this->phone ?: null;
        }
        if (strlen($digits) === 11) {
            return '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 5) . '-' . substr($digits, 7);
        }
        return '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 4) . '-' . substr($digits, 6);
    }

    /**
     * URL do WhatsApp para contato (Brasil: 55 + DDD + número).
     */
    public function getWhatsappUrlAttribute(): ?string
    {
        $digits = self::phoneToDigits($this->phone);
        if ($digits === null || strlen($digits) < 10) {
            return null;
        }
        return 'https://wa.me/55' . $digits;
    }

    public static function statusOptions(): array
    {
        return [
            'pending' => __('store.request_status_pending'),
            'contacted' => __('store.request_status_contacted'),
            'completed' => __('store.request_status_completed'),
            'cancelled' => __('store.request_status_cancelled'),
        ];
    }
}
