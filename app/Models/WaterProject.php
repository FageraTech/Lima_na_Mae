<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WaterProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'county',
        'status',
        'start_date',
        'completion_date',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'completion_date' => 'date',
        ];
    }

    public function waterSources(): HasMany
    {
        return $this->hasMany(WaterSource::class);
    }
}
