<?php
declare(strict_types=1);

namespace steinmb\onewire;

class Temperature
{
    private $slaveFile = 'w1_slave';
    private $id;
    private $directory;

    public function __construct(string $id, string $directory)
    {
        $this->id = $id;
        $this->directory = $directory;
    }

    public function temperature(): string
    {
        $rawData = file_get_contents($this->directory . '/' . $this->id . '/' . $this->slaveFile);

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
