<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelBonusAccFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_id',
        'access_file_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function accessFile(): BelongsTo
    {
        return $this->belongsTo(AccessFile::class);
    }

    public function scopeByLevel($query, $levelId)
    {
        return $query->where('level_id', $levelId);
    }

    public function scopeByAccessFile($query, $accessFileId)
    {
        return $query->where('access_file_id', $accessFileId);
    }
}
