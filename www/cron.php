<?php
declare(strict_types = 1);

/**
 * @file cron.php
 *
 * Periodic read data from all attached sensors and store them to a log.
 */

define('BREW_ROOT', getcwd());
define('LOG_DIRECTORY', BREW_ROOT . '/../../brewlogs/');
define('LOG_FILENAME', 'temperature.log');
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
    $log->setLogDirectory(LOG_DIRECTORY);
    $log->setLogfile(LOG_FILENAME);
    $log->writeLogFile($logString);
}
