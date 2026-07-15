<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ResetPassword extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'expired_at',
        'is_used',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }

    public function scopeValid($query)
    {
        return $query->where('is_used', false)
                     ->where('expired_at', '>', now());
    }

    public static function createForUser(int $userId): self
    {
        return self::create([
            'user_id' => $userId,
            'token' => Str::random(60),
            'expired_at' => now()->addHours(24),
            'is_used' => false,
        ]);
    }

    public static function findByToken(string $token): ?self
    {
        return self::where('token', $token)->first();
    }

    public function isValid(): bool
    {
        return !$this->is_used && $this->expired_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->expired_at->isPast();
    }

    public function markAsUsed(): void
    {
        $this->update(['is_used' => true]);
    }
}
