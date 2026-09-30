<?php

namespace Database\Factories;

use App\Models\WaterProject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WaterProject>
 */
class WaterProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'location' => fake()->city(),
            'county' => fake()->randomElement(['Kitui', 'Makueni', 'Machakos', 'Embu']),
            'status' => fake()->randomElement(['planned', 'active', 'completed']),
            'start_date' => fake()->optional()->date(),
            'completion_date' => fake()->optional()->date(),
        ];
    }
}
