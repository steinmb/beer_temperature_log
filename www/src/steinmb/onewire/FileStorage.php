<?php
declare(strict_types=1);

namespace steinmb\onewire;

use UnexpectedValueException;

class FileStorage implements File
{
    private $directory;
    private $fileName;

    public function __construct(string $directory, string $fileName)
    {
        $this->directory = $directory;
        $this->fileName = $fileName;
    }

    public function storage($fileHandle): void
    {

        if (!file_exists($this->directory) && !mkdir($this->directory, 0755,
            true) && !is_dir($this->directory)) {
            throw new UnexpectedValueException(
              'Unable to create log directory: ' . $this->directory
            );
        }

        if (!$fileHandle) {
            throw new UnexpectedValueException(
              'Unable to open or create log file: ' . $this->directory . $this->fileName
            );
        }

    }

    public function read()
    {
        $fileHandle = fopen($this->directory . $this->fileName, 'rb+');
        $this->storage($fileHandle);
        $content = '';
        $fileSize = filesize($this->directory . $this->fileName);

        if ($fileSize === 0) {
            return $content;
        }

        $content = fread($fileHandle, $fileSize);

        if ($content === false) {
            throw new UnexpectedValueException(
              'Unable to read: ' . $this->directory . $this->fileName
            );
        }

        return $content;
    }

    public function write(string $logString): void
    {
        $fileHandle = fopen($this->directory . $this->fileName, 'ab+');
        $this->storage($fileHandle);
        $result = fwrite($fileHandle, $logString);

        if (!$result) {
            throw new UnexpectedValueException(
              'Unable to write to log file: ' . $this->directory . $this->fileName
            );
        }

        fclose($fileHandle);
    }

}
