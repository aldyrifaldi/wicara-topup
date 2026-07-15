<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ApiIntegration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'key',
        'secret',
        'is_active',
        'whitelist_ip',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'whitelist_ip' => 'array',
    ];

    protected $hidden = [
        'secret',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ApiIntegrationLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function isIpAllowed(string $ip): bool
    {
        if (empty($this->whitelist_ip)) {
            return true;
        }

        return in_array($ip, $this->whitelist_ip);
    }

    public static function generateKey(): string
    {
        return 'wic_' . Str::random(32);
    }

    public static function generateSecret(): string
    {
        return Str::random(64);
    }

    public function rotateSecret(): void
    {
        $this->update(['secret' => self::generateSecret()]);
    }
}
