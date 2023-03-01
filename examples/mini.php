<?php

declare(strict_types=1);

include_once __DIR__ . '/../vendor/autoload.php';

$oneWireService = new steinmb\Onewire\OneWire(
  __DIR__ . '/../tests/data_all_valid',
);
$sensorFactory = New steinmb\Onewire\SensorFactory($oneWireService);
$sensors = [];

foreach ($oneWireService->allSensors() as $id) {
    $sensors[] = $sensorFactory->createSensor($id);
}

foreach ($sensors as $sensor) {
    echo $sensor->sensorValue() . PHP_EOL;
}
