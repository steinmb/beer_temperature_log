<?php

/**
 * Created by PhpStorm.
 * User: steinmb
 * Date: 15/03/16
 * Time: 16:56
 */
class logFile
{
    protected $fileName = BREW_ROOT . '/../../temperatur/temp.log';
    protected $line = '';

    public function __construct()
    {
        $this->data = file($this->fileName);
    }

    public function getStructedData()
    {
        $line = $this->data[count($this->data) - 1];
        $line = explode(",", $line);

        return $line;
    }

    public function getLines()
    {
        $total_lines = count($this->data);

        return $total_lines;
    }
}
