<?php
declare(strict_types=1);

namespace steinmb\onewire;

use UnexpectedValueException;

final class FileStorage implements File
{
    private $directory;
    private $fileName;
    private $fileHandle;

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
        $this->fileHandle = fopen($this->directory . $this->fileName, 'rb+');
        $this->storage($this->fileHandle);
        $content = '';
        $fileSize = filesize($this->directory . $this->fileName);

        if ($fileSize === 0) {
            return $content;
        }

        $content = fread($this->fileHandle, $fileSize);

        if ($content === false) {
            throw new UnexpectedValueException(
              'Unable to read: ' . $this->directory . $this->fileName
            );
        }

        fclose($this->fileHandle);

        return $content;
    }

    public function write(string $message): void
    {
        $this->fileHandle = fopen($this->directory . $this->fileName, 'ab+');
        $this->storage($this->fileHandle);
        $result = fwrite($this->fileHandle, $message);

        if (!$result) {
            throw new UnexpectedValueException(
              'Unable to write to log file: ' . $this->directory . $this->fileName
            );
        }

    }
    public function close(): void
    {
        fclose($this->fileHandle);
        $this->fileHandle = null;
    }

    public function __destruct()
    {
        fclose($this->fileHandle);
    }

}
