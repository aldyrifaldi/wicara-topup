<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'account_number',
        'account_holder',
        'logo',
        'is_active',
        'payment_method',
        'admin_fee',
        'admin_fee_type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'admin_fee' => 'decimal:2',
    ];

    // Admin fee types
    const FEE_TYPE_FIXED = 'fixed';
    const FEE_TYPE_PERCENTAGE = 'percentage';

    // Payment methods
    const PAYMENT_MIDTRANS = 'midtrans';
    const PAYMENT_IPAYMU = 'ipaymu';
    const PAYMENT_VIPAYMENT = 'vipayment';
    const PAYMENT_DIGIFLAZZ = 'digiflazz';

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function calculateFee(float $amount): float
    {
        if ($this->admin_fee_type === self::FEE_TYPE_PERCENTAGE) {
            return $amount * ($this->admin_fee / 100);
        }

        return $this->admin_fee;
    }

    public function getMaskedAccountNumberAttribute(): string
    {
        $number = $this->account_number;
        $length = strlen($number);

        if ($length <= 4) {
            return $number;
        }

        return substr($number, 0, 2) . str_repeat('*', $length - 4) . substr($number, -2);
    }
}
