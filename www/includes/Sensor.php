<?php
declare(strict_types=1);

/**
 * @file Sensor.php
 */

/**
 * Create sensor object based on existing data sources.
 *
 */
class Sensor
{
    private $baseDirectory;
    private $sensors;

    public function __construct(string $baseDirectory)
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
     */
    public function getSensors(): void
    {
        if (file_exists($this->baseDirectory)) {
            $content = dir($this->baseDirectory);
            while (false !== ($entry = $content->read())) {
                if (false !== strpos($entry, '10-') || false !== strpos($entry, '28-')) {
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
    public function createEntities(): array
    {
        $type = 'temperature';
        $entities = [];
        foreach ($this->sensors as $sensor) {
            $entities[] = new DataEntity($sensor, $type);
        }

        return $entities;
    }
}
