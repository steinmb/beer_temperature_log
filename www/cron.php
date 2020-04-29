<?php declare(strict_types = 1);

/**
 * @file cron.php
 *
 * Reads and store data from all attached sensors.
 */

use steinmb\EntityFactory;
use steinmb\Environment;
use steinmb\Logger\FileStorage;
use steinmb\Logger\Logger;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Sensor;
use steinmb\Onewire\Temperature;
use steinmb\SystemClock;

include_once __DIR__ . '/vendor/autoload.php';

Environment::setSetting('BREW_ROOT', __DIR__);
$oneWire = new OneWire();
$sensor = new Sensor($oneWire, new SystemClock(), new EntityFactory());
$probes = (!$oneWire->getSensors()) ? exit('No probes found.'): $oneWire->getSensors();
$logger = new Logger('temperature');
$handler = new FileStorage();
$logger->pushHandler($handler);

foreach ($probes as $probe) {
    $temperature = new Temperature($sensor->createEntity($probe));
    $logger->write((string) $temperature);
}

$logger->close();
