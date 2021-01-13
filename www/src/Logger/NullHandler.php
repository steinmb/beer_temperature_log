<?php declare(strict_types = 1);

namespace steinmb\Logger;

final class NullHandler implements HandlerInterface
{
    private $message;

    public function __construct()
    {
    }

    public function read(): string
    {
        return 'Test data from NullHandler';
    }

    public function write(array $message): void
    {
        $this->message = $message['message'];
    }

    public function close(): void
    {
    }

    public function lastEntry(): string
    {
        return $this->message;
    }
}
