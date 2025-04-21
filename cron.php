<?php

declare(strict_types=1);

/**
 * Reads and store data from all attached sensors.
 */

use steinmb\BrewSession;
use steinmb\BrewSessionConfig;
use steinmb\Logger\Handlers\BrewersFriendHandler;
use steinmb\Logger\Curl;
use steinmb\Logger\JsonDecode;
use steinmb\Logger\Handlers\TelegramHandler;
use steinmb\Onewire\SensorFactory;
use steinmb\RuntimeEnvironment;
use steinmb\Logger\Handlers\FileStorageHandler;
use steinmb\Logger\Logger;
use steinmb\Onewire\OneWire;
use steinmb\SystemClock;

include_once __DIR__ . '/vendor/autoload.php';

RuntimeEnvironment::init();
$oneWire = new OneWire();
$sensorFactory = new SensorFactory($oneWire);
$brewSessionConfig = new BrewSessionConfig(RuntimeEnvironment::getSetting('BATCH'));
$sensors = $sensorFactory->allSensors();
$loggerService = new Logger('temperature');
$fileLogger = new Logger('Files');
$clockService = new SystemClock();

if (RuntimeEnvironment::getSetting('BREWERS_FRIEND')) {
    $loggerService->pushHandler(
        new BrewersFriendHandler(
            RuntimeEnvironment::getSetting('BREWERS_FRIEND')['SESSION_ID'],
            RuntimeEnvironment::getSetting('BREWERS_FRIEND')['TOKEN'],
            new JsonDecode(),
            new Curl()
        )
    );
}

if (RuntimeEnvironment::getSetting('TELEGRAM')) {
    $loggerService->pushHandler(
        new TelegramHandler(
            RuntimeEnvironment::getSetting('TELEGRAM')['TOKEN'],
            RuntimeEnvironment::getSetting('TELEGRAM')['CHANNEL'],
            new JsonDecode(),
            new Curl()
        )
    );
}

/**
 * Create a logfile pr. sensor with ambient sensor temperature.
 * Write logs to file, Telegram, Brewers Friend etc.
 */
foreach ($sensors as $probe) {
    $brewSession = $brewSessionConfig->sessionIdentity($probe);

    if ($brewSession instanceof BrewSession) {
        $ambientId = $brewSession->ambient;
        $ambientSensor = '';

        foreach ($sensors as $sensor) {
            if ($sensor->id === $ambientId) {
                $ambientSensor = $sensor;
            }
        }

        if (!$ambientSensor) {
            throw new RuntimeException(
                'Ambient sensor: ' . $brewSession->ambient . ' in session: ' . $brewSession->sessionId . ' not found');
        }

        $context = [
            'brewSession' => $brewSession,
            'sensor' => $probe,
            'ambient_sensor' => $ambientSensor,
            'ambient' => $ambientSensor->temperature(),
            'temperature' => $probe->temperature(),
            'clock' => $clockService->currentTime(),
        ];
        $loggerService->write('', $context);

        $fileLogger = new Logger('Files');
        $fileLogger->pushHandler(new FileStorageHandler(
            $probe->id . '.csv',
            RuntimeEnvironment::getSetting('LOG_DIRECTORY') . '/' . $brewSession->sessionId
        ));
        $fileLogger->write('', $context);
    }
}

//$loggerService->close();
