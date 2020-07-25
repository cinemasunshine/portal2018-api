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
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @return void
     */
    public function testConstruct()
    {
        $secure = true;
        $name = 'storage';
        $key = 'aaabbbccc';
        $endpoint = null;
        $container = 'container';
        $blob = 'blob';

        $targetMock = $this->createTargetMock();
        $targetMock
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $connectionStr = 'example_connection_string';
        $targetMock
            ->shouldReceive('createConnectionString')
            ->once()
            ->with($secure, $name, $key, $endpoint)
            ->andReturn($connectionStr);

        $blobRestProxyMock = $this->createBlobRestProxyAliasMock();
        $blobRestProxyMock
            ->shouldReceive('createBlobService')
            ->with($connectionStr)
            ->andReturn($blobRestProxyMock);

        $targetRef = $this->createTargetReflection();

        /** @var \ReflectionMethod $targetConstructorRef */
        $targetConstructorRef = $targetRef->getConstructor();
        $targetConstructorRef->invoke($targetMock, $secure, $name, $key, $endpoint, $container, $blob);

        $clientRef = $targetRef->getProperty('client');
        $clientRef->setAccessible(true);
        $this->assertEquals($blobRestProxyMock, $clientRef->getValue($targetMock));
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
    public function testCreateConnectionString()
    {
        $targetRef = $this->createTargetReflection();
        $createConnectionStringRef = $targetRef->getMethod('createConnectionString');
        $createConnectionStringRef->setAccessible(true);

        $targetMock = $this->createTargetMock();

        $secure = true;
        $name = 'example_name';
        $key = 'example_key';
        $endpoint = null;

        $resulFirst = $createConnectionStringRef->invoke(
            $targetMock,
            $secure,
            $name,
            $key,
            $endpoint
        );

        $this->assertStringContainsString('DefaultEndpointsProtocol=https;', $resulFirst);
        $this->assertStringContainsString(sprintf('AccountName=%s;', $name), $resulFirst);
        $this->assertStringContainsString(sprintf('AccountKey=%s;', $key), $resulFirst);
        $this->assertStringNotContainsString('BlobEndpoint', $resulFirst);

        $secure = false;
        $endpoint = 'https://blob.example.com';

        $resulSecond = $createConnectionStringRef->invoke(
            $targetMock,
            $secure,
            $name,
            $key,
            $endpoint
        );
        $this->assertStringContainsString('DefaultEndpointsProtocol=http;', $resulSecond);
        $this->assertStringContainsString(sprintf('BlobEndpoint=%s;', $endpoint), $resulSecond);
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
