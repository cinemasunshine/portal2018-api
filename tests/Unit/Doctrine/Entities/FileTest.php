<?php

namespace Tests\Unit\Doctrine\Entities;

use App\Doctrine\Entities\File;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&File
     */
    protected function createTargetMock()
    {
        return Mockery::mock(File::class);
    }

    /**
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(File::class);
    }

    /**
     * @test
     * @return void
     */
    public function testGetBlobContainer()
    {
        $targetRef = $this->createTargetReflection();
        $blobContainerRef = $targetRef->getProperty('blobContainer');
        $blobContainerRef->setAccessible(true);
        $blobContainer = $blobContainerRef->getValue(new File());

        $this->assertEquals($blobContainer, File::getBlobContainer());
    }

    /**
     * @test
     * @return void
     */
    public function testGetUrl()
    {
        $targetMock = $this->createTargetMock();
        $targetMock->makePartial();
        $targetMock
            ->shouldReceive('getName')
            ->andReturn('sample.txt');

        $url = 'https://storage.example.com/sample.txt';
        $filesystemMock = $this->createFilesystemMock();
        $filesystemMock
            ->shouldReceive('url')
            ->andReturn($url);

        Storage::shouldReceive('disk')
            ->with('azure-blob-file')
            ->andReturn($filesystemMock);

        $this->assertEquals($url, $targetMock->getUrl());
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&Filesystem
     */
    protected function createFilesystemMock()
    {
        return Mockery::mock(Filesystem::class);
    }
}
