<?php

namespace Tests\Unit\Doctrine\Entities;

use App\Doctrine\Entities\Schedule;
use App\Doctrine\Repositories\ScheduleRepository;
use Doctrine\ORM\QueryBuilder;
use Hamcrest\Matchers;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ScheduleRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&ScheduleRepository
     */
    protected function createTargetMock()
    {
        return Mockery::mock(ScheduleRepository::class);
    }

    /**
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(ScheduleRepository::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&QueryBuilder
     */
    protected function createQueryBuilderMock()
    {
        return Mockery::mock(QueryBuilder::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface
     */
    protected function createQueryMock()
    {
        return Mockery::mock('Query');
    }

    /**
     * @test
     * @return void
     */
    public function testAddActiveQuery()
    {
        $alias = 'test';
        $queryBuilderMock = $this->createQueryBuilderMock();
        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->with(Matchers::containsString($alias . '.isDeleted'))
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->with(Matchers::containsString($alias . '.publicStartDt'))
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->with(Matchers::containsString($alias . '.publicEndDt'))
            ->andReturn($queryBuilderMock);

        $targetRef = $this->createTargetReflection();
        $addActiveQueryRef = $targetRef->getMethod('addActiveQuery');
        $addActiveQueryRef->setAccessible(true);

        $targetMock = $this->createTargetMock();
        $addActiveQueryRef->invoke($targetMock, $queryBuilderMock, $alias);
    }

    /**
     * @test
     * @return void
     */
    public function testFindOneActive()
    {
        $id = 12;
        $alias = 's';

        $queryBuilderMock = $this->createQueryBuilderMock();
        $queryBuilderMock
            ->shouldReceive('where')
            ->with($alias . '.id = :id')
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->with('id', $id)
            ->andReturn($queryBuilderMock);

        $schedule = new Schedule();
        $queryMock = $this->createQueryMock();
        $queryMock
            ->shouldReceive('getSingleResult')
            ->andReturn($schedule);

        $queryBuilderMock
            ->shouldReceive('getQuery')
            ->with()
            ->andReturn($queryMock);

        $targetMock = $this->createTargetMock();
        $targetMock
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $targetMock
            ->shouldReceive('createQueryBuilder')
            ->with(Mockery::type('string'))
            ->andReturn($queryBuilderMock);
        $targetMock
            ->shouldReceive('addActiveQuery')
            ->with($queryBuilderMock, $alias);

        $result = $targetMock->findOneActive($id);
        $this->assertEquals($schedule, $result);
    }
}
