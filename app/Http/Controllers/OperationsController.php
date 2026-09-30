<?php

namespace App\Http\Controllers;

use App\Models\WaterPointAssignment;
use App\Models\WaterPointReport;
use App\Models\WaterSource;
use Illuminate\View\View;

class OperationsController extends Controller
{
    public function __invoke(): View
    {
        $overdueCutoff = now()->subDays(90);

        return view('operations.index', [
            'openIncidents' => WaterPointReport::with('waterSource')
                ->where('type', 'incident')
                ->where('status', 'open')
                ->latest('reported_at')
                ->get(),
            'activeAssignments' => WaterPointAssignment::with('waterSource')
                ->where('status', '!=', 'completed')
                ->orderBy('due_at')
                ->get(),
            'overdueInspections' => WaterSource::with('waterProject')
                ->where(function ($query) use ($overdueCutoff): void {
                    $query->whereNull('last_checked_at')
                        ->orWhere('last_checked_at', '<', $overdueCutoff);
                })
                ->where('lifecycle_status', '!=', 'decommissioned')
                ->get(),
        ]);
    }
}
