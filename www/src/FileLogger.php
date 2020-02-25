<?php
declare(strict_types = 1);

namespace steinmb\onewire;

use Error;
use InvalidArgumentException;

class FileLogger implements Logger
{

    private $fqFileName;
    private $fileHandle;
    private $data = [];

    public function __construct(string $logfile, string $directory)
    {

        if (!file_exists($directory) && !mkdir($directory, 0755,
            true) && !is_dir($directory)) {
            throw new InvalidArgumentException(
              'Unable to create log directory: ' . $directory
            );
        }

        $this->fqFileName = $directory . '/' . $logfile;
        $this->fileHandle = fopen($this->fqFileName, 'wb+');

    }

    public function writeLogFile($logString): void
    {
        $timestamp[] = date('Y-m-d H:i:s');
        $logString = array_merge($timestamp, $logString);
        $logString = implode(', ', $logString);
        print $logString . PHP_EOL;
        $logString .= "\r\n";

        fwrite($this->fileHandle, $logString);
        fclose($this->fileHandle);
    }

    public function getLogData(): void
    {
        $fileSize = filesize($this->fqFileName);

        if ($fileSize !== 0) {
            $content = fread($this->fileHandle, filesize($this->fqFileName));

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