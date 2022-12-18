<?php

declare(strict_types=1);

/**
 * @file test.php
 *   Tests OneWire basic functions. Scan for sensors and get sensor data.
 */

use steinmb\Onewire\OneWire;
use steinmb\Onewire\SensorFactory;

include_once __DIR__ . '/../vendor/autoload.php';

$oneWire = new OneWire(__DIR__ . '/../tests/data_all_valid');
$sensorFactory = new SensorFactory($oneWire);
$ids = $oneWire->allSensors();

foreach ($ids as $id) {
    $sensor = $sensorFactory->createSensor($id);
    echo $sensor->sensorValue() . PHP_EOL;
    echo $sensor->temperature('celsius', -1) . PHP_EOL;
}
