<?php declare(strict_types=1);

namespace steinmb\Onewire;

class SensorFactory
{
    public function __construct(private OneWireInterface $oneWire) {}

    public function createSensor(string $Id): Sensors
    {

        if (str_contains($Id, '10-') || str_contains($Id, '28-')) {
            return new TemperatureSensor(
              $this->oneWire
            );
        }

        throw new \UnexpectedValueException(
            'Unknown sensor type: ' . $Id
        );

    }
}
