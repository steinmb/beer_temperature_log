<?php declare(strict_types=1);

namespace steinmb;

use steinmb\Onewire\EntityInterface;
use steinmb\Onewire\OneWireInterface;

interface EntityFactoryInterface
{
    public function newItem(
      Clock $clock,
      OneWireInterface $oneWire,
      string $sensor,
      string $sensorType
    ): EntityInterface;
}
