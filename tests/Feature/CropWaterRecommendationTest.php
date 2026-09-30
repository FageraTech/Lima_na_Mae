<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CropWaterRecommendationTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_farmer_can_generate_crop_and_water_recommendations(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('crop-water-recommendations.store'), [
            'location' => 'makueni',
            'rainfall' => 'low',
            'resources' => 'limited',
        ]);

        $response->assertOk();
        $response->assertSee('Sorghum');
        $response->assertSee('Zai pits');
        $response->assertSee('Sand dams');
    }

    public function test_the_recommendation_form_requires_all_context_inputs(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from(route('crop-water-recommendations.create'))->post(route('crop-water-recommendations.store'), []);

        $response->assertRedirect(route('crop-water-recommendations.create'));
        $response->assertSessionHasErrors(['location', 'rainfall', 'resources']);
    }
}
