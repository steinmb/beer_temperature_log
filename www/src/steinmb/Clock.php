<?php declare(strict_types = 1);

namespace steinmb;

use DateTimeImmutable;

interface Clock
{
    public function currentTime(): DateTimeImmutable;

}
