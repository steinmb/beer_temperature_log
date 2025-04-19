<?php

declare(strict_types=1);

namespace steinmb\Logger;

use Psr\Log\AbstractLogger;
use steinmb\Enums\DateFormat;
use steinmb\Logger\Handlers\FileStorageHandler;
use steinmb\Logger\Handlers\HandlerInterface;

final class Logger extends AbstractLogger implements LoggerInterface
{
    /** @var HandlerInterface[] */
    private array $handlers = [];

    public function __construct(private string $name)
    {
    }

    /**
     * Return a new cloned instance with the name changed.
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
        $fullMessage = '';

        if (!$message && !$context) {
            return;
        }

        if (!$message) {
            $fullMessage = $this->message($context);
        }

        if (!$fullMessage) {
            $fullMessage = $message;
        }

        $record = [
            'message' => $fullMessage,
            'context' => $context,
            'channel' => $this->name,
        ];

        foreach ($this->handlers as $handler) {
            $handler->write($record);
        }
    }

    private function message(array $context): string
    {
        $brewSession = $context['brewSession'];
        $temperature = $context['temperature'];
        $ambient_temperature = $context['ambient'];
        $time = $context['clock'];
        $message = [
            $time->format(DateFormat::DateTime->value),
            'Brew session: ' . $brewSession->sessionId,
            'Fermentor: ' . $brewSession->probe . ' ' . $temperature,
            'Ambient: ' . $brewSession->ambient . ' ' . $ambient_temperature,
        ];

        return implode(PHP_EOL, $message);
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

    public function lastEntries(int $lines): string
    {
        foreach ($this->handlers as $handler) {
            if ($handler instanceof FileStorageHandler) {
                return $handler->lastEntries($lines);
            }
        }

        return '';
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

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        // TODO: Implement log() method.
    }
}
