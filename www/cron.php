<?php

declare(strict_types=1);

/**
 * @file cron.php
 *
 * Cron that needs to be run periodic from crond.
 */
define('BREW_ROOT', getcwd());
require_once BREW_ROOT . '/includes/OldSensor.php';
require_once BREW_ROOT . '/includes/Logger.php';
$w1gpio = '';
$logString = false;
$log = '';

/**
 * Check for runtime parameters and scan for attached sensors.
 */
if ($argc > 1) {

    if ($argv[1] === '--test') {
        echo 'Running in test mode.' . PHP_EOL;
        $w1gpio = new OldSensor('./test');
        $log = new Logger('temperature_test.log');
        $log->setLogDirectory(BREW_ROOT . '/test/');
        $sensors = $w1gpio->getSensors();
    } else {
        echo 'Invalid argument. Valid arguments: --test' . PHP_EOL;
        exit;
    }

    if (count($sensors) !== 4) {
        throw new Exception('Missing sensors. Expected 4, only got:' . count($sensors));
    }
} else {
    $w1gpio = new OldSensor('/sys/bus/w1/devices');
}

$sensors = $w1gpio->getSensors();
if (!$sensors) {
    echo 'No sensors detected. Giving up.' . PHP_EOL;
    exit;
}

$logString = $w1gpio->getData($sensors);

if ($logString) {
    if (!$log) {
        $log = new Logger('temperature.log');
        $log->setLogDirectory(BREW_ROOT . '/../../brewlogs/');
        $log->setLogfile('temperature.log');
    }
    $log->writeLogFile($logString);
}
