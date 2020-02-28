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
//define('SENSOR_DIRECTORY', '/sys/bus/w1/devices');
define('SENSOR_DIRECTORY', __DIR__ . '/test');

$oneWire = new OneWire(SENSOR_DIRECTORY);
$sensor = new Sensor(
  $oneWire,
  new SystemClock()
);

$probes = $oneWire->getSensors();

If (!$probes) {
    exit;
}

$log = new FileLogger(new FileStorage(LOG_DIRECTORY, LOG_FILENAME));
$fileHandle = $log->file->write();
$content = '';

foreach ($probes as $probe) {
    $entity = $sensor->createEntity($probe);
    $temperature = new Temperature($entity);
    $content .= "{$entity->timeStamp()}, {$entity->id()}, {$temperature->temperature()}\r\n";
}

$log->write($fileHandle, $content);
fclose($fileHandle);
