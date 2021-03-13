<?php declare(strict_types=1);

namespace steinmb;

use steinmb\Formatters\FormatterInterface;
use steinmb\Onewire\EntityInterface;
use steinmb\Onewire\Temperature;

final class Block
{
    private $formatter;

    public function __construct(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function unorderedLists(Temperature $temperature, EntityInterface $entity): string
    {
        return $this->formatter->unorderedList($temperature, $entity);
    }
}
