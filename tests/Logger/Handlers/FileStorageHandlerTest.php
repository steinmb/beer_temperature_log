<?php

declare(strict_types=1);

namespace Logger\Handlers;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use steinmb\Formatters\NullFormatter;
use steinmb\Logger\Handlers\FileStorageHandler;
use steinmb\DataEntity;
use steinmb\Onewire\Temperature;
use steinmb\SystemClockFixed;

/**
 * @coversDefaultClass \steinmb\Logger\Handlers\FileStorageHandler
 */
class FileStorageHandlerTest extends TestCase
{

    private $fileStorage;
    private const testDirectory =   __DIR__ . '/Fixtures';
    private $temperature;
    private $formatter;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        if (!self::testDirectory) {
            mkdir(self::testDirectory);
        }

        $measurement = '25 00 4b 46 ff ff 07 10 cc : crc=cc YES
                        25 00 4b 46 ff ff 07 10 cc t=20000';
        $this->temperature = new Temperature(new DataEntity(
                '28-1234567',
                'temperature',
                $measurement,
                new SystemClockFixed(new dateTimeImmutable('16.07.2018 13.01.00')))
        );

        $this->fileStorage = new FileStorageHandler('test.csv', self::testDirectory);
        $this->formatter = new NullFormatter();
    }

    /**
     * @covers ::lastEntries
     */
    public function testRead(): void
    {
        $randomRecord = uniqid('Test', true);
        $this->fileStorage->write(['message' => $randomRecord], $this->formatter);
        self::assertSame($randomRecord, $this->fileStorage->lastEntries(1));
    }

    /**
     * @covers ::lastEntries
     */
    public function testReadMultiple(): void
    {
        $randomRecord = uniqid('Test', true);
        $randomRecord2 = uniqid('Test', true);
        $this->fileStorage->write(['message' => $randomRecord], $this->formatter);
        $this->fileStorage->write(['message' => $randomRecord2], $this->formatter);
        self::assertSame($randomRecord . PHP_EOL . $randomRecord2, $this->fileStorage->lastEntries(2));
    }

    /**
     * @covers ::write
     */
    public function testWrite(): void
    {
        $this->fileStorage->write(['message' => 'Test string'], $this->formatter);
        self::assertSame('Test string', $this->fileStorage->lastEntry());
    }

    public function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        $this->fileStorage->close();
        foreach (glob(self::testDirectory . '/*.csv') as $file) {
            unlink($file);
        }
        rmdir(self::testDirectory);
    }
}
