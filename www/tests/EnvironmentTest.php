<?php declare(strict_types=1);

use steinmb\Environment;
use PHPUnit\Framework\TestCase;

final class EnvironmentTest extends TestCase
{
    public function testSetting(): void
    {
        Environment::setSetting('DEMO_MODE', TRUE);
        $this->assertEquals(
          TRUE,
          Environment::getSetting('DEMO_MODE')
        );
    }
}
