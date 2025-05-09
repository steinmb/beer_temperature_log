<?php

declare(strict_types=1);

namespace steinmb\Tests\Unit\OneWire;

use PHPUnit\Framework\Attributes\CoversClass;
use steinmb\Onewire\OneWire;
use PHPUnit\Framework\TestCase;

#[CoversClass(OneWire::class)]
final class OneWireTest extends TestCase
{
    private OneWire $oneWire;

    protected function setUp(): void
    {
        parent::setUp();

        $this->oneWire = new OneWire(__DIR__ . '/../../test_data');
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
