<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WaterPointReport;
use App\Models\WaterPointSubscription;
use App\Models\WaterProject;
use App\Models\WaterSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WaterPointLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_farmer_can_view_the_water_point_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('water-points.index'));

        $response->assertOk();
        $response->assertSee('No water points yet');
    }

    public function test_a_farmer_can_view_a_water_point_history(): void
    {
        $user = User::factory()->create();
        $waterSource = WaterSource::factory()->create();

        $response = $this->actingAs($user)->get(route('water-points.show', $waterSource));

        $response->assertOk();
        $response->assertSee($waterSource->name);
    }

    public function test_a_farmer_can_view_the_operations_hub(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('operations.index'));

        $response->assertOk();
        $response->assertSee('Operations');
        $response->assertSee('Community reports');
    }

    public function test_an_inspection_updates_freshness_and_adds_history(): void
    {
        $user = User::factory()->create();
        $waterSource = WaterSource::factory()->create();

        $response = $this->actingAs($user)->post(route('water-points.inspections.store', $waterSource), [
            'checked_at' => '2026-09-22',
            'outcome' => 'operational',
            'notes' => 'Pump and tap are working normally.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('water_sources', [
            'id' => $waterSource->id,
            'lifecycle_status' => 'operational',
            'last_checked_at' => '2026-09-22 00:00:00',
        ]);
        $this->assertDatabaseHas('water_point_events', [
            'water_source_id' => $waterSource->id,
            'type' => 'inspection',
            'user_id' => $user->id,
        ]);
    }

    public function test_maintenance_is_recorded_without_changing_point_status(): void
    {
        $user = User::factory()->create();
        $waterSource = WaterSource::factory()->create(['lifecycle_status' => 'needs_repair']);

        $response = $this->actingAs($user)->post(route('water-points.maintenance.store', $waterSource), [
            'performed_at' => '2026-09-22',
            'work_done' => 'Replaced the worn valve.',
            'technician' => 'Moses Technician',
            'cost' => '850.00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('water_sources', [
            'id' => $waterSource->id,
            'lifecycle_status' => 'needs_repair',
        ]);
        $this->assertDatabaseHas('water_point_events', [
            'water_source_id' => $waterSource->id,
            'type' => 'maintenance',
        ]);
    }

    public function test_decommissioning_preserves_the_water_point_and_adds_history(): void
    {
        $user = User::factory()->create();
        $waterSource = WaterSource::factory()->create();

        $response = $this->actingAs($user)->post(route('water-points.decommission', $waterSource));

        $response->assertRedirect();
        $this->assertDatabaseHas('water_sources', [
            'id' => $waterSource->id,
            'is_operational' => false,
            'lifecycle_status' => 'decommissioned',
        ]);
        $this->assertDatabaseHas('water_point_events', [
            'water_source_id' => $waterSource->id,
            'type' => 'status_change',
        ]);
    }

    public function test_critical_incidents_notify_active_subscribers(): void
    {
        $reporter = User::factory()->create();
        $subscriber = User::factory()->create();
        $waterSource = WaterSource::factory()->create();
        WaterPointSubscription::factory()->create([
            'water_source_id' => $waterSource->id,
            'user_id' => $subscriber->id,
        ]);

        $response = $this->actingAs($reporter)->post(route('water-points.incidents.store', $waterSource), [
            'severity' => 'critical',
            'description' => 'The pump has stopped and the tap is dry.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('water_point_reports', [
            'water_source_id' => $waterSource->id,
            'severity' => 'critical',
            'status' => 'open',
        ]);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $subscriber->id,
            'notifiable_type' => User::class,
            'type' => 'App\\Notifications\\WaterPointStatusChanged',
        ]);
    }

    public function test_impact_report_exposes_persisted_water_point_metrics(): void
    {
        $user = User::factory()->create();
        $project = WaterProject::factory()->create(['county' => 'Kitui']);
        $waterSource = WaterSource::factory()->create([
            'water_project_id' => $project->id,
            'is_operational' => true,
            'estimated_users' => 180,
        ]);
        WaterPointReport::factory()->create([
            'water_source_id' => $waterSource->id,
            'reported_at' => now()->subDays(10),
        ]);

        $response = $this->actingAs($user)->get(route('impact-reports.index', ['county' => 'Kitui']));

        $response->assertOk();
        $response->assertSee('Kitui');
        $response->assertSee('100%');
        $response->assertSee('180');
    }

    public function test_water_project_management_requires_authentication(): void
    {
        $project = WaterProject::factory()->create();

        $this->get(route('water-projects.index'))->assertRedirect(route('login'));
        $this->delete(route('water-projects.destroy', $project))->assertRedirect(route('login'));
    }
}
