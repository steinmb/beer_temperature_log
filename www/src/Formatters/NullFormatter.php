<?php


namespace steinmb\Formatters;


class NullFormatter implements FormatterInterface
{

    public function format(string $record): string
    {
        return $record;
    }
}
