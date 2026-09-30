<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\IrrigationSystem;
use App\Models\WaterSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IrrigationSystem>
 */
class IrrigationSystemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'farm_id' => Farm::factory(),
            'water_source_id' => WaterSource::factory(),
            'type' => fake()->randomElement(['drip', 'sprinkler', 'surface']),
            'status' => fake()->randomElement(['planned', 'active', 'maintenance']),
            'installation_date' => fake()->optional()->date(),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
