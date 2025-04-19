<?php

declare(strict_types=1);

namespace steinmb\Onewire;

use UnexpectedValueException;

final readonly class SensorFactory
{
    public function __construct(private OneWireInterface $oneWire)
    {
    }

    public function createSensor(string $id): TemperatureSensor
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

    /**
     * @return TemperatureSensor[]
     */
    public function allSensors(): array
    {
        $sensors = [];

        foreach ($this->oneWire->allSensors() as $id) {
            $sensors[] = $this->createSensor($id);
        }

        return $sensors;
    }
}
