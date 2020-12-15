<?php

namespace Tests\Unit\Doctrine\Entities;

use App\Doctrine\Entities\Title;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
class TitleTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&Title
     */
    protected function createTargetMock()
    {
        return Mockery::mock(Title::class);
    }

    /**
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(Title::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function testGetRatingText()
    {
        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $rating = 1;
        $targetMock
            ->shouldReceive('getRating')
            ->with()
            ->andReturn($rating, null);

        $targetRef       = $this->createTargetReflection();
        $ratingLabelsRef = $targetRef->getProperty('ratingLabels');
        $ratingLabelsRef->setAccessible(true);
        $ratingLabels = $ratingLabelsRef->getValue($targetMock);

        $this->assertEquals($ratingLabels[$rating], $targetMock->getRatingText());
        $this->assertEquals(null, $targetMock->getRatingText());
    }

    /**
     * @test
     *
     * @return void
     */
    public function testGetUniversalTexts()
    {
        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();

        $universal = [1, 2];
        $targetMock
            ->shouldReceive('getUniversal')
            ->with()
            ->andReturn($universal, null);

        $targetRef          = $this->createTargetReflection();
        $universalLabelsRef = $targetRef->getProperty('universalLabels');
        $universalLabelsRef->setAccessible(true);
        $universalLabels = $universalLabelsRef->getValue($targetMock);

        $result = $targetMock->getUniversalTexts();

        foreach ($universal as $value) {
            $this->assertContains($universalLabels[$value], $result);
        }

        $this->assertEquals([], $targetMock->getUniversalTexts());
    }
}
