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
   * @return array of parsed data from sensors.
   */
  public function getData(array $sensors)
  {
    $data = '';
    foreach($sensors as $sensor) {
      $rawData = file_get_contents($this->baseDirectory . '/' . $sensor . '/' . $this->slaveFile);
      if ($rawData) {
        $result = $this->parseData($rawData);
        if ($result) {
          $data[] = $result;
        }
        else {
          $data[] = '';
        }
      }
    }

    return $data;
  }

  /**
   * Parse sensor raw data. Check for CRC fail and temperatur data.
   * @param $data string of raw data from sensor.
   *
   * @return bool|string return parsed data. False if CRC fails.
   */
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
}
