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
        RuntimeEnvironment::setSetting('DEMO_MODE', TRUE);
        $this->assertEquals(
          TRUE,
          RuntimeEnvironment::getSetting('DEMO_MODE')
        );
    }

    public function testDemoMode(): void
    {
        $sensorDirectory = RuntimeEnvironment::getSetting('SENSOR_DIRECTORY');
        $sensors = RuntimeEnvironment::getSetting('SENSORS');
        RuntimeEnvironment::setSetting('DEMO_MODE', TRUE);
        self::assertEquals($sensorDirectory, RuntimeEnvironment::getSetting('SENSOR_DIRECTORY'));
        self::assertEquals($sensors, RuntimeEnvironment::getSetting('SENSORS'));
    }

}
