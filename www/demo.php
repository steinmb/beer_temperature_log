<?php
declare(strict_types=1);

include_once __DIR__ . '/vendor/autoload.php';

use steinmb\onewire\Sensor;
use steinmb\onewire\Temperature;

$oneWire = new Sensor(__DIR__ . '/test');
$sensors = $oneWire->getSensors();

if (!$sensors) {
    exit('No sensors found');
}

foreach ($sensors as $sensor) {
    $temp = new Temperature($sensor, __DIR__ . '/test');
    print "{$temp->id()} {$temp->temperature()} \n";
}
