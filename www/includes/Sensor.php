<?php

/**
 * Create sensor object based on existing data sources.
 *
 * @return array of sensors with attached data time tagged.
 */

class Sensor
{
    private $sensorData;
    private $sensorID;
    private $sensors;

    public function __construct()
    {
//        $this->sensorData = $sensorData;
//        $this->sensorID = $sensorID;

      $data = new logFile();
      $this->sensorData = $data->getStructuredData();

      foreach ($this->sensorData as $samples) {
        foreach ($samples as $key => $row) {
          if ($this->sensors < $key) {
            $this->sensors = $key;
          }
        }
      }

    }

    public function getSensorData()
    {
        return $this->sensorData;
    }

    public function getSensorID()
    {
        return $this->sensorID;
    }

    public function getSensors()
    {
      return $this->sensors;
    }

}
