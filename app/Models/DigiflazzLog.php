<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigiflazzLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'ref_id',
        'status',
        'message',
        'data',
        'response',
    ];

    protected $casts = [
        'data' => 'array',
        'response' => 'array',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_UNKNOWN = 'unknown';

    public function scopeByOrder($query, $orderCode)
    {
        return $query->where('order_code', $orderCode);
    }

    public function scopeByRefId($query, $refId)
    {
        return $query->where('ref_id', $refId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
