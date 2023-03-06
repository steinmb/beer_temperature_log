<?php

declare(strict_types=1);

namespace steinmb;

use steinmb\Formatters\HTMLFormatter;
use steinmb\Onewire\TemperatureSensor;

final readonly class Block
{

    public function __construct(private HTMLFormatter $formatter)
    {
    }

    public function unorderedLists(TemperatureSensor $temperature, Clock $dateTime): string
    {
        return $this->formatter->unorderedList($temperature, $dateTime);
    }
}
