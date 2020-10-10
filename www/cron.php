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

RuntimeEnvironment::setSetting('BREW_ROOT', __DIR__);
$sensor = new Sensor(new OneWire(), new SystemClock(), new EntityFactory());
$probes = (!$sensor->getTemperatureSensors()) ? exit('No probes found.'): $sensor->getTemperatureSensors();
$logger = new Logger('temperature');
$handler = new FileStorage('temperature.csv');
$logger->pushHandler($handler);

foreach ($probes as $probe) {
    $temperature = new Temperature($sensor->createEntity($probe));
    $logger->write((string) $temperature);
}

$logger->close();
