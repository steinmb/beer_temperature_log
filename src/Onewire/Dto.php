<?php

declare(strict_types=1);

namespace steinmb\Onewire;

class Dto
{
    public function __construct(
      public readonly string $sensorId,
      public readonly string $sensorContent,
    ) {}
}
