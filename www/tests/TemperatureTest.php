<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use steinmb\Onewire\DataEntity;
use steinmb\Onewire\Temperature;
use steinmb\SystemClockFixed;

/**
 * Class TemperatureTest
 *
 * @covers \steinmb\Onewire\Temperature
 */
final class TemperatureTest extends TestCase
{
    private $temperature;
    private $temperatureOffset;

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

        $this->temperatureOffset = new Temperature(new DataEntity(
            '28-1234567',
            'temperature',
            $measurement,
            new SystemClockFixed(new dateTimeImmutable('16.07.2018 13.01.00')))
            , -0.5
        );
    }

    public function testCelsius(): void
    {
        self::assertEquals('20.000', $this->temperature->temperature());
        self::assertEquals('20.000', $this->temperature->temperature('celsius'));
    }

    public function testFahrenheit(): void
    {
        self::assertEquals('68.000', $this->temperature->temperature('fahrenheit'));
    }

    public function testKevin(): void
    {
        self::assertEquals('293.150', $this->temperature->temperature('kelvin'));
    }

    public function testCelsiusOffset(): void
    {
        self::assertEquals('19.500', $this->temperatureOffset->temperature('celsius'));
    }

    public function testUnknownScale(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $unknownScale = 'parsec';
        self::assertEquals('Unknown temperature scale: ' . $unknownScale,
            $this->temperature->temperature($unknownScale),
            'Failed detecting a uknown temperature scale.'
        );
    }

}
