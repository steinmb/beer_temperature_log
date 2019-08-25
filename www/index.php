<?php

declare(strict_types=1);

/**
 * @file index.php
 *
 * Create web interface interface.
 */

define('BREW_ROOT', getcwd());
//define('SENSOR_DIRECTORY', '/sys/bus/w1/devices');
define('SENSOR_DIRECTORY', BREW_ROOT . '/test');
define('LOG_DIRECTORY', BREW_ROOT . '/../../brewlogs/');
define('LOG_FILENAME', 'temperature.log');

require_once BREW_ROOT . '/includes/dataSource.php';
require_once BREW_ROOT . '/includes/Sensor.php';
require_once BREW_ROOT . '/includes/DataEntity.php';
require_once BREW_ROOT . '/includes/Block.php';
require_once BREW_ROOT . '/includes/Logger.php';
require_once BREW_ROOT . '/includes/Calculate.php';

$sensorData = [];
$microLAN = new Sensor(SENSOR_DIRECTORY);
$sensors = $microLAN->getSensors();

if (!$sensors) {
    return;
}

foreach ($sensors as $sensor) {
    $sensorData[] = new DataEntity($sensor, 'temperature', 2000);
}

$log = new Logger(LOG_FILENAME, LOG_DIRECTORY);
$log->getLogData();
$lastReading = $log->getLastReading();

foreach ($sensorData as $entity) {
    $block = new Block($entity, new Calculate($log));
    $blocks[] = $block->currentValue();
}

// Calculate trends and so on....
//    $block->renderBlock(10);

include 'page.php';
