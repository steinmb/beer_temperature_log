<?php
/**
 * Create web interface interface.
 */

define('BREW_ROOT', getcwd());
$fileName = BREW_ROOT . '/../../brewlogs/temperature.log';
require_once BREW_ROOT . '/includes/dataSource.php';
require_once BREW_ROOT . '/includes/Sensor.php';
require_once BREW_ROOT . '/includes/DataEntity.php';
require_once BREW_ROOT . '/includes/Block.php';
require_once BREW_ROOT . '/includes/OldSensor.php';

if (file_exists($fileName)) {
  $source = file($fileName);
  $data = new DataSource($source);
  $sensors = new Sensor($data);
  $entities = $sensors->getEntities();

  foreach ($entities as $entity) {
    $blocks[] = new Block($entity);
  }

  include 'page.php';
}
