<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductInput extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'label',
        'type',
        'placeholder',
        'is_required',
        'options',
        'sort_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'sort_order' => 'integer',
        'options' => 'array',
    ];

    // Input types
    const TYPE_TEXT = 'text';
    const TYPE_NUMBER = 'number';
    const TYPE_EMAIL = 'email';
    const TYPE_SELECT = 'select';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_RADIO = 'radio';
    const TYPE_FILE = 'file';

    // Product types
    const TYPE_PRODUCT = 'product';
    const TYPE_JOKI = 'joki';

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }
}
