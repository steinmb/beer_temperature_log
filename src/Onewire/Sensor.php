<?php declare(strict_types=1);

namespace steinmb\Onewire;

use steinmb\Clock;
use steinmb\EntityFactory;

final class Sensor
{
    private const sensorType = 'temperature';

    public function __construct(
      private readonly OneWireInterface $oneWire,
      private readonly Clock $clock,
      private readonly EntityFactory $itemFactory
    ) {}

    public function getTemperatureSensors(): array
    {
        return $this->oneWire->temperatureSensors();
    }

    public function rawData(): string
    {
        $sample = '';

        foreach ($this->oneWire->allSensors() as $sensor) {
            $now = $this->clock->currentTime();
            $sample .= 'Time: ' . $now->format('Y-m-d H:i:s') . PHP_EOL;
            $sample .= "Sensor: $sensor \n";
            $sample .= $this->oneWire->content($sensor);
        }

        return $sample;
    }

    public function createEntity(string $sensor): EntityInterface
    {
        return $this->itemFactory->newItem(
          $this->clock,
          $this->oneWire,
          $sensor,
          self::sensorType
        );
    }

}
