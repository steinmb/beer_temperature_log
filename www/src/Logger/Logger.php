<?php declare(strict_types = 1);

namespace steinmb\Logger;

final class Logger implements LoggerInterface
{
    private $name;
    private $handlers = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Return a new cloned instance with the name changed
     *
     * @param string $name
     * @return Logger
     */
    public function withName(string $name): self
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function write(string $message, $context = []): void
    {
        if (!$message) {
            return;
        }

        foreach ($this->handlers as $handler) {
            $record = [
                'message' => $message,
                'context' => $context,
                'channel' => $this->name,
            ];
            $handler->write($record);
        }
    }

    public function read(): string
    {
        $value = '';
        foreach ($this->handlers as $handler) {
            $value = $handler->read();
        }

        return $value;
    }

    public function lastEntry(): string
    {
        $value = '';
        foreach ($this->handlers as $handler) {
            $value = $handler->lastEntry();
        }

        return $value;
    }

    public function pushHandler(HandlerInterface $handler): self
    {
        array_unshift($this->handlers, $handler);

        return $this;
    }

    public function close(): void
    {
        foreach ($this->handlers as $handler) {
            $handler->close();
        }
    }
}
