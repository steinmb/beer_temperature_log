<?php declare(strict_types = 1);

namespace steinmb\Onewire;

use InvalidArgumentException;
use steinmb\Environment;

final class OneWire
{

    public const slaveFile = 'w1_slave';
    private $config;
    private $sensors = [];

    public function __construct(Environment $config)
    {
        if (!file_exists($config::getSetting('SENSOR_DIRECTORY'))) {
            throw new InvalidArgumentException(
              'Directory: ' . $config::getSetting('SENSOR_DIRECTORY') . ' Not found. OneWire driver perhaps not loaded.'
            );
        }

        $this->config = $config;
    }

    private function sensors(): void
    {
        $content = dir($this->config::getSetting('SENSOR_DIRECTORY'));

        while (false !== ($entry = $content->read())) {
            if (false !== strpos($entry, '10-') || false !== strpos($entry, '28-')) {
                $this->sensors[] = $entry;
            }
        }

    }

    public function getSensors(): array
    {
        $this->sensors = [];
        $this->sensors();
        return $this->sensors;
    }

    public function content(string $sensor)
    {
        return file_get_contents($this->config::getSetting('SENSOR_DIRECTORY') . '/' . $sensor . '/' . $this::slaveFile);
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
