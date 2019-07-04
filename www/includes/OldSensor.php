<?php

declare(strict_types=1);

/**
 * @file OldSensor.php
 */

/**
 * Read data from Dallas DS18B20 one wire digital thermometer.
 */
class OldSensor
{

    private $baseDirectory;
    private $slaveFile = 'w1_slave';

    public function __construct($baseDirectory)
    {
        $this->baseDirectory = $baseDirectory;
    }

    /**
     * Initialize one wire GPIO bus by loading 1 wires drivers.
     */
    public function initW1(): void
    {
        echo exec('sudo modprobe w1-gpio');
        echo exec('sudo modprobe w1-therm');
    }

    /**
     * Scan one wire bus for attached sensors.
     *
     * @return array $sensors
     *  A list of sensors found.
     */
    public function getSensors(): array
    {
        $sensors = [];
        if (!file_exists($this->baseDirectory)) {
            return $sensors;
        }

        $content = dir($this->baseDirectory);
        while (false !== ($entry = $content->read())) {
            if (strstr($entry, '10-') || strstr($entry, '28-')) {
                $sensors[] = $entry;
            }
        }

        return $sensors;
    }

    /**
     * Read data from sensor.
     *
     * @param array $sensors of sensor ID.
     * @return array of parsed data from sensors.
     */
    public function getData(array $sensors): array
    {
        $data = [];
        foreach ($sensors as $sensor) {
            $rawData = file_get_contents($this->baseDirectory . '/' . $sensor . '/' . $this->slaveFile);

            if ($rawData) {
                $result = $this->parseData($rawData);
                if ($result) {
                    $data[] = $result;
                } else {
                    $data[] = '';
                }
            }
        }

        return $data;
    }

    /**
     * Parse sensor raw data. Check for CRC fail and temperature data.
     *
     * @param $data string of raw data from sensor.
     * @return bool|string return parsed data. False if CRC fails.
     */
    private function parseData($data)
    {
        if (false === strpos($data, 'YES')) {
            print 'Sensor read error. CRC fail.' . PHP_EOL;
            return false;
        }

        echo 'Raw: ' . $data . PHP_EOL;
        $data = strstr($data, 't=');
        $data = trim($data, "t=");
        echo 'Raw selected: ' . $data . PHP_EOL;
        $data = number_format($data / 1000, 3);

        return $data;
    }
}
