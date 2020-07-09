<?php

namespace Tests\Unit\Http\Controllers\Api;

use App\Doctrine\Entities\Schedule as ScheduleEntity;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Resources\Schedule as ScheduleResource;
use App\Http\Resources\ScheduleCollection as ScheduleCollectionResource;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
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
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @return void
     */
    public function testIndexNowShowing()
    {
        $schedules = [
            new ScheduleEntity(),
        ];

        $theater = '999';
        $requestMock = $this->createRequestMock($theater);

        $repositoryMock = $this->createScheduleRepositoryMock();
        $repositoryMock
            ->shouldReceive('findNowShowing')
            ->with($theater)
            ->andReturn($schedules);

        $entityMangerMock = $this->createEntityManagerMock($repositoryMock);

        $targetMoc = $this->createTargetMock();
        $targetMoc->makePartial();
        $result = $targetMoc->index($requestMock, 'now-showing', $entityMangerMock);

        $this->assertInstanceOf(ScheduleCollectionResource::class, $result);
    }

    /**
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @return void
     */
    public function testIndexComingSoon()
    {
        $schedules = [
            new ScheduleEntity(),
        ];

        $theater = '999';
        $requestMock = $this->createRequestMock($theater);

        $repositoryMock = $this->createScheduleRepositoryMock();
        $repositoryMock
            ->shouldReceive('findComingSoon')
            ->with($theater)
            ->andReturn($schedules);

        $entityMangerMock = $this->createEntityManagerMock($repositoryMock);

        $targetMoc = $this->createTargetMock();
        $targetMoc->makePartial();
        $result = $targetMoc->index($requestMock, 'coming-soon', $entityMangerMock);

        $this->assertInstanceOf(ScheduleCollectionResource::class, $result);
    }

    /**
     * @test
     * @return void
     */
    public function testIndexInvalidType()
    {
        $this->expectException(\InvalidArgumentException::class);

        $requestMock = $this->createRequestMock('999');
        $repositoryMock = $this->createScheduleRepositoryMock();
        $entityMangerMock = $this->createEntityManagerMock($repositoryMock);
        $targetMoc = $this->createTargetMock();
        $targetMoc->makePartial();

        $targetMoc->index($requestMock, 'invalid', $entityMangerMock);
    }

    /**
     * @param mixed $theater
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&Request
     */
    protected function createRequestMock($theater)
    {
        $mock = Mockery::mock(Request::class);
        $mock
            ->shouldReceive('validate')
            ->with(Mockery::on(function ($argument) {
                return isset($argument['theater']);
            }));
        $mock
            ->shouldReceive('query')
            ->with('theater')
            ->andReturn($theater);

        return $mock;
    }

    /**
     * @param mixed $repository
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&EntityManager
     */
    protected function createEntityManagerMock($repository)
    {
        $mock = Mockery::mock(EntityManager::class);
        $mock
            ->shouldReceive('getRepository')
            ->with(ScheduleEntity::class)
            ->andReturn($repository);

        return $mock;
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface
     */
    protected function createScheduleRepositoryMock()
    {
        return Mockery::mock('ScheduleRepository');
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
