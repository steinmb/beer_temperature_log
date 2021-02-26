<?php declare(strict_types=1);

namespace steinmb;

use steinmb\Onewire\Temperature;
use UnexpectedValueException;

class Alarm
{
    private $brewSession;

    public function __construct(BrewSessionInterface $brewSession)
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

        $this->brewSession = $brewSession;
    }

    public function checkLimits(Temperature $temperature): string
    {
        $status = '';

        if ($this->highLimit($temperature)) {
            $status = 'Batch: ' . $this->brewSession->sessionId . '. High limit ' . $temperature->temperature() . 'ºC reached';
        }

        if ($this->lowLimit($temperature)) {
            $status = 'Batch: ' . $this->brewSession->sessionId . '. Low limit ' . $temperature->temperature() . 'ºC reached';
        }

        return $status;
    }

    private function highLimit(Temperature $temperature): bool
    {
        return !($this->brewSession->high_limit > $temperature->temperature());
    }

    private function lowLimit(Temperature $temperature): bool
    {
        return !($this->brewSession->low_limit < $temperature->temperature());
    }
}
