<?php declare(strict_types=1);

use steinmb\Environment;
use PHPUnit\Framework\TestCase;

final class EnvironmentTest extends TestCase
{
    public function testDefaultConfig()
    {
        $this->assertInstanceOf(
          Environment::class,
          new Environment('.')
        );
    }
}
