<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPointReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'point_reward_id',
        'points_used',
        'status',
        'exchanged_at',
    ];

    protected $casts = [
        'points_used' => 'integer',
        'exchanged_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pointReward(): BelongsTo
    {
        return $this->belongsTo(PointReward::class);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('exchanged_at', 'desc');
    }
}
