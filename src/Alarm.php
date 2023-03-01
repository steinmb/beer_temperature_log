<?php

declare(strict_types=1);

namespace steinmb;

use steinmb\Onewire\TemperatureSensor;
use UnexpectedValueException;

final class Alarm
{
    public function __construct(private readonly BrewSessionInterface $brewSession)
    {
        if (!$brewSession->high_limit) {
            throw new UnexpectedValueException(
              'No high temperature limit defined in session ' . $brewSession->sessionId
            );
        }

        if (!$brewSession->low_limit) {
            throw new UnexpectedValueException(
              'No high temperature limit defined in session ' . $brewSession->sessionId
            );
        }
    }

    public function checkLimits(TemperatureSensor $temperature): string
    {
        $status = '';
        $currentTemperature = $temperature->temperature();

        if ($this->highLimit($temperature)) {
            $status = 'Batch: ' . $this->brewSession->sessionId . ' - High limit ' . $this->brewSession->high_limit . 'C reached: ' . $currentTemperature;
        }

        if ($this->lowLimit($temperature)) {
            $status = 'Batch: ' . $this->brewSession->sessionId . ' - Low limit ' . $this->brewSession->low_limit . 'C reached: ' . $currentTemperature;
        }

        return $status;
    }

    private function highLimit(TemperatureSensor $temperature): bool
    {
        return !($this->brewSession->high_limit > $temperature->temperature());
    }

    private function lowLimit(TemperatureSensor $temperature): bool
    {
        return !($this->brewSession->low_limit < $temperature->temperature());
    }
}
