<?php
declare(strict_types = 1);

/**
 * @file cron.php
 *
 * Cron that needs to be run periodic from crond.
 */

define('BREW_ROOT', getcwd());
define('SENSOR_DIRECTORY', '/sys/bus/w1/devices');

require_once BREW_ROOT . '/includes/OldSensor.php';
require_once BREW_ROOT . '/includes/Logger.php';
require_once BREW_ROOT . '/includes/TestRunner.php';
require_once BREW_ROOT . '/test/SensorTest.php';

if ($argc === 2) {
  testRunner($argv[1]);
}

$logString = false;
$log = '';
$w1gpio = new OldSensor(SENSOR_DIRECTORY);
$sensors = $w1gpio->getSensors();

if (!$sensors) {
    echo 'No sensors detected. Giving up.' . PHP_EOL;
    exit;
}

$logString = $w1gpio->getData($sensors);

if ($logString) {
    $log = new Logger('temperature.log');
    $log->setLogDirectory(BREW_ROOT . '/../../brewlogs/');
    $log->setLogfile('temperature.log');
    $log->writeLogFile($logString);
}
