<?php

namespace Tests\Unit\Http\Resources;

use App\Doctrine\Entities\ShowingTheater as ShowingTheaterEntity;
use App\Doctrine\Entities\Theater as TheaterEntity;
use App\Http\Resources\ShowingTheater;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ShowingTheaterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface&LegacyMockInterface&ShowingTheater
     */
    protected function createTargetMock()
    {
        return Mockery::mock(ShowingTheater::class);
    }

    /**
     * @return ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new ReflectionClass(ShowingTheater::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&ShowingTheaterEntity
     */
    protected function createShowingTheaterEntityMock()
    {
        return Mockery::mock(ShowingTheaterEntity::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&TheaterEntity
     */
    protected function createTheaterEntityMock()
    {
        return Mockery::mock(TheaterEntity::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testToArray()
    {
        $id      = 6;
        $name    = 'example_name';
        $nameJa  = '仮劇場';
        $coaCode = '999';

        $theaterEntityMock = $this->createTheaterEntityMock();
        $theaterEntityMock
            ->shouldReceive('getId')
            ->once()
            ->andReturn($id);
        $theaterEntityMock
            ->shouldReceive('getName')
            ->once()
            ->andReturn($name);
        $theaterEntityMock
            ->shouldReceive('getNameJa')
            ->once()
            ->andReturn($nameJa);
        $theaterEntityMock
            ->shouldReceive('getMasterCode')
            ->once()
            ->andReturn($coaCode);

        $showingTheaterEntityMock = $this->createShowingTheaterEntityMock();
        $showingTheaterEntityMock
            ->shouldReceive('getTheater')
            ->once()
            ->andReturn($theaterEntityMock);

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();
        $targetMock->resource = $showingTheaterEntityMock;

        $result = $targetMock->toArray(Mockery::mock(Request::class));
        $this->assertEquals($id, $result['id']);
        $this->assertEquals($name, $result['name']);
        $this->assertEquals($nameJa, $result['name_ja']);
        $this->assertEquals($coaCode, $result['coa_code']);
    }
}
