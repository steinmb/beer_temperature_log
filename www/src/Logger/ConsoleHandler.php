<?php declare(strict_types=1);

namespace steinmb\Logger;

class ConsoleHandler implements HandlerInterface
{
    private $messages = [];
    private $lastMessage = '';

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

        $this->messages[] = $message['message'];
        $this->lastMessage = $message['message'];
        echo $message['channel'] . ': ' . $message['message'] . PHP_EOL;
    }

    public function lastEntry(): string
    {
        echo $this->lastMessage . PHP_EOL;
        return $this->lastMessage;
    }

    public function close(): void {}
}
