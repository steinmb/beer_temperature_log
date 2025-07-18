<?php

declare(strict_types=1);

namespace steinmb\Tests\Unit\Utils;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use steinmb\Enums\TrendFormat;
use steinmb\Utils\Calculate;
use steinmb\Utils\Trend;

#[CoversClass(Trend::class)]
#[UsesClass(TrendFormat::class)]
final class TrendTest extends TestCase
{
    public function testCalculateTrend(): void
    {
        $log = [
            '2025-04-21 15:47:31, 18.312',
            '2025-04-21 15:56:05, 18.312',
            '2025-04-21 15:56:10, 18.312',
            '2025-04-21 15:56:22, 18.312',
            '2025-04-21 15:56:24, 18.312',
            '2025-04-21 15:56:26, 18.312',
            '2025-04-21 16:03:19, 18.312',
            '2025-04-21 16:03:42, 18.312',
            '2025-04-21 16:03:46, 18.312',
            '2025-04-21 17:09:19, 18.312',
            '2025-04-21 17:13:59, 18.312',
            '2025-04-21 17:14:01, 18.312',
        ];
        $calculator = new Calculate();
        $trend = $calculator->calculateTrend(7, '1', $log);
        self::assertEquals(
            '0.000000000000',
            $trend->getTrend(),
        );
    }

    public function testCreateTrendLabels(): void
    {
        $trend = new Trend(0.1);
        self::assertEquals(
            'stable',
            $trend->createTrendLabels(),
        );
        $trend = new Trend(-0.1);
        self::assertEquals(
            'stable',
            $trend->createTrendLabels(),
        );

        $trend = new Trend(0.3);
        self::assertEquals(
            'slowly',
            $trend->createTrendLabels(),
        );
        $trend = new Trend(-0.3);
        self::assertEquals(
            'slowly',
            $trend->createTrendLabels(),
        );

        $trend = new Trend(0.4);
        self::assertEquals(
            'steady',
            $trend->createTrendLabels(),
        );
        $trend = new Trend(-0.4);
        self::assertEquals(
            'steady',
            $trend->createTrendLabels(),
        );

        $trend = new Trend(0.92);
        self::assertEquals(
            'medium',
            $trend->createTrendLabels(),
        );
        $trend = new Trend(-0.92);
        self::assertEquals(
            'medium',
            $trend->createTrendLabels(),
        );

        $trend = new Trend(3);
        self::assertEquals(
            'fast',
            $trend->createTrendLabels(),
        );
        $trend = new Trend(-3);
        self::assertEquals(
            'fast',
            $trend->createTrendLabels(),
        );
    }
}
