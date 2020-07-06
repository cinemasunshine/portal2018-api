<?php

namespace Tests\Unit\Doctrine\Entities;

use App\Doctrine\Entities\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
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
}
