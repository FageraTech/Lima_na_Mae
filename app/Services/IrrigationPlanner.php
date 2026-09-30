<?php

namespace App\Services;

use InvalidArgumentException;

class IrrigationPlanner
{
    private const REFERENCE_ET = 5.0;

    private const IRRIGATION_EFFICIENCY = 0.75;

    private const CROPS = [
        'beans' => ['label' => 'Beans', 'coefficient' => 1.00],
        'maize' => ['label' => 'Maize', 'coefficient' => 0.95],
        'onions' => ['label' => 'Onions', 'coefficient' => 1.00],
        'sorghum' => ['label' => 'Sorghum', 'coefficient' => 0.90],
        'tomatoes' => ['label' => 'Tomatoes', 'coefficient' => 1.15],
        'vegetables' => ['label' => 'Mixed vegetables', 'coefficient' => 1.05],
        'wheat' => ['label' => 'Wheat', 'coefficient' => 0.95],
    ];

    private const SOILS = [
        'sandy' => ['label' => 'Sandy soil', 'events' => 3],
        'loam' => ['label' => 'Loam soil', 'events' => 2],
        'clay' => ['label' => 'Clay soil', 'events' => 1],
    ];

    /**
     * @return array<string, array{label: string, coefficient: float}>
     */
    public function crops(): array
    {
        return self::CROPS;
    }

    /**
     * @return array<string, array{label: string, events: int}>
     */
    public function soils(): array
    {
        return self::SOILS;
    }

    /**
     * @return array<string, mixed>
     */
    public function calculate(string $crop, float $acreage, string $soil, string $waterSource, ?float $sourceCapacity = null): array
    {
        if (! isset(self::CROPS[$crop], self::SOILS[$soil])) {
            throw new InvalidArgumentException('The selected crop or soil is not supported.');
        }

        $cropData = self::CROPS[$crop];
        $soilData = self::SOILS[$soil];
        $areaSquareMetres = $acreage * 4046.86;
        $dailyCropUseMillimetres = self::REFERENCE_ET * $cropData['coefficient'];
        $weeklyNetMillimetres = $dailyCropUseMillimetres * 7;
        $weeklyGrossLitres = ($weeklyNetMillimetres / self::IRRIGATION_EFFICIENCY) * $areaSquareMetres;
        $dailyGrossLitres = $weeklyGrossLitres / 7;
        $eventLitres = $weeklyGrossLitres / $soilData['events'];
        $tankLitres = ceil(($dailyGrossLitres * 3 * 1.1) / 100) * 100;

        return [
            'crop' => $cropData['label'],
            'soil' => $soilData['label'],
            'water_source' => $waterSource,
            'acreage' => $acreage,
            'weekly_litres' => (int) round($weeklyGrossLitres),
            'daily_litres' => (int) round($dailyGrossLitres),
            'event_litres' => (int) round($eventLitres),
            'tank_litres' => (int) $tankLitres,
            'events_per_week' => $soilData['events'],
            'schedule' => $this->schedule($soilData['events'], (int) round($eventLitres)),
            'source_capacity' => $sourceCapacity,
            'capacity_status' => $this->capacityStatus($sourceCapacity, $dailyGrossLitres),
            'assumptions' => [
                'reference_et' => self::REFERENCE_ET,
                'crop_coefficient' => $cropData['coefficient'],
                'irrigation_efficiency' => self::IRRIGATION_EFFICIENCY,
            ],
            'explanation' => $this->explanation($cropData['label'], $soilData['label'], $soilData['events'], (int) round($weeklyGrossLitres), (int) $tankLitres),
        ];
    }

    /**
     * @return array<int, array{day: string, litres: int}>
     */
    private function schedule(int $events, int $litres): array
    {
        $days = match ($events) {
            1 => ['Monday'],
            2 => ['Monday', 'Thursday'],
            default => ['Monday', 'Wednesday', 'Saturday'],
        };

        return array_map(fn (string $day): array => ['day' => $day, 'litres' => $litres], $days);
    }

    private function capacityStatus(?float $capacity, float $dailyDemand): string
    {
        if ($capacity === null) {
            return 'unknown';
        }

        return $capacity >= $dailyDemand ? 'enough' : 'short';
    }

    private function explanation(string $crop, string $soil, int $events, int $weeklyLitres, int $tankLitres): string
    {
        $frequency = $events === 1 ? 'once' : $events.' times';

        return "For {$crop} on {$soil}, plan to irrigate {$frequency} each week. The field needs about ".number_format($weeklyLitres).' litres each week. A tank of about '.number_format($tankLitres).' litres gives roughly three days of cover plus a small reserve.';
    }
}
