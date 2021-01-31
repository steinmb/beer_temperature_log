<?php declare(strict_types=1);

use steinmb\EntityFactory;
use steinmb\Formatters\HTMLFormatter;
use PHPUnit\Framework\TestCase;
use steinmb\Onewire\DataEntity;
use steinmb\Onewire\OneWireFixed;
use steinmb\Onewire\Sensor;
use steinmb\Onewire\Temperature;
use steinmb\SystemClockFixed;

/**
 * Class HTMLFormatterTest
 *
 * @covers \steinmb\Formatters\HTMLFormatter
 */
final class HTMLFormatterTest extends TestCase
{
    private $entity;
    private $sensor;
    private $sensors = [];
    private $temperature;

    protected function setUp(): void
    {
        parent::setUp();
        $measurement = '25 00 4b 46 ff ff 07 10 cc : crc=cc YES
                        25 00 4b 46 ff ff 07 10 cc t=20000';
        $this->temperature = new Temperature(new DataEntity(
                '28-1234567',
                'temperature',
                $measurement,
                new SystemClockFixed(new dateTimeImmutable('16.07.2018 13.01.00')))
        );
        $this->sensor = new Sensor(
            new OneWireFixed(),
            new SystemClockFixed(new DateTimeImmutable('16.07.1970 03:55')),
            new EntityFactory()
        );
        $this->sensors = $this->sensor->getTemperatureSensors();
        $this->entity = new DataEntity(
            '10-123456789',
            'temp',
            '20.000',
            new SystemClockFixed(new DateTimeImmutable('16.07.1970 03:55'))
        );
    }

    public function testUnorderedList(): void
    {
        $formatter = new HTMLFormatter($this->entity);
        $expected = <<<HTML
            <div class="block">
            <h2 class="title">10-123456789</h2><ul>
            <li>1970-07-16 03:55:00</li>
            <li>20.000</li>
            </ul></div>
            HTML;
        self::assertSame(
            $expected,
            $formatter->unorderedList($this->temperature)
        );

    }

    public function testBlockTitle(): void
    {
        foreach ($this->sensors as $sensor) {
            $temperature = new Temperature($this->sensor->createEntity($sensor));
            $formatter = new HTMLFormatter($this->sensor->createEntity($sensor));
            self::assertStringContainsString(
                '<h2 class="title">' . $sensor . '</h2>',
                $formatter->unorderedList($temperature)
            );
        }
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
        $formatter = new HTMLFormatter($this->entity);
        $htmlList = $formatter->trendList(
            1.000001,
            30,
            '21.2, 21.3, 21.5, 22'
        );

        self::assertSame($expected, $htmlList
        );
    }
}
