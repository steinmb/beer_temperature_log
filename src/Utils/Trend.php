<?php

declare(strict_types=1);

namespace steinmb\Utils;

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
        if ($this->trend === null) {
            return '';
        }

        $speed = '';
        foreach (self::ranges as $key => $range) {
            if ($this->trend > $range) {
                $speed = $key;
            }
        }

        return $this->direction() . ' ' . $speed;
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
