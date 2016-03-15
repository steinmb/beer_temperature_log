<?php
/**
 * Create www interface.
 */

define('BREW_ROOT', getcwd());
require_once BREW_ROOT . '/includes/bootstrap.inc';

$file = '/home/pi/temperatur/temp.log';
$data = file($file);
$sensors = array('time', 'ambient', 'fermentor1');
$ambient = 0;
$fermentor1 = 0;

$ambient_trend = trend($fermentor1, $ambient, $data);
$fermentor1_trend = trend($fermentor1, $ambient, $data);
$status = '<p class="temp">' . $sample_time . $ambient_status . $fermentor1_status . '</p>';

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
