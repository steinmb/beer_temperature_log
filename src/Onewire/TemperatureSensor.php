<?php

declare(strict_types=1);

namespace steinmb\Onewire;

use UnexpectedValueException;

readonly class TemperatureSensor implements Sensors
{
    public function __construct(
      private OneWireInterface $interface,
      public string $id,
    ) {}

    public function sensorValue(): int
    {
        $rawTemp = strstr($this->rawValue(), 't=');
        return (int) trim($rawTemp, 't=');
    }

    public function rawValue(): string
    {
        return $this->interface->content($this->id);
    }

    public function temperature(string $scale = 'celsius', float $offset = 0): string
    {
        $validate = $this->validate();
        if (!$validate) {
            return '';
        }

        $celsius = $this->celsius($this->sensorValue());

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

    private function validate(): bool
    {
        if (!$this->validateCRC()) {
            return false;
        }

        if (!$this->validateTemperature($this->sensorValue())) {
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

    private function validateCRC(): bool
    {
        if (strpos($this->rawValue(), 'YES')) {
            return true;
        }

        throw new UnexpectedValueException(
          $this->id . ': CRC check. Check sensor wiring and pull up resistor value.'
        );
    }

    private function validateTemperature(int $temperature): bool
    {
        if ($temperature !== 127687 && $temperature !== 85000 && $temperature !== 00000 && $temperature !== -1250) {
            return true;
        }

        throw new UnexpectedValueException(
          $this->id . ": Value ($temperature) out of range. Check sensor and wiring."
        );
    }

    public function __toString(): string
    {
        return $this->id . ', ' . $this->temperature();
    }
}
