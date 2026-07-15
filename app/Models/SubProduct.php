<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'code',
        'type',
        'price',
        'discount',
        'special_discount',
        'is_active',
        'is_unlimited',
        'stock',
        'cashback',
        'bonus',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'special_discount' => 'decimal:2',
        'cashback' => 'decimal:2',
        'is_active' => 'boolean',
        'is_unlimited' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFinalPriceAttribute(): float
    {
        $price = $this->price;

        if ($this->special_discount > 0 && $this->special_discount < $price) {
            return $this->special_discount;
        }

        if ($this->discount > 0 && $this->discount < $price) {
            return $this->discount;
        }

        return $price;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('is_unlimited', true)
              ->orWhere('stock', '>', 0);
        });
    }
}