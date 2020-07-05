<?php declare(strict_types=1);

use steinmb\EntityFactory;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Sensor;
use PHPUnit\Framework\TestCase;
use steinmb\SystemClock;

/**
 * Class TemperatureTest
 *
 * @covers \steinmb\Onewire\Temperature
 */
final class TemperatureTest extends TestCase
{
    private $sensor;

    /**
     * @var array
     */
    private $enties;

    protected function setUp(): void
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

        foreach ($this->sensor->getTemperatureSensors() as $temperatureSensor) {
            $this->enties[] = $this->sensor->createEntity($temperatureSensor);
        }

    }

    public function testTemperature(): void
    {
        foreach ($this->enties as $enty) {
            $temperature = new \steinmb\Onewire\Temperature($enty);
            $celsius = $temperature->temperature();
            $measurement = $enty->measurement();
            self::assertIsFloat($celsius);
        }
    }

    public function testRawData(): void
    {
        self::assertStringContainsString('YES', $this->sensor->rawData());
    }

}
