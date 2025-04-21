<?php

declare(strict_types=1);

namespace steinmb\Utils;

use UnexpectedValueException;

final class Calculate
{
    /**
     * @todo CPU and memory intensive on large data sets.
     */
    private function reverse(array $measurements, string $lastMeasurement, int $time): array
    {
        $x = '';
        $x2 = [];
        $y = [];

        foreach ($measurements as $key => $measurement) {
            $measurement = explode(', ', $measurement);
            $sample = (float) $measurement[1];
            $y[] = 1000 * $sample;
            $x = $key + 1;
            $x = (string) $x;
            $x2[] = bcpow($x, $x);

            if (strtotime($measurement[0]) <= strtotime($lastMeasurement[0]) - ($time * 60)) {
                break;
            }
        }

        return ['x' => $x, 'x2' => $x2, 'y' => $y];
    }

    public function calculateTrend(int $time, string $lastMeasurement, array $lastEntries): Trend
    {
        if ($lastEntries === []) {
            throw new UnexpectedValueException();
        }

        $reversed = $this->reverse($lastEntries, $lastMeasurement, $time);
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

        $vector = $this->vector($samples, $xySummary, $xSummary, $ySummary, $x2Summary);
        return new Trend($vector);
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

    public static function mean(array $values): float|int
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
