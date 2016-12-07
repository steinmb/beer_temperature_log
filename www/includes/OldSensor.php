<?php

/**
 * @file
 *
 * Read data from DS18x20 one wire digital thermometer and write data to log file.
 */

class OldSensor
{
  private $baseDirectory = '';

  public function __construct($baseDirectory)
  {
    $this->baseDirectory = $baseDirectory;
  }

  /**
   * Scan one wire bus for attached sensors.
   *
   * @return array $sensors of sensors found.
   */
  public function getSensors()
  {
    $sensors = array();

    if (file_exists($this->baseDirectory)) {
      $content = dir($this->baseDirectory);
      while (FALSE !== ($entry = $content->read())) {
        if (strstr($entry, '10-')) {
          $sensors[] = $entry;
        }
      }
    }

    return $sensors;
  }

  /**
   * Initialize one wire GPIO bus.
   */
  public function initW1()
  {
    echo exec('sudo modprobe w1-gpio');
    echo exec('sudo modprobe w1-therm');
  }

  /**
   * Create data file pointers to attached sensors.
   *
   * @param array $sensors
   * @return array
   */
  public function getStreams(array $sensors)
  {
    $slaveFile = 'w1_slave';
    foreach($sensors as $sensor) {
      $streams[] = fopen($this->baseDirectory . '/' . $sensor . '/' . $slaveFile, 'r');
    }

    return $streams;
  }

  /**
   * Read data from attached sensors.
   *
   * @param array $streams
   * @return bool|string. Return false if no data if no streams.
   */
  public function readSensors(array $streams)
  {
    if (!$streams) {
      return FALSE;
    }

    $logString = '';

    foreach($streams as $key => $stream) {
      $raw = '';
      $raw = stream_get_contents($stream, -1);
      $temp = strstr($raw, 't=');
      $temp = trim($temp, "t=");
      $temp = number_format($temp/1000, 3);
      if ($key == 0) {
        $logString = date('Y-m-d H:i:s') . ', ' . $temp;
        print date('Y-m-d H:i:s');
        print (' - Sensor' . $key . ' ' . $temp . 'ºC');
      }
      else {
        $logString .= ', ' . $temp;
        print (' - Sensor' . $key . ' ' . $temp . 'ºC');
      }
    }
    $logString .= "\r\n";
    print "\n";

    return $logString;
  }

  /**
   * Write data from sensors to log file.
   *
   * @param $logString
   */
  public function writeLogFile($logString)
  {
    $fileName = 'temp.log';
    $logFile = fopen($fileName, 'a');
    fwrite($logFile, $logString);
    fclose($logFile);
  }

  /**
   * Close all attached sensors.
   * @param array $streams
   */
  public function closeStreams(array $streams)
  {
    if ($streams) {
      foreach($streams as $stream) {
        fclose($stream);
        print("Closing $stream. \n");
      }
    }
  }
}
