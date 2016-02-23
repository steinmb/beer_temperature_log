<?php
/**
 * Test
 */

$file = '/home/pi/temperatur/temp.log'; 
$data = file($file);
$line = $data[count($data)-1];
$line = explode(",", $line);
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
    h1.title { width: 30%; float: left; font-size: 1.3em; margin: 0; z-index: 3; }
    .title {}
    .temp { width: 60%; float: right; }
    img { height: 95%; width: auto; z-index: 1; }
    .content { }
  </style>

</head>
<meta charset="UTF-8" http-equiv="refresh" content="180">

<body>

  <div class="header">
    <h1 class="title">Brewpi temperature log</h1>
    <?php
      print '<p class="temp"> Measured: ' . $line[0] . ' <span class="ambient"> Ambient: ' . $line[1] . '</span><span class="fermentor"> Fermentor: ' . $line[2] . '</span></p>';
    ?>
  </div>

  <div class="content">
    <img alt="temperatur log" src="temperatur.png">
  </div>

</body>
</html>

