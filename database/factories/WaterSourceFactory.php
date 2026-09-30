<?php

namespace Database\Factories;

use App\Models\WaterProject;
use App\Models\WaterSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WaterSource>
 */
class WaterSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'water_project_id' => WaterProject::factory(),
            'name' => fake()->sentence(2),
            'type' => fake()->randomElement(['borehole', 'dam', 'well', 'river']),
            'location' => fake()->city(),
            'capacity' => fake()->randomFloat(2, 1000, 100000),
            'capacity_unit' => 'litres',
            'is_operational' => fake()->boolean(80),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
