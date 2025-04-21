<?php

declare(strict_types=1);

use steinmb\Logger\Logger;
use steinmb\Utils\Calculate;

include_once __DIR__ . '/../vendor/autoload.php';

$lastMeasurement = '2016-01-23 21:00:35, 21, 20';
$lastEntries = <<<LOG
    2016-01-23 19:00:35, 17, 19
    2016-01-23 19:10:35, 17, 19
    2016-01-23 19:20:35, 17, 19
    2016-01-23 19:30:35, 17, 19
    2016-01-23 19:40:35, 17, 18
    2016-01-23 19:50:35, 17, 20
    2016-01-23 20:00:35, 16, 20
    2016-01-23 20:10:35, 16, 20
    2016-01-23 20:20:35, 17, 20
    2016-01-23 20:30:35, 18, 20
    2016-01-23 20:40:35, 18, 20
    2016-01-23 20:50:35, 19, 20
    2016-01-23 21:00:35, 19, 20
    LOG;

$calculator = new Calculate();
$loggerService = new Logger('Demo');
$trend = $calculator->calculateTrend(40, $lastMeasurement, $loggerService->toArray($lastEntries));
echo $trend->getTrend() . PHP_EOL;
