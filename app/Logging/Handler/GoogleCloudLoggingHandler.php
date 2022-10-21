<?php

declare(strict_types=1);

namespace App\Logging\Handler;

use Blue32a\MonologGoogleCloudLoggingHandler\GoogleCloudLoggingHandler as BaseHandler;
use Google\Cloud\Logging\PsrLogger;
use Monolog\Logger;

class GoogleCloudLoggingHandler extends BaseHandler
{
    protected PsrLogger $logger;

    /**
     * @param array<string, mixed> $clientConfig
     */
    public function __construct(
        string $name,
        array $clientConfig = [],
        int $level = Logger::DEBUG,
        bool $bubble = true
    ) {
        $client = BaseHandler::factoryLoggingClient($clientConfig);

        parent::__construct($name, $client, [], $level, $bubble);
    }
}
