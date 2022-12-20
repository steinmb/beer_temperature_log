<?php

declare(strict_types=1);

use steinmb\EntityFactory;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Sensor;
use steinmb\RuntimeEnvironment;
use steinmb\SystemClock;

include_once __DIR__ . '/../vendor/autoload.php';

RuntimeEnvironment::setSetting('BREW_ROOT', __DIR__);
$oneWire = new OneWire(__DIR__ . '/../tests/data_all_valid');

$sensor = new Sensor($oneWire, new SystemClock(), new EntityFactory());
$probes = (!$sensor->getTemperatureSensors()) ? exit('No probes found.') : $sensor->getTemperatureSensors();
