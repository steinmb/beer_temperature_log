<?php declare(strict_types=1);

namespace steinmb;

use steinmb\Onewire\OneWireInterface;

final class EntityFactory
{

    public function newItem(
      Clock $clock,
      OneWireInterface $oneWire,
      string $sensor,
      string $sensorType
    ): DataEntity
    {
        return new DataEntity(
          $sensor,
          $sensorType,
          $oneWire->content($sensor),
          $clock
        );
    }

}
