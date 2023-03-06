<?php

declare(strict_types=1);

use steinmb\Enums\DateFormat;
use steinmb\Formatters\HTMLFormatter;
use PHPUnit\Framework\TestCase;
use steinmb\SystemClockFixed;

/**
 * Class HTMLFormatterTest
 *
 * @covers ::HTMLFormatter
 */
final class HTMLFormatterTest extends TestCase
{
    private SystemClockFixed $timestamp;
    private HTMLFormatter $htmlFormatter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->htmlFormatter = new HTMLFormatter();
        $this->timestamp = new SystemClockFixed(
            new dateTimeImmutable('16.07.1970 03:55'),
        );
    }

    public function testUnorderedList(): void
    {
        $expected = <<<HTML
            <div class="block">
            <h2 class="title">10-123456789</h2><ul>
            <li>1970-07-16 03:55:00</li>
            <li>20.000</li>
            </ul></div>
            HTML;
        $timestamp = $this->timestamp->currentTime();
        $result = $this->htmlFormatter->unorderedList(
            '10-123456789',
            '20.000',
            $timestamp->format(DateFormat::DateTime->value),
        );

        self::assertSame($expected, $result);
    }

    public function testBlockTitle(): void
    {
        $sensor_id = '10-123456789';
        self::assertStringContainsString(
            '<h2 class="title">' . $sensor_id . '</h2>',
            $this->htmlFormatter->unorderedList(
                $sensor_id,
                '22.3',
                $this->timestamp->currentTime()->format(DateFormat::DateTime->value),
            )
        );
    }

    public function testTrend(): void
    {
        $expected = <<<HTML
            <div class="block">
            <h2 class="title">10-123456789</h2><ul>
            <li>21.2</li>
            <li>21.3</li>
            <li>21.5</li>
            <li>22</li>
            <li>Trend: 1.000001 the last 30min</li>
            </ul></div>
            HTML;

        $htmlList = $this->htmlFormatter->trendList(
            '1.000001',
            30,
            '21.2, 21.3, 21.5, 22',
            '10-123456789',
        );

        self::assertSame($expected, $htmlList);
    }
}
