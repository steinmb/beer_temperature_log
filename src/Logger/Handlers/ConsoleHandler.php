<?php declare(strict_types=1);

namespace steinmb\Logger\Handlers;

use steinmb\Formatters\FormatterInterface;

final class ConsoleHandler implements HandlerInterface
{
    private $messages = [];
    private $lastMessage = '';

    public function read(): string
    {
        $content = implode(PHP_EOL, $this->messages);
        echo $content . PHP_EOL;
        return $content;
    }

    public function write(array $message, FormatterInterface $formatter): void
    {
        if (!$message) {
            return;
        }

        $formattedMessage = $formatter->format($message['message']);
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
