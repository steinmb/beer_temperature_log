<?php
declare(strict_types=1);

namespace steinmb\onewire;

/**
 * @file Sensor.php
 */

/**
 * Find attached 1 wire devices and read data from them.
 */
class Sensor
{
    private $baseDirectory;

    public function __construct(string $baseDirectory)
    {
        if (!file_exists($baseDirectory)) {
            throw new InvalidArgumentException(
              'Invalid directory: ' . $baseDirectory . ' One wire GPIO not loaded.'
            );
        }

        $this->baseDirectory = $baseDirectory;
    }

    /**
     * Scan one wire bus for attached sensors and return id.
     */
    public function getSensors(): array
    {
        $sensors = [];
        $content = dir($this->baseDirectory);

        while (false !== ($entry = $content->read())) {
            if (false !== strpos($entry, '10-') || false !== strpos($entry, '28-')) {
                $sensors[] = $entry;
            }
        }

        return $sensors;
    }

    public function getDataRaw(string $sensor)
    {
        $slaveFile = 'w1_slave';
        $rawData = file_get_contents($this->baseDirectory . '/' . $sensor . '/' . $slaveFile);
        return $rawData;
    }

    public function createEntities(string $sensor, DataEntity $dataEntity): DataEntity
    {
        if (!$sensor) {
            throw new InvalidArgumentException(
              'Sensor name missing'
            );
        }

        $rawData = $this->getDataRaw($sensor);

        $type = 'temperature';
        $entity = new DataEntity($sensor, $type, 2000);

        return $entity;
    }
}
