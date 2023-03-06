<?php

declare(strict_types=1);

use steinmb\Onewire\OneWire;
use steinmb\Onewire\SensorFactory;
use steinmb\RuntimeEnvironment;

include_once __DIR__ . '/../vendor/autoload.php';

RuntimeEnvironment::init();
$oneWire = new OneWire(__DIR__ . '/../tests/data_all_valid');
$sensorFactory = new SensorFactory($oneWire);
$sensors = $sensorFactory->allSensors();

foreach ($sensors as $sensor) {
    echo $sensor->id . PHP_EOL;
}
