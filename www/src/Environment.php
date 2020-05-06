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
        'SENSORS' => '/sys/bus/w1/devices/w1_bus_master1/w1_master_slaves',
        'TEST_DIRECTORY' => '/test',
    ];

    public static function getSetting($setting)
    {
        if (self::$settings['DEMO_MODE'] === TRUE) {
            self::demoMode();
        }

        return self::$settings[$setting];
    }

    static private function demoMode()
    {
        self::$settings['SENSOR_DIRECTORY'] = self::$settings['BREW_ROOT'] . '/test';
        self::$settings['SENSORS'] = self::$settings['BREW_ROOT'] . '/test/w1_master_slaves';
    }

    public static function setSetting(string $setting, $value): void
    {
        self::$settings[$setting] = $value;
    }

}
