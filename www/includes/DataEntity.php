<?php

/**
 * File DataEntity.php
 *
 * Create a data entity from a temperatur sesonr.
 */

class DataEntity
{
    private $id;
    private $data;
    private $trend;

  /**
   * DataEntity constructor.
   * @param $data
   */
    public function __construct($data)
    {
      $this->data = $data;
    }

  /**
   * Set the entity ID.
   *
   * @param $id
   */
    public function setId($id)
    {
      $this->id = $id;
    }

  /**
   * Get the entity ID.
   *
   * @return integer entity ID.
   */
    public function getId()
    {
      return $this->id;
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

  /**
   * Calculate trend of the last temperature readings.
   *
   * @param $time integer minutes to calculate latest trend from.
   */
    public function calculateTrend($time = 15)
    {
        $x = '';
        $y = array();
        $x2 = array();
        $xy = array();
        $last = $this->getLastReading();

        foreach (array_reverse($this->data) as $key => $row) {
          $y[] = 1000 * $row['Sensor'];
          $x = $key + 1;
          $xy[] = $x * $y[$key];
          $x2[] = pow($x, 2);

          if (strtotime($row['Date']) <= strtotime($last['Date']) - ($time * 60)) {
            break;
          }
        }

        $samples = $x;
        $x = range(1, $x);
        $xSummary = array_sum($x);
        $ySummary = array_sum($y);
        $xySummary = array_sum($xy);
        $x2Summary = array_sum($x2);

        $this->trend = ($samples * $xySummary - ($xSummary * $ySummary)) / (($samples * $x2Summary) - (sqrt($xSummary)));
    }


  /**
   * Get entity trend data and round it down to 4 decimals.
   *
   * @return float.
   */
    public function getTrend() {
      return round($this->trend, 4);
    }

  /**
  /**
   * Analyze trend index and calculate a human friendly label for it.
   *
   * @return string index label.
   */
  public function analyzeTrend() {
    $direction = 'increasing';

    if ($this->trend < 0 ) {
      $direction = 'decreasing';
    }

    $ranges = [
      'stable' => 0.1,
      'slowly' => 0.21,
      'steady' => 0.3,
      'medium' => 0.9,
      'fast' => 2,
    ];

    $speed = '';
    foreach ($ranges as $key => $range) {
      if (ltrim($this->trend, '-') > $range) {
        $speed =  $key;
      }
    }

    return $direction . ' ' . $speed;
  }
}
