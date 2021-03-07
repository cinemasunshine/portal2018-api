<?php

declare(strict_types=1);

namespace Tests\Unit\Logging\Handler;

use App\Logging\Handler\AzureBlobStorageHandler;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;
use ReflectionMethod;

/**
 * @group unit
 */
class AzureBlobStorageHandlerTest extends TestCase
{
    /**
     * @return MockInterface&LegacyMockInterface&AzureBlobStorageHandler
     */
    protected function createTargetMock()
    {
        return Mockery::mock(AzureBlobStorageHandler::class);
    }

    protected function createTargetReflection(): ReflectionClass
    {
        return new ReflectionClass(AzureBlobStorageHandler::class);
    }

    /**
     * @return MockInterface&LegacyMockInterface&BlobRestProxy
     */
    protected function createBlobRestProxyMock()
    {
        return Mockery::mock(BlobRestProxy::class);
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     * @test
     */
    public function testConstruct(): void
    {
        $secure    = true;
        $name      = 'storage';
        $key       = 'aaabbbccc';
        $endpoint  = null;
        $container = 'container';
        $blob      = 'blob';

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

        /** @var ReflectionMethod $targetConstructorRef */
        $targetConstructorRef = $targetRef->getConstructor();
        $targetConstructorRef->invoke($targetMock, $secure, $name, $key, $endpoint, $container, $blob);

        $clientRef = $targetRef->getProperty('client');
        $clientRef->setAccessible(true);
        $this->assertEquals($blobRestProxyMock, $clientRef->getValue($targetMock));
    }

    /**
     * @return MockInterface&LegacyMockInterface&BlobRestProxy
     */
    protected function createBlobRestProxyAliasMock()
    {
        return Mockery::mock('alias:' . BlobRestProxy::class);
    }

    /**
     * @test
     */
    public function testCreateConnectionString(): void
    {
        $targetRef                 = $this->createTargetReflection();
        $createConnectionStringRef = $targetRef->getMethod('createConnectionString');
        $createConnectionStringRef->setAccessible(true);

        $targetMock = $this->createTargetMock();

        $secure   = true;
        $name     = 'example_name';
        $key      = 'example_key';
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

        $secure   = false;
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
     */
    public function testWrite(): void
    {
        $isBlobCreated = false;
        $record        = ['formatted' => 'test'];

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
     * @doesNotPerformAssertions
     * @test
     */
    public function testWriteIsBlobCreated(): void
    {
        $isBlobCreated = true;
        $record        = ['formatted' => 'test'];

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
     * @return MockInterface&LegacyMockInterface&BlobRestProxy
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
     * @doesNotPerformAssertions
     * @test
     */
    public function testCreateBlobExisting(): void
    {
        $container = 'example';
        $blob      = 'test.log';

        $blobRestProxyMock = $this->createBlobRestProxyMock();
        $blobRestProxyMock
            ->shouldReceive('getBlobMetadata')
            ->once()
            ->with($container, $blob);

        $blobRestProxyMock
            ->shouldReceive('createAppendBlob')
            ->never();

        $targetMock = $this->createTargetMock();
        $targetRef  = $this->createTargetReflection();

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
     * @doesNotPerformAssertions
     * @test
     */
    public function testCreateBlobNotFound(): void
    {
        $container = 'example';
        $blob      = 'test.log';

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
        $targetRef  = $this->createTargetReflection();

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
     */
    public function testCreateBlobServiceError(): void
    {
        $container = 'example';
        $blob      = 'test.log';

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
        $targetRef  = $this->createTargetReflection();

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

    protected function createServiceException(int $status): ServiceException
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
     * @return MockInterface&LegacyMockInterface&ResponseInterface
     */
    protected function createResponceMock()
    {
        return Mockery::mock(ResponseInterface::class);
    }
}
