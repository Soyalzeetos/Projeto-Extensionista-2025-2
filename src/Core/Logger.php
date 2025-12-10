<?php

namespace App\Core;

class Logger
{
    private const LOG_DIR = __DIR__ . '/../../logs';

    public static function info(string $message, array $context = []): void
    {
        self::write('INF', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::write('ERR', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::write('WRN', $message, $context);
    }

    private static function write(string $level, string $message, array $context = []): void
    {
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        $contextString = self::formatContext($context);

        $logLine = sprintf(
            "%s %s %s %s" . PHP_EOL,
            $timestamp,
            $level,
            $message,
            $contextString
        );

        file_put_contents('php://stderr', $logLine, FILE_APPEND);

        if (!is_dir(self::LOG_DIR)) {
            mkdir(self::LOG_DIR, 0777, true);
        }
        $filename = self::LOG_DIR . '/app-' . date('Y-m-d') . '.log';
        file_put_contents($filename, $logLine, FILE_APPEND);
    }

    private static function formatContext(array $context): string
    {
        $pairs = [];
        foreach ($context as $key => $value) {
            if (is_bool($value)) {
                $valStr = $value ? 'true' : 'false';
            } elseif (is_null($value)) {
                $valStr = 'null';
            } elseif (is_array($value) || is_object($value)) {
                $valStr = json_encode($value);
            } else {
                $valStr = (string)$value;
            }

            if (str_contains($valStr, ' ') || str_contains($valStr, '"') || str_contains($valStr, '=')) {
                $valStr = '"' . str_replace('"', '\"', $valStr) . '"';
            }

            $pairs[] = "$key=$valStr";
        }

        return implode(' ', $pairs);
    }
}
