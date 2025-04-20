<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use steinmb\RuntimeEnvironment;
use PHPUnit\Framework\TestCase;

#[CoversClass(RuntimeEnvironment::class)]
final class EnvironmentTest extends TestCase
{

    public function testDefault(): void
    {
        self::assertNotEquals('', RuntimeEnvironment::getSetting('BREW_ROOT'));
    }

    public function testSetting(): void
    {
        $newValue = 'newfile.log';
        RuntimeEnvironment::setSetting('LOG_INFO', $newValue);
        self::assertEquals(
          $newValue,
          RuntimeEnvironment::getSetting('LOG_INFO')
        );
    }

}
