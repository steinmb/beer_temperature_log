<?php
declare(strict_types=1);

/**
 * @file index.php
 *
 * Create web interface interface.
 */

use steinmb\Environment;
use steinmb\Formatters\Block;
use steinmb\Formatters\HTMLFormatter;
use steinmb\Utils\Calculate;
use steinmb\Logger\Logger;
use steinmb\Logger\FileStorage;
use steinmb\onewire\OneWire;
use steinmb\onewire\Sensor;
use steinmb\SystemClock;
use steinmb\onewire\Temperature;

include_once __DIR__ . '/vendor/autoload.php';

$config = new Environment(__DIR__);

if (file_exists($config::getSetting('BREW_ROOT') . '/temperatur.png')) {
    $graph = $config::getSetting('BREW_ROOT') . '/temperatur.png';
}

$logger = new Logger('temperature');
$handle = new FileStorage($config);
$logger->pushHandler($handle);
$oneWire = new OneWire($config);
$probes = (!$oneWire->getSensors()) ? exit('No probes found.'): $oneWire->getSensors();
$sensor = new Sensor($oneWire, new SystemClock());

foreach ($probes as $probe) {
    $entity = $sensor->createEntity($probe);
    $temperature = new Temperature($entity);
    $formatter = new Block($temperature, new HTMLFormatter());
    $blocks[] = $formatter->unorderedlist();
}

//$lastReading = $logger->lastEntry();
//if ($lastReading) {
//    $formatter->listHistoric(10, $lastReading, new Calculate($logger), $logger);
//}

include 'page.php';
$logger->close();
