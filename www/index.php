<?php
/**
 * Test
 */

$file = '/home/pi/temperatur/temp.log'; 
$data = file($file);
$line = $data[count($data)-1];
?>

<html>
<head>
  <title>Gjæring</title>

  <style>
    body { color: #333; background-color: #fff; margin: 0 auto; }
    .header { width: 100%; display: inline-block; z-index: 3; }
    .temp { color: red; font-style: italic; margin: 0; text-align: right; }
    h1.title { width: 60%; float: left; font-size: 1.5em; margin: 0; z-index: 3; }
    .title {}
    .temp { width: 30%; float: right; }
    img { height: auto; width: 100%; z-index: 1; }
    .content { }
  </style>

</head>
<meta charset="UTF-8" http-equiv="refresh" content="180">

<body>

  <div class="header">
    <h1 class="title">Gjæring</h1>
    <?php print '<p class="temp">' . $line . ' </p>'; ?>
  </div>

  <div class="content">
    <img alt="temperatur log" src="temperatur.png">
  </div>

</body>
</html>

