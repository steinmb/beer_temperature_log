<?php
declare(strict_types=1);

/**
 * @file Sensor.php
 */

/**
 * Create sensor object based on existing data sources.
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
     * Initialize one wire GPIO bus by loading 1 wires drivers.
     */
    public function initW1(): void
    {
        echo exec('sudo modprobe w1-gpio');
        echo exec('sudo modprobe w1-therm');
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

    /**
     * Create entities. One per data object pr. sensor found.
     *
     * @param array $sensors
     * @return array of data objects.
     */
    public function createEntities(array $sensors, DataEntity $dataEntity): array
    {
        if (!$sensors) {
            return [];
        }

        $type = 'temperature';
        $entities = [];
        foreach ($sensors as $sensor) {
            $entities[] = new DataEntity($sensor, $type);
        }

        return $entities;
    }
}
