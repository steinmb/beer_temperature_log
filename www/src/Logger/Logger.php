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
