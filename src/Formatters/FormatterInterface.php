<?php

declare(strict_types=1);

namespace steinmb\Formatters;

interface FormatterInterface
{
    public function format(string $record);
}
