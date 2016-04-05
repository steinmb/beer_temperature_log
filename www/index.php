<?php
/**
 * Create www interface.
 */

define('BREW_ROOT', getcwd());

$file = BREW_ROOT . '/../temp.log';
//$file = '/home/pi/temperatur/temp.log';
$data = file($file);
$samples = 20; // Number of samples to test on.
$total_lines = count($data);
$sensors = array('time', 'ambient', 'fermentor1');
$readings = array();
$ambient = 0;
$fermentor1 = 0;
$samples_run = $samples;

function trend(array $data) {
    $trends = array();
    $sensors = array('Ambient', 'Fermentor 1');
    $y = array();
    $yx = array();
    $x2 = array();
    $x = '';
    foreach ($sensors as $sensor) {
        foreach ($data as $key => $row) {
            $y[] = 1000 * $row[$sensor];
            $x = $key + 1;
            $xy[] = $x * $y[$key];
            $x2[] = $y[$key] * $y[$key];
        }
        $samples = $x;
        $x = range(1, $x);
        $xSummary = array_sum($x);
        $ySummary = array_sum($y);
        $xySummary = array_sum($yx);
        $x2Summary = array_sum($x2);
        $trends[$sensor] = ($samples * $xySummary - ($xSummary * $ySummary)) / (($samples * $x2Summary) - (sqrt($xSummary)));
    }

    return $trends;
}

while ($total_lines > $total_lines - $samples_run) {
  $reading = explode(",", $data[$total_lines - $samples_run]);
  $readings[] = array(
      'Date' => $reading[0],
      'Ambient' => str_replace("\r\n", '', $reading[2]),
      'Fermentor 1' => str_replace("\r\n", '', $reading[1]
      ));
  $samples_run--;
}

$trends = trend($readings);

foreach ($readings as $reading) {
  $ambient = $ambient + $reading['Ambient'];
  $fermentor1 = $fermentor1 + $reading['Fermentor 1'];
}

$ambient_trend = '';
$fermentor1_trend = '';
foreach ($trends as $sensor => $trend) {
    if ($sensor == 'Ambient') {
        if ($trend > 0) {
            $ambient_trend = 'Climbing';
        } else {
            $ambient_trend = 'Falling';
        }
    }

    if ($sensor == 'Fermentor 1') {
        if ($trend > 0) {
            $fermentor1_trend = 'Climbing';
        } else {
            $fermentor1_trend = 'Falling';
        }
    }
}

$lastSample = '';
$lastSample = array_pop($readings);
$sample_time = 'Measured: ' . $lastSample['Date'];
$ambient_status = '<span class="ambient"> Ambient: ' .$lastSample['Ambient'] . ' ' . $ambient_trend . '</span>';
$fermentor1_status = '<span class="fermentor"> Fermentor: ' . $lastSample['Fermentor 1'] . ' ' . $fermentor1_trend . '</span>';

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
    <?php
      print '<p class="temp">' . $sample_time . $ambient_status . $fermentor1_status . '</p>';
    ?>
  </div>

  <div class="content">
    <img alt="temperatur log" src="temperatur.png">
  </div>

</body>
</html>

