<?php

declare(strict_types=1);

namespace steinmb\Enums;

enum DateFormat: string
{
    case DateTime = 'Y-m-d H:i:s';
    case Date = 'Y-m-d';
}
