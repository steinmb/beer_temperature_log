<?php

declare(strict_types=1);

namespace steinmb;

final class AmbiguousSessionId implements BrewSessionInterface
{
    public string $sessionId = '';
    public string $probe = '';
    public string $ambient = '';
    public int|float $low_limit = 0;
    public int|float $high_limit = 0;

    public function __construct()
    {
    }
}
