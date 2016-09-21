<?php

/**
 * Read logged data into a structured array.
 */
class LogFile
{
    protected $fileName = BREW_ROOT . '/../../temperatur/temp.log';
    private $totalLines = 0;
    private $structuredData = [];

    public function __construct()
    {
        $data = file($this->fileName);
        $this->totalLines = count($data);

        foreach ($data as $item) {
            $this->structuredData[] = str_replace("\r\n", '', explode(',', $item));
        }
    }

  /**
   * @return array of structured data.
   */
    public function getStructuredData()
    {
        return $this->structuredData;
    }
}
