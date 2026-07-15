<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThirdProductLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'ref_id',
        'provider_name',
        'status',
        'message',
        'data',
        'response',
        'is_success',
    ];

    protected $casts = [
        'data' => 'array',
        'response' => 'array',
        'is_success' => 'boolean',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

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

    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider_name', $provider);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeSuccessful($query)
    {
        return $query->where('is_success', true);
    }

    public function scopeFailed($query)
    {
        return $query->where('is_success', false);
    }
}
