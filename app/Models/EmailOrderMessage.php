<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailOrderMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'subject',
        'body',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Order statuses
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    public static function getByStatus(string $status): ?self
    {
        return self::where('status', $status)
                   ->where('is_active', true)
                   ->first();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function compileTemplate(array $data): string
    {
        $body = $this->body;

        foreach ($data as $key => $value) {
            $body = str_replace("{{{$key}}}", $value, $body);
        }

        return $body;
    }
}
