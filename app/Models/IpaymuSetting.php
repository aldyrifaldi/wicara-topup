<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpaymuSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_key',
        'virtual_account',
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

        return !empty($settings->api_key);
    }

    public function getApiUrlAttribute(): string
    {
        return $this->is_sandbox
            ? 'https://sandbox.ipaymu.com/api'
            : 'https://my.ipaymu.com/api';
    }
}
