<?php declare(strict_types=1);

namespace steinmb;

use steinmb\Onewire\EntityInterface;
use steinmb\Onewire\OneWire;

interface EntityFactoryInterface
{
    public function newItem(
      Clock $clock,
      OneWire $oneWire,
      string $sensor,
      string $sensorType
    ): EntityInterface;
}
