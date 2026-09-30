<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WaterPointReport;
use App\Models\WaterSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WaterPointReport>
 */
class WaterPointReportFactory extends Factory
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
            'type' => 'incident',
            'severity' => fake()->randomElement(['low', 'medium', 'critical']),
            'status' => 'open',
            'description' => fake()->sentence(),
            'reported_at' => now(),
            'resolved_at' => null,
        ];
    }
}
