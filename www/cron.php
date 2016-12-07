<?php
/**
 * @file cron.php
 *
 * Cron that needs to be run periodic from crond.
 */
define('BREW_ROOT', getcwd());
require_once BREW_ROOT . '/includes/OldSensor.php';

/**
 * Read data to logfile.
 */
$w1gpio = new OldSensor('/sys/bus/w1/devices');

$sensors = $w1gpio->getSensors();
if (!$sensors) {
  exit;
}

$logString = FALSE;

if ($sensors) {
  $streams = $w1gpio->getStreams($sensors);
  $logString = $w1gpio->readSensors($streams);
}

if ($logString) {
  writeLogFile($logString);
}

$w1gpio->closeStreams($streams);
