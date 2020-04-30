<?php declare(strict_types=1);

namespace steinmb\Onewire;

use UnexpectedValueException;

final class Temperature
{
    public $entity;

    private $offset = 0;

    public function __construct(EntityInterface $entity)
    {
        $this->entity = $entity;
    }

    public function temperature(string $scale = 'celsius')
    {
        $rawData = $this->entity->measurement();

        if (!$this->validateCRC($rawData)) {
            return 'error';
        }

        $rawTemp = strstr($rawData, 't=');
        $rawTempTrimmed = (int) trim($rawTemp, 't=');

        if (!$this->validateTemperature($rawTempTrimmed)) {
            return 'error';
        }

        $celsius = (float) number_format($rawTempTrimmed / 1000, 3);
        if ($scale === 'celsius') {
            return $celsius;
        }

        if ($scale === 'fahrenheit') {
            $temperature = $celsius * (9/5) + 32;
        } elseif ($scale === 'kelvin') {
            $temperature = $celsius + 273.15;
        } else {
            throw new UnexpectedValueException(
              'Unknown temperature scale: ' . $scale
            );
        }

        return $temperature;
    }

    public function offset($offset = 0)
    {
        return $offset;
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
