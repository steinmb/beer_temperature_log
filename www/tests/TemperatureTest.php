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

    protected function setUp(): void
    {
        parent::setUp();
        $this->temperature = new Temperature(new DataEntity(
            '28-1234567',
            'temperature',
            '20.123',
            new SystemClockFixed(new dateTimeImmutable('16.07.2018 13.01.00')))
        );
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

    public function testRawData(): void
    {
        self::assertStringContainsString('YES', $this->sensor->rawData());
    }

}
