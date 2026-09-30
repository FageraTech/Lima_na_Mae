<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WaterSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'water_project_id',
        'name',
        'type',
        'location',
        'latitude',
        'longitude',
        'estimated_users',
        'capacity',
        'capacity_unit',
        'is_operational',
        'lifecycle_status',
        'last_checked_at',
        'verified_at',
        'decommissioned_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'decimal:2',
            'is_operational' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'last_checked_at' => 'datetime',
            'verified_at' => 'datetime',
            'decommissioned_at' => 'datetime',
        ];
    }

    public function waterProject(): BelongsTo
    {
        return $this->belongsTo(WaterProject::class);
    }

    public function irrigationSystems(): HasMany
    {
        return $this->hasMany(IrrigationSystem::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(WaterPointEvent::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(WaterPointReport::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(WaterPointSubscription::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(WaterPointAssignment::class);
    }
}
