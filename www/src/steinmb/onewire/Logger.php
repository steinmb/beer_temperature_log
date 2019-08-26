<?php
declare(strict_types = 1);

namespace steinmb\onewire;

/**
 * Read and write data to data storage.
 */
class Logger {

    private $logfile;
    private $directory;
    private $data;

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
        $logString .= "\r\n";

        $handle = fopen($this->directory . $this->logfile, 'a');
        fwrite($handle, $logString);
        fclose($handle);
    }

    /**
     * Read logfile.
     */
    public function getLogData(): void
    {
        $logfile = $this->directory . $this->logfile;
         $content = file($logfile);
         if ($content === false) {
             throw new Error(
               'Unable to read read content from logfile: ' . $logfile
             );
         }
        $this->data = $content;
    }

    public function getData()
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
        $lastReading = $this->data[count($this->data) - 1];

        return (string) $lastReading;
    }
}
