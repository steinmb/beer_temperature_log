<?php declare(strict_types = 1);

use steinmb\Logger\Logger;
use steinmb\onewire\OneWire;

final class SensorTest
{
    public $testActivated = false;
    public $logger;
    private $oneWire;

    public function __construct(string $argument, OneWire $oneWire, Logger $logger)
    {

        if ($argument === '--test') {
            echo 'Running in test mode.' . PHP_EOL;
            $this->testActivated = true;
        }
        else {
            echo 'Invalid argument. Valid arguments: --test' . PHP_EOL;
        }

        $this->oneWire = $oneWire;
        $this->logger = $logger;

    }

    private function findSensors(): array
    {
        $sensors = $this->oneWire->getSensors();

        if (count($sensors) !== 4) {
            throw new RuntimeException('Missing sensors. Expected 4, only got:' . count($sensors));
        }

        return $sensors;
    }

    public function logData(): void
    {
        $logString = $this->logger->read();
        $this->logger->write($logString);
    }
}
