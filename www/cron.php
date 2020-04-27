<?php declare(strict_types = 1);

/**
 * @file cron.php
 *
 * Reads and store data from all attached sensors.
 */

use steinmb\Environment;
use steinmb\Logger\FileStorage;
use steinmb\Logger\Logger;
use steinmb\onewire\OneWire;
use steinmb\onewire\Sensor;
use steinmb\SystemClock;
use steinmb\Onewire\Temperature;

include_once __DIR__ . '/vendor/autoload.php';

$config = new Environment(__DIR__);
$oneWire = new OneWire($config);
$sensor = new Sensor($oneWire, new SystemClock());
$probes = (!$oneWire->getSensors()) ? exit('No probes found.'): $oneWire->getSensors();
$logger = new Logger('temperature');
$handler = new FileStorage($config);
$logger->pushHandler($handler);

foreach ($probes as $probe) {
    $temperature = new Temperature($sensor->createEntity($probe));
    $logger->write((string) $temperature);
}

$logger->close();
