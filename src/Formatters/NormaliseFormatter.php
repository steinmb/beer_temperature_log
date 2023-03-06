<?php

declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\Enums\DateFormat;
use steinmb\SystemClock;

class NormaliseFormatter implements FormatterInterface
{
    private string $dateFormat;

    public function __construct(?string $dateFormat = null)
    {
        $this->dateFormat = $dateFormat ?? DateFormat::DateTime->value;
    }

    public function format(string $record): string
    {
        $time = new SystemClock();
        return $time->currentTime()->format($this->dateFormat) . ', ' . $record;
    }
}
