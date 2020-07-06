<?php

namespace Tests\Unit\Http\Resources;

use App\Doctrine\Entities\ShowingFormat as ShowingFormatEntity;
use App\Http\Resources\ShowingFormat;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ShowingFormatTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&ShowingFormat
     */
    protected function createTargetMock()
    {
        return Mockery::mock(ShowingFormat::class);
    }

    /**
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(ShowingFormat::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&ShowingFormatEntity
     */
    protected function createShowingFormatEntityMock()
    {
        return Mockery::mock(ShowingFormatEntity::class);
    }

    /**
     * @test
     * @return void
     */
    public function testToArray()
    {
        $system = 'example system';
        $sound = 'example sound';
        $voice = 'example voice';

        $showingFormatEntityMock = $this->createShowingFormatEntityMock();
        $showingFormatEntityMock
            ->shouldReceive('getSystemText')
            ->once()
            ->andReturn($system);
        $showingFormatEntityMock
            ->shouldReceive('getSoundText')
            ->once()
            ->andReturn($sound);
        $showingFormatEntityMock
            ->shouldReceive('getVoiceText')
            ->once()
            ->andReturn($voice);

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();
        $targetRef = $this->createTargetReflection();
        $resourceRef = $targetRef->getProperty('resource');
        $resourceRef->setAccessible(true);
        $resourceRef->setValue($targetMock, $showingFormatEntityMock);

        $result = $targetMock->toArray(Mockery::mock(Request::class));
        $this->assertEquals($system, $result['system']);
        $this->assertEquals($sound, $result['sound']);
        $this->assertEquals($voice, $result['voice']);
    }
}
