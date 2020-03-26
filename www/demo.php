<?php
declare(strict_types=1);

include_once __DIR__ . '/vendor/autoload.php';

use steinmb\Formatters\Block;
use steinmb\Logger\Logger;
use steinmb\Logger\FileStorage;
use steinmb\onewire\Sensor;
use steinmb\onewire\SystemClock;
use steinmb\onewire\OneWire;
use steinmb\onewire\Temperature;
use steinmb\steinmb\Formatters\HTMLFormatter;

$oneWire = new OneWire(__DIR__ . '/test');
$sensor = new Sensor($oneWire, new SystemClock());
$probes = $oneWire->getSensors();

$logger = new Logger('Demo');
$handle = new FileStorage('/Users/steinmb/sites/brewlogs/temperature.log');
$logger->pushHandler($handle);
$logger->close();
$blocks = [];

foreach ($probes as $probe) {
    $temperature = new Temperature($sensor->createEntity($probe));
    $formatter = new Block($temperature, new HTMLFormatter());
    $blocks[] = $formatter->unorderedlist();
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature()}ºC \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('fahrenheit')}ºF \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('kelvin')}ºK \n";
    print $temperature . PHP_EOL;
}

foreach ($blocks as $block) {
    print $block . PHP_EOL;
}
