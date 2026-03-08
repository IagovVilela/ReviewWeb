<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class StoreSetting extends Model
{
    protected $table = 'store_settings';

    protected $fillable = ['key', 'value'];

    public const KEY_NOTIFICATION_EMAIL = 'notification_email';

    /**
     * Retorna o e-mail configurado para receber as solicitações da loja.
     */
    public static function getNotificationEmail(): ?string
    {
        try {
            if (!Schema::hasTable('store_settings')) {
                return null;
            }
            $email = Cache::remember('store_setting_' . self::KEY_NOTIFICATION_EMAIL, 300, function () {
                $row = self::where('key', self::KEY_NOTIFICATION_EMAIL)->first();
                return $row && $row->value ? trim($row->value) : null;
            });
            return $email !== '' ? $email : null;
        } catch (\Throwable $e) {
            Log::warning('StoreSetting::getNotificationEmail failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Define o e-mail que receberá as solicitações da loja.
     */
    public static function setNotificationEmail(?string $email): void
    {
        try {
            if (!Schema::hasTable('store_settings')) {
                return;
            }
            $value = $email !== null && $email !== '' ? trim($email) : null;
            self::updateOrCreate(
                ['key' => self::KEY_NOTIFICATION_EMAIL],
                ['value' => $value]
            );
            Cache::forget('store_setting_' . self::KEY_NOTIFICATION_EMAIL);
        } catch (\Throwable $e) {
            Log::warning('StoreSetting::setNotificationEmail failed', ['error' => $e->getMessage()]);
        }
    }
}
