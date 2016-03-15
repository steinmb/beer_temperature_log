<?php

/*
 * @file
 * Functions needed to be loaded on every page request.
 */

function trend($fermentor1, $ambient, $data) {
    $trend = '';
    $samples = 20;
    $samples_run = $samples;
    $line = $data[count($data)-1];
    $line = explode(",", $line);
    $readings = array();
    $total_lines = count($data);

    foreach ($readings as $reading) {
        $ambient = $ambient + $reading['Ambient'];
        $fermentor1 = $fermentor1 + $reading['Fermentor 1'];
    }

    $sample_time = 'Measured: ' . $line[0];

    while ($total_lines > $total_lines - $samples_run) {
        $reading = explode(",", $data[$total_lines - $samples_run]);
        $readings[] = array('Date' => $reading[0], 'Ambient' => $reading[2], 'Fermentor 1' => $reading[1]);
        $samples_run--;
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

    $ambient_status = '<span class="ambient"> Ambient: ' . $line[1] . ' ' . $ambient_trend . '</span>';
    $fermentor1_status = '<span class="fermentor"> Fermentor: ' . $line[2] . ' ' . $fermentor1_trend . '</span>';


    return $trend;
}

