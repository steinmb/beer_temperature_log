<?php declare(strict_types = 1);

include_once __DIR__ . '/../vendor/autoload.php';

use steinmb\Alarm;
use steinmb\BrewSession;
use steinmb\BrewSessionConfig;
use steinmb\EntityFactory;
use steinmb\RuntimeEnvironment;
use steinmb\Logger\Logger;
use steinmb\Logger\FileStorageHandler;
use steinmb\Onewire\Sensor;
use steinmb\SystemClock;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Temperature;
use steinmb\Utils\Calculate;

RuntimeEnvironment::init();
RuntimeEnvironment::setSetting('SENSOR_DIRECTORY', __DIR__ . '/../tests/test_data');
RuntimeEnvironment::setSetting('SENSORS', __DIR__ . '/../tests/test_data/w1_master_slaves');

$sensor = new Sensor(new OneWire(), new SystemClock(), new EntityFactory());
$probes = (!$sensor->getTemperatureSensors()) ? exit('No probes found.'): $sensor->getTemperatureSensors();
$trendCalculator = new Calculate();
$brewSessionConfig = new BrewSessionConfig(RuntimeEnvironment::getSetting('BATCH'));

foreach ($probes as $probe) {
    $loggerService = new Logger('Demo');
    $loggerService->pushHandler(new FileStorageHandler($probe . '.csv'));
    $entity = $sensor->createEntity($probe);
    $temperature = new Temperature($entity);
    $trend = $trendCalculator->calculateTrend(
        15,
        $loggerService->lastEntry(),
        $loggerService->lastEntries(15)
    );
    $brewSession = $brewSessionConfig->sessionIdentity($probe);

    $alarmStatus = '';
    if ($brewSession instanceof BrewSession) {
        $alarm = new Alarm($brewSession);
        $alarmStatus = $alarm->checkLimits($temperature);
    }

    if ($alarmStatus) {
        echo $alarmStatus . PHP_EOL;
    }

    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature()}ºC \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('fahrenheit')}ºF \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('kelvin')}ºK \n";
    print $temperature . PHP_EOL;
    print 'Trend: ' . $trend . PHP_EOL;
    $loggerService->write((string) $temperature);
    $loggerService->close();
}
