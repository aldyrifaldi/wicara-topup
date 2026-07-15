<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VIPaymentSubProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'code',
        'name',
        'price',
        'vipayment_price',
        'discount',
        'special_discount',
        'vipayment_status',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'vipayment_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'special_discount' => 'decimal:2',
    ];

    // Status values
    const STATUS_AVAILABLE = 'available';
    const STATUS_UNAVAILABLE = 'unavailable';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeAvailable($query)
    {
        return $query->where('vipayment_status', self::STATUS_AVAILABLE);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
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

    public function getProfitAttribute(): float
    {
        return $this->getFinalPriceAttribute() - $this->vipayment_price;
    }

    public function isProfitable(): bool
    {
        return $this->getFinalPriceAttribute() > $this->vipayment_price;
    }

    public function isAvailableForSale(): bool
    {
        return $this->vipayment_status === self::STATUS_AVAILABLE &&
               $this->status === self::STATUS_ACTIVE &&
               $this->isProfitable();
    }
}
