<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'name',
        'location',
        'size',
        'size_unit',
        'primary_crop',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'decimal:2',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function irrigationSystems(): HasMany
    {
        return $this->hasMany(IrrigationSystem::class);
    }
}
