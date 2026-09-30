<?php

namespace App\Models;

use Database\Factories\WaterPointEventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterPointEvent extends Model
{
    protected $fillable = [
        'water_source_id',
        'user_id',
        'type',
        'title',
        'description',
        'metadata',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'occurred_at' => 'datetime',
        ];
    }

    public function waterSource(): BelongsTo
    {
        return $this->belongsTo(WaterSource::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @use HasFactory<WaterPointEventFactory> */
    use HasFactory;
}
