<?php

declare(strict_types=1);

namespace Tests\Unit\Doctrine\Repositories;

use App\Doctrine\Entities\Schedule;
use App\Doctrine\Entities\ShowingTheater;
use App\Doctrine\Repositories\ScheduleRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use InvalidArgumentException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @group unit
 */
class ScheduleRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface&LegacyMockInterface&ScheduleRepository
     */
    protected function createTargetMock()
    {
        return Mockery::mock(ScheduleRepository::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(ScheduleRepository::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&QueryBuilder
     */
    protected function createQueryBuilderMock()
    {
        return Mockery::mock(QueryBuilder::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface
     */
    protected function createQueryMock()
    {
        return Mockery::mock('Query');
    }

    /**
     * @test
     */
    public function testFindOneActive(): void
    {
        $id    = 12;
        $alias = 's';

        $queryBuilderMock = $this->createQueryBuilderMockForFindOneActive($alias, $id);

        $schedule  = new Schedule();
        $queryMock = $this->createQueryMock();
        $queryMock
            ->shouldReceive('setFetchMode')
            ->with(ShowingTheater::class, 'theater', ClassMetadata::FETCH_EAGER);
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
            ->shouldReceive('addPublicQuery')
            ->with($queryBuilderMock, $alias);

        $result = $targetMock->findOneActive($id);
        $this->assertEquals($schedule, $result);
    }

    /**
     * @param mixed $id
     * @return MockInterface&LegacyMockInterface&QueryBuilder
     */
    protected function createQueryBuilderMockForFindOneActive(string $alias, $id)
    {
        $mock = $this->createQueryBuilderMock();

        $aliasTitle      = 't';
        $aliasTitleImage = 'ti';
        $mock
            ->shouldReceive('addSelect')
            ->with($aliasTitle)
            ->andReturn($mock);
        $mock
            ->shouldReceive('innerJoin')
            ->with($alias . '.title', $aliasTitle)
            ->andReturn($mock);
        $mock
            ->shouldReceive('addSelect')
            ->with($aliasTitleImage)
            ->andReturn($mock);
        $mock
            ->shouldReceive('leftJoin')
            ->with($aliasTitle . '.image', $aliasTitleImage)
            ->andReturn($mock);

        $aliasShowingFormats = 'sf';
        $mock
            ->shouldReceive('addSelect')
            ->with($aliasShowingFormats)
            ->andReturn($mock);
        $mock
            ->shouldReceive('innerJoin')
            ->with($alias . '.showingFormats', $aliasShowingFormats)
            ->andReturn($mock);

        $aliasShowingTheaters = 'st';
        $mock
            ->shouldReceive('addSelect')
            ->with($aliasShowingTheaters)
            ->andReturn($mock);
        $mock
            ->shouldReceive('innerJoin')
            ->with($alias . '.showingTheaters', $aliasShowingTheaters)
            ->andReturn($mock);

        $mock
            ->shouldReceive('where')
            ->with($alias . '.id = :id')
            ->andReturn($mock);
        $mock
            ->shouldReceive('setParameter')
            ->with('id', $id)
            ->andReturn($mock);

        return $mock;
    }

    /**
     * @test
     */
    public function testFindPublicNowShowing(): void
    {
        $alias     = 's';
        $type      = ScheduleRepository::PUBLIC_TYPE_NOW_SHOWING;
        $theater   = null;
        $schedules = [
            new Schedule(),
        ];

        $queryMock        = $this->createQueryMockForFindPublic($schedules);
        $queryBuilderMock = $this->createQueryBuilderMockForFindPublic($alias, $theater, $queryMock);
        $targetMock       = $this->createTargetMockForFindPublic($alias, $queryBuilderMock, $type);

        $result = $targetMock->findPublic($type, $theater);
        $this->assertEquals($schedules, $result);
    }

    /**
     * @test
     */
    public function testFindPublicCommingSoon(): void
    {
        $alias     = 's';
        $type      = ScheduleRepository::PUBLIC_TYPE_COMING_SOON;
        $theater   = null;
        $schedules = [
            new Schedule(),
        ];

        $queryMock        = $this->createQueryMockForFindPublic($schedules);
        $queryBuilderMock = $this->createQueryBuilderMockForFindPublic($alias, $theater, $queryMock);
        $targetMock       = $this->createTargetMockForFindPublic($alias, $queryBuilderMock, $type);

        $result = $targetMock->findPublic($type, $theater);
        $this->assertEquals($schedules, $result);
    }

    /**
     * @test
     */
    public function testFindPublicWithTheater(): void
    {
        $alias     = 's';
        $type      = ScheduleRepository::PUBLIC_TYPE_NOW_SHOWING;
        $theater   = '999';
        $schedules = [
            new Schedule(),
        ];

        $queryMock        = $this->createQueryMockForFindPublic($schedules);
        $queryBuilderMock = $this->createQueryBuilderMockForFindPublic($alias, $theater, $queryMock);
        $targetMock       = $this->createTargetMockForFindPublic($alias, $queryBuilderMock, $type);

        $result = $targetMock->findPublic($type, $theater);
        $this->assertEquals($schedules, $result);
    }

    /**
     * @test
     */
    public function testFindPublicInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $type = 'invalid';

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();
        $targetMock->findPublic($type);
    }

    /**
     * @param mixed $queryBuilder
     * @return MockInterface&LegacyMockInterface&ScheduleRepository
     */
    protected function createTargetMockForFindPublic(string $alias, $queryBuilder, string $type)
    {
        $mock = $this->createTargetMock();
        $mock
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mock
            ->shouldReceive('createQueryBuilder')
            ->with($alias)
            ->andReturn($queryBuilder);

        $mock
            ->shouldReceive('addNowShowingQuery')
            ->times($type === ScheduleRepository::PUBLIC_TYPE_NOW_SHOWING ? 1 : 0)
            ->with($queryBuilder, $alias);

        $mock
            ->shouldReceive('addComingSoonQuery')
            ->times($type === ScheduleRepository::PUBLIC_TYPE_COMING_SOON ? 1 : 0)
            ->with($queryBuilder, $alias);

        return $mock;
    }

    /**
     * @param mixed $result
     * @return MockInterface&LegacyMockInterface
     */
    protected function createQueryMockForFindPublic($result)
    {
        $mock = $this->createQueryMock();
        $mock
            ->shouldReceive('setFetchMode')
            ->with(ShowingTheater::class, 'theater', ClassMetadata::FETCH_EAGER);
        $mock
            ->shouldReceive('getResult')
            ->andReturn($result);

        return $mock;
    }

    /**
     * @param mixed $query
     * @return MockInterface&LegacyMockInterface&QueryBuilder
     */
    protected function createQueryBuilderMockForFindPublic(string $alias, ?string $theater, $query)
    {
        $mock = $this->createQueryBuilderMock();

        $aliasTitle      = 't';
        $aliasTitleImage = 'ti';
        $mock
            ->shouldReceive('addSelect')
            ->with($aliasTitle)
            ->andReturn($mock);
        $mock
            ->shouldReceive('innerJoin')
            ->with($alias . '.title', $aliasTitle)
            ->andReturn($mock);
        $mock
            ->shouldReceive('addSelect')
            ->with($aliasTitleImage)
            ->andReturn($mock);
        $mock
            ->shouldReceive('leftJoin')
            ->with($aliasTitle . '.image', $aliasTitleImage)
            ->andReturn($mock);

        $aliasShowingFormats = 'sf';
        $mock
            ->shouldReceive('addSelect')
            ->with($aliasShowingFormats)
            ->andReturn($mock);
        $mock
            ->shouldReceive('innerJoin')
            ->with($alias . '.showingFormats', $aliasShowingFormats)
            ->andReturn($mock);

        $aliasShowingTheaters1 = 'st1';
        $mock
            ->shouldReceive('addSelect')
            ->with($aliasShowingTheaters1)
            ->andReturn($mock);
        $mock
            ->shouldReceive('innerJoin')
            ->with($alias . '.showingTheaters', $aliasShowingTheaters1)
            ->andReturn($mock);

        $aliasShowingTheaters2 = 'st2';
        $aliasTheater          = 'th';

        $mock
            ->shouldReceive('innerJoin')
            ->times($theater ? 1 : 0)
            ->with($alias . '.showingTheaters', $aliasShowingTheaters2)
            ->andReturn($mock);
        $mock
            ->shouldReceive('innerJoin')
            ->times($theater ? 1 : 0)
            ->with($aliasShowingTheaters2 . '.theater', $aliasTheater)
            ->andReturn($mock);
        $mock
            ->shouldReceive('andWhere')
            ->times($theater ? 1 : 0)
            ->with($aliasTheater . '.masterCode = :theater')
            ->andReturn($mock);
        $mock
            ->shouldReceive('setParameter')
            ->times($theater ? 1 : 0)
            ->with('theater', $theater)
            ->andReturn($mock);

        $mock
            ->shouldReceive('getQuery')
            ->with()
            ->andReturn($query);

        return $mock;
    }
}
