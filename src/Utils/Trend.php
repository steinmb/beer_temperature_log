<?php

declare(strict_types=1);

namespace steinmb\Utils;

use steinmb\Enums\TrendFormat;

final class Trend
{
    private const ranges = [
        'stable' => 0.1,
        'slowly' => 0.21,
        'steady' => 0.3,
        'medium' => 0.9,
        'fast' => 2,
    ];

    public function __construct(readonly public string $trend)
    {
    }

    public function getTrend(): string
    {
        return $this->trend;
    }

    /**
     * Create human friendly trend labels.
     *
     * @return string
     */
    public function createTrendLabels(): string
    {
        if ($this->trend < 0) {
            return $this->isFalling();
        }

        if ($this->trend === '' || $this->trend <= TrendFormat::Stable->speed()) {
            return TrendFormat::Stable->value;
        }

        foreach (array_reverse(TrendFormat::cases()) as $range) {
            $speed = $range->speed();

            if ($this->trend >= $speed) {
                return "$range->value {$this->direction()} ($speed)";
            }
        }

        return '';
    }

    private function isFalling(): string
    {
//        TrendFormat::cases();

        foreach (TrendFormat::cases() as $case) {
            $speed = $case->speed();
            if ($this->trend <= $speed) {
                return "$case->value {$this->direction()} ($speed)";
            }
        }
    }

    private function direction(): string
    {
        $direction = 'increasing';

        if ($this->trend < 0) {
            $direction = 'decreasing';
        }

        return $direction;
    }

}
