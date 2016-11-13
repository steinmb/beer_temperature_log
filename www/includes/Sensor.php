<?php

/**
 * Create sensor object based on existing data sources.
 *
 * @return array of sensors with sample date stamp.
 */

class Sensor
{
    private $sensorData;
    private $sensorID;
    private $sensors;

    public function __construct()
    {

      $data = new logFile();

      foreach ($data->getStructuredData() as $entry) {
        $this->sensorData[0][] = [
          'Date' => $entry[0],
          'Sensor' => $entry[1],
          ];
        $this->sensorData[1][] = [
          'Date' => $entry[0],
          'Sensor' => $entry[2],
          ];
      }

      foreach ($data->getStructuredData() as $samples) {
        foreach ($samples as $key => $row) {
          if ($this->sensors < $key) {
            $this->sensors = $key;
          }
        }
      }

    }

  /**
   * @return array
   */
  public function getEntities()
    {
      foreach ($this->sensorData as $index => $entry) {
        $entity = new DataEntity($entry);
        $entity->setId($index);
        $entities[] = $entity;

      }
        return $entities;
    }


}
