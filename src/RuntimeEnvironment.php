<?php

declare(strict_types=1);

namespace steinmb;

use RuntimeException;

final class RuntimeEnvironment
{
    private static array $settings = [
        'BREW_ROOT' => __DIR__ . '/..',
        'LOG_DIRECTORY' => __DIR__ . '/../../brewlogs',
        'TEST_DATA' => __DIR__ . '/../tests/test_data',
        'DEMO_MODE' => FALSE,
        'LOG_INFO' => 'info.log',
    ];

    public static function init(): void
    {
        $configuration = [];
        $settings = self::$settings['BREW_ROOT'] . '/settings.php';

        if (file_exists($settings)) {
            include_once $settings;
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
