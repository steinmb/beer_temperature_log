<?php declare(strict_types=1);

namespace steinmb\Onewire;

use UnexpectedValueException;

class SensorFactory
{
    public function __construct(private readonly OneWireInterface $oneWire) {}

    public function createSensor(string $id): Sensors
    {

        if (str_contains($id, '10-') || str_contains($id, '28-')) {
            return new TemperatureSensor(
              $this->oneWire,
              $id,
            );
        }

        throw new UnexpectedValueException(
            'Unknown sensor type: ' . $id
        );

    }
}
