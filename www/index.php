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
<head>
  <title>Gjæring</title>

  <style>
    body { color: #333; background-color: #fff; margin: 0 auto; }
    .header { width: 100%; display: inline-block; z-index: 3; }
    .temp { font-style: italic; margin: 0; text-align: right; }
    .ambient { color: red; padding-left: 1em; }
    .fermentor { color: green; padding-left: 1em; }
    .title { width: 25%; float: left; text-transform: uppercase; margin: 0; z-index: 3; font-size: 1.1em }
    .title {}
    .temp { width: 75%; float: right; }
    img { height: 95%; width: auto; z-index: 1; }
    .content { }
  </style>

</head>
<meta charset="UTF-8" http-equiv="refresh" content="180">

<body>

  <div class="header">
    <h1 class="title">Brewpi temperature log</h1>
  </div>

  <div class="content">
    <img alt="temperatur log" src="temperatur.png">
  </div>

</body>
</html>

