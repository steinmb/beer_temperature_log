<?php
/**
 * @file cron.php
 *
 * Cron that needs to be run periodic from crond.
 */


/**
 * Read data to logfile.
 */
$w1gpio = new OldSensor();
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
