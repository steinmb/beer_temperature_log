<?php declare(strict_types = 1);

namespace steinmb\Utils;

use steinmb\Logger\LoggerInterface;

/**
 * @file Calculate.php
 */

class Calculate
{
    private $log;
    private $trend;

    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    public function getTrend()
    {
        return $this->trend;
    }

    private function reverse(array $log, string $last, $time): array
    {
        $x = '';
        $x2 = [];
        $y = [];

        foreach (array_reverse($log) as $key => $row) {
            $row = explode(', ', $row);
            $y[] = 1000 * $row[2];
            $x = $key + 1;
            $x = (string) $x;
            $x2[] = bcpow($x, $x);

            $foo = strtotime($last[0]);
            if (strtotime($row[0]) <= strtotime($last[0]) - ($time * 60)) {
                break;
            }
        }

        return ['x' => $x, 'x2' => $x2, 'y' => $y];
    }

    public function calculateTrend(int $time, string $last)
    {
        $content = $this->log->read();
        $log = explode("\n" , $content);
        array_pop($log);
        $reversed = $this->reverse($log, $last, $time);

        $y = array_reverse($reversed['y']);
        $samples = (string) $reversed['x'];
        $x = range(1, $reversed['x']);

        $xy = [];
        foreach ($x as $key => $item) {
            $xy[] = $item * $y[$key];
        }

        $xSummary = (string) array_sum($x);
        $ySummary = (string) array_sum($y);
        $xySummary = (string) array_sum($xy);

        $x2Summary = 0;
        foreach ($reversed['x2'] as $item) {
            $x2Summary = bcadd((string) $x2Summary, $item, 10);
        }

        $vector1 = bcsub(bcmul($samples, $xySummary), bcmul($xSummary, $ySummary));
        $vector2 = bcsub(bcmul($samples, $x2Summary), bcsqrt($xSummary, 30));

        $result = 0;
        if ($vector2 > 0) {
            $result = bcdiv($vector1, $vector2, 12);
        }
        return $result;
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

    public function listHistoric(int $minutes, string $sample): string
    {
        $this->trend = $this->calculateTrend($minutes, $sample);
        return $this->analyzeTrend();

//        $content = '';
//        $trend = $this->calculateTrend($minutes, $sample);
//        $content .= '<div class="block">';
//        $content .= '<h2 class="title">' . $this->entity->id() . '</h2>';
//        $content .= '<ul>';
//        $content .= '<li>' . $sample[0] . '</li>';
//        $content .= '<li>' . $sample[1] . 'ºC' . '</li>';
//        $content .= '<li>' . $minutes . 'min ' . $calculate->analyzeTrend() . ' (' . $trend . ')</li>';
//        $content .= '</ul>';
//        $content .= '</div>';
//
//        return $content;
    }
}
