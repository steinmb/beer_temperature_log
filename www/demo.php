<?php
declare(strict_types=1);

include_once __DIR__ . '/vendor/autoload.php';

use steinmb\onewire\Sensor;
use steinmb\onewire\SystemClock;
use steinmb\onewire\OneWire;
use steinmb\onewire\Temperature;

$workDir = __DIR__ . '/test';
$oneWire = new OneWire($workDir);

$sensor = new Sensor(
  $oneWire,
  new SystemClock()
);

$probes = $oneWire->getSensors();

foreach ($probes as $probe) {
    $temperature = new Temperature($sensor->createEntity($probe));
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature()}ºC \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('fahrenheit')}ºF \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('kelvin')}ºK \n";
    print $temperature . PHP_EOL;
}
