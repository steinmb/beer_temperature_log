<?php

declare(strict_types=1);

namespace Logger\Handlers;

use PHPUnit\Framework\TestCase;
use steinmb\Formatters\NullFormatter;
use steinmb\Logger\Handlers\FileStorageHandler;

/**
 * @coversDefaultClass \steinmb\Logger\Handlers\FileStorageHandler
 */
class FileStorageHandlerTest extends TestCase
{
    private const testDirectory =  __DIR__ . '/Fixtures';

    private FileStorageHandler $fileStorage;

    public function setUp(): void
    {
        parent::setUp();

        if (!self::testDirectory) {
            mkdir(self::testDirectory);
        }

        $measurement = '25 00 4b 46 ff ff 07 10 cc : crc=cc YES
                        25 00 4b 46 ff ff 07 10 cc t=20000';

        $formatter = new NullFormatter();
        $this->fileStorage = new FileStorageHandler('test.csv', self::testDirectory, $formatter);
    }

    /**
     * @covers ::lastEntries
     */
    public function testRead(): void
    {
        $randomRecord = uniqid('Test', true);
        $this->fileStorage->write(['message' => $randomRecord]);
        self::assertSame($randomRecord, $this->fileStorage->lastEntries(1));
    }

    /**
     * @covers ::lastEntries
     */
    public function testReadMultiple(): void
    {
        $randomRecord = uniqid('Test', true);
        $randomRecord2 = uniqid('Test', true);
        $this->fileStorage->write(['message' => $randomRecord]);
        $this->fileStorage->write(['message' => $randomRecord2]);
        self::assertSame(
            $randomRecord . PHP_EOL . $randomRecord2,
            $this->fileStorage->lastEntries(2)
        );
    }

    /**
     * @covers ::write
     */
    public function testWrite(): void
    {
        $this->fileStorage->write(['message' => 'Test string']);
        self::assertSame('Test string', $this->fileStorage->lastEntry());
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->fileStorage->close();

        foreach (glob(self::testDirectory . '/*.csv') as $file) {
            unlink($file);
        }

        rmdir(self::testDirectory);
    }
}
