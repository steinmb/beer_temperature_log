<?php

declare(strict_types=1);

namespace steinmb\Onewire;

readonly class Dto
{
    public function __construct(
        public string $sensorId,
        public string $sensorContent,
    ) {
    }
}
