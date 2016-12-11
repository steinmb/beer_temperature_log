<?php
/**
 * @file cron.php
 *
 * Cron that needs to be run periodic from crond.
 */
define('BREW_ROOT', getcwd());
require_once BREW_ROOT . '/includes/OldSensor.php';
$w1gpio = '';
$logString = FALSE;

/**
 * Check for runtime parameters and scan for attached sensors.
 */
if ($argc > 1) {

  if ($argv[1] == '--test') {
    echo 'Running in test mode.' . PHP_EOL;
    $w1gpio = new OldSensor('./test');
  }
  else {
    echo 'Invalid argument. Valid arguments: --test' . PHP_EOL;
    exit;
  }
}
else {
  $w1gpio = new OldSensor('/sys/bus/w1/devices');
}

$sensors = $w1gpio->getSensors();
if (!$sensors) {
  echo 'No sensors detected. Giving up.' . PHP_EOL;
  exit;
}

if ($sensors) {
  $streams = $w1gpio->getStreams($sensors);
  $logString = $w1gpio->readSensors($streams);
}

if ($logString) {
  $w1gpio->writeLogFile($logString);
}

$w1gpio->closeStreams($streams);
