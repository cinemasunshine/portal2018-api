<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\ScheduleCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ScheduleCollectionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&ScheduleCollection<mixed>
     */
    protected function createTargetMock()
    {
        return Mockery::mock(ScheduleCollection::class);
    }

    /**
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(ScheduleCollection::class);
    }

    /**
     * @test
     * @return void
     */
    public function testToArray()
    {
        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $collectionMock = Mockery::mock(Collection::class);
        $targetMock->collection = $collectionMock;

        $result = $targetMock->toArray(Mockery::mock(Request::class));
        $this->assertEquals($collectionMock, $result['schedules']);
    }
}
