<?php

declare(strict_types = 1);

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
   * @param $id string sensor unique ID.
   * @param $type string sensor type.
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
