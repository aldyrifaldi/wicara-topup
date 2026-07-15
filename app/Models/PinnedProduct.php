<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PinnedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public static function isPinned(int $userId, int $productId): bool
    {
        return self::where('user_id', $userId)
                   ->where('product_id', $productId)
                   ->exists();
    }

    public static function pin(int $userId, int $productId): self
    {
        return self::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    public static function unpin(int $userId, int $productId): void
    {
        self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();
    }

    public static function toggle(int $userId, int $productId): bool
    {
        if (self::isPinned($userId, $productId)) {
            self::unpin($userId, $productId);
            return false;
        }

        self::pin($userId, $productId);
        return true;
    }
}
