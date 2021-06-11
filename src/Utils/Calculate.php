<?php declare(strict_types=1);

namespace steinmb\Utils;

use UnexpectedValueException;

final class Calculate
{
    private $trend;
    private const ranges = [
        'stable' => 0.1,
        'slowly' => 0.21,
        'steady' => 0.3,
        'medium' => 0.9,
        'fast' => 2,
        ];

    public function __construct() {}

    public function getTrend()
    {
        return $this->trend;
    }

    /**
     * @param array $log
     * @return array
     * @todo CPU and memory intensive on large log files.
     */
    private function reverse(array $log): array
    {
        if (!$log) {
            return [];
        }

        $x = '';
        $x2 = [];
        $y = [];

        foreach (array_reverse($log) as $key => $row) {
            $y[] = 1000 * $row;
            $x = $key + 1;
            $x = (string) $x;
            $x2[] = bcpow($x, $x);
        }

        return ['x' => $x, 'x2' => $x2, 'y' => $y];
    }

    /**
     * Method of Least Squares (linear regression).
     *
     * @param array $lastEntries
     * @return string
     */
    public function calculateTrend(array $lastEntries): string
    {
        if (!$lastEntries) {
            return '';
        }

        $reversed = $this->reverse($lastEntries);
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

        return $this->vector($samples, $xySummary, $xSummary, $ySummary, $x2Summary);
    }

    private function vector($samples, $xySummary, $xSummary, $ySummary, $x2Summary): string
    {
        $vector1 = bcsub(bcmul($samples, $xySummary), bcmul($xSummary, $ySummary));
        $vector2 = bcsub(bcmul($samples, $x2Summary), bcsqrt($xSummary, 30));

        if ($vector2 > 0) {
            return bcdiv($vector1, $vector2, 12);
        }

        return '';
    }

    /**
     * Create human friendly trend labels.
     */
    private function createTrendLabels(): string
    {
        if ($this->trend === null) {
            return '';
        }

        $speed = '';
        foreach (self::ranges as $key => $range) {
            if (ltrim($this->trend, '-') > $range) {
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

    public static function mean(array $values)
    {
        if (!$values) {
            throw new UnexpectedValueException(
              'Cannot calculate on a empty data set.'
            );
        }

        if (count($values) === 1) {
            throw new UnexpectedValueException(
                'Cannot calculate mean value of a single digit: ' . array_pop($values)
            );
        }

        return array_sum($values) / count($values);
    }

    public static function meanDistance(array $values): array
    {
        $result = [];
        $mean = self::mean($values);

        foreach ($values as $value) {
            $result[] = $value - $mean;
        }

        return $result;
    }

    public static function xSquare(array $values): array
    {
        $result = [];
        $meanDistances = self::meanDistance($values);

        foreach ($meanDistances as $meanDistance) {
            $result[] = $meanDistance ** 2;
        }

        return $result;
    }

    public static function b1(array $x, array $y): array
    {
        $result = [];
        $meanDistances_x = self::meanDistance($x);
        $meanDistances_y = self::meanDistance($y);

        foreach ($meanDistances_x as $key => $meanDistance_x) {
            $result[] = $meanDistance_x * $meanDistances_y[$key];
        }

        return $result;
    }

    public static function b1Summary(array $x, array $y): float
    {
        $meanDistances_x = self::meanDistance($x);
        $meanDistances_x_squared = self::xSquare($meanDistances_x);
        $meanDistances_y = self::meanDistance($y);

        return array_sum($meanDistances_x_squared) / array_sum(self::b1($meanDistances_x, $meanDistances_y));
    }
}
