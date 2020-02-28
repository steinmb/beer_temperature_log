<?php
declare(strict_types=1);

namespace steinmb\onewire;

use InvalidArgumentException;

final class OneWire
{

    public const slaveFile = 'w1_slave';
    private $baseDirectory;
    private $sensors = [];

    public function __construct(string $baseDirectory)
    {
        if (!file_exists($baseDirectory)) {
            throw new InvalidArgumentException(
              'Directory: ' . $baseDirectory . ' Not found. OneWire driver perhaps not loaded.'
            );
        }

        $this->baseDirectory = $baseDirectory;
    }

    private function sensors(): void
    {
        $content = dir($this->baseDirectory);

        while (false !== ($entry = $content->read())) {
            if (false !== strpos($entry, '10-') || false !== strpos($entry, '28-')) {
                $this->sensors[] = $entry;
            }
        }

    }

    public function getSensors(): array
    {
        $this->sensors();
        return $this->sensors;
    }

    public function content(string $sensor)
    {
        return file_get_contents($this->baseDirectory . '/' . $sensor . '/' . $this::slaveFile);
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
