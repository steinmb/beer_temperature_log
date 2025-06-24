<?php

declare(strict_types=1);

namespace steinmb\Tests\Unit\Temperature;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use steinmb\Alarm;
use steinmb\BrewSessionConfig;
use steinmb\Onewire\OneWireFixed;
use steinmb\Onewire\SensorFactory;
use steinmb\Onewire\TemperatureSensor;
use UnexpectedValueException;

#[CoversClass(TemperatureSensor::class)]
#[UsesClass(OneWireFixed::class)]
final class TemperatureTest extends TestCase
{
    private SensorFactory $sensorFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sensorFactory = new SensorFactory(new OneWireFixed());
    }

    public function testCelsius(): void
    {
        $sensor = $this->sensorFactory->createSensor('28-0000098101de');
        self::assertEquals('18.312', $sensor->temperature());
        self::assertEquals('18.312', $sensor->temperature('celsius'));
    }

    public function testFahrenheit(): void
    {
        $sensor = $this->sensorFactory->createSensor('28-0000098101de');
        self::assertEquals('64.962', $sensor->temperature('fahrenheit'));
    }

    public function testKevin(): void
    {
        $sensor = $this->sensorFactory->createSensor('28-0000098101de');
        self::assertEquals('291.462', $sensor->temperature('kelvin'));
    }

    public function testCelsiusOffset(): void
    {
        $sensor = $this->sensorFactory->createSensor('28-0000098101de');
        self::assertEquals('19.500', $sensor->temperature('celsius', 1.188));
    }

    public function testUnknownScale(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $unknownScale = 'parsec';
        $sensor = $this->sensorFactory->createSensor('28-0000098101de');
        self::assertEquals(
            'Unknown temperature scale: ' . $unknownScale,
            $sensor->temperature($unknownScale),
            'Failed detecting a unknown temperature scale.'
        );
    }

    public function testBadCrc(): void
    {
        $oneWire = <<<SENSOR
        25 00 4b 46 ff ff 07 10 cc : crc=cc NO
        25 00 4b 46 ff ff 07 10 cc t=20000';
        SENSOR;
        $factory = new SensorFactory(new OneWireFixed($oneWire));
        $this->expectException(UnexpectedValueException::class);
        $sensor = $factory->createSensor('28-1234567');
        $sensor->temperature();
    }

    public function testInvalidTemperature(): void
    {
        $oneWire = <<<SENSOR
        25 00 4b 46 ff ff 07 10 cc : crc=cc YES
        25 00 4b 46 ff ff 07 10 cc t=
        SENSOR;

        $invalidValues = ['3333', '85000', '127687', '0'];
        $this->expectException(UnexpectedValueException::class);

        foreach ($invalidValues as $invalidValue) {
            $measurement = $oneWire . $invalidValue;
            $factory = new SensorFactory(new OneWireFixed($measurement));
            $sensor = $factory->createSensor('28-1234567');
            $sensor->temperature();
        }
    }

    public function testHighLimit(): void
    {
        $settings = [
            '100' => [
                'probe' => '28-0000098101de',
                'ambient' => '10-000802be73fa',
                'low_limit' => 15,
                'high_limit' => 23,
            ],
        ];
        $brewSessionConfig = new BrewSessionConfig($settings);
        $measurement = '25 00 4b 46 ff ff 07 10 cc : crc=cc YES
                        25 00 4b 46 ff ff 07 10 cc t=24000';
        $factory = new SensorFactory(new OneWireFixed($measurement));
        $sensor = $factory->createSensor('28-0000098101de');
        $brewSession = $brewSessionConfig->sessionIdentity($sensor);
        $alarmService = new Alarm($brewSession);

        self::assertNotEmpty($alarmService->checkLimits($sensor));
    }

    public function testLowLimit(): void
    {
        $settings = [
            '100' => [
                'probe' => '28-0000098101de',
                'ambient' => '10-000802be73fa',
                'low_limit' => 15,
                'high_limit' => 23,
            ],
        ];
        $brewSessionConfig = new BrewSessionConfig($settings);
        $measurement = '25 00 4b 46 ff ff 07 10 cc : crc=cc YES
                        25 00 4b 46 ff ff 07 10 cc t=14000';
        $factory = new SensorFactory(new OneWireFixed($measurement));
        $sensor = $factory->createSensor('28-0000098101de');
        $brewSession = $brewSessionConfig->sessionIdentity($sensor);
        $alarmService = new Alarm($brewSession);

        self::assertNotEmpty($alarmService->checkLimits($sensor));
    }
}
