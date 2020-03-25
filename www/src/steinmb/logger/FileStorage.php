<?php
declare(strict_types=1);

namespace steinmb\Logger;

use UnexpectedValueException;

final class FileStorage implements HandlerInterface
{
    private $directory;
    public $stream;

    public function __construct($stream)
    {
        $this->stream = $stream;
    }

    public function storage(): void
    {

        $this->getDirFromStream($this->stream);

        if (!file_exists($this->directory) && !mkdir($this->directory, 0755,
            true) && !is_dir($this->directory)) {
            throw new UnexpectedValueException(
              'Unable to create log directory: ' . $this->directory
            );
        }

        if (!$this->stream) {
            throw new UnexpectedValueException(
              'Unable to open or create log file: ' . $this->stream
            );
        }

    }

    public function read()
    {
        $content = '';
        $fileSize = filesize($this->stream);
        $stream = fopen($this->stream, 'rb+');
        $this->storage();

        if ($fileSize === 0) {
            return $content;
        }

        $content = fread($stream, $fileSize);

        if ($content === false) {
            throw new UnexpectedValueException(
              'Unable to read: ' . $stream
            );
        }

        return $content;
    }

    public function write(string $message): void
    {
        $stream = fopen($this->stream, 'ab+');
        $this->storage();
        $result = fwrite($stream, $message);

        if (!$result) {
            throw new UnexpectedValueException(
              'Unable to write to log file: ' . $stream
            );
        }

    }

    public function close(): void
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }

        $this->stream = null;
    }

    private function getDirFromStream(string $stream): void
    {
        $pos = strpos($stream, '://');
        if ($pos === false) {
            $this->directory = dirname($stream);
        }

        if (strpos($stream, 'file://') === 0) {
            $this->directory = dirname(substr($stream, 7));
        }

    }

    public function __destruct()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }

        $this->stream = null;
    }

}
