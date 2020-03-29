<?php declare(strict_types = 1);

namespace steinmb\Formatters;

use steinmb\Logger\LoggerInterface;
use steinmb\onewire\Temperature;
use steinmb\Utils\Calculate;

/**
 * @file Block.php
 */

class Block
{
    private $temperature;
    private $formatter;

    public function __construct(Temperature $temperature, HTMLFormatter $formatter)
    {
        $this->temperature = $temperature;
        $this->formatter = $formatter;
    }

    public function unorderedlist(): string
    {
        return $this->formatter->unorderedList($this->temperature);
    }


}
