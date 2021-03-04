<?php declare(strict_types=1);

namespace steinmb;

use steinmb\Onewire\DataEntity;
use steinmb\Onewire\EntityInterface;
use steinmb\Onewire\OneWireInterface;

final class EntityFactory
{

    public function newItem(
      Clock $clock,
      OneWireInterface $oneWire,
      string $sensor,
      string $sensorType
    ): EntityInterface
    {
        return new DataEntity(
          $sensor,
          $sensorType,
          $oneWire->content($sensor),
          $clock
        );
    }

}
