<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Resources;

use App\Doctrine\Entities\File as FileEntity;
use App\Doctrine\Entities\Title as TitleEntity;
use App\Http\Resources\Title;
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
     * @return MockInterface&LegacyMockInterface&TitleEntity
     */
    protected function createTitleEntityMock()
    {
        return Mockery::mock(TitleEntity::class);
    }

    /**
     * @test
     */
    public function testToArray(): void
    {
        $id   = 7;
        $name = 'example name';

        $image     = 'https://storage.example.com/sample.jpg';
        $imageMock = $this->createFileEntityMock();
        $imageMock
            ->shouldReceive('getUrl')
            ->once()
            ->andReturn($image);

        $credit       = 'example credit';
        $catchcopy    = 'example catchcopy';
        $introduction = 'example introduction';
        $director     = 'example director';
        $cast         = 'example cast';
        $officialSite = 'https://example.com/official-site';
        $rating       = 'example rating';
        $universal    = ['example universal'];

        $titleEntityMock = $this->createTitleEntityMock();
        $titleEntityMock
            ->shouldReceive('getId')
            ->andReturn($id);
        $titleEntityMock
            ->shouldReceive('getImage')
            ->andReturn($imageMock, null);
        $titleEntityMock
            ->shouldReceive('getName')
            ->andReturn($name);
        $titleEntityMock
            ->shouldReceive('getCredit')
            ->andReturn($credit);
        $titleEntityMock
            ->shouldReceive('getCatchcopy')
            ->andReturn($catchcopy);
        $titleEntityMock
            ->shouldReceive('getIntroduction')
            ->andReturn($introduction);
        $titleEntityMock
            ->shouldReceive('getDirector')
            ->andReturn($director);
        $titleEntityMock
            ->shouldReceive('getCast')
            ->andReturn($cast);
        $titleEntityMock
            ->shouldReceive('getOfficialSite')
            ->andReturn($officialSite);
        $titleEntityMock
            ->shouldReceive('getRatingText')
            ->andReturn($rating);
        $titleEntityMock
            ->shouldReceive('getUniversalTexts')
            ->andReturn($universal);

        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();
        $targetRef   = $this->createTargetReflection();
        $resourceRef = $targetRef->getProperty('resource');
        $resourceRef->setAccessible(true);
        $resourceRef->setValue($targetMock, $titleEntityMock);

        $result = $targetMock->toArray(Mockery::mock(Request::class));
        $this->assertEquals($id, $result['id']);
        $this->assertEquals($name, $result['name']);
        $this->assertEquals($image, $result['image']);
        $this->assertEquals($credit, $result['credit']);
        $this->assertEquals($catchcopy, $result['catchcopy']);
        $this->assertEquals($introduction, $result['introduction']);
        $this->assertEquals($director, $result['director']);
        $this->assertEquals($cast, $result['cast']);
        $this->assertEquals($officialSite, $result['official_site']);
        $this->assertEquals($rating, $result['rating']);
        $this->assertEquals($universal, $result['universal']);

        $result2 = $targetMock->toArray(Mockery::mock(Request::class));
        $this->assertNull($result2['image']);
    }

    /**
     * @return MockInterface&LegacyMockInterface&FileEntity
     */
    protected function createFileEntityMock()
    {
        return Mockery::mock(FileEntity::class);
    }
}
