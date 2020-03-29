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
$logger = new Logger('temperature');
$handle = new FileStorage($config);
$logger->pushHandler($handle);
$lastReading = $logger->lastEntry();
$oneWire = new OneWire($config);
$probes = (!$oneWire->getSensors()) ? exit('No probes found.'): $oneWire->getSensors();
$sensor = new Sensor($oneWire, new SystemClock());
$calculate = new Calculate($logger);

foreach ($probes as $probe) {
    $entity = $sensor->createEntity($probe);
    $temperature = new Temperature($entity);
    $formatter = new Block($temperature, new HTMLFormatter());
    $blocks[] = $formatter->unorderedlist();

    if ($lastReading) {
        $trend[] = $calculate->listHistoric(10, $lastReading);
        echo $temperature->entity->id() . ': ' . $calculate->getTrend() . PHP_EOL;
    }
}

if (file_exists($config::getSetting('BREW_ROOT') . '/temperatur.png')) {
    $graph = $config::getSetting('BREW_ROOT') . '/temperatur.png';
}
include 'page.php';

$logger->close();
