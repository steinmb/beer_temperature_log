<?php declare(strict_types=1);

use steinmb\Onewire\DataEntity;
use PHPUnit\Framework\TestCase;
use steinmb\SystemClockFixed;

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
            new SystemClockFixed(new DateTimeImmutable('16.07.1970 03:55'))
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

    /**
     * @covers \steinmb\Onewire\DataEntity::timeStamp
     */
    public function testTimestamp(): void
    {
        self::assertEquals('1970-07-16 03:55:00', $this->entity->timeStamp(),
            'Testing timestamp failed.'
        );
    }
}
