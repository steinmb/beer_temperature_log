<?php declare(strict_types = 1);

namespace steinmb\Logger;

class NullHandler implements HandlerInterface
{
    private $message;

    public function __construct()
    {
    }

    public function read(): string
    {
        return 'Test data from NullHandler';
    }

    public function write(string $message): void
    {
        $this->message = $message;
    }

    public function close(): void
    {
    }

    public function lastEntry(): string
    {
        return $this->message;
    }
}