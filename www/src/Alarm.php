<?php declare(strict_types=1);

namespace steinmb;

use steinmb\Onewire\Temperature;
use UnexpectedValueException;

class Alarm
{
    private $low_limit;
    private $high_limit;

    public function __construct(BrewSessionInterface $brewSession)
    {
        if (!$brewSession->high_limit) {
            throw new UnexpectedValueException(
                'No high temperature limit defined in session ' . $brewSession->sessionId
            );
        }
        $this->high_limit = $brewSession->high_limit;

        if (!$brewSession->low_limit) {
            throw new UnexpectedValueException(
                'No high temperature limit defined in session ' . $brewSession->sessionId
            );
        }
        $this->low_limit = $brewSession->low_limit;
    }

    public function checkLimits(Temperature $temperature): string
    {
        $status = '';

        if ($this->highLimit($temperature)) {
            $status = 'High limit reached: ' . $temperature->temperature();
        }

        if ($this->lowLimit($temperature)) {
            $status = 'Low limit reached: ' . $temperature->temperature();
        }

        return $status;
    }

    private function highLimit(Temperature $temperature): bool
    {
        return !($this->high_limit > $temperature->temperature());
    }

    private function lowLimit(Temperature $temperature): bool
    {
        return !($this->low_limit < $temperature->temperature());
    }
}
