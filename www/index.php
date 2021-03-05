<?php declare(strict_types=1);

/**
 * @file index.php
 *
 * Create web interface interface.
 */

use steinmb\EntityFactory;
use steinmb\Logger\Logger;
use steinmb\RuntimeEnvironment;
use steinmb\Formatters\Block;
use steinmb\Formatters\HTMLFormatter;
use steinmb\Utils\Calculate;
use steinmb\Logger\FileStorageHandler;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Sensor;
use steinmb\SystemClock;
use steinmb\Onewire\Temperature;

include_once __DIR__ . '/vendor/autoload.php';

RuntimeEnvironment::setSetting('BREW_ROOT', __DIR__);
$loggerService = new Logger('temperature');
$sensor = new Sensor(new OneWire, new SystemClock(), new EntityFactory());
$probes = (!$sensor->getTemperatureSensors()) ? exit('No probes found.'): $sensor->getTemperatureSensors();
$trendInterval = 30;

foreach ($probes as $probe) {
    $fileLogger = new FileStorageHandler($probe . '.csv');
    $lastReading = $fileLogger->lastEntry();
    $entity = $sensor->createEntity($probe);
    $formatter = new Block(new HTMLFormatter());
    $blocks[] = $formatter->unorderedLists(new Temperature($entity), $entity);

    if ($lastReading) {
        $trendCalculator = new Calculate();
        $blocks[] = $formatter->trendList(
            $trendCalculator->calculateTrend(
                $trendInterval,
                $lastReading,
                $fileLogger->lastEntries($trendInterval)
            ),
            $trendInterval,
            $lastReading,
            $entity
        );
    }
    $fileLogger->close();
}

if (file_exists(RuntimeEnvironment::getSetting('BREW_ROOT') . '/temperatur.png')) {
    $graph = RuntimeEnvironment::getSetting('BREW_ROOT') . '/temperatur.png';
}
include 'page.php';
