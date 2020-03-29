<?php declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\onewire\Temperature;
use steinmb\Utils\Calculate;

interface FormatterInterface
{
    public function unorderedList(Temperature $sensor): string;

    public function trendList(Calculate $calculator, $minutes, $sample): string;
}
