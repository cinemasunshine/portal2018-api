<?php

namespace Tests\Unit\Http\Controllers\Api;

use App\Doctrine\Entities\Schedule as ScheduleEntity;
use App\Doctrine\Repositories\ScheduleRepository;
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
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @test
     *
     * @return void
     */
    public function testIndexNowShowing()
    {
        $schedules = [
            new ScheduleEntity(),
        ];

        $type        = ScheduleRepository::PUBLIC_TYPE_NOW_SHOWING;
        $theater     = '999';
        $requestMock = $this->createRequestMock($theater);

        $repositoryMock = $this->createScheduleRepositoryMock();
        $repositoryMock
            ->shouldReceive('findPublic')
            ->with($type, $theater)
            ->andReturn($schedules);

        $entityMangerMock = $this->createEntityManagerMock($repositoryMock);

        $targetMoc = $this->createTargetMock();
        $targetMoc->makePartial();
        $result = $targetMoc->index($requestMock, $type, $entityMangerMock);

        $this->assertInstanceOf(ScheduleCollectionResource::class, $result);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @test
     *
     * @return void
     */
    public function testIndexComingSoon()
    {
        $schedules = [
            new ScheduleEntity(),
        ];

        $type        = ScheduleRepository::PUBLIC_TYPE_COMING_SOON;
        $theater     = '999';
        $requestMock = $this->createRequestMock($theater);

        $repositoryMock = $this->createScheduleRepositoryMock();
        $repositoryMock
            ->shouldReceive('findPublic')
            ->with($type, $theater)
            ->andReturn($schedules);

        $entityMangerMock = $this->createEntityManagerMock($repositoryMock);

        $targetMoc = $this->createTargetMock();
        $targetMoc->makePartial();
        $result = $targetMoc->index($requestMock, $type, $entityMangerMock);

        $this->assertInstanceOf(ScheduleCollectionResource::class, $result);
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
            ->with(Mockery::on(static function ($argument) {
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
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&ScheduleRepository
     */
    protected function createScheduleRepositoryMock()
    {
        return Mockery::mock(ScheduleRepository::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testShow()
    {
        $targetMoc = $this->createTargetMock();
        $targetMoc->makePartial();

        $schedule = new ScheduleEntity();
        $result   = $targetMoc->show($schedule);
        $this->assertInstanceOf(ScheduleResource::class, $result);
    }
}
