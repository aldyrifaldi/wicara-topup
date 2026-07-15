<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'image',
        'description',
        'type',
        'is_active',
        'is_featured',
        'sort_order',
        'digiflazz_status',
        'digiflazz_name',
        'vipayment_status',
        'vipayment_name',
        'third_product_status',
        'third_product_name',
        'provider_name',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // Product types
    const TYPE_PRODUCT = 'product';
    const TYPE_JOKI = 'joki';

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subProducts(): HasMany
    {
        return $this->hasMany(SubProduct::class)->where('type', self::TYPE_PRODUCT)->orderBy('price');
    }

    public function jokiSubProducts(): HasMany
    {
        return $this->hasMany(SubProduct::class)->where('type', self::TYPE_JOKI)->orderBy('price');
    }

    public function productInputs(): HasMany
    {
        return $this->hasMany(ProductInput::class)->where('type', self::TYPE_PRODUCT);
    }

    public function jokiInputs(): HasMany
    {
        return $this->hasMany(ProductInput::class)->where('type', self::TYPE_JOKI);
    }

    public function productNote(): HasOne
    {
        return $this->hasOne(ProductNote::class)->where('type', self::TYPE_PRODUCT);
    }

    public function jokiNote(): HasOne
    {
        return $this->hasOne(ProductNote::class)->where('type', self::TYPE_JOKI);
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    // Vendor integrations
    public function digiflazzSubProducts(): HasMany
    {
        return $this->hasMany(DigiflazzSubProduct::class);
    }

    public function vipaymentSubProducts(): HasMany
    {
        return $this->hasMany(VIPaymentSubProduct::class)->orderBy('price');
    }

    public function thirdSubProducts(): HasMany
    {
        return $this->hasMany(ThirdSubProduct::class)->orderBy('price');
    }

    // Available products (with price validation)
    public function vipaymentAvailable(): HasMany
    {
        return $this->hasMany(VIPaymentSubProduct::class)
            ->where('vipayment_status', 'available')
            ->where('status', self::STATUS_ACTIVE)
            ->whereRaw('price > vipayment_price')
            ->orderBy('price');
    }

    public function thirdProductAvailable(): HasMany
    {
        return $this->hasMany(ThirdSubProduct::class)
            ->where('provider_status', 'active')
            ->where('status', self::STATUS_ACTIVE)
            ->whereRaw('price > provider_price')
            ->orderBy('price');
    }

    // Helper methods
    public function hasVendorIntegration(): bool
    {
        return $this->digiflazz_status === self::STATUS_ACTIVE ||
               $this->vipayment_status === self::STATUS_ACTIVE ||
               $this->third_product_status === self::STATUS_ACTIVE;
    }

    public function getLowestPriceAttribute(): float
    {
        $available = $this->subProducts()->where('is_active', true);
        return $available->min('price') ?? 0;
    }

    public function getHighestPriceAttribute(): float
    {
        $available = $this->subProducts()->where('is_active', true);
        return $available->max('price') ?? 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
    }
}
