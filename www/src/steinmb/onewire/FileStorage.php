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

        return $fileHandle;
    }

    public function write()
    {
        $fileHandle = fopen($this->directory . $this->fileName, 'ab+');
        $this->storage($fileHandle);

        return $fileHandle;
    }

}
