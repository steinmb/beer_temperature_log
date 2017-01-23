<?php

/**
 * File DataEntity.php
 *
 * Create a data entity from a temperature log.
 */

class DataEntity
{
  private $id;
  private $type;

  /**
   * DataEntity constructor.
   *
   * @param $id Sensor unique ID.
   * @param $type Sensor type.
   */
  public function __construct($id, $type)
  {
    $this->id = $id;
    $this->type = $type;
  }

  /**
   * Get the entity ID.
   *
   * @return string entity ID.
   */
  public function getId()
  {
    return $this->id;
  }
}
