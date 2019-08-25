<?php

declare(strict_types=1);

/**
 * @file Calculate.php
 */

/**
 * Class Calculate
 *   Calculate data.
 */
class Calculate
{

    private $data;
    private $log;
    private $trend;
    private $lastReading;

    public function __construct(Logger $log)
    {
        $this->log = $log;
    }

    /**
     * Calculate trend of the last temperature readings.
     *
     * @param $time integer minutes to calculate latest trend from.
     */
    public function calculateTrend(int $time = 15): void
    {
        $x = '';
        $y = [];
        $x2 = [];
        $xy = [];
        $last = $this->log->getLastReading();

        foreach (array_reverse($this->log->getData()) as $key => $row) {
            $y[] = 1000 * $row['Sensor'];
            $x = $key + 1;
            $x2[] = bcpow($x, $x);

            if (strtotime($row['Date']) <= strtotime($last['Date']) - ($time * 60)) {
                break;
            }
        }

        $y = array_reverse($y);
        $samples = $x;
        $x = range(1, $x);

        foreach ($x as $key => $item) {
            $xy[] = $item * $y[$key];
        }

        $xSummary = array_sum($x);
        $ySummary = array_sum($y);
        $xySummary = array_sum($xy);

        $x2Summary = 0;
        foreach ($x2 as $item) {
            $x2Summary = bcadd($x2Summary, $item, 10);
        }

        $vector1 = bcsub(bcmul($samples, $xySummary),
          bcmul($xSummary, $ySummary));
        $vector2 = bcsub(bcmul($samples, $x2Summary), (bcsqrt($xSummary, 30)));
        $this->trend = bcdiv($vector1, $vector2, 12);
    }

    /**
     * Get entity trend data and round it down to 4 decimals.
     *
     * @return float.
     */
    public function getTrend(): float
    {
        return $this->trend;
    }

    /**
     * Analyze trend index and calculate a human friendly label for it.
     *
     * @return string index label.
     */
    public function analyzeTrend(): string
    {
        $direction = 'increasing';

        if ($this->trend < 0) {
            $direction = 'decreasing';
        }

        $ranges = [
          'stable' => 0.1,
          'slowly' => 0.21,
          'steady' => 0.3,
          'medium' => 0.9,
          'fast' => 2,
        ];

        $speed = '';
        foreach ($ranges as $key => $range) {
            if (ltrim($this->trend, '-') > $range) {
                $speed = $key;
            }
        }

        return $direction . ' ' . $speed;
    }

}
