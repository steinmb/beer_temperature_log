<?php

declare(strict_types=1);

namespace steinmb\Onewire;

interface OneWireInterface
{
    public function allSensors(): array;
    public function temperatureSensors(): array;
    public function content(string $sensor): string;
}
