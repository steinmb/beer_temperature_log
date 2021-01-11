<?php declare(strict_types=1);

namespace steinmb;

class BrewSession implements BrewSessionInterface
{
    public $sessionId;
    public $probe;
    public $ambient;
    public $low_limit;
    public $high_limit;

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
