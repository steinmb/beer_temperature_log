<?php declare(strict_types = 1);

use steinmb\Onewire\OneWire;
use PHPUnit\Framework\TestCase;

final class OneWireTest extends TestCase
{
    public function testOneWire()
    {
        self::assertInstanceOf(
          OneWire::class,
          new OneWire(),
        );
    }

    public function testSensor()
    {
        $oneWire = new OneWire('/Users/steinmb/sites/beer_temperature_log/www/test');
        self::assertIsArray($oneWire->getTemperatureSensors());
    }
}
