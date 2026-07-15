<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiIntegrationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Common setting keys
    const KEY_MAX_REQUESTS_PER_MINUTE = 'max_requests_per_minute';
    const KEY_ENABLE_IP_WHITELIST = 'enable_ip_whitelist';
    const KEY_ENABLE_LOGGING = 'enable_logging';
    const KEY_LOG_RETENTION_DAYS = 'log_retention_days';

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if ($setting && $setting->is_active) {
            return $setting->value;
        }

        return $default;
    }

    public static function set(string $key, $value, bool $isActive = true): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'is_active' => $isActive,
            ]
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
