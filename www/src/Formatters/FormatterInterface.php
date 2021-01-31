<?php declare(strict_types=1);

namespace steinmb\Formatters;

use steinmb\Onewire\EntityInterface;

interface FormatterInterface
{
    public function __construct(EntityInterface $entity, ?string $dateFormat = null);

    public function format($record);
}
