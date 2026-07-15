<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderJoki extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'sub_product_id',
        'user_id',
        'player_id',
        'server_id',
        'login_via',
        'contact_whatsapp',
        'rank_from',
        'rank_to',
        'price',
        'total',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Login via options
    const LOGIN_VIA_MOBILE = 'mobile';
    const LOGIN_VIA_PC = 'pc';
    const LOGIN_VIA_RDP = 'rdp';

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function subProduct(): BelongsTo
    {
        return $this->belongsTo(SubProduct::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedPlayerInfoAttribute(): string
    {
        return "ID: {$this->player_id} | Server: {$this->server_id}";
    }

    public function getRankProgressAttribute(): string
    {
        if ($this->rank_from && $this->rank_to) {
            return "{$this->rank_from} → {$this->rank_to}";
        }

        return $this->rank_from ?? $this->rank_to ?? '-';
    }
}
