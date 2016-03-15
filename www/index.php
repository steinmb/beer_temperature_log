<?php
/**
 * Create www interface.
 */

define('BREW_ROOT', getcwd());
require_once BREW_ROOT . '/includes/bootstrap.inc';
require_once BREW_ROOT . '/includes/logFile.php';

$logFile = new logFile();
$BrewData = new BrewData;

print '<pre>';
print_r($logFile->getStructedData());
print_r($logFile->getLines());
//print_r($logFile);
print_r($BrewData);
print '</pre>';

//$data = readLogFile('/home/pi/temperatur/temp.log');
//$data = readLogFile('../temp.log'); // Demo/test log file.
//$ambient_trend = trend($data);
//$fermentor1_trend = trend($data);
//$status = createStatusMessage();
//
//print('<pre>' . $status . '</pre>');

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
