<?php

declare(strict_types=1);

namespace steinmb;

final class BrewSession implements BrewSessionInterface
{
    public function __construct(
        public string $sessionId,
        public string $probe,
        public string $ambient,
        public float $low_limit = 0,
        public float $high_limit = 0,
    ) {
    }
}
