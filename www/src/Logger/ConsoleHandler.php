<?php declare(strict_types=1);

namespace steinmb\Logger;

class ConsoleHandler implements HandlerInterface
{
    private $messages = [];
    private $lastMessage;

    public function read(): string
    {
        $content = implode(PHP_EOL, $this->messages);
        echo $content . PHP_EOL;
        return $content;
    }

    public function write(string $message)
    {
        if (!$message) {
            return;
        }

        $this->messages[] = $message;
        $this->lastMessage = $message;
    }

    public function lastEntry(): string
    {
        echo $this->lastMessage . PHP_EOL;
        return $this->lastMessage;
    }

    public function close()
    {
    }
}
