<?php

/**
 * @file
 *
 * Read data from DS18x20 one wire digital thermometer and write data to log file.
 */

class OldSensor
{
  private $baseDirectory = '';
  private $slaveFile = 'w1_slave';

  public function __construct($baseDirectory)
  {
    $this->baseDirectory = $baseDirectory;
  }

  /**
   * Initialize one wire GPIO bus by loading 1 wires drivers.
   */
  public function initW1()
  {
    echo exec('sudo modprobe w1-gpio');
    echo exec('sudo modprobe w1-therm');
  }

  /**
   * Scan one wire bus for attached sensors.
   *
   * @return array $sensors of sensor ID found.
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
   * Create file streams. One pr. attached sensor.
   *
   * @param array $sensors ID.
   * @return array of file stream pointers.
   */
  public function getStreams(array $sensors)
  {
    $streams = '';
    foreach($sensors as $sensor) {
      $streams[] = fopen($this->baseDirectory . '/' . $sensor . '/' . $this->slaveFile, 'r');
    }

    return $streams;
  }

  /**
   * Read data from attached sensors and tag it with date information.
   *
   * @param array $streams of resource streams.
   * @return string $logString. Return FALSE if no streams is passed as argument.
   */
  public function readSensors(array $streams)
  {
    if (!$streams) {
      return FALSE;
    }

    $logString = '';

    foreach($streams as $key => $stream) {
      $raw = stream_get_contents($stream, -1);
      $temperatur = strstr($raw, 't=');
      $temperatur = trim($temperatur, "t=");
      $temperatur = number_format($temperatur/1000, 3);
      if ($key == 0) {
        $logString = date('Y-m-d H:i:s') . ', ' . $temperatur;
        print date('Y-m-d H:i:s');
        print (' - Sensor' . $key . ' ' . $temperatur . 'ºC');
      }
      else {
        $logString .= ', ' . $temperatur;
        print (' - Sensor' . $key . ' ' . $temperatur . 'ºC');
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
