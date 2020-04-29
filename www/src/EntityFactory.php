<?php declare(strict_types=1);

namespace steinmb;

use steinmb\Onewire\DataEntity;
use steinmb\Onewire\EntityInterface;
use steinmb\Onewire\OneWire;

final class EntityFactory implements EntityFactoryInterface
{

    public function newItem(
      Clock $clock,
      OneWire $oneWire,
      string $sensor,
      string $sensorType
    ): EntityInterface
    {
        return new DataEntity(
          $sensor,
          $sensorType,
          $oneWire->content($sensor),
          $clock->currentTime());
    }

}
