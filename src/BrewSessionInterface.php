<?php

declare(strict_types=1);

namespace steinmb;

interface BrewSessionInterface
{
    public function __construct(
        string $sessionId,
        string $probe,
        string $ambient,
        float $low_limit,
        float $high_limit,
    );
}
