<?php declare(strict_types=1);

namespace steinmb\Onewire;

use steinmb\Clock;

final class DataEntity implements EntityInterface
{
    private const format = 'Y-m-d H:i:s';
    private $id;
    private $type;
    private $measurement;
    private $time;

    public function __construct(
        string $id,
        string $type,
        string $measurement,
        Clock $time
    )
    {
        $this->id = $id;
        $this->type = $type;
        $this->measurement = $measurement;
        $this->time = $time->currentTime();
    }

    public function __toString(): string
    {
        return "{$this->id}, {$this->type}, {$this->measurement}, {$this->time->format($this::format)}";
    }

    public function timeStamp(): string
    {
        return $this->time->format($this::format);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function measurement(): string
    {
        return $this->measurement;
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
