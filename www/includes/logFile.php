<?php

/**
 * Created by PhpStorm.
 */
class LogFile
{
    protected $fileName = BREW_ROOT . '/../../temperatur/temp.log';
    private $totalLines = 0;
    private $structuredData;
    private $sensors = 0;

    public function __construct()
    {
        $data = file($this->fileName);
        $this->totalLines = count($data);

        foreach ($data as $item) {
            $this->structuredData[] = str_replace("\r\n", '', explode(',', $item));
        }

        foreach ($this->structuredData as $samples) {
            foreach ($samples as $key => $row) {
                if ($this->sensors < $key) {
                    $this->sensors = $key;
                }
            }
        }
    }

    public function getSensors()
    {
        return $this->sensors;
    }

    public function getTotalLines()
    {
        return $this->totalLines;
    }

    public function getStructuredData()
    {
        return $this->structuredData;
    }
}
