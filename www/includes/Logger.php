<?php

declare(strict_types = 1);

/**
 * Read and write data to data storage.
 */
class Logger {

  private $logfile;
  private $directory;

    public function __construct(string $logfile, string $directory)
    {

        if (!file_exists($directory) && !mkdir($directory, 0755,
            true) && !is_dir($directory)) {
                throw new InvalidArgumentException(
                  'Unable to create log directory: ' . $directory
                );
            }

        $this->logfile = $logfile;
        $this->directory = $directory;
    }

    /**
     * Write data from sensors to log file.
     *
     * @param $logString
     */
    public function writeLogFile($logString): void
    {
        $timestamp[] = date('Y-m-d H:i:s');
        $logString = array_merge($timestamp, $logString);
        $logString = implode(', ', $logString);
        print $logString . PHP_EOL;
        $logString = $logString . "\r\n";

        $handle = fopen($this->directory . $this->logfile, 'a');
        fwrite($handle, $logString);
        fclose($handle);
    }

    /**
     * Read logfile.
     */
    public function getLogData(): void
    {
        $handle = fopen($this->directory . $this->logfile, 'r');
        $data = fread($handle);
    }

    /**
     * Get the last sample from temperature log.
     *
     * @return string $lastReading
     *  Return last log entry.
     */
    public function getLastReading(): string
    {
        $lastReading = $this->data[count($this->data) - 1];

        return (string) $lastReading;
    }
}
