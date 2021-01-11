<?php declare(strict_types=1);

namespace steinmb;

final class AmbiguousSessionId implements BrewSessionInterface
{
    public $sessionId = '';
    public $probe = '';
    public $ambient = '';
    public $low_limit = 0;
    public $high_limit = 0;

    public function __construct()
    {
    }
}
