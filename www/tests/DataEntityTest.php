<?php declare(strict_types=1);

use steinmb\Onewire\DataEntity;
use PHPUnit\Framework\TestCase;
use steinmb\SystemClock;

final class DataEntityTest extends TestCase
{
    private $entity;

    public function setup(): void
    {
        parent::setUp();
        $this->entity = new DataEntity(
            '10-123456789',
            'temp',
            '20.000',
            new SystemClock()
        );
    }

    public function testId(): void
    {
        self::assertSame('10-123456789', $this->entity->id());
    }

    public function testMeasurement(): void
    {
        self::assertEquals('20.000', $this->entity->measurement(), 'Failed to get measurement');
    }

}
