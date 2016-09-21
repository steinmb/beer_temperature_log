<?php
/**
 * Create www interface.
 */

define('BREW_ROOT', getcwd());
require_once BREW_ROOT . '/includes/bootstrap.inc';
require_once BREW_ROOT . '/includes/LogFile.php';
require_once BREW_ROOT . '/includes/Sensor.php';

//$logFile = new LogFile();

$sensors = new Sensor();



//$brewData = new BrewData($logFile);

print '<pre>';
//$sensors = $brewData->getSensor();
//print_r($foo);
//foreach ($sensors as $sensor) {
//    print_r($item->getSensorData());
//    print("Sensor: {$sensor->getSensorID()}\n");
//}
//$foo = new ReflectionClass('LogFile');
//$foo::export($foo);
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
