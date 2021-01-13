<?php declare(strict_types = 1);

/**
 * @file cron.php
 *
 * Reads and store data from all attached sensors.
 */

use steinmb\EntityFactory;
use steinmb\Logger\BrewersFriendHandler;
use steinmb\Logger\JsonDecode;
use steinmb\Logger\TelegramHandler;
use steinmb\RuntimeEnvironment;
use steinmb\Logger\FileStorage;
use steinmb\Logger\Logger;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Sensor;
use steinmb\Onewire\Temperature;
use steinmb\SystemClock;

include_once __DIR__ . '/vendor/autoload.php';

RuntimeEnvironment::init();

$sensor = new Sensor(new OneWire(), new SystemClock(), new EntityFactory());
$probes = (!$sensor->getTemperatureSensors()) ? exit('No probes found.'): $sensor->getTemperatureSensors();
$loggerService = new Logger('temperature');
$fileLogger = new Logger('Files');

if (RuntimeEnvironment::getSetting('BREWERS_FRIEND')) {
    $loggerService->pushHandler(
        new BrewersFriendHandler(
            RuntimeEnvironment::getSetting('BREWERS_FRIEND')['SESSION_ID'],
            RuntimeEnvironment::getSetting('BREWERS_FRIEND')['TOKEN'],
            new JsonDecode()
        )
    );
}

if (RuntimeEnvironment::getSetting('TELEGRAM')) {
    $loggerService->pushHandler(
        new TelegramHandler(
            RuntimeEnvironment::getSetting('TELEGRAM')['TOKEN'],
            RuntimeEnvironment::getSetting('TELEGRAM')['CHANNEL'],
        )
    );
}

foreach ($probes as $probe) {
    $temperature = new Temperature($sensor->createEntity($probe));
    $loggerService->write((string) $temperature);
    $fileLogger->pushHandler(new FileStorage($probe . '.csv'));
    $fileLogger->write((string) $temperature, ['sensor' => $probe]);
}

$loggerService->close();
