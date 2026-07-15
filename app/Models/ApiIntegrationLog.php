<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiIntegrationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_integration_id',
        'endpoint',
        'method',
        'request',
        'response',
        'status_code',
        'ip_address',
    ];

    protected $casts = [
        'request' => 'array',
        'response' => 'array',
        'status_code' => 'integer',
    ];

    public function apiIntegration(): BelongsTo
    {
        return $this->belongsTo(ApiIntegration::class);
    }

    public function scopeByIntegration($query, $integrationId)
    {
        return $query->where('api_integration_id', $integrationId);
    }

    public function scopeByEndpoint($query, $endpoint)
    {
        return $query->where('endpoint', $endpoint);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status_code', '>=', 200)
                     ->where('status_code', '<', 300);
    }

    public function scopeFailed($query)
    {
        return $query->where('status_code', '>=', 400);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function isSuccess(): bool
    {
        return $this->status_code >= 200 && $this->status_code < 300;
    }

    public function isClientError(): bool
    {
        return $this->status_code >= 400 && $this->status_code < 500;
    }

    public function isServerError(): bool
    {
        return $this->status_code >= 500;
    }
}
