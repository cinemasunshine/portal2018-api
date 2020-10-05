<?php

declare(strict_types=1);

namespace App\Logging\Handler;

use Blue32a\Monolog\Handler\AzureBlobStorageHandler as BaseHelper;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use Monolog\Logger;

class AzureBlobStorageHandler extends BaseHelper
{
    /** @var bool */
    protected $isBlobCreated = false;

    /**
     * @param boolean    $secure
     * @param string     $name
     * @param string     $key
     * @param string     $container
     * @param string     $blob
     * @param int|string $level
     * @param boolean    $bubble
     */
    public function __construct(
        bool $secure,
        string $name,
        string $key,
        ?string $endpoint,
        string $container,
        string $blob,
        $level = Logger::DEBUG,
        $bubble = true
    ) {
        $connectionStr = $this->createConnectionString($secure, $name, $key, $endpoint);
        $client        = BlobRestProxy::createBlobService($connectionStr);

        parent::__construct($client, $container, $blob, $level, $bubble);
    }

    /**
     * @param boolean     $secure
     * @param string      $name
     * @param string      $key
     * @param string|null $endpoint
     * @return string
     */
    protected function createConnectionString(
        bool $secure,
        string $name,
        string $key,
        ?string $endpoint
    ): string {
        $connectionStr = sprintf(
            'DefaultEndpointsProtocol=%s;AccountName=%s;AccountKey=%s;',
            $secure ? 'https' : 'http',
            $name,
            $key
        );

        if ($endpoint) {
            $connectionStr .= sprintf('BlobEndpoint=%s;', $endpoint);
        }

        return $connectionStr;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
        if (! $this->isBlobCreated) {
            $this->createBlob();
            $this->isBlobCreated = true;
        }

        parent::write($record);
    }

    /**
     * @return void
     */
    protected function createBlob()
    {
        try {
            $this->client->getBlobMetadata($this->container, $this->blob);
        } catch (ServiceException $e) {
            if ($e->getCode() !== 404) {
                throw $e;
            }

            $this->client->createAppendBlob($this->container, $this->blob);
        }
    }
}
