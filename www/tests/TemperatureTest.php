<?php declare(strict_types=1);

use steinmb\EntityFactory;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Sensor;
use PHPUnit\Framework\TestCase;
use steinmb\Onewire\Temperature;
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

    private $temperature;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sensor = new Sensor(
          new OneWire(
            __DIR__ . '/test_data',
            __DIR__ . '/test_data/w1_master_slaves'
          ),
          new SystemClock(),
          new EntityFactory()
        );

        foreach ($this->sensor->getTemperatureSensors() as $temperatureSensor) {
            $this->enties[] = $this->sensor->createEntity($temperatureSensor);
        }

        $this->temperature = new Temperature(new \steinmb\Onewire\DataEntity(
            '28-1234567',
            'temperature',
            '20.123',
            new SystemClock()
        ));
    }

    public function testC(): void
    {
        self::assertEquals('20', $this->temperature->temperature());
    }

    public function testTemperature(): void
    {
        foreach ($this->enties as $enty) {
            $temperature = new Temperature($enty);
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
