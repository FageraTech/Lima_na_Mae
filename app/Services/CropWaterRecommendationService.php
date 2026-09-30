<?php

namespace App\Services;

use InvalidArgumentException;

class CropWaterRecommendationService
{
    private const LOCATIONS = [
        'kitui' => ['label' => 'Kitui', 'note' => 'Hot and dry conditions make moisture capture and low-loss crops especially useful.'],
        'makueni' => ['label' => 'Makueni', 'note' => 'Dry conditions favour hardy crops and careful storage of short rains.'],
        'machakos' => ['label' => 'Machakos', 'note' => 'Seasonal rainfall can be turned into useful planting moisture with field structures.'],
        'embu' => ['label' => 'Embu', 'note' => 'Higher and more reliable rainfall gives more flexibility, while storage protects dry spells.'],
        'meru' => ['label' => 'Meru', 'note' => 'More reliable rainfall supports mixed choices, but soil moisture should still be protected.'],
    ];

    private const RAINFALL_PATTERNS = [
        'low' => ['label' => 'Low and unreliable', 'score' => 1],
        'seasonal' => ['label' => 'Seasonal but usable', 'score' => 2],
        'reliable' => ['label' => 'Reliable most seasons', 'score' => 3],
    ];

    private const RESOURCE_LEVELS = [
        'limited' => ['label' => 'Limited money, labour, or water', 'score' => 1],
        'moderate' => ['label' => 'Some money, labour, and water', 'score' => 2],
        'strong' => ['label' => 'Good access to money, labour, and water', 'score' => 3],
    ];

    /**
     * @return array<string, array{label: string, note: string}>
     */
    public function locations(): array
    {
        return self::LOCATIONS;
    }

    /**
     * @return array<string, array{label: string, score: int}>
     */
    public function rainfallPatterns(): array
    {
        return self::RAINFALL_PATTERNS;
    }

    /**
     * @return array<string, array{label: string, score: int}>
     */
    public function resourceLevels(): array
    {
        return self::RESOURCE_LEVELS;
    }

    /**
     * @return array<string, mixed>
     */
    public function recommend(string $location, string $rainfall, string $resources): array
    {
        if (! isset(self::LOCATIONS[$location], self::RAINFALL_PATTERNS[$rainfall], self::RESOURCE_LEVELS[$resources])) {
            throw new InvalidArgumentException('The selected location, rainfall pattern, or resource level is not supported.');
        }

        $rainfallScore = self::RAINFALL_PATTERNS[$rainfall]['score'];
        $resourceScore = self::RESOURCE_LEVELS[$resources]['score'];

        return [
            'location' => self::LOCATIONS[$location]['label'],
            'rainfall' => self::RAINFALL_PATTERNS[$rainfall]['label'],
            'resources' => self::RESOURCE_LEVELS[$resources]['label'],
            'location_note' => self::LOCATIONS[$location]['note'],
            'crops' => $this->cropRecommendations($rainfallScore, $resourceScore),
            'harvesting_methods' => $this->harvestingRecommendations($location, $rainfallScore, $resourceScore),
            'summary' => $this->summary($rainfallScore, $resourceScore),
        ];
    }

    /**
     * @return array<int, array{name: string, reason: string, priority: string}>
     */
    private function cropRecommendations(int $rainfallScore, int $resourceScore): array
    {
        $crops = [
            ['name' => 'Sorghum', 'reason' => 'Handles dry conditions and can keep producing when rainfall stops early.', 'priority' => 'Best first choice'],
            ['name' => 'Green grams', 'reason' => 'Matures quickly, so it can fit a shorter rainy season and needs less water than many staple crops.', 'priority' => 'Good short-season choice'],
            ['name' => 'Cowpeas', 'reason' => 'Tolerates heat, adds food and leaves useful nitrogen in the soil.', 'priority' => 'Good resilience choice'],
        ];

        if ($rainfallScore === 3 && $resourceScore === 3) {
            $crops[0]['reason'] = 'Keeps a drought-tolerant safety net in the field while your stronger water access allows a wider crop mix.';
            $crops[1]['priority'] = 'Good income and food choice';
        }

        return $crops;
    }

    /**
     * @return array<int, array{name: string, reason: string, effort: string}>
     */
    private function harvestingRecommendations(string $location, int $rainfallScore, int $resourceScore): array
    {
        $methods = [];

        if ($resourceScore <= 2) {
            $methods[] = [
                'name' => 'Zai pits',
                'reason' => 'Low-cost planting pits slow runoff and place moisture near each crop.',
                'effort' => 'Start with one small plot and add pits each season.',
            ];
        }

        if ($rainfallScore <= 2 || in_array($location, ['kitui', 'makueni'], true)) {
            $methods[] = [
                'name' => 'Sand dams',
                'reason' => 'Store rainy-season flows below the sand where water is protected from fast evaporation.',
                'effort' => 'Needs community planning, a suitable seasonal river, and technical checks.',
            ];
        }

        if ($resourceScore >= 2 || $rainfallScore >= 2) {
            $methods[] = [
                'name' => 'Farm ponds',
                'reason' => 'Capture runoff for later planting or supplementary irrigation when the rains pause.',
                'effort' => 'Needs a safe site, a lined or well-compacted basin, and an overflow channel.',
            ];
        }

        return $methods;
    }

    private function summary(int $rainfallScore, int $resourceScore): string
    {
        if ($rainfallScore === 1 && $resourceScore === 1) {
            return 'Begin with the lowest-cost soil and water measures. Protect a small plot first, then expand after you see which structure holds water.';
        }

        if ($rainfallScore === 3 && $resourceScore === 3) {
            return 'You have room to combine drought-tolerant crops with a farm pond, while keeping sorghum, green grams, or cowpeas as a reliable fallback.';
        }

        return 'Spread risk across hardy crops and at least one water-harvesting method. Start small, observe the first rainy season, and improve the structures before expanding.';
    }
}
