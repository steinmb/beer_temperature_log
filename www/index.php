<?php
declare(strict_types=1);

/**
 * @file index.php
 *
 * Create web interface interface.
 */

use steinmb\onewire\Block;
use steinmb\onewire\Calculate;
use steinmb\onewire\DataEntity;
use steinmb\onewire\FileLogger;
use steinmb\onewire\OneWire;
use steinmb\onewire\Sensor;
use steinmb\onewire\SystemClock;
use steinmb\onewire\Temperature;

include_once __DIR__ . '/vendor/autoload.php';

define('BREW_ROOT', getcwd());
//define('SENSOR_DIRECTORY', '/sys/bus/w1/devices');
define('SENSOR_DIRECTORY', BREW_ROOT . '/test');
define('LOG_DIRECTORY', BREW_ROOT . '/../../brewlogs/');
define('LOG_FILENAME', 'temperature.log');

if (file_exists(BREW_ROOT . '/' . 'temperatur.png')) {
    $graph = BREW_ROOT . '/' . 'temperatur.png';
}

$sensorData = [];
$microLAN = new OneWire(SENSOR_DIRECTORY);
$probes = $microLAN->getSensors();

if (!$probes) {
    return;
}

$sensor = new Sensor(
  $microLAN,
  new SystemClock()
);

foreach ($probes as $probe) {
    $entity = $sensor->createEntity($probe);
    $temperature = new Temperature($entity);
    $block = new Block($entity, $temperature);
    $blocks[] = $block->listCurrent();
}

$log = new FileLogger(LOG_FILENAME, LOG_DIRECTORY);
$log->getLogData();
$lastReading = $log->getLastReading();
$block->listHistoric(10, new Calculate($log), $log);

include 'page.php';
