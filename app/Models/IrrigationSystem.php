<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IrrigationSystem extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'water_source_id',
        'type',
        'status',
        'installation_date',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'installation_date' => 'date',
        ];
    }

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }

    public function waterSource(): BelongsTo
    {
        return $this->belongsTo(WaterSource::class);
    }
}
