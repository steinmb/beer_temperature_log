<?php declare(strict_types=1);

namespace steinmb;

final class BrewSession implements BrewSessionInterface
{
    public string $sessionId;
    public string $probe;
    public string $ambient;
    public int|float $low_limit;
    public int|float $high_limit;

    public function __construct(
        string $sessionId = '',
        string $probe = '',
        string $ambient = '',
        float $low_limit = 0,
        float $high_limit = 0
    )
    {
        $this->sessionId = $sessionId;
        $this->probe = $probe;
        $this->ambient = $ambient;
        $this->low_limit = $low_limit;
        $this->high_limit = $high_limit;
    }
}
