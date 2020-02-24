<?php
declare(strict_types=1);

namespace steinmb\onewire;

final class Temperature
{
    private $entity;

    public function __construct(DataEntity $entity)
    {
        $this->entity = $entity;
    }

    public function temperature(): string
    {
        $rawData = $this->entity->measurement();

        if (!$this->validateCRC($rawData)) {
            return 'error';
        }

        $rawTemp = strstr($rawData, 't=');
        $rawTempTrimmed = trim($rawTemp, 't=');

        if (!$this->validateTemperature($rawTempTrimmed)) {
            return 'error';
        }

        return number_format((int) $rawTempTrimmed / 1000, 3);
    }

    private function validateCRC(string $rawData): bool
    {
        return !(false === strpos($rawData, 'YES'));
    }

    private function validateTemperature(string $temperature): bool
    {
        return ($temperature !== 127687 or $temperature !== 85000);
    }

}
