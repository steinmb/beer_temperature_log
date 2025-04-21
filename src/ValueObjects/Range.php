<?php

declare(strict_types=1);

namespace steinmb\ValueObjects;

final readonly class Range
{
    public function __construct(
        public float $low,
        public float $high,
    ) {
    }

    public function withinRange(float $value): bool
    {
        return $value >= $this->low && $value <= $this->high;
    }

    public function negativeWithinRange(float $value): bool
    {
        return $value <= - $this->low && $value >= - $this->high;
    }
}
