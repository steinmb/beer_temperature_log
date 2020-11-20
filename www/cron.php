<?php declare(strict_types = 1);

/**
 * @file cron.php
 *
 * Reads and store data from all attached sensors.
 */

use steinmb\EntityFactory;
use steinmb\RuntimeEnvironment;
use steinmb\Logger\FileStorage;
use steinmb\Logger\Logger;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Sensor;
use steinmb\Onewire\Temperature;
use steinmb\SystemClock;

include_once __DIR__ . '/vendor/autoload.php';

RuntimeEnvironment::init(__DIR__ . '/settings.php');

//RuntimeEnvironment::setSetting('BREW_ROOT', __DIR__);

$sensor = new Sensor(new OneWire(), new SystemClock(), new EntityFactory());
$probes = (!$sensor->getTemperatureSensors()) ? exit('No probes found.'): $sensor->getTemperatureSensors();
$loggerService = new Logger('temperature');

foreach ($probes as $probe) {
    $temperature = new Temperature($sensor->createEntity($probe));
    $fileLogger = $loggerService->pushHandler(new FileStorage($probe . '.csv'));
    $fileLogger->write((string) $temperature);
    $fileLogger->close();
}
