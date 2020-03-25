<?php
declare(strict_types = 1);

/**
 * @file cron.php
 *
 * Reads and store data from all attached sensors.
 */

use steinmb\Logger\FileStorage;
use steinmb\onewire\OneWire;
use steinmb\onewire\Sensor;
use steinmb\onewire\SystemClock;
use steinmb\onewire\Temperature;

include_once __DIR__ . '/vendor/autoload.php';

//define('SENSOR_DIRECTORY', __DIR__ . '/test');
define('SENSOR_DIRECTORY', '/sys/bus/w1/devices');

$oneWire = new OneWire(SENSOR_DIRECTORY);
$sensor = new Sensor(
  $oneWire,
  new SystemClock()
);

$probes = $oneWire->getSensors();
If (!$probes) {
    exit("No probes found \n");
}

$logger = new steinmb\Logger\Logger('temperature');
$handler = new FileStorage(getcwd() . '/../../brewlogs/temperature.log');
$logger->pushHandler($handler);
$message = '';

foreach ($probes as $probe) {
    $temperature = new Temperature($sensor->createEntity($probe));
    $logger->write((string) $temperature);
}

$logger->close();
