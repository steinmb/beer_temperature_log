<?php

declare(strict_types=1);

/**
 * @file index.php
 *
 * Creates a web interface.
 */

use steinmb\Logger\Logger;
use steinmb\RuntimeEnvironment;
use steinmb\Block;
use steinmb\Formatters\HTMLFormatter;
use steinmb\Utils\Calculate;
use steinmb\Logger\Handlers\FileStorageHandler;
use steinmb\Onewire\OneWire;
use steinmb\SystemClock;

include_once __DIR__ . '/../vendor/autoload.php';

RuntimeEnvironment::init();
$oneWire = new OneWire();
$sensorFactory = New steinmb\Onewire\SensorFactory($oneWire);
$sensors = $sensorFactory->allSensors();
$loggerService = new Logger('temperature');
$clockService = new SystemClock();

$trendInterval = 30;
$trendCalculator = new Calculate();
$htmlFormatter = new HTMLFormatter();
$blocks = [];

foreach ($sensors as $sensor) {
    $fileLogger = new FileStorageHandler($sensor->id . '.csv');
    $lastReading = $fileLogger->lastEntry();
    $timestamp = $clockService->currentTime();

    $block = new Block(new HTMLFormatter());
    $blocks[] = $block->unorderedLists($sensor, $clockService);

    if ($lastReading) {
        $blocks[] = $htmlFormatter->trendList(
            $trendCalculator->calculateTrend(
                $trendInterval,
                $lastReading,
                $fileLogger->lastEntries($trendInterval)
            ),
            $trendInterval,
            $lastReading,
            $sensor
        );
    }
    $fileLogger->close();
}

if (file_exists(RuntimeEnvironment::getSetting('BREW_ROOT') . '/temperatur.png')) {
    $graph = RuntimeEnvironment::getSetting('BREW_ROOT') . '/temperatur.png';
}
include 'page.php';
