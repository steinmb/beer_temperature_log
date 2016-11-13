<?php

/**
 * Read logged data into a structured array.
 */
class DataSource
{
    private $totalLines = 0;
    private $structuredData = [];

    public function __construct($data)
    {
        $this->totalLines = count($data);

        foreach ($data as $item) {
          $this->setStructuredData(str_replace("\r\n", '', explode(',', $item)));
        }
    }

  /**
   * @return array of structured data.
   */
    public function getStructuredData()
    {
        return $this->structuredData;
    }

  /**
   * @param array $structuredData
   */
  public function setStructuredData($structuredData)
  {
      $this->structuredData[] = $structuredData;
  }
}
