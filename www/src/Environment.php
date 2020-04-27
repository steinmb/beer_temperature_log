<?php declare(strict_types = 1);

namespace steinmb;

class Environment
{
    private const SETTINGS = 'settings.php';
    public static $settings = [];

    public function __construct(string $applicationDirectory, string $applicationConfig = '')
    {
        if (!$applicationConfig) {
            $applicationConfig = $applicationDirectory . '/config';
        }

        self::$settings = include $applicationConfig . '/' . self::SETTINGS;
        self::setSetting('BREW_ROOT', $applicationDirectory);

        if (self::getSetting('DEMO_MODE') === TRUE) {
            self::setSetting('SENSOR_DIRECTORY', self::getSetting('BREW_ROOT') . '/test');
        }
    }

    public static function getSetting($setting)
    {
        if (self::$settings['DEMO_MODE'] === TRUE) {
            self::$settings['SENSOR_DIRECTORY'] = self::$settings['BREW_ROOT'] . '/test';
        }

        return self::$settings[$setting];
    }

    public static function setSetting(string $setting, $value): void
    {
        self::$settings[$setting] = $value;
    }

    public function __toString(): string
    {
        $settings = '';

        foreach (self::$settings as $setting) {
            $settings .= $setting . ' ';
        }

        return $settings;
    }
}
