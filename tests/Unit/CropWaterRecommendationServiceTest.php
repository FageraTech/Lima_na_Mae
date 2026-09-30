<?php

namespace Tests\Unit;

use App\Services\CropWaterRecommendationService;
use PHPUnit\Framework\TestCase;

class CropWaterRecommendationServiceTest extends TestCase
{
    public function test_dry_limited_conditions_prioritize_hardy_crops_and_low_cost_methods(): void
    {
        $result = (new CropWaterRecommendationService)->recommend('makueni', 'low', 'limited');

        $this->assertSame('Makueni', $result['location']);
        $this->assertSame('Sorghum', $result['crops'][0]['name']);
        $this->assertSame('Green grams', $result['crops'][1]['name']);
        $this->assertSame('Zai pits', $result['harvesting_methods'][0]['name']);
        $this->assertSame('Sand dams', $result['harvesting_methods'][1]['name']);
    }
}
