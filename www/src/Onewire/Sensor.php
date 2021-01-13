<?php declare(strict_types=1);

namespace steinmb\Onewire;

use steinmb\Clock;
use steinmb\EntityFactory;

final class Sensor
{
    private const sensorType = 'temperature';
    private $oneWire;
    private $clock;
    private $itemFactory;
    private $sensors;
    private $temperatureSensors;

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

    public function getTemperatureSensors(): array
    {
        return $this->tempSensors();
    }

    private function tempSensors(): array
    {
        if (!file_exists($this->oneWire->directory())) {
            throw new \RuntimeException(
              'Directory: ' . $this->oneWire->directory() . ' Not found. OneWire support perhaps not loaded.'
            );
        }

        $temperatureSensors = [];
        $content = dir($this->oneWire->directory());

        while (false !== ($entry = $content->read())) {
            if (false !== strpos($entry, '10-') || false !== strpos($entry, '28-')) {
                $temperatureSensors[] = $entry;
            }
        }

        return $temperatureSensors;
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
