<?php

namespace Tests\Unit\Http\Resources;

use App\Doctrine\Entities\Schedule as ScheduleEntity;
use App\Doctrine\Entities\Title as TitleEntity;
use App\Http\Resources\Schedule;
use App\Http\Resources\ShowingFormat;
use App\Http\Resources\ShowingTheater;
use App\Http\Resources\Title;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
class ScheduleTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&Schedule
     */
    protected function createTargetMock()
    {
        return Mockery::mock(Schedule::class);
    }

    /**
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(Schedule::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&ScheduleEntity
     */
    protected function createScheduleEntityMock()
    {
        return Mockery::mock(ScheduleEntity::class);
    }

    /**
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @return void
     */
    public function testToArray()
    {
        $id = 5;
        $startDate = '2020-07-10';
        $endDate = '2020-07-31';
        $remark = 'example remark';
        $title = new TitleEntity();
        $showingFormats = new ArrayCollection();
        $showingTheaters = new ArrayCollection();

        $scheduleEntityMock = $this->createScheduleEntityMock();
        $scheduleEntityMock
            ->shouldReceive('getId')
            ->once()
            ->andReturn($id);
        $scheduleEntityMock
            ->shouldReceive('getStartDate')
            ->once()
            ->andReturn(new \DateTime($startDate));
        $scheduleEntityMock
            ->shouldReceive('getEndDate')
            ->once()
            ->andReturn(new \DateTime($endDate));
        $scheduleEntityMock
            ->shouldReceive('getRemark')
            ->once()
            ->andReturn($remark);
        $scheduleEntityMock
            ->shouldReceive('getTitle')
            ->once()
            ->andReturn($title);
        $scheduleEntityMock
            ->shouldReceive('getShowingFormats')
            ->once()
            ->andReturn($showingFormats);
        $scheduleEntityMock
            ->shouldReceive('getShowingTheaters')
            ->once()
            ->andReturn($showingTheaters);

        $formats = Mockery::mock(AnonymousResourceCollection::class);
        $showingFormatMock = $this->createShowingFormatMock();
        $showingFormatMock
            ->shouldReceive('collection')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn($formats);

        $theaters = Mockery::mock(AnonymousResourceCollection::class);
        $showingTheaterMock = $this->createShowingTheaterMock();
        $showingTheaterMock
            ->shouldReceive('collection')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn($theaters);

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();
        $targetRef = $this->createTargetReflection();
        $resourceRef = $targetRef->getProperty('resource');
        $resourceRef->setAccessible(true);
        $resourceRef->setValue($targetMock, $scheduleEntityMock);

        $result = $targetMock->toArray(Mockery::mock(Request::class));
        $this->assertEquals($id, $result['id']);
        $this->assertEquals($startDate, $result['start_date']);
        $this->assertEquals($endDate, $result['end_date']);
        $this->assertEquals($remark, $result['remark']);
        $this->assertInstanceOf(Title::class, $result['title']);
        $this->assertEquals($formats, $result['formats']);
        $this->assertEquals($theaters, $result['theaters']);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&ShowingFormat
     */
    protected function createShowingFormatMock()
    {
        return Mockery::mock('alias:' . ShowingFormat::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&ShowingTheater
     */
    protected function createShowingTheaterMock()
    {
        return Mockery::mock('alias:' . ShowingTheater::class);
    }
}
