<?php

declare(strict_types=1);

namespace steinmb;

interface BrewSessionInterface
{
    public string $sessionId {
        get;
        set;
    }

    public string $probe {
        get;
        set;
    }

    public string $ambient {
        get;
        set;
    }

    public float $low_limit {
        get;
        set;
    }

    public float $high_limit {
        get;
        set;
    }

    public function __construct(
        string $sessionId,
        string $probe,
        string $ambient,
        float $low_limit,
        float $high_limit,
    );
}
