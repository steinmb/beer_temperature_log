<?php

/**
 * Created by PhpStorm.
 * User: steinmb
 * Date: 22/03/16
 * Time: 23:30
 */
class Sensor
{
    private $sensorData;
    private $sensorID;

    public function __construct($sensorData, $sensorID)
    {
        $this->sensorData = $sensorData;
        $this->sensorID = $sensorID;
    }

    public function getSensorData()
    {
        return $this->sensorData;
    }

    public function getSensorID()
    {
        return $this->sensorID;
    }
}
