<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    // Setting types
    const TYPE_TEXT = 'text';
    const TYPE_NUMBER = 'number';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_JSON = 'json';
    const TYPE_IMAGE = 'image';

    // Setting groups
    const GROUP_GENERAL = 'general';
    const GROUP_PAYMENT = 'payment';
    const GROUP_EMAIL = 'email';
    const GROUP_SMS = 'sms';
    const GROUP_WHATSAPP = 'whatsapp';
    const GROUP_SOCIAL = 'social';
    const GROUP_FEATURES = 'features';

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if ($setting) {
            return $setting->value;
        }

        return $default;
    }

    public static function set(string $key, $value, string $type = self::TYPE_TEXT, string $group = self::GROUP_GENERAL): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );
    }

    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
