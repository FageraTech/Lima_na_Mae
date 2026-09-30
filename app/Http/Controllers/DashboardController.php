<?php

namespace App\Http\Controllers;

use App\Models\WaterPointAssignment;
use App\Models\WaterPointReport;
use App\Models\WaterProject;
use App\Models\WaterSource;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'projectCount' => WaterProject::query()->count(),
            'activeProjectCount' => WaterProject::query()->where('status', 'active')->count(),
            'waterSourceCount' => WaterSource::query()->count(),
            'openIncidentCount' => WaterPointReport::query()->where('type', 'incident')->where('status', 'open')->count(),
            'activeAssignmentCount' => WaterPointAssignment::query()->where('status', '!=', 'completed')->count(),
            'overdueInspectionCount' => WaterSource::query()
                ->where(function ($query): void {
                    $query->whereNull('last_checked_at')
                        ->orWhere('last_checked_at', '<', now()->subDays(90));
                })
                ->where('lifecycle_status', '!=', 'decommissioned')
                ->count(),
            'recentProjects' => WaterProject::query()->latest()->limit(3)->get(),
        ]);
    }
}
