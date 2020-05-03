<?php declare(strict_types = 1);

include_once __DIR__ . '/vendor/autoload.php';

use steinmb\EntityFactory;
use steinmb\Environment;
use steinmb\Formatters\Block;
use steinmb\Logger\Logger;
use steinmb\Logger\FileStorage;
use steinmb\Onewire\Sensor;
use steinmb\SystemClock;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Temperature;
use steinmb\Formatters\HTMLFormatter;

Environment::setSetting('BREW_ROOT', __DIR__);
Environment::setSetting('DEMO_MODE', TRUE);
$oneWire = new OneWire();
$sensor = new Sensor($oneWire, new SystemClock(), new EntityFactory());
$probes = (!$oneWire->getTemperatureSensors()) ? exit('No probes found.'): $oneWire->getTemperatureSensors();
$logger = new Logger('Demo');
$handle = new FileStorage();
$logger->pushHandler($handle);
$logger->close();
$blocks = [];

foreach ($probes as $probe) {
    $entity = $sensor->createEntity($probe);
    $temperature = new Temperature($entity);
    $logger->write((string) $temperature);
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
