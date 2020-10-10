<?php declare(strict_types=1);

namespace steinmb\Logger;

use steinmb\RuntimeEnvironment;
use UnexpectedValueException;

final class FileStorage implements HandlerInterface
{
    private $directory;
    private $fileName;
    private $message;
    private $stream;

    public function __construct(string $fileName, string $directory = '')
    {
        if (!$directory) {
            $this->directory = RuntimeEnvironment::getSetting('LOG_DIRECTORY');
        } else {
            $this->directory = $directory;
        }

        $this->fileName = $fileName;
        $this->stream = $this->directory . '/'. $this->fileName;
        $this->storage();
    }

    private function storage(): void
    {

        $this->getDirFromStream($this->stream);

        if (!file_exists($this->directory) && !mkdir($this->directory, 0755,
            true) && !is_dir($this->directory)) {
            throw new UnexpectedValueException(
              'Unable to create log directory: ' . $this->directory
            );
        }

        if (!file_exists($this->stream) && !fopen($this->stream, 'wb+')) {
            throw new UnexpectedValueException(
              'Unable to open or create log file: ' . $this->stream
            );
        }

    }

    public function read(): string
    {
        $content = '';
        $fileSize = filesize($this->stream);
        $stream = fopen($this->stream, 'rb+');

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
        $result = fwrite($stream, $message . PHP_EOL);

        if (!$result) {
            throw new UnexpectedValueException(
              'Unable to write to log file: ' . $stream
            );
        }

        $this->message = $message;
    }

    public function lastEntry(): string
    {
        return $this->message;
    }

    public function close(): bool
    {
        $result = false;

        if (is_resource($this->stream)) {
            $result = fclose($this->stream);
        }

        $this->stream = null;
        return $result;
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
        $this->close();
    }
}
