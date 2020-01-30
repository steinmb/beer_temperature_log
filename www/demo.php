<?php
declare(strict_types=1);

include_once __DIR__ . '/vendor/autoload.php';

use steinmb\onewire\Sensor;
use steinmb\onewire\Temperature;

$oneWire = new Sensor(__DIR__ . '/test');
$sensors = $oneWire->getSensors();

foreach ($sensors as $sensor) {
    $temp = new Temperature($sensor, __DIR__ . '/test');
    print $temp->temperature() . PHP_EOL;
}
