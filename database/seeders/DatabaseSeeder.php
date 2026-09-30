<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WaterPointAssignment;
use App\Models\WaterPointEvent;
use App\Models\WaterPointReport;
use App\Models\WaterPointSubscription;
use App\Models\WaterProject;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $amina = User::updateOrCreate(
            ['email' => 'amina@limanamae.test'],
            [
                'name' => 'Amina Wanjiku',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );
        $brian = User::updateOrCreate(
            ['email' => 'brian@limanamae.test'],
            [
                'name' => 'Brian Kilonzo',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );
        $grace = User::updateOrCreate(
            ['email' => 'grace@limanamae.test'],
            [
                'name' => 'Grace Njeri',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );

        $kituiProject = WaterProject::updateOrCreate(
            ['name' => 'Ngomeni Community Water Project'],
            [
                'description' => 'Rehabilitating reliable water access for farming households in Ngomeni.',
                'location' => 'Ngomeni',
                'county' => 'Kitui',
                'status' => 'active',
                'start_date' => '2026-01-15',
            ],
        );
        $makueniProject = WaterProject::updateOrCreate(
            ['name' => 'Kathonzweni Water Access Programme'],
            [
                'description' => 'Community water access and smallholder irrigation support across Kathonzweni.',
                'location' => 'Kathonzweni',
                'county' => 'Makueni',
                'status' => 'active',
                'start_date' => '2025-08-01',
            ],
        );
        $machakosProject = WaterProject::updateOrCreate(
            ['name' => 'Yatta Community Water Initiative'],
            [
                'description' => 'Expanding drought-resilient water access for Yatta households and farms.',
                'location' => 'Yatta',
                'county' => 'Machakos',
                'status' => 'planned',
                'start_date' => '2026-10-01',
            ],
        );
        $embuProject = WaterProject::updateOrCreate(
            ['name' => 'Runyenjes Irrigation Support Project'],
            [
                'description' => 'Supporting farmer-led irrigation around the Runyenjes highlands.',
                'location' => 'Runyenjes',
                'county' => 'Embu',
                'status' => 'completed',
                'start_date' => '2024-03-10',
                'completion_date' => '2025-11-20',
            ],
        );

        $ngomeniBorehole = $kituiProject->waterSources()->updateOrCreate(
            ['name' => 'Ngomeni Market Borehole'],
            [
                'type' => 'borehole',
                'location' => 'Ngomeni Market',
                'latitude' => -1.3672,
                'longitude' => 38.0108,
                'estimated_users' => 420,
                'capacity' => 24000,
                'capacity_unit' => 'litres per day',
                'is_operational' => false,
                'lifecycle_status' => 'needs_repair',
                'last_checked_at' => Carbon::now()->subDays(104),
                'notes' => 'Main community borehole serving the market and nearby farms.',
            ],
        );
        $mutomoWell = $kituiProject->waterSources()->updateOrCreate(
            ['name' => 'Mutomo School Well'],
            [
                'type' => 'well',
                'location' => 'Mutomo',
                'latitude' => -2.2761,
                'longitude' => 38.2098,
                'estimated_users' => 160,
                'capacity' => 9000,
                'capacity_unit' => 'litres per day',
                'is_operational' => true,
                'lifecycle_status' => 'operational',
                'last_checked_at' => Carbon::now()->subDays(12),
                'notes' => 'Shared by the primary school and surrounding households.',
            ],
        );
        $kathonzweniTap = $makueniProject->waterSources()->updateOrCreate(
            ['name' => 'Kathonzweni Community Tap'],
            [
                'type' => 'tap',
                'location' => 'Kathonzweni Centre',
                'latitude' => -2.7965,
                'longitude' => 37.5206,
                'estimated_users' => 310,
                'capacity' => 18000,
                'capacity_unit' => 'litres per day',
                'is_operational' => true,
                'lifecycle_status' => 'operational',
                'last_checked_at' => Carbon::now()->subDays(21),
                'notes' => 'Solar-pumped tap serving nearby farms and households.',
            ],
        );
        $yattaDam = $machakosProject->waterSources()->updateOrCreate(
            ['name' => 'Yatta Earth Dam'],
            [
                'type' => 'dam',
                'location' => 'Yatta Plateau',
                'latitude' => -1.3547,
                'longitude' => 37.5732,
                'estimated_users' => 600,
                'capacity' => 950000,
                'capacity_unit' => 'litres',
                'is_operational' => true,
                'lifecycle_status' => 'operational',
                'last_checked_at' => Carbon::now()->subDays(7),
                'notes' => 'Seasonal earth dam planned for expanded community access.',
            ],
        );
        $runyenjesRiver = $embuProject->waterSources()->updateOrCreate(
            ['name' => 'Runyenjes River Intake'],
            [
                'type' => 'river',
                'location' => 'Runyenjes',
                'latitude' => -0.4209,
                'longitude' => 37.5722,
                'estimated_users' => 275,
                'capacity' => 32000,
                'capacity_unit' => 'litres per day',
                'is_operational' => true,
                'lifecycle_status' => 'operational',
                'last_checked_at' => Carbon::now()->subDays(34),
                'notes' => 'Completed intake supporting farmer irrigation groups.',
            ],
        );

        foreach ([$mutomoWell, $kathonzweniTap, $yattaDam, $runyenjesRiver] as $waterPoint) {
            WaterPointEvent::firstOrCreate(
                [
                    'water_source_id' => $waterPoint->id,
                    'type' => 'inspection',
                    'title' => 'Routine inspection recorded',
                ],
                [
                    'user_id' => $amina->id,
                    'description' => 'Water point checked and operating normally.',
                    'metadata' => ['outcome' => 'operational'],
                    'occurred_at' => $waterPoint->last_checked_at ?? now(),
                ],
            );
        }

        WaterPointEvent::firstOrCreate(
            [
                'water_source_id' => $ngomeniBorehole->id,
                'type' => 'maintenance',
                'title' => 'Maintenance work recorded',
            ],
            [
                'user_id' => $brian->id,
                'description' => 'Pump control panel requires replacement after repeated faults.',
                'metadata' => ['technician' => 'Kilonzo Water Services', 'cost' => 18500],
                'occurred_at' => Carbon::now()->subDays(16),
            ],
        );

        $incidentDescriptions = [
            'Pump loses pressure during the afternoon collection period.',
            'Control panel trips when the solar pump starts.',
            'Water flow stops after twenty minutes of operation.',
        ];
        foreach ($incidentDescriptions as $index => $description) {
            WaterPointReport::firstOrCreate(
                ['water_source_id' => $ngomeniBorehole->id, 'type' => 'incident', 'description' => $description],
                [
                    'user_id' => $index === 0 ? $grace->id : $amina->id,
                    'severity' => $index === 2 ? 'critical' : 'medium',
                    'status' => 'open',
                    'reported_at' => Carbon::now()->subDays(12 - ($index * 4)),
                ],
            );
        }
        WaterPointReport::firstOrCreate(
            [
                'water_source_id' => $kathonzweniTap->id,
                'type' => 'incident',
                'description' => 'Tap handle was replaced and water service restored.',
            ],
            [
                'user_id' => $brian->id,
                'severity' => 'medium',
                'status' => 'resolved',
                'reported_at' => Carbon::now()->subDays(36),
                'resolved_at' => Carbon::now()->subDays(29),
            ],
        );
        WaterPointReport::firstOrCreate(
            [
                'water_source_id' => $mutomoWell->id,
                'type' => 'confirmation',
                'description' => 'Community member confirmed that this water point is working.',
            ],
            [
                'user_id' => $grace->id,
                'status' => 'confirmed',
                'reported_at' => Carbon::now()->subDays(3),
            ],
        );

        WaterPointSubscription::updateOrCreate(
            ['water_source_id' => $ngomeniBorehole->id, 'user_id' => $grace->id],
            ['channel' => 'database', 'active' => true],
        );
        WaterPointSubscription::updateOrCreate(
            ['water_source_id' => $kathonzweniTap->id, 'user_id' => $amina->id],
            ['channel' => 'database', 'active' => true],
        );

        WaterPointAssignment::updateOrCreate(
            ['water_source_id' => $ngomeniBorehole->id, 'task' => 'Repair pump control panel'],
            [
                'assigned_by' => $brian->id,
                'technician_name' => 'Kilonzo Water Services',
                'technician_phone' => '+254 712 345 678',
                'status' => 'assigned',
                'due_at' => Carbon::now()->subDays(2),
                'critical' => true,
                'notes' => 'Prioritise before the next market day.',
            ],
        );
        WaterPointAssignment::updateOrCreate(
            ['water_source_id' => $kathonzweniTap->id, 'task' => 'Inspect solar pump output'],
            [
                'assigned_by' => $amina->id,
                'technician_name' => 'Makueni Rural Water Team',
                'technician_phone' => '+254 723 456 789',
                'status' => 'assigned',
                'due_at' => Carbon::now()->addDays(8),
                'critical' => false,
                'notes' => 'Check output before the dry-season planting cycle.',
            ],
        );
    }
}
