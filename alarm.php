<?php

declare(strict_types=1);

use steinmb\Alarm;
use steinmb\BrewSession;
use steinmb\BrewSessionConfig;
use steinmb\EntityFactory;
use steinmb\Logger\Curl;
use steinmb\Logger\Handlers\FileStorageHandler;
use steinmb\Logger\JsonDecode;
use steinmb\Logger\Logger;
use steinmb\Logger\Handlers\TelegramHandler;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Sensor;
use steinmb\Onewire\Temperature;
use steinmb\RuntimeEnvironment;
use steinmb\SystemClock;

include_once __DIR__ . '/vendor/autoload.php';

RuntimeEnvironment::init();
$brewSessionConfig = new BrewSessionConfig(RuntimeEnvironment::getSetting('BATCH'));

$sensor = new Sensor(
  new OneWire(__DIR__ . '/tests/data_all_valid'),
  new SystemClock(),
  new EntityFactory()
);

$probes = (!$sensor->getTemperatureSensors()) ? exit('No probes found.') : $sensor->getTemperatureSensors();

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

foreach ($probes as $probe) {
    $brewSession = $brewSessionConfig->sessionIdentity($probe);
    $alarmStatus = '';

    if ($brewSession instanceof BrewSession) {
        $brewTemperature = new Temperature($sensor->createEntity($brewSession->probe));
        $alarm = new Alarm($brewSession);
        $alarmStatus = $alarm->checkLimits($brewTemperature);
    }

    if ($alarmStatus) {
        $alarmLogger->write($alarmStatus);
        $alarmLogger->close();
    }
}
