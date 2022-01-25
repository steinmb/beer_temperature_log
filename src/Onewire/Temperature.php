<?php declare(strict_types=1);

namespace steinmb\Onewire;

use steinmb\BrewSessionInterface;
use UnexpectedValueException;

final class Temperature
{
    public function __construct(public EntityInterface $entity, private float $offset = 0) {}

    private function validate(): bool
    {
        if (!$this->validateCRC($this->entity->id())) {
            return false;
        }

        if (!$this->validateTemperature($this->getTemperature(), $this->entity->id())) {
            return false;
        }

        return true;
    }

    public function temperature(string $scale = 'celsius'): string
    {
        $this->validate();
        $celsius = $this->celsius($this->getTemperature());

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

        $result = $temperature + $this->offset;

        return number_format($result, 3);
    }

    public function highLimit(BrewSessionInterface $brewSession, Temperature $temperature): bool
    {
        if (!$brewSession->high_limit) {
            throw new UnexpectedValueException(
                'No high temperature limit defined.'
            );
        }

        return !($brewSession->high_limit > $temperature->temperature());
    }

    public function lowLimit(BrewSessionInterface $brewSession, Temperature $temperature): bool
    {
        if (!$brewSession->low_limit) {
            throw new UnexpectedValueException(
                'No high temperature limit defined.'
            );
        }
        return !($brewSession->low_limit < $temperature->temperature());
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
        if (strpos($this->entity->measurement(), 'YES')) {
            return true;
        }

        throw new UnexpectedValueException(
            $sensorID . ': CRC check. Check sensor wiring and pull up resistor value.'
        );
    }

    private function getTemperature(): int
    {
        $rawTemp = strstr($this->entity->measurement(), 't=');
        return (int) trim($rawTemp, 't=');
    }

    private function validateTemperature(int $temperature, string $sensorID): bool
    {
        if ($temperature !== 127687 && $temperature !== 85000 && $temperature !== 00000 && $temperature !== -1250) {
            return TRUE;
        }

        throw new UnexpectedValueException(
            $sensorID . ': Value out of range. Check sensor wiring.'
        );
    }

    public function __toString(): string
    {
        return $this->entity->timeStamp() . ', ' . $this->entity->id() . ', ' . $this->temperature();
    }
}
