<?php declare(strict_types = 1);

namespace steinmb;

use DateTimeImmutable;

final class SystemClockFixed implements Clock
{
    private $dateTime;

    public function __construct(DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function currentTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }
}
