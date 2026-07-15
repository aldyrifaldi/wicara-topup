<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigiflazzSubProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'code',
        'name',
        'price',
        'provider_price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'provider_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function getProfitAttribute(): float
    {
        return $this->price - $this->provider_price;
    }

    public function getProfitMarginAttribute(): float
    {
        if ($this->provider_price <= 0) {
            return 0;
        }

        return (($this->price - $this->provider_price) / $this->provider_price) * 100;
    }

    public function isProfitable(): bool
    {
        return $this->price > $this->provider_price;
    }
}
