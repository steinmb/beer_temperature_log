<?php declare(strict_types=1);

namespace steinmb\Onewire;

use UnexpectedValueException;

final class Temperature
{
    public $entity;
    private $offset;

    public function __construct(EntityInterface $entity, float $offset = 0)
    {
        $this->entity = $entity;
        $this->offset = $offset;
    }

    private function validate(): bool
    {
        if (!$this->validateCRC()) {
            return false;
        }

        if (!$this->validateTemperature($this->getTemperature())) {
            return false;
        }

        return true;
    }

    public function temperature(string $scale = 'celsius'): string
    {
        if (!$this->validate()) {
            return 'error';
        }

        $celsius = $this->celsius($this->getTemperature());

        if ($scale === 'celsius') {
            $temperature = $celsius;
        } elseif ($scale === 'fahrenheit') {
            $temperature = $celsius * (9/5) + 32;
        } elseif ($scale === 'kelvin') {
            $temperature = $celsius + 273.15;
        } else {
            throw new UnexpectedValueException(
              'Unknown temperature scale: ' . $scale
            );
        }

        $result = $temperature + $this->offset;
        return (string) number_format($result, 3);
    }

    private function celsius($rawTempTrimmed): float
    {
        return (float) number_format($rawTempTrimmed / 1000, 3);
    }

    private function validateCRC(): bool
    {
        return !(false === strpos($this->entity->measurement(), 'YES'));
    }

    private function getTemperature(): int
    {
        $rawTemp = strstr($this->entity->measurement(), 't=');
        return (int) trim($rawTemp, 't=');
    }

    private function validateTemperature(int $temperature): bool
    {
        return !($temperature === 127687 or $temperature === 85000);
    }

    public function __toString(): string
    {
        return $this->entity->timeStamp() . ', ' . $this->entity->id() . ', ' . $this->temperature();
    }

}
