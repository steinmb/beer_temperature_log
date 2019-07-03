<?php

declare(strict_types = 1);

/**
 * @file index.php
 * Create web interface interface.
 */

define('BREW_ROOT', getcwd());
$fileName = BREW_ROOT . '/../../brewlogs/temperature.log';
require_once BREW_ROOT . '/includes/dataSource.php';
require_once BREW_ROOT . '/includes/Sensor.php';
require_once BREW_ROOT . '/includes/DataEntity.php';
require_once BREW_ROOT . '/includes/Block.php';
require_once BREW_ROOT . '/includes/OldSensor.php';
require_once BREW_ROOT . '/includes/Logger.php';
$entities = FALSE;

/**
 * Check for runtime parameters and scan for attached sensors.
 */
if ($argc > 1) {

  if ($argv[1] == '--test') {
    echo 'Running in test mode.' . PHP_EOL;
    $sensors = new Sensor('./test');
    if ($sensors) {
      $entities = $sensors->createEntities();
    }
  }
  else {
    echo 'Invalid argument. Valid arguments: --test' . PHP_EOL;
    exit;
  }
}
else {
  $w1gpio = new OldSensor('/sys/bus/w1/devices');
}

  if ($entities) {
    foreach ($entities as $entity) {
      $log = new Logger($entity->getID());
      $blocks[] = new Block($entity);
    }
    include 'page.php';
  }
