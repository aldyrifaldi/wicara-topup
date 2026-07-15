<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MidtransLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'transaction_id',
        'transaction_status',
        'payment_type',
        'amount',
        'data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'data' => 'array',
    ];

    // Transaction status constants
    const STATUS_PENDING = 'pending';
    const STATUS_SETTLEMENT = 'settlement';
    const STATUS_CAPTURE = 'capture';
    const STATUS_DENY = 'deny';
    const STATUS_CANCEL = 'cancel';
    const STATUS_EXPIRE = 'expire';
    const STATUS_REFUND = 'refund';
    const STATUS_PARTIAL_REFUND = 'partial_refund';

    public function scopeByOrder($query, $orderCode)
    {
        return $query->where('order_code', $orderCode);
    }

    public function scopeByTransactionId($query, $transactionId)
    {
        return $query->where('transaction_id', $transactionId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('transaction_status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function isPaid(): bool
    {
        return in_array($this->transaction_status, [
            self::STATUS_SETTLEMENT,
            self::STATUS_CAPTURE,
        ]);
    }

    public function isPending(): bool
    {
        return $this->transaction_status === self::STATUS_PENDING;
    }

    public function isFailed(): bool
    {
        return in_array($this->transaction_status, [
            self::STATUS_DENY,
            self::STATUS_CANCEL,
            self::STATUS_EXPIRE,
        ]);
    }
}
