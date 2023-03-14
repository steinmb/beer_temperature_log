<?php

declare(strict_types=1);

namespace steinmb\Logger\Handlers;

interface HandlerInterface
{
    public function read(): string;
    public function write(array $message);
    public function lastEntry(): string;
    public function close();
}
