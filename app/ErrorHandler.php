<?php

namespace App;

class ErrorHandler
{
    public static function handleException(\Throwable $exception): void
    {
        http_response_code(500);

        echo json_encode([
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage(),
            'line'    => $exception->getLine(),
            'file'    => $exception->getFile(),
        ], JSON_PRETTY_PRINT);
    }

    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        http_response_code(500);

        echo json_encode([
            'code'    => $errno,
            'message' => $errstr,
            'line'    => $errline,
            'file'    => $errfile,
        ], JSON_PRETTY_PRINT);

        return true; // indicates that PHP should not execute its internal error handler
    }
}
