<?php declare(strict_types = 1);

namespace steinmb\Onewire;

use steinmb\Environment;

final class OneWire implements OneWireInterface
{
    public const slaveFile = 'w1_slave';
    private $sensors;
    private $directory;

    public function __construct(string $directory = '', string $sensors = '')
    {
        if (!$sensors) {
            $sensors = Environment::getSetting('SENSORS');
        }

        if (!$directory) {
            $directory = Environment::getSetting('SENSOR_DIRECTORY');
        }

        $this->sensors = $sensors;
        $this->directory = $directory;
    }

    public function directory(): string
    {
        return $this->directory;
    }

    public function sensors(): string
    {
        return $this->sensors;
    }

    public function allSensors(): array
    {
        $sensors = rtrim(file_get_contents($this->sensors), PHP_EOL);
        return explode(PHP_EOL, $sensors);
    }

    public function content(string $sensor)
    {
        return file_get_contents($this->directory . '/' . $sensor . '/' . $this::slaveFile);
    }

    /**
     * Initialize one wire GPIO bus by loading 1 wires drivers.
     */
    public function initW1(): void
    {
        echo exec('sudo modprobe w1-gpio');
        echo exec('sudo modprobe w1-therm');
    }

}
