<?php
/**
 * Create web interface interface.
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
  $entity->calculateTrend();
  $sample = $entity->getLastReading();
  print '<li>Sensor ';
  print $entity->getId();
  print ' ' . $sample['Date'];
  print $sample['Sensor'] . 'ºC';
  print ' ' . $entity->analyzeTrend() . ' (' . $entity->getTrend() . ')';
  print '</li>';
}
print '</ul>';

print $page;
