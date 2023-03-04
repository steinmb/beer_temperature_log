<?php

declare(strict_types=1);

namespace steinmb;

use steinmb\Formatters\HTMLFormatter;
use steinmb\Onewire\Temperature;

final readonly class Block
{

    public function __construct(private HTMLFormatter $formatter)
    {
    }

    public function unorderedLists(Temperature $temperature, EntityInterface $entity): string
    {
        return $this->formatter->unorderedList($temperature, $entity);
    }
}
