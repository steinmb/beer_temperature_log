<?php declare(strict_types=1);

/**
 * @file cron.php
 *
 * Reads and store data from all attached sensors.
 */

use steinmb\Alarm;
use steinmb\BrewSession;
use steinmb\BrewSessionConfig;
use steinmb\EntityFactory;
use steinmb\Logger\BrewersFriendHandler;
use steinmb\Logger\JsonDecode;
use steinmb\Logger\TelegramHandler;
use steinmb\RuntimeEnvironment;
use steinmb\Logger\FileStorageHandler;
use steinmb\Logger\Logger;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Sensor;
use steinmb\Onewire\Temperature;
use steinmb\SystemClock;

include_once __DIR__ . '/vendor/autoload.php';

RuntimeEnvironment::init();
$batches = RuntimeEnvironment::getSetting('BATCH');
$brewSessionConfig = new BrewSessionConfig($batches);
$sensor = new Sensor(new OneWire(), new SystemClock(), new EntityFactory());
$probes = (!$sensor->getTemperatureSensors()) ? exit('No probes found.'): $sensor->getTemperatureSensors();
$loggerService = new Logger('temperature');
$fileLogger = new Logger('Files');
$alarmLogger = new Logger('Alarms');

if (RuntimeEnvironment::getSetting('TELEGRAM_ALARM')) {
    $alarmLogger->pushHandler(
        new TelegramHandler(
            RuntimeEnvironment::getSetting('TELEGRAM_ALARM')['TOKEN'],
            RuntimeEnvironment::getSetting('TELEGRAM_ALARM')['CHANNEL'],
        )
    );
}

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
    $brewSession = $brewSessionConfig->sessionIdentity($probe);
    $alarmStatus = '';

    if ($brewSession instanceof BrewSession) {
        $brewTemperature = new Temperature($sensor->createEntity($brewSession->probe));
        $alarm = new Alarm($brewSession);
        $alarmStatus = $alarm->checkLimits($brewTemperature);
        $fileLogger = new Logger('Files');
        $context = [
            'brewSession' => $brewSession,
            'sensor' => $sensor,
            'temperature' => $brewTemperature,
            'ambient' => new Temperature($sensor->createEntity($brewSession->ambient)),
        ];
        $loggerService->write('', $context);
        $fileLogger->pushHandler(new FileStorageHandler(
            $probe . '.csv',
            RuntimeEnvironment::getSetting('LOG_DIRECTORY') . '/' . $brewSession->sessionId
        ));
        $fileLogger->write('', $context);
    }

    if ($alarmStatus) {
        $alarmLogger->write($alarmStatus);
    }
}

//$loggerService->close();
