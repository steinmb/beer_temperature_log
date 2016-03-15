<?php

/*
 * @file
 * Functions needed to be loaded on every page request.
 */

class BrewData
{
    public $ambient_trend = '';
    public $fermentor1_trend = '';
    public $ambient_status = '';
    public $sample_time = '';
    public $fermentor1_status = '';
    public $line = [0, 0, 0];
    public $ambient = '';
    public $fermentor1 = '';
    public $trend = '';
    public $samples = 20;
    public $samples_run = 20;
    public $readings = array();
//    public $data = $line;
//    public $sensors = array('time', 'ambient', 'fermentor1');
//    public $sample_time = 'Measured: ' . $line[0];

    public function trend($line)
    {

        while ($total_lines > $total_lines - $samples_run) {
            $reading = explode(",", $data[$total_lines - $samples_run]);
            $readings[] = array('Date' => $reading[0], 'Ambient' => $reading[2], 'Fermentor 1' => $reading[1]);
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

        return $trend;
    }

    public function readLogFile($fileName)
    {
        $data = file($fileName);
        $line = $data[count($data) - 1];
        $line = explode(",", $line);

        return $data;
    }

    public function createStatusMessage()
    {
        $status = '<span class="ambient"> Ambient: ' . $line[1] . ' ' . $ambient_trend . '</span>';
        $status .= '<span class="fermentor"> Fermentor: ' . $line[2] . ' ' . $fermentor1_trend . '</span>';
        $status .= '<p class="temp">' . $sample_time . $ambient_status . $fermentor1_status . '</p>';

        return $status;
    }
}
