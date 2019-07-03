<?php

declare(strict_types = 1);

/**
 * Read and write data to data storage.
 */

class Logger {

  private $logfile = '';
  private $directory;

 public function __construct($logfile) {
   $this->logfile = $logfile;
 }

 public function setLogDirectory($directory) {
   $this->directory = $directory;
   if (!file_exists($directory)) {
     $result = mkdir($directory, 0755, TRUE);
     if (!$result) {
       die('Unable to create log directory. Giving up.');
     }
   }
 }

  /**
   * Define the name of the logfile.
   *
   * @param string $logfile file of logfile.
   */
   public function setLogfile($logfile) {
     $this->logfile = $logfile;
  }

  /**
   * Write data from sensors to log file.
   *
   * @param $logString
   */
  public function writeLogFile($logString)
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
  public function getLogData()
  {
    $handle = fopen($this->directory . $this->logfile, 'r');
    $data = fread($handle);
  }

  /**
   * Get the last sample from temperature log.
   *
   * @return string of last temperature sample.
   */
  public function getLastReading()
  {
    $lastReading = $this->data[count($this->data) - 1];

    return $lastReading;
  }
}
