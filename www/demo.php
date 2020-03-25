<?php
declare(strict_types=1);

include_once __DIR__ . '/vendor/autoload.php';

use steinmb\Logger\FileLogger;
use steinmb\Logger\FileStorage;
use steinmb\onewire\Sensor;
use steinmb\onewire\SystemClock;
use steinmb\onewire\OneWire;
use steinmb\onewire\Temperature;

define('BREW_ROOT', getcwd());

$oneWire = new OneWire(__DIR__ . '/test');
$sensor = new Sensor($oneWire, new SystemClock());
$probes = $oneWire->getSensors();

define('LOG_DIRECTORY', BREW_ROOT . '/../../brewlogs/');
define('LOG_FILENAME', 'temperature.log');
$log = new FileLogger(new FileStorage(
  LOG_DIRECTORY,
  LOG_FILENAME));

foreach ($probes as $probe) {
    $temperature = new Temperature($sensor->createEntity($probe));
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature()}ºC \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('fahrenheit')}ºF \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('kelvin')}ºK \n";
    print $temperature . PHP_EOL;
}
