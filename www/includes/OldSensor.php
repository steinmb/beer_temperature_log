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
            if (false !== strpos($entry, '10-') || false !== strpos($entry, '28-')) {
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
                $data[] = $result;
            }
        }

        return $data;
    }

    /**
     * Parse sensor raw data. Check for CRC fail and temperature data.
     *
     * @param $rawData string
     *  Raw data from sensor.
     * @return string
     *  Return temp. in Celsius or empty if CRC check failed.
     */
    private function parseData(string $rawData): string
    {
        if (false === strpos($rawData, 'YES')) {
            print 'Sensor read error. CRC fail.' . PHP_EOL;
            return '';
        }

        $data = strstr($rawData, 't=');
        $data = trim($data, 't=');
        return number_format((int) $data / 1000, 3);
    }
}
