<?php

declare(strict_types=1);

namespace steinmb;

use steinmb\Onewire\TemperatureSensor;
use UnexpectedValueException;

final class Alarm
{
    private const HIGH = 'High limit';
    private const LOW = 'Low limit';

    public function __construct(private readonly BrewSession $brewSession)
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
        if ($this->highLimit($temperature)) {
            return $this->statusMessage(self::HIGH, $temperature);
        }

        if ($this->lowLimit($temperature)) {
            return $this->statusMessage(self::LOW, $temperature);
        }

        return '';
    }

    private function statusMessage(string $message, TemperatureSensor $temperature): string
    {
        return 'Batch: ' . $this->brewSession->sessionId . ' - ' . $message . ' ' . $this->brewSession->high_limit . 'C reached: ' . $temperature->temperature();
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
