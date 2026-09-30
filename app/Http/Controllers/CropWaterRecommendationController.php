<?php

namespace App\Http\Controllers;

use App\Services\CropWaterRecommendationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CropWaterRecommendationController extends Controller
{
    public function __construct(private CropWaterRecommendationService $recommendations) {}

    public function create(): View
    {
        return view('crop-water-recommendations', $this->formData());
    }

    public function store(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'location' => ['required', 'string', 'in:'.implode(',', array_keys($this->recommendations->locations()))],
            'rainfall' => ['required', 'string', 'in:'.implode(',', array_keys($this->recommendations->rainfallPatterns()))],
            'resources' => ['required', 'string', 'in:'.implode(',', array_keys($this->recommendations->resourceLevels()))],
        ]);

        return view('crop-water-recommendations', array_merge(
            $this->formData(),
            [
                'result' => $this->recommendations->recommend($validated['location'], $validated['rainfall'], $validated['resources']),
                'selected' => $validated,
            ],
        ));
    }

    /**
     * @return array<string, array<string, array<string, string|int>>>
     */
    private function formData(): array
    {
        return [
            'locations' => $this->recommendations->locations(),
            'rainfallPatterns' => $this->recommendations->rainfallPatterns(),
            'resourceLevels' => $this->recommendations->resourceLevels(),
        ];
    }
}
