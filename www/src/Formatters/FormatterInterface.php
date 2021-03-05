<?php declare(strict_types=1);

namespace steinmb\Formatters;

interface FormatterInterface
{
    public function __construct(?string $dateFormat = null);

    public function format($record);
}
