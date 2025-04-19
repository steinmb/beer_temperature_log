<?php

declare(strict_types=1);

namespace steinmb\Utils;

use PHPUnit\Framework\TestCase;
use steinmb\Enums\TrendFormat;

class TrendTest extends TestCase
{
    /** @covers \steinmb\Utils\Trend */
    public function testCreateTrendLabels(): void
    {
        $trend = new Trend('');
        self::assertEquals(TrendFormat::Stable->value, $trend->createTrendLabels());

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
            'decreasing medium' ,
            TrendFormat::Slowly->value, $trend->createTrendLabels()
        );

        $trend = new Trend('-0.5');
        self::assertEquals(
            'decreasing steady',
            $trend->createTrendLabels()
        );

        $trend = new Trend('0.1');
        self::assertEquals(
            'decreasing stable',
            $trend->createTrendLabels()
        );
    }
}
