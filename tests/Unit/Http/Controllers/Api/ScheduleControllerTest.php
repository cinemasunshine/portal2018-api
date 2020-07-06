<?php

namespace Tests\Unit\Http\Controllers\Api;

use App\Doctrine\Entities\Schedule as ScheduleEntity;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Resources\Schedule as ScheduleResource;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ScheduleControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&ScheduleController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(ScheduleController::class);
    }

    /**
     * @test
     * @return void
     */
    public function testShow()
    {
        $targetMoc = $this->createTargetMock();
        $targetMoc->makePartial();

        $schedule = new ScheduleEntity();
        $result = $targetMoc->show($schedule);
        $this->assertInstanceOf(ScheduleResource::class, $result);
    }
}
