<?php declare(strict_types = 1);

namespace steinmb;

use DateTimeImmutable;

final class SystemClock implements Clock
{

    public function currentTime(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

}
