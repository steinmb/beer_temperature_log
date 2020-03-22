<?php
declare(strict_types = 1);

namespace steinmb\onewire;

class FileLogger implements Logger
{
    public $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function write(string $logString): void
    {
        if (!$logString) {
            return;
        }

        $this->file->write($logString . "\n");
    }

    public function read(): string
    {
        return $this->file->read();
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
}
