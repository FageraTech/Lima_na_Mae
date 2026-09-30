<?php

namespace App\Http\Controllers;

use App\Models\WaterProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WaterProjectController extends Controller
{
    public function index(): View
    {
        $waterProjects = WaterProject::withCount('waterSources')
            ->latest()
            ->get();

        return view('water-projects.index', compact('waterProjects'));
    }

    public function create(): View
    {
        return view('water-projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'county' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:planned,active,completed'],
            'start_date' => ['nullable', 'date'],
            'completion_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        WaterProject::create($validated);

        return redirect()
            ->route('water-projects.index')
            ->with('success', 'Water project created successfully.');
    }

    public function show(WaterProject $waterProject): View
    {
        $waterProject->load('waterSources');

        return view('water-projects.show', compact('waterProject'));
    }

    public function edit(WaterProject $waterProject): View
    {
        return view('water-projects.edit', compact('waterProject'));
    }

    public function update(
        Request $request,
        WaterProject $waterProject
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'county' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:planned,active,completed'],
            'start_date' => ['nullable', 'date'],
            'completion_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $waterProject->update($validated);

        return redirect()
            ->route('water-projects.index')
            ->with('success', 'Water project updated successfully.');
    }

    public function destroy(WaterProject $waterProject): RedirectResponse
    {
        $waterProject->delete();

        return redirect()
            ->route('water-projects.index')
            ->with('success', 'Water project deleted successfully.');
    }
}
