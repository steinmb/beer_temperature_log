<?php

declare(strict_types=1);

namespace steinmb\Enums;

use Exception;
use steinmb\ValueObjects\Range;

enum TrendFormat: string
{
    case Stable = 'stable';
    case Slowly = 'slowly';
    case Steady = 'steady';
    case Medium = 'medium';
    case Fast = 'fast';

    /**
     * @throws Exception
     */
    public function description(): string
    {
        return match ($this) {
            self::Stable => throw new Exception('To be implemented'),
            self::Slowly => throw new Exception('To be implemented'),
            self::Steady => throw new Exception('To be implemented'),
            self::Medium => throw new Exception('To be implemented'),
            self::Fast => throw new Exception('To be implemented'),
        };
    }

    public function speed(): Range
    {
        return match ($this) {
            self::Stable => new Range(0.1, 0.2),
            self::Slowly => new Range(0.21, 0.3),
            self::Steady => new Range(0.31, 0.9),
            self::Medium => new Range(0.91, 2),
            self::Fast => new Range(2.01, 5),
        };
    }
}
