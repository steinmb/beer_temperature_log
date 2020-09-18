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

    public function temperature(string $scale = 'celsius')
    {
        if (!$this->validateCRC($this->entity->measurement())) {
            return 'error';
        }

        $rawTemp = strstr($this->entity->measurement(), 't=');
        $rawTempTrimmed = (int) trim($rawTemp, 't=');

        if (!$this->validateTemperature($rawTempTrimmed)) {
            return 'error';
        }

        $celsius = $this->celsius($rawTempTrimmed);

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

        return $temperature + $this->offset;
    }

    private function celsius($rawTempTrimmed): float
    {
        return (float) number_format($rawTempTrimmed / 1000, 3);
    }

    private function validateCRC(string $rawData): bool
    {
        return !(false === strpos($rawData, 'YES'));
    }

    private function validateTemperature(int $temperature): bool
    {
        return ($temperature !== 127687 or $temperature !== 85000);
    }

    public function __toString(): string
    {
        return $this->entity->timeStamp() . ', ' . $this->entity->id() . ', ' . $this->temperature();
    }

}
