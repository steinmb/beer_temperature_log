<?php declare(strict_types = 1);

namespace steinmb\Onewire;

use RuntimeException;
use steinmb\RuntimeEnvironment;

final class OneWire implements OneWireInterface
{
    public const slaveFile = 'w1_slave';

    public function __construct(
      private string $directory = '',
      private string $sensors = '',
    )
    {
        if (!$sensors) {
            $this->sensors = RuntimeEnvironment::getSetting('SENSORS');
        }

        if (!$directory) {
            $this->directory = RuntimeEnvironment::getSetting('SENSOR_DIRECTORY');
        }
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
            if (str_contains($entry, '10-') || str_contains($entry, '28-')) {
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

    public function content(string $sensor, int $retries = 3): string
    {
      $fileContent = '';

      while ($retries) {
        $fileContent = file_get_contents($this->directory . '/' . $sensor . '/' . $this::slaveFile);
        if ($fileContent) {
          return $fileContent;
        }
        $retries--;
        sleep(1);
      }

      return $fileContent;
    }

    public function __toString(): string
    {
      return implode(PHP_EOL, $this->allSensors());
    }

  /**
     * Initialize the GPIO bus by loading the 1-Wire kernel driver.
     */
    public function initW1(): void
    {
        if (PHP_OS !== 'Linux') {
            echo 'Error: Loading one wire drivers only supported on Linux systems.';
            return;
        }

        echo "You need to run the following commands as root or with sudo: \n";
        echo "sudo modprobe w1-gpio \n";
        echo "sudo modprobe w1-therm \n";
    }
}
