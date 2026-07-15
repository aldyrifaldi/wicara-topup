<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'method',
        'is_active',
        'config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
    ];

    // Payment methods
    const METHOD_BALANCE = 'balance';
    const METHOD_MIDTRANS = 'midtrans';
    const METHOD_IPAYMU = 'ipaymu';
    const METHOD_VIPAYMENT = 'vipayment';
    const METHOD_DIGIFLAZZ = 'digiflazz';

    public static function getActiveMethods(): array
    {
        return self::where('is_active', true)->pluck('method')->toArray();
    }

    public static function isMethodActive(string $method): bool
    {
        return self::where('method', $method)
                   ->where('is_active', true)
                   ->exists();
    }

    public static function getConfig(string $method): ?self
    {
        return self::where('method', $method)->first();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getMethodLabelAttribute(): string
    {
        $labels = [
            self::METHOD_BALANCE => 'Balance',
            self::METHOD_MIDTRANS => 'Midtrans',
            self::METHOD_IPAYMU => 'Ipaymu',
            self::METHOD_VIPAYMENT => 'VIPayment',
            self::METHOD_DIGIFLAZZ => 'Digiflazz',
        ];

        return $labels[$this->method] ?? $this->method;
    }
}
