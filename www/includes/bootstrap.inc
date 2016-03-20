<?php

/*
 * @file
 * Functions needed to be loaded on every page request.
 */

class BrewData
{
    protected $logFile;
    public $ambient_trend;
    public $fermentor1_trend;
    public $ambient_status;
    public $sample_time;
    public $fermentor1_status;
    public $line = [0, 0, 0];
    public $ambient;
    public $fermentor1;
    public $trend;
    public $samples = 20;
    public $samples_run = 20;
    public $readings;

    public function __construct(LogFile $logFile)
    {
        $this->logFile = $logFile;
    }

    public function trend()
    {
        $lastSamples = array_slice($this->logFile->getStructuredData(), -$this->samples, $this->samples);
        foreach ($lastSamples as $lastSample) {
            foreach ($lastSample as $key => $item) {
                if ($key !== 0) {
                    $sensor[$key][] = str_replace("\r\n", '', $item);
                }
            }
            $trend = $sensor;
        }

//        if (($sensor * count($sensor)) / $ambient > 1) {
//            $ambient_trend = 'Climbing';
//        } else {
//            $ambient_trend = 'Falling';
//        }
//
//        if (($line[2] * $samples) / $fermentor1 > 1) {
//            $fermentor1_trend = 'Climbing';
//        } else {
//            $fermentor1_trend = 'Falling';
//        }

        return $trend;
    }

    public function createStatusMessage()
    {
        $status = '<span class="ambient"> Ambient: ' . $line[1] . ' ' . $ambient_trend . '</span>';
        $status .= '<span class="fermentor"> Fermentor: ' . $line[2] . ' ' . $fermentor1_trend . '</span>';
        $status .= '<p class="temp">' . $sample_time . $ambient_status . $fermentor1_status . '</p>';

        return $status;
    }
}
