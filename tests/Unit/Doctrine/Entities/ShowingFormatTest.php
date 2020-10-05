<?php

namespace Tests\Unit\Doctrine\Entities;

use App\Doctrine\Entities\ShowingFormat;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
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
     * @test
     * @return void
     */
    public function testGetSystemText()
    {
        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $system = 1;
        $targetMock
            ->shouldReceive('getSystem')
            ->with()
            ->andReturn($system);

        $targetRef       = $this->createTargetReflection();
        $systemLablesRef = $targetRef->getProperty('systemLables');
        $systemLablesRef->setAccessible(true);
        $systemLables = $systemLablesRef->getValue($targetMock);

        $this->assertEquals($systemLables[$system], $targetMock->getSystemText());
    }

    /**
     * @test
     * @return void
     */
    public function testGetSoundText()
    {
        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $sound = 1;
        $targetMock
            ->shouldReceive('getSound')
            ->with()
            ->andReturn($sound);

        $targetRef      = $this->createTargetReflection();
        $soundLabelsRef = $targetRef->getProperty('soundLabels');
        $soundLabelsRef->setAccessible(true);
        $soundLabels = $soundLabelsRef->getValue($targetMock);

        $this->assertEquals($soundLabels[$sound], $targetMock->getSoundText());
    }

    /**
     * @test
     * @return void
     */
    public function testGetVoiceText()
    {
        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $voice = 1;
        $targetMock
            ->shouldReceive('getVoice')
            ->with()
            ->andReturn($voice);

        $targetRef      = $this->createTargetReflection();
        $voiceLabelsRef = $targetRef->getProperty('voiceLabels');
        $voiceLabelsRef->setAccessible(true);
        $voiceLabels = $voiceLabelsRef->getValue($targetMock);

        $this->assertEquals($voiceLabels[$voice], $targetMock->getVoiceText());
    }
}
