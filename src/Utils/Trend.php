<?php

declare(strict_types=1);

namespace steinmb\Utils;

use steinmb\Enums\TrendFormat;

final class Trend
{
    private const array ranges = [
        'stable' => [0.1, 0.2],
        'slowly' => [0.2, 0.3],
        'steady' => [0.3, 0.9],
        'medium' => [0.9, 2],
        'fast' => [2, 5],
    ];

    public function __construct(readonly public string $trend)
    {
    }

    public function getTrend(): string
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
        if ($this->trend === '') {
            return TrendFormat::Stable->value;
        }

        if ($this->trend < 0) {
            return $this->isFalling();
        }

        if ($this->trend <= TrendFormat::Stable->speed()) {
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
        foreach (TrendFormat::cases() as $case) {
            $ranges[$case->value] = (float) $case->speed() + (float) $this->trend;
        }

        sort($ranges, SORT_NUMERIC);
        foreach ($ranges as $range) {
        }

        foreach (TrendFormat::cases() as $case) {
            $speed = $case->speed();
            $difference[$case->value] = (float) $speed + (float) $this->trend;

            if ($this->trend <= $speed) {
//                return "$case->value {$this->direction()} ($speed)";
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
