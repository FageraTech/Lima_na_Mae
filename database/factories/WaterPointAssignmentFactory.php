<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WaterPointAssignment;
use App\Models\WaterSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WaterPointAssignment>
 */
class WaterPointAssignmentFactory extends Factory
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
            'assigned_by' => User::factory(),
            'technician_name' => fake()->name(),
            'technician_phone' => fake()->phoneNumber(),
            'task' => 'Inspect and repair water point',
            'status' => 'assigned',
            'due_at' => now()->addDays(7),
            'completed_at' => null,
            'critical' => false,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
