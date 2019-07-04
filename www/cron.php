<?php

declare(strict_types = 1);

/**
 * @file cron.php
 *
 * Cron that needs to be run periodic from crond.
 */

define('BREW_ROOT', getcwd());
require_once BREW_ROOT . '/includes/OldSensor.php';
require_once BREW_ROOT . '/includes/Logger.php';
require_once BREW_ROOT . '/test/SensorTest.php';

$w1gpio = '';
$logString = false;
$log = '';

if ($argc === 2) {

    $test = new SensorTest(
      $argv[1],
      new OldSensor('./test'),
      new Logger('temperature_test.log')
    );

    if (!$test->testActivated) {
        exit;
    }

    $test->logData();
    exit;
}

$w1gpio = new OldSensor('/sys/bus/w1/devices');
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
