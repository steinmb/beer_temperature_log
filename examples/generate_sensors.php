<?php

declare(strict_types=1);

include_once __DIR__ . '/../vendor/autoload.php';

$oneWire = new steinmb\Onewire\OneWire(__DIR__ . '/../tests/data_all_valid');
$sensorFactory = new steinmb\Onewire\SensorFactory($oneWire);
$sensors = $sensorFactory->allSensors();

foreach ($sensors as $sensor) {
    echo $sensor->id . PHP_EOL;
}
