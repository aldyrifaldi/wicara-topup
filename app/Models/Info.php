<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Info types
    const TYPE_INFO = 'info';
    const TYPE_WARNING = 'warning';
    const TYPE_MAINTENANCE = 'maintenance';

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeMaintenance($query)
    {
        return $query->where('type', self::TYPE_MAINTENANCE)
                    ->where('is_active', true);
    }

    public static function isMaintenanceMode(): bool
    {
        return self::maintenance()->exists();
    }

    public function getInfoTypeIconAttribute(): string
    {
        $icons = [
            self::TYPE_INFO => 'information-circle',
            self::TYPE_WARNING => 'exclamation-triangle',
            self::TYPE_MAINTENANCE => 'tools',
        ];

        return $icons[$this->type] ?? 'information-circle';
    }
}
