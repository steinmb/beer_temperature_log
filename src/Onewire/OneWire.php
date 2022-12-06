<?php declare(strict_types=1);

namespace steinmb\Onewire;

use RuntimeException;
use steinmb\RuntimeEnvironment;

final class OneWire implements OneWireInterface
{
    public const slaveFile = 'w1_slave';
    public const master_slave = 'w1_master_slaves';

    public function __construct(
      private string $sensorDirectory = '',
    )
    {
        if (!$sensorDirectory) {
            $this->sensorDirectory = RuntimeEnvironment::getSetting('SENSOR_DIRECTORY');
        }
    }

    private function directory(): string
    {
        if (!file_exists($this->sensorDirectory)) {
            throw new RuntimeException(
              'Directory: ' . $this->sensorDirectory . ' Not found. OneWire support perhaps not loaded.'
            );
        }

        return $this->sensorDirectory;
    }

    public function temperatureSensors(): array
    {
        $temperatureSensors = [];

        foreach ($this->allSensors() as $sensor) {
            if (str_contains($sensor, '10-') || str_contains($sensor, '28-')) {
                $temperatureSensors[] = $sensor;
            }
        }

        return $temperatureSensors;
    }

    public function allSensors(): array
    {
        $sensors = file($this->directory() . '/' . $this::master_slave, FILE_IGNORE_NEW_LINES);
        if ($sensors === false) {
            return [];
        }

        return $sensors;
    }

    public function content(string $sensor, int $retries = 3): string
    {
      $fileContent = '';

      while ($retries) {
        $fileContent = file_get_contents($this->sensorDirectory . '/' . $sensor . '/' . $this::slaveFile);
        if ($fileContent) {
          return $fileContent;
        }
        $retries--;
        sleep(1);
      }

      return $fileContent;
    }

    public function readSensor(string $sensor, int $retries = 3): Dto
    {
        $sensorData = '';

        while ($retries) {
            $sensorData = file_get_contents($this->directory() . '/' . $sensor . '/' . $this::slaveFile);
            if ($sensorData) {
                break;
            }
            $retries--;
            sleep(1);
        }

        return new Dto(
          $sensor,
          $sensorData,
        );
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
