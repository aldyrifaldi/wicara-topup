<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level_id',
        'points',
        'next_level_points',
        'is_active',
    ];

    protected $casts = [
        'points' => 'integer',
        'next_level_points' => 'integer',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function canUpgradeToNextLevel(): bool
    {
        return $this->points >= $this->next_level_points;
    }

    public function getProgressPercentage(): float
    {
        if ($this->next_level_points <= 0) {
            return 100;
        }

        return min(100, ($this->points / $this->next_level_points) * 100);
    }
}
