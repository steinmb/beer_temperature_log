<?php declare(strict_types = 1);

namespace steinmb\Formatters;

use steinmb\Onewire\Temperature;

final class Block
{
    private $temperature;
    private $formatter;

    public function __construct(Temperature $temperature, FormatterInterface $formatter)
    {
        $this->temperature = $temperature;
        $this->formatter = $formatter;
    }

    public function unorderedlist(): string
    {
        return $this->formatter->unorderedList($this->temperature);
    }

    public function trendList($calculator, $time, $sample): string
    {
        return $this->formatter->trendList($calculator, $time, $sample);
    }
}
