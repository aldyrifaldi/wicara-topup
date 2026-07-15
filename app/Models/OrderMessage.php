<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'message',
        'is_from_admin',
        'is_read',
    ];

    protected $casts = [
        'is_from_admin' => 'boolean',
        'is_read' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeFromAdmin($query)
    {
        return $query->where('is_from_admin', true);
    }

    public function scopeFromUser($query)
    {
        return $query->where('is_from_admin', false);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }
}
