<?php
declare(strict_types = 1);

namespace steinmb\onewire;

use Error;
use InvalidArgumentException;

class FileLogger implements Logger
{
    public $file;
    private $data = [];

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function writeLogFile($fileHandle, $logString): void
    {
        print $logString . PHP_EOL;
        $logString .= "\r\n";
        fwrite($fileHandle, $logString);
        fclose($fileHandle);
    }

    public function getLogData(string $directory, string $fileName): void
    {
        $fileSize = filesize($directory . '/' . $fileName);

        if ($fileSize !== 0) {
            $content = fread($this->file->storage($directory, $fileName), $fileSize);

            if ($content === false) {
                throw new Error(
                  'Unable to read read content from logfile: ' . $this->fqFileName
                );
            }

            $this->data = explode("\r\n", $content);
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function lastEntry(string $directory, string $file): string
    {
        if (!$this->data) {
            return '';
        }

        $lastReading = $this->data[count($this->data) - 1];
        return (string) $lastReading;
    }

}