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
use steinmb\onewire\Logger;
use steinmb\onewire\Sensor;

include_once __DIR__ . '/vendor/autoload.php';

define('BREW_ROOT', getcwd());
define('SENSOR_DIRECTORY', '/sys/bus/w1/devices');
//define('SENSOR_DIRECTORY', BREW_ROOT . '/test');
define('LOG_DIRECTORY', BREW_ROOT . '/../../brewlogs/');
define('LOG_FILENAME', 'temperature.log');

$sensorData = [];
$microLAN = new Sensor(SENSOR_DIRECTORY);
$sensors = $microLAN->getSensors();

if (!$sensors) {
    return;
}

if (file_exists(BREW_ROOT . '/' . 'temperatur.png')) {
    $graph = BREW_ROOT . '/' . 'temperatur.png';
}

foreach ($sensors as $sensor) {
    $sensorData[] = new DataEntity($sensor, 'temperature', 2000);
}

$log = new Logger(LOG_FILENAME, LOG_DIRECTORY);
$log->getLogData();
$lastReading = $log->getLastReading();

foreach ($sensorData as $entity) {
    $block = new Block($entity, new Calculate($log), $log);
    $blocks[] = $block->listCurrent();
}

$block->listHistoric(10);

include 'page.php';
