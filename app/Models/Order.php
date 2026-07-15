<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'type',
        'status',
        'payment_status',
        'payment_method',
        'total_amount',
        'discount_amount',
        'final_amount',
        'bank_id',
        'payment_gateway',
        'payment_reference',
        'payment_url',
        'payment_expiry',
        'notes',
        'admin_notes',
        'is_refund',
        'refund_reason',
        'completed_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'completed_at' => 'datetime',
        'is_refund' => 'boolean',
    ];

    // Order types
    const TYPE_PRODUCT = 'product';
    const TYPE_JOKI = 'joki';
    const TYPE_BALANCE = 'balance';
    const TYPE_UPGRADE_ACCOUNT = 'upgrade_account';
    const TYPE_UPGRADE_LEVEL = 'upgrade_level';
    const TYPE_FILE_ACCESS = 'file_access';
    const TYPE_INVITE_USER = 'invite_user';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    // Payment methods
    const PAYMENT_BALANCE = 'balance';
    const PAYMENT_MIDTRANS = 'midtrans';
    const PAYMENT_IPAYMU = 'ipaymu';
    const PAYMENT_VIPAYMENT = 'vipayment';
    const PAYMENT_DIGIFLAZZ = 'digiflazz';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderProduct(): HasOne
    {
        return $this->hasOne(OrderProduct::class);
    }

    public function orderJoki(): HasOne
    {
        return $this->hasOne(OrderJoki::class);
    }

    public function orderBalance(): HasOne
    {
        return $this->hasOne(OrderBalance::class);
    }

    public function orderUpgradeAccount(): HasOne
    {
        return $this->hasOne(OrderUpgradeAccount::class);
    }

    public function orderUpgradeLevel(): HasOne
    {
        return $this->hasOne(OrderUpgradeLevel::class);
    }

    public function orderFileAccess(): HasOne
    {
        return $this->hasOne(OrderAccFile::class);
    }

    public function orderInviteUser(): HasOne
    {
        return $this->hasOne(OrderInviteUser::class);
    }

    public function orderMessages(): HasMany
    {
        return $this->hasMany(OrderMessage::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    // Helper methods
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isUnpaid(): bool
    {
        return $this->payment_status === 'unpaid';
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        $methods = [
            self::PAYMENT_BALANCE => 'Balance',
            self::PAYMENT_MIDTRANS => 'Midtrans',
            self::PAYMENT_IPAYMU => 'Ipaymu',
            self::PAYMENT_VIPAYMENT => 'VIPayment',
            self::PAYMENT_DIGIFLAZZ => 'Digiflazz',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}