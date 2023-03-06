<?php

declare(strict_types=1);

namespace steinmb;

use steinmb\Onewire\TemperatureSensor;
use UnexpectedValueException;

final class BrewSessionConfig
{
    public function __construct(private $settings) {}

    public function sessionIdentity(TemperatureSensor $sensor): BrewSessionInterface
    {
        foreach ($this->settings as $batchID => $setting) {
            if ($setting['probe'] === $sensor->id) {
                return new BrewSession(
                    (string) $batchID,
                    $sensor->id,
                    $setting['ambient'],
                    $setting['low_limit'],
                    $setting['high_limit']
                );
            }

            if ($setting['ambient'] === $sensor->id) {
                return new AmbiguousSessionId();
            }
        }

        throw new UnexpectedValueException(
            'Probe: ' . $sensor->id . ' not found.'
        );
    }

    private function ambientProbeIs(string $probe): string
    {
        foreach ($this->settings as $batchID => $setting) {
            if ($setting['ambient'] === $probe) {
                return $probe;
            }

            if ($setting['probe'] === $probe) {
                return $setting['ambient'];
            }
        }

        throw new UnexpectedValueException(
            'Ambient probe not found.'
        );
    }
}
