<?php

/**
 * Created by PhpStorm.
 * User: steinmb
 * Date: 21/09/16
 * Time: 23:59
 */
class DataEntity
{
    private $id;
    private $data;

    public function __construct($data)
    {
      $this->data = $data;
    }

    public function setId($id)
    {
      $this->id = $id;
    }
}
