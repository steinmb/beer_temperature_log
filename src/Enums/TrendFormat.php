<?php

declare(strict_types=1);

namespace steinmb\Enums;

enum TrendFormat: string
{
    case Stable = 'stable';
    case Slowly = 'slowly';
    case Steady = 'steady';
    case Medium = 'medium';
    case Fast = 'fast';

    public function description(): string
    {
        return match ($this) {
            self::Stable => throw new \Exception('To be implemented'),
            self::Slowly => throw new \Exception('To be implemented'),
            self::Steady => throw new \Exception('To be implemented'),
            self::Medium => throw new \Exception('To be implemented'),
            self::Fast => throw new \Exception('To be implemented'),
        };
    }

    public function speed(): string
    {
        return match ($this) {
            self::Stable => '0.1',
            self::Slowly => '0.21',
            self::Steady => '0.3',
            self::Medium => '0.9',
            self::Fast => '2',
        };
    }
}
