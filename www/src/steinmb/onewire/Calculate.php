<?php
declare(strict_types=1);

namespace steinmb\onewire;

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
     */
    public function calculateTrend(int $time, string $last)
    {
        $x = '';
        $y = [];
        $x2 = [];
        $xy = [];

        foreach (array_reverse($this->log->getData()) as $key => $row) {
            if (!isset($row['Sensor'])) {
                return;
            }

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
        $vector2 = bcsub(bcmul($samples, $x2Summary), bcsqrt($xSummary, 30));

        return bcdiv($vector1, $vector2, 12);
    }

    /**
     * Analyze trend index and calculate a human friendly label for it.
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
