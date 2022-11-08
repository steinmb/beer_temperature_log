<?php declare(strict_types=1);

namespace steinmb\Logger\Handlers;

use steinmb\Formatters\FormatterInterface;

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

    public function write(array $message, FormatterInterface $formatter = NULL): void
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
