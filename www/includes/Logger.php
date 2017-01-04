<?php

/**
 * Logger class. Store temperatur log.
 *
 */
class Logger {

  private $logFile = '';

 public function __construct($log = 'temp.log') {
   $this->logFile = $log;
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

    $handle = fopen($this->logFile, 'a');
    fwrite($handle, $logString);
    fclose($handle);
  }
}
