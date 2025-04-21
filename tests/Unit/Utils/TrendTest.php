<?php

declare(strict_types=1);

namespace steinmb\Tests\Unit\Utils;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use steinmb\Enums\TrendFormat;
use steinmb\Utils\Trend;

#[CoversClass(Trend::class)]
#[CoversClass(TrendFormat::class)]
final class TrendTest extends TestCase
{
    private Trend $trend;

    public function setUp(): void
    {
        $this->trend = new Trend('');
    }

    public function testCreateTrendLabels(): void
    {
        self::assertEquals(TrendFormat::Stable->value, $this->trend->createTrendLabels());

        $trend = new Trend(TrendFormat::Stable->speed());
        self::assertEquals(TrendFormat::Stable->value, $trend->createTrendLabels());

        $trend = new Trend(TrendFormat::Slowly->speed());
        $expected_result = TrendFormat::Slowly->value . ' increasing (' . TrendFormat::Slowly->speed() . ')';
        self::assertEquals($expected_result, $trend->createTrendLabels());

        $trend = new Trend(TrendFormat::Fast->speed());
        $expected_result = TrendFormat::Fast->value . ' increasing (' . TrendFormat::Fast->speed() . ')';
        self::assertEquals($expected_result, $trend->createTrendLabels());

        $trend = new Trend('-0.95');
        self::assertEquals(
            'decreasing medium',
            TrendFormat::Slowly->value,
            $trend->createTrendLabels(),
        );

        $trend = new Trend('-0.5');
        self::assertEquals(
            'decreasing steady',
            $trend->createTrendLabels(),
        );

        $trend = new Trend('0.1');
        self::assertEquals(
            'decreasing stable',
            $trend->createTrendLabels(),
        );
    }
}
