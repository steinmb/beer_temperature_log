<?php declare(strict_types = 1);

namespace steinmb\Onewire;

use RuntimeException;
use steinmb\RuntimeEnvironment;

final class OneWire implements OneWireInterface
{
    public const slaveFile = 'w1_slave';
    private $sensors;
    private $directory;

    public function __construct(string $directory = '', string $sensors = '')
    {
        if (!$sensors) {
            $sensors = RuntimeEnvironment::getSetting('SENSORS');
        }

        if (!$directory) {
            $directory = RuntimeEnvironment::getSetting('SENSOR_DIRECTORY');
        }

        $this->sensors = $sensors;
        $this->directory = $directory;
    }

    private function directory(): string
    {
        return $this->directory;
    }

    public function temperatureSensors(): array
    {
        if (!file_exists($this->directory())) {
            throw new RuntimeException(
                'Directory: ' . $this->directory() . ' Not found. OneWire support perhaps not loaded.'
            );
        }

        $temperatureSensors = [];
        $content = dir($this->directory());

        while (false !== ($entry = $content->read())) {
            if (false !== strpos($entry, '10-') || false !== strpos($entry, '28-')) {
                $temperatureSensors[] = $entry;
            }
        }

        return $temperatureSensors;
    }

    public function allSensors(): array
    {
        $sensors = rtrim(file_get_contents($this->sensors), PHP_EOL);
        return explode(PHP_EOL, $sensors);
    }

    public function content(string $sensor): string
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
