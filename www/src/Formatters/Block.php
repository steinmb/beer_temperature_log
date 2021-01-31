<?php declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\Onewire\Temperature;

final class Block
{
    private $formatter;

    public function __construct(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function unorderedLists(Temperature $temperature): string
    {
        return $this->formatter->unorderedList($temperature);
    }

    public function trendList(float $trend, int $time, string $sample): string
    {
        return $this->formatter->trendList($trend, $time, $sample);
    }
}
