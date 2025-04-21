<?php

declare(strict_types=1);

namespace steinmb;

use DateTimeImmutable;

final readonly class SystemClockFixed implements Clock
{
    public function __construct(private dateTimeImmutable $dateTime)
    {
    }

    public function currentTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }
}
