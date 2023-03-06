<?php

declare(strict_types=1);

namespace steinmb\Formatters;

final class NullFormatter implements FormatterInterface
{
    public function format(string $record): string
    {
        return $record;
    }
}
