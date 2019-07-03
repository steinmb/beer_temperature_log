<?php

declare(strict_types = 1);

/**
 * Create sensor object based on existing data sources.
 *
 * @return array of sensors with sample date stamp.
 */

class Sensor
{
  private $baseDirectory = '';
  private $sensors;

  public function __construct($baseDirectory)
  {
    $this->baseDirectory = $baseDirectory;
    $this->getSensors();
  }

  /**
   * Initialize one wire GPIO bus by loading 1 wires drivers.
   */
  public function initW1() {
    echo exec('sudo modprobe w1-gpio');
    echo exec('sudo modprobe w1-therm');
  }

  /**
   * Scan one wire bus for attached sensors.
   */
  public function getSensors() {
    if (file_exists($this->baseDirectory)) {
      $content = dir($this->baseDirectory);
      while (FALSE !== ($entry = $content->read())) {
        if (strstr($entry, '10-')) {
          $this->sensors[] = $entry;
        }
      }
    }
  }

  /**
   * Create entities. One per data object pr. sensor found.
   *
   * @return array of data objects.
   */
  public function createEntities()
  {
    $type = 'temperature';
    $entities = [];
    foreach ($this->sensors as $sensor) {
      $entities[] = new DataEntity($sensor, $type);
    }

    return $entities;
  }
}
