<?php

declare(strict_types=1);

namespace steinmb;

use RuntimeException;

final class RuntimeEnvironment
{
    private static array $settings = [
        'BREW_ROOT' => __DIR__,
        'DEMO_MODE' => FALSE,
        'LOG_DIRECTORY' => '../../brewlogs',
        'LOG_INFO' => 'info.log',
        'TEST_DATA' => __DIR__ . '/tests/test_data',
    ];

    public static function init(): void
    {
        $configuration = [];

        if (file_exists(__DIR__ . '/../settings.php')) {
            include_once __DIR__ . '/../settings.php';
            $mergedSettings = array_merge(self::$settings, $configuration);
            self::$settings = $mergedSettings;
        }

        if (!file_exists(self::getSetting('BREW_ROOT'))) {
            throw new RuntimeException('Application root does not exist. Giving up.');
        }
    }

    public static function getSetting($setting)
    {

        if (!self::$settings['BREW_ROOT']) {
            self::setSetting('BREW_ROOT', dirname(__DIR__));
        }

        return self::$settings[$setting] ?? '';
    }

    public static function setSetting(string $setting, $value): void
    {
        self::$settings[$setting] = $value;
    }
}
