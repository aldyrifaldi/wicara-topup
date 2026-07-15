<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Refferal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'referrer_id',
        'code',
        'commission',
    ];

    protected $casts = [
        'commission' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function scopeByReferrer($query, $referrerId)
    {
        return $query->where('referrer_id', $referrerId);
    }

    public function scopeByReferred($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = 'REF' . strtoupper(Str::random(6));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public static function findByCode(string $code): ?self
    {
        return self::where('code', $code)->first();
    }

    public function isValid(): bool
    {
        return $this->referrer_id && $this->referrer->is_active;
    }
}
