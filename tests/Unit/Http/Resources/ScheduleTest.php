<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Resources;

use App\Doctrine\Entities\Schedule as ScheduleEntity;
use App\Doctrine\Entities\Title as TitleEntity;
use App\Http\Resources\Schedule;
use App\Http\Resources\Title;
use DateTime;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @group unit
 */
class ScheduleTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface&LegacyMockInterface&Schedule
     */
    protected function createTargetMock()
    {
        return Mockery::mock(Schedule::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(Schedule::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&ScheduleEntity
     */
    protected function createScheduleEntityMock()
    {
        return Mockery::mock(ScheduleEntity::class);
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     * @test
     */
    public function testToArray(): void
    {
        $id        = 5;
        $startDate = '2020-07-10';
        $endDate   = '2020-07-31';
        $remark    = 'example remark';
        $title     = new TitleEntity();

        $scheduleEntityMock = $this->createScheduleEntityMock();
        $scheduleEntityMock
            ->shouldReceive('getId')
            ->once()
            ->andReturn($id);
        $scheduleEntityMock
            ->shouldReceive('getStartDate')
            ->once()
            ->andReturn(new DateTime($startDate));
        $scheduleEntityMock
            ->shouldReceive('getEndDate')
            ->once()
            ->andReturn(new DateTime($endDate));
        $scheduleEntityMock
            ->shouldReceive('getRemark')
            ->once()
            ->andReturn($remark);
        $scheduleEntityMock
            ->shouldReceive('getTitle')
            ->once()
            ->andReturn($title);

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();
        $targetRef   = $this->createTargetReflection();
        $resourceRef = $targetRef->getProperty('resource');
        $resourceRef->setAccessible(true);
        $resourceRef->setValue($targetMock, $scheduleEntityMock);

        $result = $targetMock->toArray(Mockery::mock(Request::class));
        $this->assertEquals($id, $result['id']);
        $this->assertEquals($startDate, $result['start_date']);
        $this->assertEquals($endDate, $result['end_date']);
        $this->assertEquals($remark, $result['remark']);
        $this->assertInstanceOf(Title::class, $result['title']);
    }
}
