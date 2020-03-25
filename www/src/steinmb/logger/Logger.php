<?php
declare(strict_types = 1);

namespace steinmb\Logger;

class Logger implements LoggerInterface
{
    private $name;
    private $handler;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function write(string $message): void
    {
        if (!$message) {
            return;
        }

        $this->handler->write($message . PHP_EOL);
    }

    public function read(): string
    {
        return $this->handler->read();
    }

    public function lastEntry(): string
    {
        $content = $this->read();

        if (!$content) {
            return '';
        }

        $log = explode("\r\n" , $content);
        array_pop($log);
        $lastReading = $log[count($log) - 1];

        return (string) $lastReading;
    }

    public function pushHandler(HandlerInterface $handler): self
    {
        $this->handler = $handler;

        return $this;
    }

    public function close(): void
    {
        // TODO: Implement close() method.
    }

}
