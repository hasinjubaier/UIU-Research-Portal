<?php

namespace App\Utils;

use Throwable;

class Logger
{
    private static function getLogFile(): string
    {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        return $logDir . '/app-' . date('Y-m-d') . '.log';
    }

    public static function log(string $level, string $message, mixed $context = []): void
    {
        $contextStr = '';
        if ($context instanceof Throwable) {
            $contextStr = sprintf(
                " Exception: %s in %s:%d\nStack trace:\n%s",
                $context->getMessage(),
                $context->getFile(),
                $context->getLine(),
                $context->getTraceAsString()
            );
        } elseif (!empty($context)) {
            $contextStr = ' ' . json_encode($context, JSON_UNESCAPED_SLASHES);
        }

        $formatted = sprintf(
            "[%s] [%s] %s%s\n",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message,
            $contextStr
        );

        file_put_contents(self::getLogFile(), $formatted, FILE_APPEND | LOCK_EX);
    }

    public static function info(string $message, mixed $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    public static function error(string $message, mixed $context = []): void
    {
        self::log('ERROR', $message, $context);
    }

    public static function warning(string $message, mixed $context = []): void
    {
        self::log('WARNING', $message, $context);
    }

    public static function debug(string $message, mixed $context = []): void
    {
        self::log('DEBUG', $message, $context);
    }
}
