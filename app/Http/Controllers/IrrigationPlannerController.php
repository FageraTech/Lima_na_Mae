<?php

namespace App\Http\Controllers;

use App\Models\WaterSource;
use App\Services\IrrigationPlanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IrrigationPlannerController extends Controller
{
    public function __construct(private IrrigationPlanner $planner) {}

    public function create(): View
    {
        return view('irrigation-planner', [
            'crops' => $this->planner->crops(),
            'soils' => $this->planner->soils(),
            'waterSources' => WaterSource::query()->orderBy('name')->get(['id', 'name', 'capacity', 'capacity_unit']),
        ]);
    }

    public function store(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'crop' => ['required', 'string', 'in:'.implode(',', array_keys($this->planner->crops()))],
            'acreage' => ['required', 'numeric', 'gt:0', 'max:10000'],
            'soil' => ['required', 'string', 'in:'.implode(',', array_keys($this->planner->soils()))],
            'water_source_id' => ['required', 'integer', 'exists:water_sources,id'],
        ]);

        $waterSource = WaterSource::query()->findOrFail($validated['water_source_id']);
        $result = $this->planner->calculate(
            $validated['crop'],
            (float) $validated['acreage'],
            $validated['soil'],
            $waterSource->name,
            $waterSource->capacity ? (float) $waterSource->capacity : null,
        );

        return view('irrigation-planner', [
            'crops' => $this->planner->crops(),
            'soils' => $this->planner->soils(),
            'waterSources' => WaterSource::query()->orderBy('name')->get(['id', 'name', 'capacity', 'capacity_unit']),
            'result' => $result,
            'selected' => $validated,
        ]);
    }
}
