<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'label',
        'value',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Contact types
    const TYPE_PHONE = 'phone';
    const TYPE_EMAIL = 'email';
    const TYPE_ADDRESS = 'address';
    const TYPE_SOCIAL_MEDIA = 'social_media';

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getFormattedValueAttribute(): string
    {
        switch ($this->type) {
            case self::TYPE_EMAIL:
                return "mailto:{$this->value}";
            case self::TYPE_PHONE:
                return "tel:{$this->value}";
            case self::TYPE_SOCIAL_MEDIA:
                return $this->value;
            default:
                return $this->value;
        }
    }
}
