<?php
/**
 * Create www interface.
 */

define('BREW_ROOT', getcwd());
require_once BREW_ROOT . '/includes/bootstrap.inc';
require_once BREW_ROOT . '/includes/LogFile.php';
require_once BREW_ROOT . '/includes/Sensor.php';
require_once BREW_ROOT . '/includes/DataEntity.php';
//$logFile = new LogFile();

$sensors = new Sensor();
$entities = $sensors->getEntities();
foreach ($entities as $entity) {
  $sample = $entity->getLastReading();
  print 'Sensor ' . $entity->getId() . ' ' . $sample['Date'] . ' ' . $sample['Sensor'] . 'ºC<br>';
}

print '<pre>';
print '</pre>';


?>
<html>
<LINK REL=StyleSheet HREF="css/style.css" TYPE="text/css" MEDIA=screen>
<meta charset="UTF-8" http-equiv="refresh" content="180">

<head>
  <title>Gjæring</title>
  <link>
</head>

<body>
  <div class="header">
    <h1 class="title">Brewpi temperature log</h1>
  </div>
  <div class="content">
    <img alt="temperatur log" src="temperatur.png">
  </div>
</body>
</html>
