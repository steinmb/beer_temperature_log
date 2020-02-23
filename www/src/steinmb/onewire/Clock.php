<?php
declare(strict_types=1);

namespace steinmb\onewire\steinmb\onewire;

use DateTimeImmutable;

interface Clock
{
    public function currentTime(): DateTimeImmutable;

}
