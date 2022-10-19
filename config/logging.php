<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

/**
 * 例外発生時にemergency loggerが使用され、
 * GAE環境では一時ファイルでないとエラーになってしまう。
 */
$logFilePath = env('LOG_FILE_PATH', storage_path('logs/laravel.log'));

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'development_stack' => [
            'driver' => 'stack',
            'channels' => ['gcp', 'stderr'],
            'ignore_exceptions' => true,
        ],

        'production_stack' => [
            'driver' => 'stack',
            'channels' => ['gcp', 'stderr'],
            'ignore_exceptions' => true,
        ],

        'single' => [
            'driver' => 'single',
            'path' => $logFilePath,
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => $logFilePath,
            'level' => 'debug',
            'days' => 14,
        ],

        'daily_error' => [
            'driver' => 'daily',
            'path' => $logFilePath,
            'level' => 'error',
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'gcp' => [
            'driver' => 'monolog',
            'handler' => App\Logging\Handler\GoogleCloudLoggingHandler::class,
            'level' => 'debug',
            'with' => [
                'name' => 'app',
                'clientClass' => Google\Cloud\Logging\LoggingClient::class,
                'clientOptions' => [
                    'projectId' => env('GOOGLE_CLOUD_PROJECT'),
                ],
            ],
        ],

        'emergency' => [
            'path' => $logFilePath,
        ],
    ],

];
