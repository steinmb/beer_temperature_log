<?php
/**
 * Create www interface.
 */

$file = '/home/pi/temperatur/temp.log';
$data = file($file);
$samples = 20; // Number of samples to test on.
$total_lines = count($data);

$sensors = array('time', 'ambient', 'fermentor1');
$readings = array();
$ambient = 0;
$fermentor1 = 0;


$line = $data[count($data)-1];
$line = explode(",", $line);
$samples_run = $samples;

while ($total_lines > $total_lines - $samples_run) {
  $reading = explode(",", $data[$total_lines - $samples_run]);
  $readings[] = array('Date' => $reading[0], 'Ambient' => $reading[1], 'Fermentor 1' => $reading[2]);
  $samples_run--;
}

foreach ($readings as $reading) {
  $ambient = $ambient + $reading['Ambient'];
  $fermentor1 = $fermentor1 + $reading['Fermentor 1'];
}

if (($line[1] * $samples) / $ambient > 1) {
  $ambient_trend = 'Climbing';
} else {
  $ambient_trend = 'Falling';
}

if (($line[2] * $samples) / $fermentor1 > 1) {
  $fermentor1_trend = 'Climbing';
} else {
  $fermentor1_trend = 'Falling';
}

$sample_time = 'Measured: ' . $line[0];
$ambient_status = '<span class="ambient"> Ambient: ' . $line[1] . ' ' . $ambient_trend . '</span>';
$fermentor1_status = '<span class="fermentor"> Fermentor: ' . $line[2] . ' ' . $fermentor1_trend . '</span>';

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
    .title { width: 20%; float: left; texit-transform: uppercase; margin: 0; z-index: 3; font-size: 1.1em }
    .title {}
    .temp { width: 70%; float: right; }
    img { height: 95%; width: auto; z-index: 1; }
    .content { }
  </style>

</head>
<meta charset="UTF-8" http-equiv="refresh" content="180">

<body>

  <div class="header">
    <h1 class="title">Brewpi temperature log</h1>
    <?php
      print '<p class="temp">' . $sample_time . $ambient_status . $fermentor1_status . '</p>';
    ?>
  </div>

  <div class="content">
    <img alt="temperatur log" src="temperatur.png">
  </div>

</body>
</html>

