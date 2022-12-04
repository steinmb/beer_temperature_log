<?php

namespace steinmb\Onewire;

class OneWireFixed implements OneWireInterface
{

    public function __construct(string $directory = '')
    {
    }

    public function allSensors(): array
    {
        return [
            '3a-0000001e4a9f',
            '10-000802a4ef03',
            '10-000802a55696',
            '10-000802be7340',
            '28-0000098101de',
        ];
    }

    public function temperatureSensors(): array
    {
        $temperatureSensors = [];
        foreach ($this->allSensors() as $sensor) {
            if (str_contains($sensor, '10-') || str_contains($sensor, '28-')) {
                $temperatureSensors[] = $sensor;
            }
        }

        return $temperatureSensors;
    }

    public function content(string $sensor): string
    {
        return '25 00 4b 46 ff ff 07 10 cc : crc=cc YES 
        25 00 4b 46 ff ff 07 10 cc t=18312';
    }
}
