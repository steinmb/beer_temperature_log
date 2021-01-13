<?php declare(strict_types = 1);

namespace steinmb\Utils;

use steinmb\Logger\LoggerInterface;
use UnexpectedValueException;

final class Calculate
{
    private $log;
    private $trend;
    private const ranges = [
        'stable' => 0.1,
        'slowly' => 0.21,
        'steady' => 0.3,
        'medium' => 0.9,
        'fast' => 2,
        ];

    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    public function getTrend()
    {
        return $this->trend;
    }

    /**
     * @param array $log
     * @param string $last
     * @param $time
     *
     * @return array
     *
     * @todo CPU and memory intensive on large log files.
     */
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

            if (strtotime($row[0]) <= strtotime($last[0]) - ($time * 60)) {
                break;
            }
        }

        return ['x' => $x, 'x2' => $x2, 'y' => $y];
    }

    public function calculateTrend(int $time, string $lastMeasurement)
    {
        $content = $this->log->read();
        $log = explode("\n" , $content);
        array_pop($log);
        $reversed = $this->reverse($log, $lastMeasurement, $time);

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

    public function listHistoric(int $minutes, string $sample): string
    {
        $this->trend = $this->calculateTrend($minutes, $sample);
        return $this->createTrendLabels();
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
