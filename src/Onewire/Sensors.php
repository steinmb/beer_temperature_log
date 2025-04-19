<?php

declare(strict_types=1);

namespace steinmb\Onewire;

interface Sensors
{
    public function __construct(OneWireInterface $interface, string $id);

    public function sensorValue();

    public function rawValue(): string;
}
