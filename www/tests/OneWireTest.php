<?php declare(strict_types = 1);

use steinmb\Onewire\OneWire;
use PHPUnit\Framework\TestCase;

final class OneWireTest extends TestCase
{
    private $OneWire;

    public function setUp(): void
    {
        parent::setUp();
        $this->OneWire = new OneWire(
          '/Users/steinmb/sites/beer_temperature_log/www/test',
          '/Users/steinmb/sites/beer_temperature_log/www/test/w1_master_slaves'
        );
    }

    public function testOneWire()
    {
        self::assertInstanceOf(
          OneWire::class,
          new OneWire(),
        );
    }

    public function testTemperatureSensor()
    {
        self::assertCount(4, $this->OneWire->getTemperatureSensors());
    }

    public function testAllSensors()
    {
        self::assertCount(5, $this->OneWire->allSensors());
    }

}
