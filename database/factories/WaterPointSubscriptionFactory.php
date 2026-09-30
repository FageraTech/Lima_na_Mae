<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WaterPointSubscription;
use App\Models\WaterSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WaterPointSubscription>
 */
class WaterPointSubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'water_source_id' => WaterSource::factory(),
            'user_id' => User::factory(),
            'channel' => 'database',
            'active' => true,
        ];
    }
}
