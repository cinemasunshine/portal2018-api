<?php

declare(strict_types=1);

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
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
class ScheduleControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface&LegacyMockInterface&ScheduleController
     */
    protected function createTargetMock()
    {
        return Mockery::mock(ScheduleController::class);
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     * @test
     */
    public function testIndexNowShowing(): void
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
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     * @test
     */
    public function testIndexComingSoon(): void
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
     * @return MockInterface&LegacyMockInterface&Request
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
     * @return MockInterface&LegacyMockInterface&EntityManager
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
     * @return MockInterface&LegacyMockInterface&ScheduleRepository
     */
    protected function createScheduleRepositoryMock()
    {
        return Mockery::mock(ScheduleRepository::class);
    }

    /**
     * @test
     */
    public function testShow(): void
    {
        $targetMoc = $this->createTargetMock();
        $targetMoc->makePartial();

        $schedule = new ScheduleEntity();
        $result   = $targetMoc->show($schedule);
        $this->assertInstanceOf(ScheduleResource::class, $result);
    }
}
