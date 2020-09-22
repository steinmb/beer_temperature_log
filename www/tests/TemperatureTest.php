<?php declare(strict_types=1);

use steinmb\EntityFactory;
use steinmb\Onewire\DataEntity;
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

        $this->temperature = new Temperature(new DataEntity(
            '28-1234567',
            'temperature',
            '20.123',
            new \steinmb\SystemClockFixed(
                new dateTimeImmutable('16.07.2018 13.01.00'))
        ));
    }

    public function testCelsius(): void
    {
        self::assertEquals('20.123', $this->temperature->temperature());
    }

    public function testUnknownScale(): void
    {
        $unknownScale = 'parsec';
        self::assertEquals('Unknown temperature scale: ' . $unknownScale,
            $this->temperature->temperature($unknownScale),
            'Failed detecting a uknow teperature scale.'
        );
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
