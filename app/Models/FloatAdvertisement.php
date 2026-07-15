<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FloatAdvertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'link',
        'position',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Ad positions
    const POSITION_BOTTOM_LEFT = 'bottom_left';
    const POSITION_BOTTOM_RIGHT = 'bottom_right';
    const POSITION_TOP_LEFT = 'top_left';
    const POSITION_TOP_RIGHT = 'top_right';
    const POSITION_SIDEBAR = 'sidebar';

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeCloseable($query)
    {
        return $query->where('is_closeable', true);
    }
}
