<?php declare(strict_types=1);

use steinmb\RuntimeEnvironment;
use PHPUnit\Framework\TestCase;

/**
 * Class EnvironmentTest
 *
 * @covers \steinmb\RuntimeEnvironment
 */
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
