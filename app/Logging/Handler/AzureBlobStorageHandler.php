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
     * @param string $name
     * @param string $key
     * @param string $container
     * @param string $blob
     * @param int|string $level
     * @param boolean $bubble
     */
    public function __construct(
        string $name,
        string $key,
        string $container,
        string $blob,
        $level = Logger::DEBUG,
        $bubble = true
    ) {
        $client = $this->createClient($name, $key);

        parent::__construct($client, $container, $blob, $level, $bubble);
    }

    /**
     * @param string $name
     * @param string $key
     * @return BlobRestProxy
     */
    protected function createClient(string $name, string $key): BlobRestProxy
    {
        $connection = sprintf(
            'DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s',
            $name,
            $key
        );

        return BlobRestProxy::createBlobService($connection);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
        if (!$this->isBlobCreated) {
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
