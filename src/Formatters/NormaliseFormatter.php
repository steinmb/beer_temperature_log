<?php

declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\SystemClock;

class NormaliseFormatter implements FormatterInterface
{
    private const standardTimeFormat = 'Y-m-d H:i:s';
    private string $dateFormat;

    public function __construct(?string $dateFormat = null)
    {
        $this->dateFormat = $dateFormat ?? self::standardTimeFormat;
    }

    public function format(string $record): string
    {
        $time = new SystemClock();
        return $time->currentTime()->format($this->dateFormat) . ', ' . $record;
    }
}
