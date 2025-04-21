<?php

declare(strict_types = 1);

/**
 * Example application.
 */

include_once __DIR__ . '/../vendor/autoload.php';

use steinmb\Alarm;
use steinmb\BrewSession;
use steinmb\BrewSessionConfig;
use steinmb\Enums\DateFormat;
use steinmb\Onewire\SensorFactory;
use steinmb\RuntimeEnvironment;
use steinmb\Logger\Logger;
use steinmb\Logger\Handlers\FileStorageHandler;
use steinmb\SystemClock;
use steinmb\Onewire\OneWire;
use steinmb\Utils\Calculate;
use steinmb\Utils\Trend;

RuntimeEnvironment::init();
$brewSessionConfig = new BrewSessionConfig(RuntimeEnvironment::getSetting('BATCH'));
$oneWire = new OneWire(__DIR__ . '/../tests/data_all_valid');
$sensorFactory = new SensorFactory($oneWire);
$trendCalculator = new Calculate();
$clockService = new SystemClock();

foreach ($sensorFactory->allSensors() as $sensor) {
    $loggerService = new Logger('Demo');
    $loggerService->pushHandler(new FileStorageHandler($sensor->id . '.csv'));
    $now = $clockService->currentTime();

    $lastEntries = $loggerService->lastEntries(15);
    if ($lastEntries !== '') {
        $foo = $loggerService->toArray($lastEntries);
        $trend = $trendCalculator->calculateTrend(
            15,
            $loggerService->lastEntry(),
            $loggerService->toArray($lastEntries),
        );
    }

    $alarmStatus = '';
    $brewSession = $brewSessionConfig->sessionIdentity($sensor);

    if ($brewSession instanceof BrewSession) {
        $alarm = new Alarm($brewSession);
        $alarmStatus = $alarm->checkLimits($sensor);
    }

    if ($alarmStatus) {
        echo $alarmStatus . PHP_EOL;
    }

    $result = 'Date: ' . $now->format(DateFormat::DateTime->value) . 'Id: ' . $sensor->id;

    print $result . ' ' . $sensor->temperature() . 'ºC' . PHP_EOL;
    print $result . ' ' . $sensor->temperature('fahrenheit') . 'ºF' . PHP_EOL;
    print $result . ' ' . $sensor->temperature('kelvin') . 'ºK' . PHP_EOL;
    print $sensor . PHP_EOL;

    if (isset($trend) && $trend instanceof Trend) {
        print 'Trend: ' . $trend->getTrend() . PHP_EOL;
    }

    $loggerService->write($sensor->temperature());
    $loggerService->close();
}
