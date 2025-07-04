<?php

declare(strict_types=1);

namespace steinmb\Tests\Unit\Logger;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use steinmb\Logger\Handlers\NullHandler;
use steinmb\Logger\Logger;

#[CoversClass(Logger::class)]
#[UsesClass(NullHandler::class)]
final class LoggerTest extends TestCase
{
    private Logger $logger;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $logger = new Logger('Test');
        $this->logger = $logger->pushHandler(new NullHandler());
    }

    public function testWithName(): void
    {
        $logger2 = $this->logger->withName('Test2');
        self::assertSame('Test2', $logger2->getName());
    }

    public function testRead(): void
    {
        self::assertSame('Test data from NullHandler', $this->logger->read());
    }

    public function testLastEntry(): void
    {
        $this->logger->write('This is the first message');
        $this->logger->write('This is the last message');
        self::assertSame('This is the last message', $this->logger->lastEntry());
    }
}
