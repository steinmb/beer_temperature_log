<?php

declare(strict_types=1);

namespace steinmb\Logger\Handlers;

use steinmb\Formatters\FormatterInterface;
use steinmb\Formatters\NormaliseFormatter;

final class ConsoleHandler implements HandlerInterface
{
    private array $messages = [];
    private string $lastMessage = '';
    private FormatterInterface $formatter;

    public function __construct(?FormatterInterface $formatter = null)
    {
        $this->formatter = $formatter ?? new NormaliseFormatter();
    }

    public function read(): string
    {
        $content = implode(PHP_EOL, $this->messages);
        echo $content . PHP_EOL;
        return $content;
    }

    public function write(array $message): void
    {
        if (!$message) {
            return;
        }

        $formattedMessage = $this->formatter->format($message['message']);
        $this->messages[] = $formattedMessage;
        $this->lastMessage = $formattedMessage;
        echo $formattedMessage . PHP_EOL;
    }

    public function lastEntry(): string
    {
        echo $this->lastMessage . PHP_EOL;
        return $this->lastMessage;
    }

    public function close(): void {}
}
