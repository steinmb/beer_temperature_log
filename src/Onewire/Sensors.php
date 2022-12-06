<?php

declare(strict_types=1);

namespace steinmb\Onewire;

interface Sensors
{
    public function __construct(OneWireInterface $interface);

    public function sensorValue(string $sensorId): int;

    public function rawValue(string $sensorId): string;
}
