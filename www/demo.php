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

$entity = $sensor->createEntity('10-000802a4ef03');
$probes = $oneWire->getSensors();

foreach ($probes as $probe) {
    $entity = $sensor->createEntity($probe);
    $temperature = new Temperature($entity);
    print "Date: {$entity->timeStamp()} Id: {$entity->id()} {$temperature->temperature()}ºC \n";
}
