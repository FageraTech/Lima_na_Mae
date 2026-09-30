<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WaterPointEvent;
use App\Models\WaterSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WaterPointEvent>
 */
class WaterPointEventFactory extends Factory
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
            'type' => fake()->randomElement(['inspection', 'maintenance', 'status_change']),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'metadata' => null,
            'occurred_at' => now(),
        ];
    }
}
