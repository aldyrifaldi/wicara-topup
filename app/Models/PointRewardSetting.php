<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointRewardSetting extends Model
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
    const KEY_POINTS_PER_RUPIAH = 'points_per_rupiah';
    const KEY_POINTS_SIGNUP_BONUS = 'points_signup_bonus';
    const KEY_POINTS_REFERRAL_BONUS = 'points_referral_bonus';
    const KEY_ENABLE_POINTS_EXPIRY = 'enable_points_expiry';
    const KEY_POINTS_EXPIRY_DAYS = 'points_expiry_days';

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

    /**
     * Calculate points from purchase amount
     */
    public static function calculatePoints(float $amount): int
    {
        $pointsPerRupiah = (float) self::get(self::KEY_POINTS_PER_RUPIAH, 0.001);

        return (int) floor($amount * $pointsPerRupiah);
    }

    /**
     * Get signup bonus points
     */
    public static function getSignupBonus(): int
    {
        return (int) self::get(self::KEY_POINTS_SIGNUP_BONUS, 0);
    }

    /**
     * Get referral bonus points
     */
    public static function getReferralBonus(): int
    {
        return (int) self::get(self::KEY_POINTS_REFERRAL_BONUS, 0);
    }
}
