<?php declare(strict_types=1);

use steinmb\EntityFactory;
use steinmb\Formatters\HTMLFormatter;
use PHPUnit\Framework\TestCase;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Sensor;
use steinmb\Onewire\Temperature;
use steinmb\SystemClock;

final class HTMLFormatterTest extends TestCase
{
    private $sensor;
    private $sensors = [];

    public function setUp(): void
    {
        parent::setUp();
        $oneWire = new OneWire(
          '/Users/steinmb/sites/beer_temperature_log/www/test',
          '/Users/steinmb/sites/beer_temperature_log/www/test/w1_master_slaves'
        );
        $this->sensors = $oneWire->getTemperatureSensors();
        $this->sensor = new Sensor(
          $oneWire,
          new SystemClock(),
          new EntityFactory()
        );
    }

    public function testUnorderedList()
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

}
