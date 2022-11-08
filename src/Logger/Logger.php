<?php declare(strict_types = 1);

namespace steinmb\Logger;

use steinmb\Formatters\FormatterInterface;
use steinmb\Formatters\NormaliseFormatter;
use steinmb\Logger\Handlers\FileStorageHandler;
use steinmb\Logger\Handlers\HandlerInterface;

final class Logger implements LoggerInterface
{
    private array $handlers = [];

    public function __construct(private string $name) {}

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

    private function message(array $context): string
    {
        $brewSession = $context['brewSession'];
        $temperature = $context['temperature'];
        $ambient = $context['ambient'];

        $message = [
            $temperature->entity->timeStamp(),
            'Brew session: ' . $brewSession->sessionId,
            'Fermentor: ' . $brewSession->probe . ' ' . $temperature->temperature(),
            'Ambient: ' . $brewSession->ambient . ' ' . $ambient->temperature(),
        ];

        return implode(PHP_EOL, $message);
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
            $handler->write($record, $handler->formatter);
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

    public function lastEntries(int $lines): string
    {
        foreach ($this->handlers as $handler) {
            if ($handler instanceof FileStorageHandler) {
                return $handler->lastEntries($lines);
            }
        }

        return '';
    }

    public function pushHandler(HandlerInterface $handler, FormatterInterface $formatter = NULL): self
    {
        array_unshift($this->handlers, $handler);
        if (!$formatter) {
            $this->handlers[0]->formatter = new NormaliseFormatter();
        } else {
            $this->handlers[0]->formatter = $formatter;
        }

        return $this;
    }

    public function close(): void
    {
        foreach ($this->handlers as $handler) {
            $handler->close();
        }
    }
}
