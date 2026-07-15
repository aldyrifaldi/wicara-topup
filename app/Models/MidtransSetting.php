<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MidtransSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'client_key',
        'server_key',
        'is_sandbox',
        'is_active',
    ];

    protected $casts = [
        'is_sandbox' => 'boolean',
        'is_active' => 'boolean',
    ];

    public static function getActiveSettings(): ?self
    {
        return self::where('is_active', true)->first();
    }

    public static function isConfigured(): bool
    {
        $settings = self::getActiveSettings();

        if (!$settings) {
            return false;
        }

        return !empty($settings->merchant_id) &&
               !empty($settings->client_key) &&
               !empty($settings->server_key);
    }

    public function getApiUrlAttribute(): string
    {
        return $this->is_sandbox
            ? 'https://api.sandbox.midtrans.com/v2'
            : 'https://api.midtrans.com/v2';
    }

    public function getSnapUrlAttribute(): string
    {
        return $this->is_sandbox
            ? 'https://app.sandbox.midtrans.com/snap/v1'
            : 'https://app.midtrans.com/snap/v1';
    }
}
