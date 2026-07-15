<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'amount',
        'payment_method',
        'unique_code',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'unique_code' => 'integer',
    ];

    // Payment methods
    const PAYMENT_MIDTRANS = 'midtrans';
    const PAYMENT_IPAYMU = 'ipaymu';
    const PAYMENT_VIPAYMENT = 'vipayment';
    const PAYMENT_DIGIFLAZZ = 'digiflazz';

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFinalAmountAttribute(): float
    {
        return $this->amount + $this->unique_code;
    }
}
