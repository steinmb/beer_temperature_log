<?php

declare(strict_types = 1);

use steinmb\Onewire\OneWire;
use PHPUnit\Framework\TestCase;

/**
 * Class OneWireTest
 *
 * @covers \steinmb\Onewire\OneWire
 */
final class OneWireTest extends TestCase
{
    private OneWire $oneWire;

    protected function setUp(): void
    {
        parent::setUp();

        $this->oneWire = new OneWire(__DIR__ . '/test_data');
    }

    public function testOneWire(): void
    {
        self::assertInstanceOf(
          OneWire::class,
          $this->oneWire,
        );
    }

    public function testAllSensors(): void
    {
        $sensors = $this->oneWire->allSensors();
        self::assertCount(5, $this->oneWire->allSensors());
    }

}
