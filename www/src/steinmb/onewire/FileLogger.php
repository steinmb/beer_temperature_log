<?php
declare(strict_types = 1);

namespace steinmb\onewire;

use Error;
use InvalidArgumentException;

class FileLogger implements Logger
{
    private $file;
    private $data = [];

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function writeLogFile($logString): void
    {
        $timestamp[] = date('Y-m-d H:i:s');
        $logString = array_merge($timestamp, $logString);
        $logString = implode(', ', $logString);
        print $logString . PHP_EOL;
        $logString .= "\r\n";

        fwrite($this->file->storage(), $logString);
        fclose($this->file->storage());
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

    /**
     * Get the last sample from temperature log.
     *
     * @return string $lastReading
     *  Last log entry.
     */
    public function getLastReading(): string
    {
        if (!$this->data) {
            return '';
        }

        $lastReading = $this->data[count($this->data) - 1];
        return (string) $lastReading;
    }

}