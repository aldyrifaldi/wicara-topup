<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'note',
        'type',
    ];

    // Note types
    const TYPE_PRODUCT = 'product';
    const TYPE_JOKI = 'joki';

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
