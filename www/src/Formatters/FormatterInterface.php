<?php declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\Onewire\DataEntity;
use steinmb\Onewire\Temperature;
use steinmb\Utils\Calculate;

interface FormatterInterface
{
    public function __construct(DataEntity $entity);

    public function unorderedList(Temperature $sensor): string;

    public function trendList(Calculate $calculator, int $minutes, string $sample): string;
}
