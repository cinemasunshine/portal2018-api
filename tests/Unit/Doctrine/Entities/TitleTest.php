<?php

declare(strict_types=1);

namespace Tests\Unit\Doctrine\Entities;

use App\Doctrine\Entities\Title;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @group unit
 */
class TitleTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return MockInterface&LegacyMockInterface&Title
     */
    protected function createTargetMock()
    {
        return Mockery::mock(Title::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(Title::class);
    }

    /**
     * @test
     */
    public function testGetRatingText(): void
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
     */
    public function testGetUniversalTexts(): void
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
