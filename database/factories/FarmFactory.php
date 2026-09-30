<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\Farmer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Farm>
 */
class FarmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'farmer_id' => Farmer::factory(),
            'name' => fake()->optional()->sentence(2),
            'location' => fake()->city(),
            'size' => fake()->randomFloat(2, 1, 100),
            'size_unit' => 'acres',
            'primary_crop' => fake()->randomElement(['maize', 'beans', 'sorghum', 'green grams']),
        ];
    }
}
