<?php

namespace App\Models;

use Database\Factories\WaterPointReportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterPointReport extends Model
{
    /** @use HasFactory<WaterPointReportFactory> */
    use HasFactory;

    protected $fillable = [
        'water_source_id',
        'user_id',
        'type',
        'severity',
        'status',
        'description',
        'reported_at',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'reported_at' => 'datetime',
            'resolved_at' => 'datetime',
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
}
