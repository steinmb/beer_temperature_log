<?php

declare(strict_types=1);

use steinmb\Alarm;
use steinmb\BrewSession;
use steinmb\BrewSessionConfig;
use steinmb\Logger\Curl;
use steinmb\Logger\Handlers\FileStorageHandler;
use steinmb\Logger\JsonDecode;
use steinmb\Logger\Logger;
use steinmb\Logger\Handlers\TelegramHandler;
use steinmb\Onewire\OneWire;
use steinmb\RuntimeEnvironment;

include_once __DIR__ . '/vendor/autoload.php';

RuntimeEnvironment::init();
$brewSessionConfig = new BrewSessionConfig(RuntimeEnvironment::getSetting('BATCH'));
$oneWire = new OneWire();
$sensorFactory = New steinmb\Onewire\SensorFactory($oneWire);

$sensors = [];
foreach ($oneWire->allSensors() as $id) {
    $sensors[] = $sensorFactory->createSensor($id);
}

$alarmLogger = new Logger('Alarms');
$alarmLogger->pushHandler(
  new FileStorageHandler(
    'alarms.txt',
    RuntimeEnvironment::getSetting('LOG_DIRECTORY')
  )
);

if (RuntimeEnvironment::getSetting('TELEGRAM_ALARM')) {
    $alarmLogger->pushHandler(
      new TelegramHandler(
        RuntimeEnvironment::getSetting('TELEGRAM_ALARM')['TOKEN'],
        RuntimeEnvironment::getSetting('TELEGRAM_ALARM')['CHANNEL'],
        new JsonDecode(),
        new Curl(),
      )
    );
}

foreach ($sensors as $probe) {
    $alarmStatus = '';
    $brewSession = $brewSessionConfig->sessionIdentity($probe->id);

    if ($brewSession instanceof BrewSession) {
        $alarmStatus = (new Alarm($brewSession))->checkLimits($probe);
    }

    if ($alarmStatus) {
        $alarmLogger->write($alarmStatus);
        $alarmLogger->close();
    }
}
