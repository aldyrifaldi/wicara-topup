<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypeBonusAccFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'access_file_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function accessFile(): BelongsTo
    {
        return $this->belongsTo(AccessFile::class);
    }

    public function scopeByType($query, $typeId)
    {
        return $query->where('type_id', $typeId);
    }

    public function scopeByAccessFile($query, $accessFileId)
    {
        return $query->where('access_file_id', $accessFileId);
    }
}
