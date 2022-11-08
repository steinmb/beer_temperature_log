<?php declare(strict_types=1);

use steinmb\BrewSessionConfig;
use steinmb\EntityFactory;
use steinmb\Onewire\OneWire;
use steinmb\Onewire\Sensor;
use steinmb\Onewire\Temperature;
use steinmb\RuntimeEnvironment;
use steinmb\SystemClock;

include_once __DIR__ . '/../vendor/autoload.php';

RuntimeEnvironment::init();
$batches = RuntimeEnvironment::getSetting('BATCH');
$brewSessionConfig = new BrewSessionConfig($batches);
$sensor = new Sensor(new OneWire(), new SystemClock(), new EntityFactory());
$probes = (!$sensor->getTemperatureSensors()) ? exit('No probes found.'): $sensor->getTemperatureSensors();

print_r($probes);
foreach ($probes as $probe) {
	$brewTemperature = new Temperature($sensor->createEntity($probe));
	var_dump($brewTemperature );
	echo $brewTemperature . PHP_EOL;
}
