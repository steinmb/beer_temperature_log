<?php declare(strict_types=1);

namespace steinmb;

use UnexpectedValueException;

final class BrewSessionConfig
{
    public function __construct(private $settings) {}

    public function sessionIdentity(string $probe): BrewSessionInterface
    {
        foreach ($this->settings as $batchID => $setting) {
            if ($setting['probe'] === $probe) {
                return new BrewSession(
                    (string) $batchID,
                    $probe,
                    $setting['ambient'],
                    $setting['low_limit'],
                    $setting['high_limit']
                );
            }

            if ($setting['ambient'] === $probe) {
                return new AmbiguousSessionId();
            }
        }

        throw new UnexpectedValueException(
            'Probe: ' . $probe . ' not found.'
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
