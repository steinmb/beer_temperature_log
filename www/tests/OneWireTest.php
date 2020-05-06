<?php declare(strict_types = 1);

use steinmb\Onewire\OneWire;
use PHPUnit\Framework\TestCase;
use steinmb\Onewire\Sensor;

final class OneWireTest extends TestCase
{
    private $OneWire;

    protected function setUp(): void
    {
        parent::setUp();
        $this->OneWire = new OneWire(
          '/Users/steinmb/sites/beer_temperature_log/www/test',
          '/Users/steinmb/sites/beer_temperature_log/www/test/w1_master_slaves'
        );
    }

    public function testOneWire(): void
    {
        self::assertInstanceOf(
          OneWire::class,
          new OneWire(),
        );
    }

    public function testAllSensors(): void
    {
        self::assertCount(5, $this->OneWire->allSensors());
    }

}
