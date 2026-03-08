<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
        $email = Cache::remember('store_setting_' . self::KEY_NOTIFICATION_EMAIL, 300, function () {
            $row = self::where('key', self::KEY_NOTIFICATION_EMAIL)->first();
            return $row && $row->value ? trim($row->value) : null;
        });
        return $email !== '' ? $email : null;
    }

    /**
     * Define o e-mail que receberá as solicitações da loja.
     */
    public static function setNotificationEmail(?string $email): void
    {
        $value = $email !== null && $email !== '' ? trim($email) : null;
        self::updateOrCreate(
            ['key' => self::KEY_NOTIFICATION_EMAIL],
            ['value' => $value]
        );
        Cache::forget('store_setting_' . self::KEY_NOTIFICATION_EMAIL);
    }
}
