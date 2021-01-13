<?php declare(strict_types = 1);

namespace steinmb\Onewire;

interface OneWireInterface
{
    public function __construct(string $directory, string $sensors);
    public function allSensors(): array;
    public function temperatureSensors(): array;
    public function content(string $sensor): string;
}
