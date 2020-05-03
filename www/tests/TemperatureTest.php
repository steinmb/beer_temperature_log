<?php declare(strict_types=1);

use steinmb\EntityFactory;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Sensor;
use PHPUnit\Framework\TestCase;
use steinmb\SystemClock;

final class TemperatureTest extends TestCase
{
    private $sensor;

    public function setUp(): void
    {
        parent::setUp();
        $this->sensor = new Sensor(
          new OneWire(
            '/Users/steinmb/sites/beer_temperature_log/www/test',
            '/Users/steinmb/sites/beer_temperature_log/www/test/w1_master_slaves'
          ),
          new SystemClock(),
          new EntityFactory()
        );

    }

    public function testRawData()
    {
        self::assertStringContainsString('YES', $this->sensor->rawData());
    }

}
