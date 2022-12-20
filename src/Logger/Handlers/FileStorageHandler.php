<?php

declare(strict_types=1);

namespace steinmb\Logger\Handlers;

use steinmb\Formatters\FormatterInterface;
use steinmb\RuntimeEnvironment;
use UnexpectedValueException;

final class FileStorageHandler implements HandlerInterface
{
    private readonly string $directory;
    private string $message = '';
    private readonly string $stream;

    public function __construct(string $fileName, string $directory = '')
    {
        if (!$directory) {
            $logDirectory = RuntimeEnvironment::getSetting('LOG_DIRECTORY');
        } else {
            $logDirectory = $directory;
        }

        $this->directory = $logDirectory;
        $this->stream = $logDirectory . '/'. $fileName;
        $this->storage();
    }

    private function storage(): void
    {
        $directory = $this->getDirFromStream($this->stream);

        if (!file_exists($directory) && !mkdir($directory, 0755,
            true) && !is_dir($this->directory)) {
            throw new UnexpectedValueException(
              'Unable to create log directory: ' . $directory
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

    private function message(array $record): string
    {
        if (!isset($record['context']) || !$record['context']) {
            return $record['message'];
        }

        $brewSession = $record['context']['brewSession'];
        $temperature = $record['context']['temperature'];
        $ambient = $record['context']['ambient'];

        $message = [
            $temperature->entity->timeStamp(),
            $brewSession->sessionId,
            $brewSession->probe,
            $temperature->temperature(),
            $brewSession->ambient,
            $ambient->temperature(),
        ];

        return implode(', ', $message);
    }

    public function write(array $message, FormatterInterface $formatter): void
    {
        $fileMessage = $formatter->format($this->message($message));
        $stream = fopen($this->stream, 'ab+');

        $result = fwrite($stream, $fileMessage . PHP_EOL);
        if (!$result) {
            throw new UnexpectedValueException(
                'Unable to write to log file: ' . $stream
            );
        }

        $this->message = $fileMessage;
    }

    public function lastEntry(): string
    {
        if (!$this->message) {
            return $this->tailFile();
        }

        return $this->message;
    }

    public function lastEntries(int $lines): string
    {
        return $this->tailFile($lines);
    }

    /**
     * Sets buffer size, according to the number of lines to retrieve.
     * This gives a performance boost when reading a few lines from the file.
     */
    private function bufferSize(int $lines = 1, bool $adaptive = true): int
    {
        if (!$adaptive) {
            return 4096;
        }
        if ($lines < 2) {
            return 64;
        }
        if ($lines < 10) {
            return 512;
        }

        return 4094;
    }

    private function tailFile(int $lines = 1, bool $adaptive = true): ?string
    {
        $f = @fopen($this->stream, "rb");
        if ($f === false) {
            return null;
        }

        $buffer = $this->bufferSize($lines, $adaptive);
        // Jump to last character.
        fseek($f, -1, SEEK_END);

        // Read it and adjust line number if necessary, otherwise the result would be
        // wrong if file doesn't end with a blank line.
        if (fread($f, 1) !== "\n") {
            --$lines;
        }

        $output = '';
        while (ftell($f) > 0 && $lines >= 0) {

            // Figure out how far back we should jump.
            $seek = min(ftell($f), $buffer);

            // Do the jump (backwards, relative to where we are).
            fseek($f, -$seek, SEEK_CUR);

            // Read a chunk and prepend it to our output.
            $output = ($chunk = fread($f, $seek)) . $output;

            // Jump back to where we started reading.
            fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);

            // Decrease our line counter.
            $lines -= substr_count($chunk, "\n");

        }

        // Because of buffer size we might have read too many lines.
        while ($lines++ < 0) {
            // Find first newline and remove all text before that
            $output = substr($output, strpos($output, "\n") + 1);
        }
        fclose($f);

        return trim($output);
    }

    public function close(): bool
    {
        $result = false;
        if (is_resource($this->stream)) {
            $result = fclose($this->stream);
        }

        return $result;
    }

    private function getDirFromStream(string $stream): string
    {
        $pos = strpos($stream, '://');
        if ($pos === false) {
            return dirname($stream);
        }

        if (str_starts_with($stream, 'file://')) {
            return dirname(substr($stream, 7));
        }

        return '';
    }

    public function __destruct()
    {
        $this->close();
    }
}
