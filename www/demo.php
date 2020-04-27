<?php declare(strict_types = 1);

include_once __DIR__ . '/vendor/autoload.php';

use steinmb\Environment;
use steinmb\Formatters\Block;
use steinmb\Logger\Logger;
use steinmb\Logger\FileStorage;
use steinmb\Onewire\Sensor;
use steinmb\SystemClock;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Temperature;
use steinmb\Formatters\HTMLFormatter;

$config = new Environment(__DIR__);
$config::setSetting('DEMO_MODE', TRUE);
$oneWire = new OneWire($config);
$sensor = new Sensor($oneWire, new SystemClock());
$probes = (!$oneWire->getSensors()) ? exit('No probes found.'): $oneWire->getSensors();
$logger = new Logger('Demo');
$handle = new FileStorage($config);
$logger->pushHandler($handle);
$logger->close();
$blocks = [];

foreach ($probes as $probe) {
    $entity = $sensor->createEntity($probe);
    $temperature = new Temperature($entity);
    $formatter = new Block($temperature, new HTMLFormatter($entity));
    $blocks[] = $formatter->unorderedlist();
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature()}ºC \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('fahrenheit')}ºF \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('kelvin')}ºK \n";
    print $temperature . PHP_EOL;
}

foreach ($blocks as $block) {
    print $block . PHP_EOL;
}
