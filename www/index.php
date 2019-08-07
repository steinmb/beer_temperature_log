<?php
declare(strict_types=1);

/**
 * @file index.php
 *
 * Create web interface interface.
 */

define('BREW_ROOT', getcwd());
//define('SENSOR_DIRECTORY', '/sys/bus/w1/devices');
define('SENSOR_DIRECTORY', BREW_ROOT . '/test');
define('LOG_DIRECTORY', BREW_ROOT . '/../../brewlogs/');
define('LOG_FILENAME', 'temperature.log');

require_once BREW_ROOT . '/includes/dataSource.php';
require_once BREW_ROOT . '/includes/Sensor.php';
require_once BREW_ROOT . '/includes/DataEntity.php';
require_once BREW_ROOT . '/includes/Block.php';
require_once BREW_ROOT . '/includes/OldSensor.php';
require_once BREW_ROOT . '/includes/Logger.php';

$entities = false;
$sensor = new Sensor(SENSOR_DIRECTORY);
$sensors = $sensor->getSensors();

if ($sensors) {
    $entities = $sensor->createEntities($sensors);
}

if ($entities) {
    foreach ($entities as $entity) {
        $log = new Logger($entity->getID());
        $blocks[] = new Block($entity, new Calculate($log->getLastReading()));
    }
    include 'page.php';
}
