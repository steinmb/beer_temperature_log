<?php declare(strict_types=1);

use steinmb\EntityFactory;
use steinmb\Formatters\HTMLFormatter;
use PHPUnit\Framework\TestCase;
use steinmb\Onewire\DataEntity;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Sensor;
use steinmb\Onewire\Temperature;
use steinmb\SystemClock;
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

    protected function setUp(): void
    {
        parent::setUp();
        $oneWire = new OneWire(
          __DIR__ . '/test_data',
          __DIR__ . 'test_data/w1_master_slaves'
        );
        $this->sensor = new Sensor(
          $oneWire,
          new SystemClock(),
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
