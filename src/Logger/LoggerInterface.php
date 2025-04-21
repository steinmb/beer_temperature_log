<?php

declare(strict_types=1);

namespace steinmb\Logger;

use steinmb\Logger\Handlers\HandlerInterface;

interface LoggerInterface
{
    public function __construct(string $name);

    public function pushHandler(HandlerInterface $handler);

    public function write(string $message): void;

    public function read(): string;

    public function close(): void;

    public function lastEntry(): string;
}
