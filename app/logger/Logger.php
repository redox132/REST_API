<?php

namespace App\Logger;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

class Logger
{
    public static function getLogger(string $channel = 'app'): MonologLogger
    {
        $logger = new MonologLogger($channel);

        // Save logs to logs/app.log
        $logFile = __DIR__ . '/../logs/app.log';
        $logger->pushHandler(new StreamHandler($logFile, MonologLogger::DEBUG));

        return $logger;
    }
}
