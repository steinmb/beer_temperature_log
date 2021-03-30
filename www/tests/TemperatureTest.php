<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use steinmb\BrewSessionConfig;
use steinmb\BrewSessionInterface;
use steinmb\Onewire\DataEntity;
use steinmb\Onewire\Temperature;
use steinmb\SystemClock;
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
    /**
     * @var BrewSessionInterface
     */
    private $brewSession;

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
            new SystemClockFixed(new dateTimeImmutable('16.07.2018 13.01.00'))),
            -0.5
        );
        $settings = [
            '100' => [
                'probe' => '28-0000098101de',
                'ambient' => '10-000802be73fa',
                'low_limit' => 15,
                'high_limit' => 23,
            ],
            'AA' => [
                'probe' => '10-000802a55696',
                'ambient' => '10-000802be73fa',
                'low_limit' => 17,
                'high_limit' => 26,
            ],
        ];
        $brewSessionConfig = new BrewSessionConfig($settings);
        $this->brewSession = $brewSessionConfig->sessionIdentity('28-0000098101de');

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
        $this->expectException(UnexpectedValueException::class);
        $unknownScale = 'parsec';
        self::assertEquals('Unknown temperature scale: ' . $unknownScale,
            $this->temperature->temperature($unknownScale),
            'Failed detecting a uknown temperature scale.'
        );
    }

    public function testBadCrc(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $sensor = '28-1234567';
        $measurement = <<<SENSOR
        25 00 4b 46 ff ff 07 10 cc : crc=cc NO
        25 00 4b 46 ff ff 07 10 cc t=20000';
        SENSOR;
        $temperatureProbe = new Temperature(new DataEntity(
                $sensor,
                'temperature',
                $measurement,
                new SystemClockFixed(new dateTimeImmutable('16.07.2018 13.01.00')))
        );
        $temperatureProbe->temperature();
    }

    public function testInvalidTemperature(): void
    {
        $sensor = '28-1234567';
        $invalidValues = ['3333', '85000', '127687', '0'];
        $this->expectException(UnexpectedValueException::class);
        $expected = <<<SENSOR
        25 00 4b 46 ff ff 07 10 cc : crc=cc YES
        25 00 4b 46 ff ff 07 10 cc t=
        SENSOR;

        foreach ($invalidValues as $invalidValue) {
            $measurement = $expected . $invalidValue;
            $temperatureProbe = new Temperature(new DataEntity(
                    $sensor,
                    'temperature',
                    $measurement,
                    new SystemClockFixed(new dateTimeImmutable('16.07.2018 13.01.00')))
            );
            $temperatureProbe->temperature();
        }
    }

    public function testHighLimit(): void
    {
        $measurement = '25 00 4b 46 ff ff 07 10 cc : crc=cc YES
                        25 00 4b 46 ff ff 07 10 cc t=24000';
        $entity = new DataEntity(
            $this->brewSession->probe,
            'temperature',
            $measurement,
            new SystemClock()
        );
        $temperature = new Temperature($entity);

        self::assertTrue($this->temperature->highLimit($this->brewSession, $temperature));
    }

    public function testLowLimit(): void
    {
        $measurement = '25 00 4b 46 ff ff 07 10 cc : crc=cc YES
                        25 00 4b 46 ff ff 07 10 cc t=14000';
        $entity = new DataEntity(
            $this->brewSession->probe,
            'temperature',
            $measurement,
            new SystemClock()
        );
        $temperature = new Temperature($entity);
        self::assertTrue($this->temperature->lowLimit($this->brewSession, $temperature));
    }
}
