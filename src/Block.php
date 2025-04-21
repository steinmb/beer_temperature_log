<?php

declare(strict_types=1);

namespace steinmb;

use steinmb\Enums\DateFormat;
use steinmb\Formatters\HTMLFormatter;
use steinmb\Onewire\TemperatureSensor;

final readonly class Block
{
    public function __construct(
        private HTMLFormatter $formatter,
        private TemperatureSensor $temperature,
        private Clock $dateTime,
    ) {
    }

    public function unorderedList(): string
    {
        return $this->formatter->unorderedList(
            $this->temperature->id,
            $this->temperature->temperature(),
            $this->dateTime->currentTime()->format(DateFormat::DateTime->value),
        );
    }
}
