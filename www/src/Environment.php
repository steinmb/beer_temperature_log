<?php declare(strict_types = 1);

namespace steinmb;

class Environment
{
    private static $settings = [
      'BREW_ROOT' => '',
      'DEMO_MODE' => FALSE,
      'LOG_DIRECTORY' => '../../brewlogs',
      'LOG_FILENAME' => 'temperature.csv',
      'LOG_INFO' => 'info.log',
      'SENSOR_DIRECTORY' => '/sys/bus/w1/devices',
      'TEST_DIRECTORY' => '/test',
    ];

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

}
