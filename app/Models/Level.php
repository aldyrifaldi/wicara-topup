<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'min_points',
        'discount_percent',
        'cashback_percent',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'min_points' => 'integer',
        'discount_percent' => 'decimal:2',
        'cashback_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function userLevels(): HasMany
    {
        return $this->hasMany(UserLevel::class);
    }

    public function features(): HasMany
    {
        return $this->hasMany(LevelFeature::class);
    }

    public function bonusAccessFiles(): HasMany
    {
        return $this->hasMany(LevelBonusAccFile::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}