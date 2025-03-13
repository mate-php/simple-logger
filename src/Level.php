<?php

declare(strict_types=1);

namespace Mate\Logger;

/**
 * @codeCoverageIgnore
 */
enum Level: int
{
    case Debug = 1;
    case Info = 2;
    case Notice = 3;
    case Warning = 4;
    case Error = 5;
    case Critical = 6;
    case Alert = 7;
    case Emergency = 8;

    public function getName(): string
    {
        return match ($this) {
            self::Debug => 'DEBUG',
            self::Info => 'INFO',
            self::Notice => 'NOTICE',
            self::Warning => 'WARNING',
            self::Error => 'ERROR',
            self::Critical => 'CRITICAL',
            self::Alert => 'ALERT',
            self::Emergency => 'EMERGENCY',
        };
    }

    public static function fromValue(int $value): self
    {
        return self::from($value);
    }

    public static function fromName(string $name): self
    {
        $name = strtolower($name);
        return match ($name) {
            'debug' => self::Debug,
            'info' => self::Info,
            'notice' => self::Notice,
            'warning' => self::Warning,
            'error' => self::Error,
            'critical' => self::Critical,
            'alert' => self::Alert,
            'emergency' => self::Emergency,
        };
    }
}
