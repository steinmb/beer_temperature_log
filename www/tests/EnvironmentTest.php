<?php declare(strict_types=1);

use steinmb\Environment;
use PHPUnit\Framework\TestCase;

final class EnvironmentTest extends TestCase
{

    public function testDefault(): void
    {
        self::assertNotEquals('', Environment::getSetting('BREW_ROOT'));
    }

    public function testSetting(): void
    {
        Environment::setSetting('DEMO_MODE', TRUE);
        $this->assertEquals(
          TRUE,
          Environment::getSetting('DEMO_MODE')
        );
    }

    public function testDemoMode(): void
    {
        $sensorDirectory = Environment::getSetting('SENSOR_DIRECTORY');
        $sensors = Environment::getSetting('SENSORS');
        Environment::setSetting('DEMO_MODE', TRUE);
        self::assertEquals($sensorDirectory, Environment::getSetting('SENSOR_DIRECTORY'));
        self::assertEquals($sensors, Environment::getSetting('SENSORS'));
    }

}
