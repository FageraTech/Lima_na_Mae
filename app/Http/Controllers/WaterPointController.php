<?php

namespace App\Http\Controllers;

use App\Models\WaterPointAssignment;
use App\Models\WaterPointSubscription;
use App\Models\WaterSource;
use App\Notifications\WaterPointStatusChanged;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WaterPointController extends Controller
{
    public function index(): View
    {
        $waterPoints = WaterSource::with('waterProject')
            ->withCount('events')
            ->latest()
            ->get();

        return view('water-points.index', compact('waterPoints'));
    }

    public function show(WaterSource $waterSource): View
    {
        $waterSource->load([
            'waterProject',
            'events' => fn ($query) => $query->with('user')->latest('occurred_at'),
            'reports' => fn ($query) => $query->latest('reported_at'),
            'assignments' => fn ($query) => $query->latest(),
        ]);

        return view('water-points.show', compact('waterSource'));
    }

    public function storeInspection(Request $request, WaterSource $waterSource): RedirectResponse
    {
        $validated = $request->validate([
            'checked_at' => ['required', 'date'],
            'outcome' => ['required', 'string', 'in:operational,needs_attention,non_operational'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $lifecycleStatus = $validated['outcome'] === 'non_operational'
            ? 'needs_repair'
            : $validated['outcome'];

        $waterSource->update([
            'is_operational' => $validated['outcome'] === 'operational',
            'lifecycle_status' => $lifecycleStatus,
            'last_checked_at' => $validated['checked_at'],
        ]);

        $waterSource->events()->create([
            'user_id' => $request->user()->id,
            'type' => 'inspection',
            'title' => 'Routine inspection recorded',
            'description' => $validated['notes'],
            'metadata' => ['outcome' => $validated['outcome']],
            'occurred_at' => $validated['checked_at'],
        ]);

        return back()->with('success', 'Inspection added to the water point history.');
    }

    public function storeMaintenance(Request $request, WaterSource $waterSource): RedirectResponse
    {
        $validated = $request->validate([
            'performed_at' => ['required', 'date'],
            'work_done' => ['required', 'string', 'max:2000'],
            'technician' => ['nullable', 'string', 'max:255'],
            'parts_replaced' => ['nullable', 'string', 'max:1000'],
            'cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $waterSource->events()->create([
            'user_id' => $request->user()->id,
            'type' => 'maintenance',
            'title' => 'Maintenance work recorded',
            'description' => $validated['work_done'],
            'metadata' => [
                'technician' => $validated['technician'] ?? null,
                'parts_replaced' => $validated['parts_replaced'] ?? null,
                'cost' => $validated['cost'] ?? null,
            ],
            'occurred_at' => $validated['performed_at'],
        ]);

        return back()->with('success', 'Maintenance added to the water point history.');
    }

    public function confirmFunctional(Request $request, WaterSource $waterSource): RedirectResponse
    {
        $waterSource->reports()->create([
            'user_id' => $request->user()->id,
            'type' => 'confirmation',
            'status' => 'confirmed',
            'description' => 'Community member confirmed that this water point is working.',
            'reported_at' => now(),
        ]);

        $waterSource->update([
            'is_operational' => true,
            'lifecycle_status' => 'operational',
            'last_checked_at' => now(),
        ]);

        $waterSource->events()->create([
            'user_id' => $request->user()->id,
            'type' => 'confirmation',
            'title' => 'Community confirmed this point is working',
            'description' => 'A community member confirmed that this water point is working.',
            'occurred_at' => now(),
        ]);

        return back()->with('success', 'Thanks. Your confirmation refreshed this water point.');
    }

    public function storeIncident(Request $request, WaterSource $waterSource): RedirectResponse
    {
        $validated = $request->validate([
            'severity' => ['required', 'string', 'in:low,medium,critical'],
            'description' => ['required', 'string', 'max:2000'],
        ]);

        $report = $waterSource->reports()->create([
            'user_id' => $request->user()->id,
            'type' => 'incident',
            'severity' => $validated['severity'],
            'status' => 'open',
            'description' => $validated['description'],
            'reported_at' => now(),
        ]);

        $waterSource->events()->create([
            'user_id' => $request->user()->id,
            'type' => 'incident',
            'title' => ucfirst($validated['severity']).' incident reported',
            'description' => $validated['description'],
            'metadata' => ['report_id' => $report->id],
            'occurred_at' => now(),
        ]);

        if ($validated['severity'] === 'critical') {
            WaterPointSubscription::with('user')
                ->where('water_source_id', $waterSource->id)
                ->where('active', true)
                ->get()
                ->each(fn (WaterPointSubscription $subscription) => $subscription->user->notify(new WaterPointStatusChanged($report)));
        }

        return back()->with('success', 'Incident reported. The water point team has been notified.');
    }

    public function subscribe(Request $request, WaterSource $waterSource): RedirectResponse
    {
        WaterPointSubscription::updateOrCreate(
            ['water_source_id' => $waterSource->id, 'user_id' => $request->user()->id],
            ['active' => true, 'channel' => 'database'],
        );

        return back()->with('success', 'You will receive alerts for this water point.');
    }

    public function assignTechnician(Request $request, WaterSource $waterSource): RedirectResponse
    {
        $validated = $request->validate([
            'technician_name' => ['required', 'string', 'max:255'],
            'technician_phone' => ['nullable', 'string', 'max:40'],
            'task' => ['required', 'string', 'max:255'],
            'due_at' => ['required', 'date'],
            'critical' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $assignment = $waterSource->assignments()->create([
            ...$validated,
            'assigned_by' => $request->user()->id,
            'critical' => $request->boolean('critical'),
        ]);

        $waterSource->events()->create([
            'user_id' => $request->user()->id,
            'type' => 'assignment',
            'title' => 'Technician assigned',
            'description' => $assignment->task.' - '.$assignment->technician_name,
            'metadata' => ['assignment_id' => $assignment->id, 'due_at' => $assignment->due_at],
            'occurred_at' => now(),
        ]);

        return back()->with('success', 'Technician task assigned.');
    }

    public function completeAssignment(WaterPointAssignment $assignment): RedirectResponse
    {
        $assignment->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Technician task marked complete.');
    }

    public function decommission(Request $request, WaterSource $waterSource): RedirectResponse
    {
        $waterSource->update([
            'is_operational' => false,
            'lifecycle_status' => 'decommissioned',
            'decommissioned_at' => now(),
        ]);

        $waterSource->events()->create([
            'user_id' => $request->user()->id,
            'type' => 'status_change',
            'title' => 'Water point decommissioned',
            'description' => 'This water point was marked as permanently closed.',
            'metadata' => ['lifecycle_status' => 'decommissioned'],
            'occurred_at' => now(),
        ]);

        return back()->with('success', 'Water point decommissioned and kept in the history.');
    }
}
