<?php

namespace Tests\Unit\Logging\Handler;

use App\Logging\Handler\AzureBlobStorageHandler;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @group unit
 */
class AzureBlobStorageHandlerTest extends TestCase
{
    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&AzureBlobStorageHandler
     */
    protected function createTargetMock()
    {
        return Mockery::mock(AzureBlobStorageHandler::class);
    }

    /**
     * @return \ReflectionClass
     */
    protected function createTargetReflection()
    {
        return new \ReflectionClass(AzureBlobStorageHandler::class);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&BlobRestProxy
     */
    protected function createBlobRestProxyMock()
    {
        return Mockery::mock(BlobRestProxy::class);
    }

    /**
     * @test
     * @return void
     */
    public function testConstruct()
    {
        $name = 'storage';
        $key = 'aaabbbccc';
        $container = 'container';
        $blob = 'blob';

        $blobRestProxyMock = $this->createBlobRestProxyMock();

        $targetMock = $this->createTargetMock();
        $targetMock
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        $targetMock
            ->shouldReceive('createClient')
            ->once()
            ->with($name, $key)
            ->andReturn($blobRestProxyMock);

        $targetRef = $this->createTargetReflection();

        /** @var \ReflectionMethod $targetConstructorRef */
        $targetConstructorRef = $targetRef->getConstructor();
        $targetConstructorRef->invoke($targetMock, $name, $key, $container, $blob);

        $clientRef = $targetRef->getProperty('client');
        $clientRef->setAccessible(true);
        $this->assertEquals($blobRestProxyMock, $clientRef->getValue($targetMock));
    }

    /**
     * @test
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @return void
     */
    public function testCreateClient()
    {
        $name = 'storage';
        $key = 'aaabbbccc';

        $blobRestProxyMock = $this->createBlobRestProxyAliasMock();
        $blobRestProxyMock
            ->shouldReceive('createBlobService')
            ->with(Mockery::on(function ($argument) use ($name, $key) {
                if (!strpos($argument, 'AccountName=' . $name)) {
                    return false;
                }

                if (!strpos($argument, 'AccountKey=' . $key)) {
                    return false;
                }

                return true;
            }))
            ->andReturn($blobRestProxyMock);

        $targetRef = $this->createTargetReflection();
        $createClientRef = $targetRef->getMethod('createClient');
        $createClientRef->setAccessible(true);

        $targetMock = $this->createTargetMock();
        $result = $createClientRef->invoke($targetMock, $name, $key);
        $this->assertEquals($blobRestProxyMock, $result);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&BlobRestProxy
     */
    protected function createBlobRestProxyAliasMock()
    {
        return Mockery::mock('alias:' . BlobRestProxy::class);
    }

    /**
     * @test
     * @return void
     */
    public function testWrite()
    {
        $isBlobCreated = false;
        $record = [
            'formatted' => 'test',
        ];

        $targetMock = $this->createTargetMock();
        $targetMock->shouldAllowMockingProtectedMethods();
        $targetMock
            ->shouldReceive('createBlob')
            ->once()
            ->with();
        $targetRef = $this->createTargetReflection();

        $isBlobCreatedRef = $targetRef->getProperty('isBlobCreated');
        $isBlobCreatedRef->setAccessible(true);
        $isBlobCreatedRef->setValue($targetMock, $isBlobCreated);

        $blobRestProxyMock = $this->createBlobRestProxyMockForWrite();

        $clientRef = $targetRef->getProperty('client');
        $clientRef->setAccessible(true);
        $clientRef->setValue($targetMock, $blobRestProxyMock);

        $writeRef = $targetRef->getMethod('write');
        $writeRef->setAccessible(true);
        $writeRef->invoke($targetMock, $record);

        $this->assertTrue($isBlobCreatedRef->getValue($targetMock));
    }

    /**
     * @test
     * @doesNotPerformAssertions
     * @return void
     */
    public function testWriteIsBlobCreated()
    {
        $isBlobCreated = true;
        $record = [
            'formatted' => 'test',
        ];

        $targetMock = $this->createTargetMock();
        $targetMock->shouldAllowMockingProtectedMethods();
        $targetMock
            ->shouldReceive('createBlob')
            ->never();
        $targetRef = $this->createTargetReflection();

        $isBlobCreatedRef = $targetRef->getProperty('isBlobCreated');
        $isBlobCreatedRef->setAccessible(true);
        $isBlobCreatedRef->setValue($targetMock, $isBlobCreated);

        $blobRestProxyMock = $this->createBlobRestProxyMockForWrite();

        $clientRef = $targetRef->getProperty('client');
        $clientRef->setAccessible(true);
        $clientRef->setValue($targetMock, $blobRestProxyMock);

        $writeRef = $targetRef->getMethod('write');
        $writeRef->setAccessible(true);
        $writeRef->invoke($targetMock, $record);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&BlobRestProxy
     */
    protected function createBlobRestProxyMockForWrite()
    {
        $mock = $this->createBlobRestProxyMock();
        $mock
            ->shouldReceive('appendBlock')
            ->once();

        return $mock;
    }

    /**
     * @test
     * @doesNotPerformAssertions
     * @return void
     */
    public function testCreateBlobExisting()
    {
        $container = 'example';
        $blob = 'test.log';

        $blobRestProxyMock = $this->createBlobRestProxyMock();
        $blobRestProxyMock
            ->shouldReceive('getBlobMetadata')
            ->once()
            ->with($container, $blob);

        $blobRestProxyMock
            ->shouldReceive('createAppendBlob')
            ->never();

        $targetMock = $this->createTargetMock();
        $targetRef = $this->createTargetReflection();

        $clientRef = $targetRef->getProperty('client');
        $clientRef->setAccessible(true);
        $clientRef->setValue($targetMock, $blobRestProxyMock);

        $containerRef = $targetRef->getProperty('container');
        $containerRef->setAccessible(true);
        $containerRef->setValue($targetMock, $container);

        $blobRef = $targetRef->getProperty('blob');
        $blobRef->setAccessible(true);
        $blobRef->setValue($targetMock, $blob);

        $createBlobRef = $targetRef->getMethod('createBlob');
        $createBlobRef->setAccessible(true);
        $createBlobRef->invoke($targetMock);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     * @return void
     */
    public function testCreateBlobNotFound()
    {
        $container = 'example';
        $blob = 'test.log';

        $exception = $this->createServiceException(404);

        $blobRestProxyMock = $this->createBlobRestProxyMock();
        $blobRestProxyMock
            ->shouldReceive('getBlobMetadata')
            ->once()
            ->with($container, $blob)
            ->andThrow($exception);

        $blobRestProxyMock
            ->shouldReceive('createAppendBlob')
            ->once();

        $targetMock = $this->createTargetMock();
        $targetRef = $this->createTargetReflection();

        $clientRef = $targetRef->getProperty('client');
        $clientRef->setAccessible(true);
        $clientRef->setValue($targetMock, $blobRestProxyMock);

        $containerRef = $targetRef->getProperty('container');
        $containerRef->setAccessible(true);
        $containerRef->setValue($targetMock, $container);

        $blobRef = $targetRef->getProperty('blob');
        $blobRef->setAccessible(true);
        $blobRef->setValue($targetMock, $blob);

        $createBlobRef = $targetRef->getMethod('createBlob');
        $createBlobRef->setAccessible(true);
        $createBlobRef->invoke($targetMock);
    }

    /**
     * @test
     * @return void
     */
    public function testCreateBlobServiceError()
    {
        $container = 'example';
        $blob = 'test.log';

        $exception = $this->createServiceException(500);

        $blobRestProxyMock = $this->createBlobRestProxyMock();
        $blobRestProxyMock
            ->shouldReceive('getBlobMetadata')
            ->once()
            ->with($container, $blob)
            ->andThrow($exception);

        $blobRestProxyMock
            ->shouldReceive('createAppendBlob')
            ->never();

        $targetMock = $this->createTargetMock();
        $targetRef = $this->createTargetReflection();

        $clientRef = $targetRef->getProperty('client');
        $clientRef->setAccessible(true);
        $clientRef->setValue($targetMock, $blobRestProxyMock);

        $containerRef = $targetRef->getProperty('container');
        $containerRef->setAccessible(true);
        $containerRef->setValue($targetMock, $container);

        $blobRef = $targetRef->getProperty('blob');
        $blobRef->setAccessible(true);
        $blobRef->setValue($targetMock, $blob);

        $this->expectException(ServiceException::class);

        $createBlobRef = $targetRef->getMethod('createBlob');
        $createBlobRef->setAccessible(true);
        $createBlobRef->invoke($targetMock);
    }

    /**
     * @param integer $status
     * @return ServiceException
     */
    protected function createServiceException(int $status)
    {
        $responceMock = $this->createResponceMock();
        $responceMock
            ->shouldReceive('getStatusCode')
            ->andReturn($status);
        $responceMock
            ->shouldReceive('getReasonPhrase')
            ->andReturn('Reason Phrase');
        $responceMock
            ->shouldReceive('getBody')
            ->andReturn('Body');

        return new ServiceException($responceMock);
    }

    /**
     * @return \Mockery\MockInterface&\Mockery\LegacyMockInterface&ResponseInterface
     */
    protected function createResponceMock()
    {
        return Mockery::mock(ResponseInterface::class);
    }
}
