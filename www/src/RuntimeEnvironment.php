<?php declare(strict_types = 1);

namespace steinmb;

class RuntimeEnvironment
{
    private static $settings = [
        'BREW_ROOT' => '',
        'DEMO_MODE' => FALSE,
        'LOG_DIRECTORY' => '../../brewlogs',
        'LOG_INFO' => 'info.log',
        'SENSOR_DIRECTORY' => '/sys/bus/w1/devices',
        'SENSORS' => '/sys/bus/w1/devices/w1_bus_master1/w1_master_slaves',
        'TEST_DATA' => '/test_data',
    ];

    public static function init(string $settingsFile): void
    {
        $configuration = [];

        if (file_exists($settingsFile)) {
            include_once $settingsFile;
            $mergedSettings = array_merge(self::$settings, $configuration);
            self::$settings = $mergedSettings;
        }

        if (!file_exists(self::getSetting('BREW_ROOT'))) {
            throw new \Exception('Application root does not exist. Giving up.');
        }
    }

    public static function getSetting($setting)
    {

        if (!self::$settings['BREW_ROOT']) {
            self::setSetting('BREW_ROOT', dirname(__DIR__));
        }

        return self::$settings[$setting];
    }

    public static function setSetting(string $setting, $value): void
    {
        self::$settings[$setting] = $value;
    }

}
