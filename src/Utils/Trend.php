<?php

declare(strict_types=1);

namespace steinmb\Utils;

use steinmb\Enums\TrendFormat;

final readonly class Trend
{
    public function __construct(public float $trend)
    {
    }

    public function getTrend(): float
    {
        return $this->trend;
    }

    /**
     * Create human-friendly trend labels.
     *
     * @return string
     */
    public function createTrendLabels(): string
    {
        foreach (TrendFormat::cases() as $case) {
            $range = $case->speed();

            if ($this->trend < 0) {
                $string = $range->negativeWithinRange($this->trend);
            } else {
                $string = $range->withinRange($this->trend);
            }

            if ($string === true) {
                return $case->value;
            }
        }

        return 'unknown';
    }

    public function direction(): string
    {
        $direction = 'increasing';

        if ($this->trend < 0) {
            $direction = 'decreasing';
        }

        return $direction;
    }
}
