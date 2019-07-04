<?php

declare(strict_types = 1);

final class SensorTest
{
    public $testActivated = FALSE;
    public $w1gpio;
    public $logger;

    public function __construct(string $argument, $w1gpio, Logger $logger)
    {
        if ($argument === '--test') {
            echo 'Running in test mode.' . PHP_EOL;
            $this->testActivated = true;
            $this->w1gpio = $w1gpio;
            $this->logger = $logger;
        } else {
            echo 'Invalid argument. Valid arguments: --test' . PHP_EOL;
            $this->testActivated = false;
        }
    }

    private function findSensors(): array
    {
        $sensors = $this->w1gpio->getSensors();

        if (count($sensors) !== 4) {
            throw new \http\Exception\UnexpectedValueException('Missing sensors. Expected 4, only got:' . count($sensors));
        }

        return $sensors;
    }

    public function setLogDirectory(): void
    {
        $this->logger->setLogDirectory(BREW_ROOT . '/test/');
    }

    public function logData(): void
    {
        $logString = $this->w1gpio->getData($this->findSensors());
        $this->logger->writeLogFile($logString);
    }
}