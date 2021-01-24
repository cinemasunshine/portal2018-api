<?php

namespace Tests\Unit\Http\Resources;

use App\Doctrine\Entities\ShowingFormat as ShowingFormatEntity;
use App\Http\Resources\ShowingFormat;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @group unit
 */
class ShowingFormatTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface&LegacyMockInterface&ShowingFormat
     */
    protected function createTargetMock()
    {
        return Mockery::mock(ShowingFormat::class);
    }

    /**
     * @return ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new ReflectionClass(ShowingFormat::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&ShowingFormatEntity
     */
    protected function createShowingFormatEntityMock()
    {
        return Mockery::mock(ShowingFormatEntity::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testToArray()
    {
        $systemId   = 2;
        $systemName = 'example system';
        $soundId    = 3;
        $soundName  = 'example sound';
        $voiceId    = 4;
        $voiceName  = 'example voice';

        $showingFormatEntityMock = $this->createShowingFormatEntityMock();
        $showingFormatEntityMock
            ->shouldReceive('getSystem')
            ->once()
            ->andReturn($systemId);
        $showingFormatEntityMock
            ->shouldReceive('getSystemText')
            ->once()
            ->andReturn($systemName);
        $showingFormatEntityMock
            ->shouldReceive('getSound')
            ->once()
            ->andReturn($soundId);
        $showingFormatEntityMock
            ->shouldReceive('getSoundText')
            ->once()
            ->andReturn($soundName);
        $showingFormatEntityMock
            ->shouldReceive('getVoice')
            ->once()
            ->andReturn($voiceId);
        $showingFormatEntityMock
            ->shouldReceive('getVoiceText')
            ->once()
            ->andReturn($voiceName);

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();
        $targetRef   = $this->createTargetReflection();
        $resourceRef = $targetRef->getProperty('resource');
        $resourceRef->setAccessible(true);
        $resourceRef->setValue($targetMock, $showingFormatEntityMock);

        $result = $targetMock->toArray(Mockery::mock(Request::class));
        $this->assertEquals($systemId, $result['system_id']);
        $this->assertEquals($systemName, $result['system_name']);
        $this->assertEquals($soundId, $result['sound_id']);
        $this->assertEquals($soundName, $result['sound_name']);
        $this->assertEquals($voiceId, $result['voice_id']);
        $this->assertEquals($voiceName, $result['voice_name']);
    }
}
