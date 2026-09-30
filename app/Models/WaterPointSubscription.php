<?php

namespace App\Models;

use Database\Factories\WaterPointSubscriptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterPointSubscription extends Model
{
    /** @use HasFactory<WaterPointSubscriptionFactory> */
    use HasFactory;

    protected $fillable = [
        'water_source_id',
        'user_id',
        'channel',
        'active',
    ];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
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
