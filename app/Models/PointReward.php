<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PointReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'points_required',
        'type',
        'image',
        'is_active',
    ];

    protected $casts = [
        'points_required' => 'integer',
        'is_active' => 'boolean',
    ];

    // Reward types
    const TYPE_DISCOUNT = 'discount';
    const TYPE_CASHBACK = 'cashback';
    const TYPE_PRODUCT = 'product';
    const TYPE_BALANCE = 'balance';
    const TYPE_FILE_ACCESS = 'file_access';

    public function userPointRewards(): HasMany
    {
        return $this->hasMany(UserPointReward::class);
    }

    public function exchangeRequests(): HasMany
    {
        return $this->hasMany(ExchangePointReward::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrderByPoints($query)
    {
        return $query->orderBy('points_required');
    }
}
