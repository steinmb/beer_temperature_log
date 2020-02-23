<?php
declare(strict_types=1);

namespace steinmb\onewire;

/**
 * @file Sensor.php
 * Service class.
 */

class Sensor
{
    private const sensorType = 'temperature';
    private $oneWire;
    private $clock;

    public function __construct(OneWire $oneWire, Clock $clock)
    {
        $this->oneWire = $oneWire;
        $this->clock = $clock;
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
        $now = $this->clock->currentTime();
        $content = $this->oneWire->content($sensor);

        return new DataEntity($sensor, self::sensorType, $content, $now);
    }
}
