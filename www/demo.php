<?php declare(strict_types = 1);

include_once __DIR__ . '/vendor/autoload.php';

use steinmb\EntityFactory;
use steinmb\RuntimeEnvironment;
use steinmb\Formatters\Block;
use steinmb\Logger\Logger;
use steinmb\Logger\FileStorageHandler;
use steinmb\Onewire\Sensor;
use steinmb\SystemClock;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Temperature;
use steinmb\Formatters\HTMLFormatter;
use steinmb\Utils\Calculate;

RuntimeEnvironment::init();
RuntimeEnvironment::setSetting('SENSOR_DIRECTORY', __DIR__ . '/tests/test_data');
RuntimeEnvironment::setSetting('SENSORS', __DIR__ . '/tests/test_data/w1_master_slaves');

$sensor = new Sensor(new OneWire(), new SystemClock(), new EntityFactory());
$probes = (!$sensor->getTemperatureSensors()) ? exit('No probes found.'): $sensor->getTemperatureSensors();
$loggerService = new Logger('Demo');
$results = [];
$trendCalculator = new Calculate();
$lastReading = '2020-07-07 21:11:46, 28-0000098101de, 15.687';

foreach ($probes as $probe) {
    $entity = $sensor->createEntity($probe);
    $temperature = new Temperature($entity);
    $fileLogger = $loggerService->pushHandler(new FileStorageHandler($probe . '.csv'));
    $fileLogger->write((string) $temperature);
    $block = new Block(new HTMLFormatter($entity));
    $results[] = $block->unorderedLists($temperature);
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature()}ºC \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('fahrenheit')}ºF \n";
    print "Date: {$temperature->entity->timeStamp()} Id: {$temperature->entity->id()} {$temperature->temperature('kelvin')}ºK \n";
    print $temperature . PHP_EOL;
    print $block->trendList(
        $trendCalculator->calculateTrend(
            15,
            $lastReading,
            $fileLogger->lastEntries(15)
        ),
        10,
        $lastReading
    );
    $fileLogger->close();
}

foreach ($results as $result) {
    print $result . PHP_EOL;
}
