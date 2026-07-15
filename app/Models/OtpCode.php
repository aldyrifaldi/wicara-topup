<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'type',
        'expired_at',
        'is_used',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    // OTP types
    const TYPE_EMAIL = 'email';
    const TYPE_SMS = 'sms';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
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
