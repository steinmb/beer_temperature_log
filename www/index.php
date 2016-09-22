<?php
/**
 * Create www interface.
 */

define('BREW_ROOT', getcwd());
require_once BREW_ROOT . '/includes/bootstrap.inc';
require_once BREW_ROOT . '/includes/LogFile.php';
require_once BREW_ROOT . '/includes/Sensor.php';
require_once BREW_ROOT . '/includes/DataEntity.php';
require_once BREW_ROOT . '/page.php';
//$logFile = new LogFile();

$sensors = new Sensor();
$entities = $sensors->getEntities();
print '<ul>';
foreach ($entities as $entity) {
  $sample = $entity->getLastReading();
  print '<li>Sensor ' . $entity->getId() . ' ' . $sample['Date'] . ' ' . $sample['Sensor'] . 'ºC</li>';
}
print '</ul>';
print $page;
