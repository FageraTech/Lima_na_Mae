<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WaterSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IrrigationPlannerTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_farmer_can_generate_a_plain_language_plan(): void
    {
        $user = User::factory()->create();
        $waterSource = WaterSource::factory()->create(['name' => 'Kathonzweni borehole', 'capacity' => 30000]);

        $response = $this->actingAs($user)->post(route('irrigation-planner.store'), [
            'crop' => 'maize',
            'acreage' => 1,
            'soil' => 'loam',
            'water_source_id' => $waterSource->id,
        ]);

        $response->assertOk();
        $response->assertSee('179,411 litres each week');
        $response->assertSee('Kathonzweni borehole');
        $response->assertSee('Weekly watering rhythm');
    }

    public function test_the_planner_requires_valid_field_inputs(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from(route('irrigation-planner.create'))->post(route('irrigation-planner.store'), []);

        $response->assertRedirect(route('irrigation-planner.create'));
        $response->assertSessionHasErrors(['crop', 'acreage', 'soil', 'water_source_id']);
    }
}
