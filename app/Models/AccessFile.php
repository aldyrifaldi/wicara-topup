<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccessFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'file_path',
        'price',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function orderFiles(): HasMany
    {
        return $this->hasMany(OrderAccFile::class);
    }

    public function userAccessFiles(): HasMany
    {
        return $this->hasMany(UserAccFile::class);
    }

    public function typeBonuses(): HasMany
    {
        return $this->hasMany(TypeBonusAccFile::class);
    }

    public function levelBonuses(): HasMany
    {
        return $this->hasMany(LevelBonusAccFile::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrderByPrice($query, $direction = 'asc')
    {
        return $query->orderBy('price', $direction);
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.');
    }
}
