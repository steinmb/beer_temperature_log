<?php
declare(strict_types=1);

namespace steinmb\Logger;

use steinmb\Formatters\FormatterInterface;

interface HandlerInterface
{
    public function read(): string;
    public function write(array $message, FormatterInterface $formatter);
    public function lastEntry(): string;
    public function close();
}
