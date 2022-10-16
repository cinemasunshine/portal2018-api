<?php

declare(strict_types=1);

namespace App\Logging\Handler;

use Google\Cloud\Logging\PsrLogger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class GoogleCloudLoggingHandler extends AbstractProcessingHandler
{
    protected PsrLogger $logger;

    /**
     * @param array<string, mixed> $clientOptions
     */
    public function __construct(
        string $name,
        string $clientClass,
        array $clientOptions = [],
        int $level = Logger::DEBUG,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);

        $client = new $clientClass($clientOptions);

        $this->logger = $client->psrLogger($name);
    }

    /**
     * {@inheritdoc}
     *
     * @param array<string, mixed> $record
     */
    protected function write(array $record): void
    {
        $this->logger->log(
            $record['level_name'],
            $record['formatted'],
            $record['context']
        );
    }
}
