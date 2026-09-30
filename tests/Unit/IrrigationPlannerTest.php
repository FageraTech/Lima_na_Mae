<?php

namespace Tests\Unit;

use App\Services\IrrigationPlanner;
use PHPUnit\Framework\TestCase;

class IrrigationPlannerTest extends TestCase
{
    public function test_it_calculates_a_repeatable_maize_plan_for_loam_soil(): void
    {
        $result = (new IrrigationPlanner)->calculate('maize', 1, 'loam', 'Test borehole', 30000);

        $this->assertSame(179411, $result['weekly_litres']);
        $this->assertSame(25630, $result['daily_litres']);
        $this->assertSame(89705, $result['event_litres']);
        $this->assertSame(84600, $result['tank_litres']);
        $this->assertSame('enough', $result['capacity_status']);
        $this->assertCount(2, $result['schedule']);
    }
}
