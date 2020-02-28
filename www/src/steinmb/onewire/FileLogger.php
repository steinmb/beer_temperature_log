<?php
declare(strict_types = 1);

namespace steinmb\onewire;

use Error;

class FileLogger implements Logger
{
    public $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function write($fileHandle, $logString): void
    {
        fwrite($fileHandle, $logString);
    }

    public function read($fileHandle, $directory, $fileName): string
    {
        $log = '';
        $fileSize = filesize($directory . $fileName);

        if ($fileSize === 0) {
            return $log;
        }

        $content = fread($fileHandle, $fileSize);

        if ($content === false) {
            throw new Error(
              'Unable to read: ' . $directory . '/' . $fileName
            );
        }

        return $log;
    }

    public function lastEntry(array $content): string
    {
        if (!$content) {
            return '';
        }

        $lastReading = $content[count($content) - 1];

        return (string) $lastReading;
    }

}