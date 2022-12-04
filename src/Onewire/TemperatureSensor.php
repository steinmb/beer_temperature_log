<?php

declare(strict_types=1);

namespace steinmb\Onewire;

use UnexpectedValueException;

class TemperatureSensor implements Sensors
{
    public function __construct(private readonly OneWireInterface $interface)
    {
    }

    public function sensorValue(string $sensorId): int
    {
        $rawTemp = strstr($this->rawValue($sensorId), 't=');
        return (int) trim($rawTemp, 't=');
    }

    public function rawValue(string $sensorId): string
    {
        return $this->interface->content($sensorId);
    }

    public function temperature(string $sensorId, string $scale = 'celsius', float $offset = 0): string
    {
        $validate = $this->validate($sensorId);
        if (!$validate) {
            return '';
        }

        $celsius = $this->celsius($this->sensorValue($sensorId));

        if ($scale === 'celsius') {
            $temperature = $celsius;
        } elseif ($scale === 'fahrenheit') {
            $temperature = $this->fahrenheit($celsius);
        } elseif ($scale === 'kelvin') {
            $temperature = $this->kevin($celsius);
        } else {
            throw new UnexpectedValueException(
              'Unknown temperature scale: ' . $scale
            );
        }

        $result = $temperature + $offset;

        return number_format($result, 3);
    }

    private function validate(string $sensorId): bool
    {
        if (!$this->validateCRC($sensorId)) {
            return false;
        }

        if (!$this->validateTemperature($this->sensorValue($sensorId), $sensorId)) {
            return false;
        }

        return true;
    }

    private function fahrenheit(float $celsius): float
    {
        return $celsius * (9/5) + 32;
    }

    private function kevin(float $celsius): float
    {
        return $celsius + 273.15;
    }

    private function celsius($rawTempTrimmed): float
    {
        return (float) number_format($rawTempTrimmed / 1000, 3);
    }

    private function validateCRC(string $sensorID): bool
    {
        if (strpos($this->rawValue($sensorID), 'YES')) {
            return true;
        }

        throw new UnexpectedValueException(
          $sensorID . ': CRC check. Check sensor wiring and pull up resistor value.'
        );
    }

    private function validateTemperature(int $temperature, string $sensorID): bool
    {
        if ($temperature !== 127687 && $temperature !== 85000 && $temperature !== 00000 && $temperature !== -1250) {
            return true;
        }

        throw new UnexpectedValueException(
          $sensorID . ': Value out of range. Check sensor wiring.'
        );
    }

}
