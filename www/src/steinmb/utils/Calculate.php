<?php
declare(strict_types=1);

namespace steinmb\Utils;

use steinmb\Logger\Logger;

/**
 * @file Calculate.php
 */

class Calculate
{
    private $log;
    private $trend;

    public function __construct(Logger $log)
    {
        $this->log = $log;
    }

    public function calculateTrend(int $time, string $last)
    {
        $x = '';
        $y = [];
        $x2 = [];
        $xy = [];
        $content = $this->log->read();
        $log = explode("\r\n" , $content);
        array_pop($log);

        foreach (array_reverse($log) as $key => $row) {

            $y[] = 1000 * $row[3];
            $x = $key + 1;
            $x = (string) $x;
            $x2[] = bcpow($x, $x);

            if (strtotime($row[0]) <= strtotime($last[0]) - ($time * 60)) {
                break;
            }
        }

        $y = array_reverse($y);
        $samples = (string) $x;
        $x = range(1, $x);

        foreach ($x as $key => $item) {
            $xy[] = $item * $y[$key];
        }

        $xSummary = (string) array_sum($x);
        $ySummary = (string) array_sum($y);
        $xySummary = (string) array_sum($xy);

        $x2Summary = 0;
        foreach ($x2 as $item) {
            $x2Summary = bcadd((string) $x2Summary, $item, 10);
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
//            if (ltrim($this->trend, '-') > $range) {
//                $speed = $key;
//            }
        }

        return $direction . ' ' . $speed;
    }

}
