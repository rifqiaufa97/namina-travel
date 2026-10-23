<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DestinationImage extends Model
{
    protected $fillable = [
        'destination_id',
        'path',
        'is_primary',
        'order'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
