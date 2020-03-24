<?php
declare(strict_types = 1);

/**
 * @file cron.php
 *
 * Periodic read data from all attached sensors and store them to a log.
 */

use steinmb\onewire\FileLogger;
use steinmb\onewire\FileStorage;
use steinmb\onewire\OneWire;
use steinmb\onewire\Sensor;
use steinmb\onewire\SystemClock;
use steinmb\onewire\Temperature;

include_once __DIR__ . '/vendor/autoload.php';

define('BREW_ROOT', getcwd());
define('LOG_DIRECTORY', BREW_ROOT . '/../../brewlogs/');
define('LOG_FILENAME', 'temperature.log');
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

$log = new FileLogger(new FileStorage(
  LOG_DIRECTORY,
  LOG_FILENAME));
$message = '';

foreach ($probes as $probe) {
    $temperature = new Temperature($sensor->createEntity($probe));
    $message .= $temperature . PHP_EOL;
    $log->write($message);
}

$log->close();
