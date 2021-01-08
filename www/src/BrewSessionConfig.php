<?php declare(strict_types=1);

namespace steinmb;

use UnexpectedValueException;

final class BrewSessionConfig
{
    private $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function sessionIdentity(string $probe): string
    {
        foreach ($this->settings as $batchID => $setting) {
            if ($setting['probe'] === $probe) {
                return (string) $batchID;
            }

            if ($setting['ambient'] === $probe) {
                return '';
            }
        }

        throw new UnexpectedValueException(
            'Probe: ' . $probe . ' not found.'
        );
    }

    public function ambientProbeIs(string $probe): string
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

    public function highLimit(float $temperature): bool
    {
        return true;
    }

    public function lowLimit(float $temperature): bool
    {
        return true;
    }
}
