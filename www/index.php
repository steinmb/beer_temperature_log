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
//$oneWire = new OneWire();
$oneWire = new OneWire(__DIR__ . '/../tests/data_all_valid');
$sensorFactory = New steinmb\Onewire\SensorFactory($oneWire);
$sensors = $sensorFactory->allSensors();
$loggerService = new Logger('temperature');
$clockService = new SystemClock();
$htmlFormatter = new HTMLFormatter();

$trendInterval = 30;
$trendCalculator = new Calculate();
$htmlFormatter = new HTMLFormatter();
$blocks = [];

foreach ($sensors as $sensor) {
    $fileLogger = new FileStorageHandler($sensor->id . '.csv');
    $lastReading = $fileLogger->lastEntry();
    $timestamp = $clockService->currentTime();

    $block = new Block($htmlFormatter, $sensor, $clockService);
    $blocks[] = $block->unorderedList();

    if ($lastReading) {
        $blocks[] = $htmlFormatter->trendList(
            $trendCalculator->calculateTrend(
                $trendInterval,
                $lastReading,
                $fileLogger->lastEntries($trendInterval)
            ),
            $trendInterval,
            $lastReading,
            $sensor->id
        );
    }
    $fileLogger->close();
}

$graphFile = __DIR__ . '/temperature.png';

if (file_exists($graphFile)) {
    $graph = $graphFile;
}
include 'page.php';
