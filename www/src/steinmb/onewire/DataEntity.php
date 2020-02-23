<?php
declare(strict_types=1);

namespace steinmb\onewire;

/**
 * @file DataEntity.php
 *
 * Temperature reading.
 */

class DataEntity
{
    private $id;
    private $type;
    private $measurement;
    private $time;

    public function __construct(
        string $id,
        string $type,
        string $measurement,
        \DateTimeImmutable $time

    )
    {
        $this->id = $id;
        $this->type = $type;
        $this->measurement = $measurement;
        $this->time = $time;
    }

    public function __toString(): string
    {
        return "{$this->id}, {$this->type}, {$this->measurement}, {$this->time->format('d,m,Y')}";
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
