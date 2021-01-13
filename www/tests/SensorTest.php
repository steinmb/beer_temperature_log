<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use steinmb\EntityFactory;
use steinmb\Onewire\OneWireFixed;
use steinmb\Onewire\Sensor;
use steinmb\SystemClockFixed;

/**
 * Class SensorTest
 *
 * @covers \steinmb\Onewire\Sensor
 */
final class SensorTest extends TestCase
{
    private $sensor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sensor = new Sensor(
            new OneWireFixed(),
            new SystemClockFixed(new dateTimeImmutable('16.07.2018 13.01.00')),
            new EntityFactory()
        );
    }

    public function testTemperatureSensor(): void
    {
        self::assertCount(4, $this->sensor->getTemperatureSensors());
    }

    public function testRawData(): void
    {
        self::assertStringContainsString('crc', $this->sensor->rawData());
    }
}
