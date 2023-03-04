<?php

declare(strict_types = 1);

/**
 * Example application.
 */

include_once __DIR__ . '/../vendor/autoload.php';

use steinmb\Alarm;
use steinmb\BrewSession;
use steinmb\BrewSessionConfig;
use steinmb\EntityFactory;
use steinmb\Onewire\SensorFactory;
use steinmb\RuntimeEnvironment;
use steinmb\Logger\Logger;
use steinmb\Logger\Handlers\FileStorageHandler;
use steinmb\Onewire\Sensor;
use steinmb\SystemClock;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Temperature;
use steinmb\Utils\Calculate;

RuntimeEnvironment::init();
$brewSessionConfig = new BrewSessionConfig(RuntimeEnvironment::getSetting('BATCH'));
$oneWire = new OneWire(__DIR__ . '/../tests/data_all_valid');
$sensorFactory = new SensorFactory($oneWire);
$trendCalculator = new Calculate();

foreach ($sensorFactory->allSensors() as $sensor) {
    $loggerService = new Logger('Demo');
    $loggerService->pushHandler(new FileStorageHandler($sensor->id . '.csv'));

    $entity = $sensor->createEntity($sensor);
    $temperature = new Temperature($entity);

    $trend = $trendCalculator->calculateTrend(
        15,
        $loggerService->lastEntry(),
        $loggerService->lastEntries(15)
    );
    $brewSession = $brewSessionConfig->sessionIdentity($sensor);

    $alarmStatus = '';
    if ($brewSession instanceof BrewSession) {
        $alarm = new Alarm($brewSession);
        $alarmStatus = $alarm->checkLimits($temperature);
    }

    if ($alarmStatus) {
        echo $alarmStatus . PHP_EOL;
    }

    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature()}ºC \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('fahrenheit')}ºF \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('kelvin')}ºK \n";
    print $temperature . PHP_EOL;
    print 'Trend: ' . $trend . PHP_EOL;
    $loggerService->write((string) $temperature);
    $loggerService->close();
}
