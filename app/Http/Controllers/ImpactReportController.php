<?php

namespace App\Http\Controllers;

use App\Models\WaterPointAssignment;
use App\Models\WaterPointReport;
use App\Models\WaterSource;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImpactReportController extends Controller
{
    public function index(Request $request): View
    {
        return view('impact-reports.index', $this->reportData($request));
    }

    public function print(Request $request): View
    {
        return view('impact-reports.print', $this->reportData($request));
    }

    public function export(Request $request): StreamedResponse
    {
        $waterPoints = $this->filteredWaterPoints($request);

        return response()->streamDownload(function () use ($waterPoints): void {
            $output = fopen('php://output', 'w');

            fputcsv($output, [
                'Water point',
                'Type',
                'County',
                'Location',
                'Status',
                'Estimated users',
                'Last checked',
            ]);

            foreach ($waterPoints as $waterPoint) {
                fputcsv($output, [
                    $waterPoint->name,
                    $waterPoint->type,
                    $waterPoint->waterProject->county,
                    $waterPoint->location,
                    $waterPoint->lifecycle_status,
                    $waterPoint->estimated_users,
                    $waterPoint->last_checked_at?->toDateString() ?? 'Not checked',
                ]);
            }

            fclose($output);
        }, 'water-point-impact-report.csv', ['Content-Type' => 'text/csv']);
    }

    /**
     * @return array<string, mixed>
     */
    private function reportData(Request $request): array
    {
        $waterPoints = $this->filteredWaterPoints($request);
        $waterPointIds = $waterPoints->modelKeys();
        $reports = WaterPointReport::query()
            ->whereIn('water_source_id', $waterPointIds)
            ->with('waterSource.waterProject')
            ->get();
        $assignments = WaterPointAssignment::query()
            ->whereIn('water_source_id', $waterPointIds)
            ->with('waterSource')
            ->get();

        $resolvedIncidents = $reports->filter(fn ($report) => $report->type === 'incident' && $report->resolved_at !== null
        );
        $countyAnalytics = $waterPoints
            ->groupBy(fn ($waterPoint) => $waterPoint->waterProject->county)
            ->map(function ($countyPoints, $county): array {
                $functional = $countyPoints->where('is_operational', true)->count();

                return [
                    'county' => $county,
                    'total' => $countyPoints->count(),
                    'functional' => $functional,
                    'functional_percentage' => $countyPoints->count() > 0
                        ? round(($functional / $countyPoints->count()) * 100)
                        : 0,
                    'estimated_users' => $countyPoints->sum('estimated_users'),
                ];
            })
            ->values();
        $recurringIssues = $reports
            ->filter(fn ($report) => $report->type === 'incident' && $report->reported_at?->gte(now()->subDays(90))
            )
            ->groupBy('water_source_id')
            ->filter(fn ($waterPointReports) => $waterPointReports->count() >= 3)
            ->map(fn ($waterPointReports) => [
                'water_point' => $waterPointReports->first()->waterSource->name,
                'county' => $waterPointReports->first()->waterSource->waterProject->county,
                'reports' => $waterPointReports->count(),
            ])
            ->values();

        return [
            'selectedCounty' => $request->string('county')->toString(),
            'counties' => $waterPoints->map(fn ($waterPoint) => $waterPoint->waterProject->county)->unique()->sort()->values(),
            'totalWaterPoints' => $waterPoints->count(),
            'functionalPercentage' => $waterPoints->count() > 0
                ? round(($waterPoints->where('is_operational', true)->count() / $waterPoints->count()) * 100)
                : 0,
            'averageResolutionDays' => $resolvedIncidents->count() > 0
                ? round($resolvedIncidents->avg(fn ($report) => $report->reported_at->diffInHours($report->resolved_at) / 24), 1)
                : 0,
            'countyAnalytics' => $countyAnalytics,
            'recurringIssues' => $recurringIssues,
            'coverageGaps' => $waterPoints->filter(fn ($waterPoint) => $waterPoint->estimated_users >= 100 && ! $waterPoint->is_operational
            )->values(),
            'overdueInspections' => $waterPoints->filter(fn ($waterPoint) => $waterPoint->last_checked_at === null || $waterPoint->last_checked_at->lt(now()->subDays(90))
            )->values(),
            'criticalReports' => $reports->where('severity', 'critical')->where('status', 'open')->values(),
            'overdueAssignments' => $assignments->filter(fn ($assignment) => $assignment->status !== 'completed' && $assignment->due_at?->isPast()
            )->values(),
        ];
    }

    private function filteredWaterPoints(Request $request)
    {
        return WaterSource::query()
            ->with('waterProject')
            ->when($request->filled('county'), function ($query) use ($request): void {
                $query->whereHas('waterProject', fn ($projectQuery) => $projectQuery->where('county', $request->string('county')->toString())
                );
            })
            ->get();
    }
}
