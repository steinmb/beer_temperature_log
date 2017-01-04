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
   * Read data from sensor.
   *
   * @param array $sensors of sensor ID.
   *
   * @return array of raw data from sensor.
   */
  public function getStreams(array $sensors)
  {
    $data = '';
    foreach($sensors as $sensor) {
      $data[] = file_get_contents($this->baseDirectory . '/' . $sensor . '/' . $this->slaveFile);
    }

    return $data;
  }

  /**
   * Read data from attached sensors and attach datestamp.
   *
   * @param array $data of resource streams.
   * @return array $sensorData of with data. Empty if no valid data found.
   */
  public function readSensors(array $data)
  {
    if (!$data) {
      return FALSE;
    }

    $sensorData = [];
    foreach($data as $raw) {
      $result = $this->parseData($raw);
      if ($result) {
        $sensorData[] = $result;
      }
      else {
        $sensorData[] = '';
      }
    }

    return $sensorData;
  }

  private function parseData($data)
  {
    if (!strstr($data, 'YES')) {
      print 'Sensor read error. CRC fail.' . PHP_EOL;
      return FALSE;
    }

    $data = strstr($data, 't=');
    $data = trim($data, "t=");
    $data = number_format($data/1000, 3);

    return $data;
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

    $fileName = 'temp.log';
    $logFile = fopen($fileName, 'a');
    fwrite($logFile, $logString);
    fclose($logFile);
  }

  /**
   * Close all attached sensors.
   * @param array $streams
   */
  public function closeStreams(array $streams) {}

}
