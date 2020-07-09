<?php

namespace Tests\Unit\Doctrine\Entities;

use App\Doctrine\Entities\Schedule;
use App\Doctrine\Repositories\ScheduleRepository;
use Doctrine\ORM\QueryBuilder;
use Hamcrest\Matchers;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
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

    /**
     * @test
     * @return void
     */
    public function testFindNowShowing()
    {
        $theater = null;
        $alias = 's';
        $schedules = [
            new Schedule(),
        ];

        $queryMock = $this->createQueryMock();
        $queryMock
            ->shouldReceive('getResult')
            ->andReturn($schedules);

        $queryBuilderMock = $this->createQueryBuilderMockForFindNowShowing(
            $alias,
            $queryMock
        );

        $aliasShowingTheaters = 'st';
        $aliasTheater = 't';
        $queryBuilderMock
            ->shouldReceive('innerJoin')
            ->never()
            ->with($alias . '.showingTheaters', $aliasShowingTheaters);
        $queryBuilderMock
            ->shouldReceive('innerJoin')
            ->never()
            ->with($aliasShowingTheaters . '.theater', $aliasTheater);
        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->never()
            ->with($aliasTheater . '.masterCode = :theater');
        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->never()
            ->with('theater', $theater);

        $targetMock = $this->createTargetMockForFindNowShowing($alias, $queryBuilderMock);
        $targetMock->makePartial();

        $result = $targetMock->findNowShowing($theater);
        $this->assertEquals($schedules, $result);
    }

    /**
     * @test
     * @return void
     */
    public function testFindNowShowingWithTheater()
    {
        $theater = '999';
        $alias = 's';
        $schedules = [
            new Schedule(),
        ];

        $queryMock = $this->createQueryMock();
        $queryMock
            ->shouldReceive('getResult')
            ->andReturn($schedules);

        $queryBuilderMock = $this->createQueryBuilderMockForFindNowShowing(
            $alias,
            $queryMock
        );

        $aliasShowingTheaters = 'st';
        $aliasTheater = 't';
        $queryBuilderMock
            ->shouldReceive('innerJoin')
            ->with($alias . '.showingTheaters', $aliasShowingTheaters)
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('innerJoin')
            ->with($aliasShowingTheaters . '.theater', $aliasTheater)
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->with($aliasTheater . '.masterCode = :theater')
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->with('theater', $theater)
            ->andReturn($queryBuilderMock);

        $targetMock = $this->createTargetMockForFindNowShowing($alias, $queryBuilderMock);
        $targetMock->makePartial();

        $result = $targetMock->findNowShowing($theater);
        $this->assertEquals($schedules, $result);
    }

    /**
     * @param string $alias
     * @param mixed $queryBuilder
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&ScheduleRepository
     */
    public function createTargetMockForFindNowShowing(string $alias, $queryBuilder)
    {
        $mock = $this->createTargetMock();
        $mock->shouldAllowMockingProtectedMethods();

        $mock
            ->shouldReceive('createQueryBuilder')
            ->with($alias)
            ->andReturn($queryBuilder);

        $mock
            ->shouldReceive('addActiveQuery')
            ->with($queryBuilder, $alias);

        return $mock;
    }

    /**
     * @param string $alias
     * @param mixed $query
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&QueryBuilder
     */
    public function createQueryBuilderMockForFindNowShowing(
        string $alias,
        $query
    ) {
        $mock = $this->createQueryBuilderMock();
        $mock
            ->shouldReceive('andWhere')
            ->with($alias . '.startDate <= CURRENT_DATE()')
            ->andReturn($mock);
        $mock
            ->shouldReceive('orderBy')
            ->with($alias . '.startDate', 'DESC')
            ->andReturn($mock);
        $mock
            ->shouldReceive('getQuery')
            ->with()
            ->andReturn($query);

        return $mock;
    }

    /**
     * @test
     * @return void
     */
    public function testFindComingSoon()
    {
        $theater = null;
        $alias = 's';
        $schedules = [
            new Schedule(),
        ];

        $queryMock = $this->createQueryMock();
        $queryMock
            ->shouldReceive('getResult')
            ->andReturn($schedules);

        $queryBuilderMock = $this->createQueryBuilderMockForFindComingSoon(
            $alias,
            $queryMock
        );

        $aliasShowingTheaters = 'st';
        $aliasTheater = 't';
        $queryBuilderMock
            ->shouldReceive('innerJoin')
            ->never()
            ->with($alias . '.showingTheaters', $aliasShowingTheaters);
        $queryBuilderMock
            ->shouldReceive('innerJoin')
            ->never()
            ->with($aliasShowingTheaters . '.theater', $aliasTheater);
        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->never()
            ->with($aliasTheater . '.masterCode = :theater');
        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->never()
            ->with('theater', $theater);

        $targetMock = $this->createTargetMockForFindComingSoon($alias, $queryBuilderMock);
        $targetMock->makePartial();

        $result = $targetMock->findComingSoon($theater);
        $this->assertEquals($schedules, $result);
    }

    /**
     * @test
     * @return void
     */
    public function testFindComingSoonWithTheater()
    {
        $theater = '999';
        $alias = 's';
        $schedules = [
            new Schedule(),
        ];

        $queryMock = $this->createQueryMock();
        $queryMock
            ->shouldReceive('getResult')
            ->andReturn($schedules);

        $queryBuilderMock = $this->createQueryBuilderMockForFindComingSoon(
            $alias,
            $queryMock
        );

        $aliasShowingTheaters = 'st';
        $aliasTheater = 't';
        $queryBuilderMock
            ->shouldReceive('innerJoin')
            ->with($alias . '.showingTheaters', $aliasShowingTheaters)
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('innerJoin')
            ->with($aliasShowingTheaters . '.theater', $aliasTheater)
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('andWhere')
            ->with($aliasTheater . '.masterCode = :theater')
            ->andReturn($queryBuilderMock);
        $queryBuilderMock
            ->shouldReceive('setParameter')
            ->with('theater', $theater)
            ->andReturn($queryBuilderMock);

        $targetMock = $this->createTargetMockForFindComingSoon($alias, $queryBuilderMock);
        $targetMock->makePartial();

        $result = $targetMock->findComingSoon($theater);
        $this->assertEquals($schedules, $result);
    }

    /**
     * @param string $alias
     * @param mixed $queryBuilder
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&ScheduleRepository
     */
    public function createTargetMockForFindComingSoon(string $alias, $queryBuilder)
    {
        $mock = $this->createTargetMock();
        $mock->shouldAllowMockingProtectedMethods();

        $mock
            ->shouldReceive('createQueryBuilder')
            ->with($alias)
            ->andReturn($queryBuilder);

        $mock
            ->shouldReceive('addActiveQuery')
            ->with($queryBuilder, $alias);

        return $mock;
    }

    /**
     * @param string $alias
     * @param mixed $query
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&QueryBuilder
     */
    public function createQueryBuilderMockForFindComingSoon(
        string $alias,
        $query
    ) {
        $mock = $this->createQueryBuilderMock();

        $mock
            ->shouldReceive('andWhere')
            ->with($alias . '.startDate > CURRENT_DATE()')
            ->andReturn($mock);
        $mock
            ->shouldReceive('orderBy')
            ->with($alias . '.startDate', 'ASC')
            ->andReturn($mock);
        $mock
            ->shouldReceive('getQuery')
            ->with()
            ->andReturn($query);

        return $mock;
    }
}
