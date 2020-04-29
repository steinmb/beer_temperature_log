<?php declare(strict_types=1);

namespace steinmb\Onewire;

use steinmb\Clock;
use steinmb\EntityFactory;

/**
 * @file Sensor.php
 * Service class.
 */

final class Sensor
{
    private const sensorType = 'temperature';
    private $oneWire;
    private $clock;
    private $itemFactory;

    public function __construct(
      OneWire $oneWire,
      Clock $clock,
      EntityFactory $itemFactory
    )
    {
        $this->oneWire = $oneWire;
        $this->clock = $clock;
        $this->itemFactory = $itemFactory;
    }

    public function rawData(): string
    {
        $sample = '';
        $sensors = $this->oneWire->getSensors();

        foreach ($sensors as $sensor) {
            $now = $this->clock->currentTime();
            $sample .= 'Time: ' . $now->format('d.m.Y') . PHP_EOL;
            $sample .= "Sensor: $sensor \n";
            $sample .= $this->oneWire->content($sensor);
        }

        return $sample;
    }

    public function createEntity(string $sensor): DataEntity
    {
        return $this->itemFactory->newItem(
          $this->clock,
          $this->oneWire,
          $sensor,
          self::sensorType
        );
    }

}
