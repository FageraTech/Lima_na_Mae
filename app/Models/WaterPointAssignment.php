<?php

namespace App\Models;

use Database\Factories\WaterPointAssignmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterPointAssignment extends Model
{
    /** @use HasFactory<WaterPointAssignmentFactory> */
    use HasFactory;

    protected $fillable = [
        'water_source_id',
        'assigned_by',
        'technician_name',
        'technician_phone',
        'task',
        'status',
        'due_at',
        'completed_at',
        'critical',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'completed_at' => 'datetime',
            'critical' => 'boolean',
        ];
    }

    public function waterSource(): BelongsTo
    {
        return $this->belongsTo(WaterSource::class);
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
