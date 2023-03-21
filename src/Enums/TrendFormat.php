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
