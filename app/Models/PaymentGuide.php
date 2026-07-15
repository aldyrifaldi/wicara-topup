<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'payment_method',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Payment methods
    const METHOD_MIDTRANS = 'midtrans';
    const METHOD_IPAYMU = 'ipaymu';
    const METHOD_VIPAYMENT = 'vipayment';
    const METHOD_DIGIFLAZZ = 'digiflazz';

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
