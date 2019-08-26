<?php
declare(strict_types=1);

namespace steinmb\onewire;

/**
 * File DataEntity.php
 *
 * Create a data entity from a temperature log.
 */

class DataEntity
{
    private $id;
    private $type;
    private $measurement;

    /**
     * DataEntity constructor.
     *
     * @param $id string sensor unique ID.
     * @param $type string sensor type.
     * @param $measurement int
     */
    public function __construct(string $id, string $type, int $measurement)
    {
        $this->id = $id;
        $this->type = $type;
        $this->measurement = $measurement;
    }

    /**
    * Get the entity ID.
    *
    * @return string entity ID.
    */
    public function getId(): string
    {
        return $this->id;
    }

    public function getSensorType(): string
    {
        return $this->type;
    }

    public function getData(): string
    {
        return "{$this->id}, {$this->type}, {$this->measurement}";
    }

}
