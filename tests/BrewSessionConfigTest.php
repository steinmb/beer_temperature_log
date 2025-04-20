<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use steinmb\AmbiguousSessionId;
use steinmb\BrewSession;
use steinmb\BrewSessionConfig;
use steinmb\BrewSessionInterface;
use steinmb\Onewire\OneWireFixed;
use steinmb\Onewire\SensorFactory;
use steinmb\Onewire\TemperatureSensor;

#[CoversClass(BrewSessionConfig::class)]
#[CoversClass(AmbiguousSessionId::class)]
#[CoversClass(BrewSession::class)]
#[CoversClass(OneWireFixed::class)]
#[CoversClass(SensorFactory::class)]
#[CoversClass(TemperatureSensor::class)]
final class BrewSessionConfigTest extends TestCase
{
    private BrewSessionConfig $brewSessionConfig;
    private BrewSessionInterface $brewSession;
    private SensorFactory $sensorFactory;

    public function setUp(): void
    {
        parent::setUp();

        $settings = [
            '100' => [
                'probe' => '28-0000098101de',
                'ambient' => '10-000802be73fa',
                'low_limit' => 15,
                'high_limit' => 23,
                ],
            'AA' => [
                'probe' => '10-000802a55696',
                'ambient' => '10-000802be73fa',
                'low_limit' => 17,
                'high_limit' => 26,
                ],
        ];

        $this->brewSessionConfig = new BrewSessionConfig($settings);
        $this->sensorFactory = new SensorFactory(new OneWireFixed());
        $sensor = $this->sensorFactory->createSensor('28-0000098101de');
        $this->brewSession = $this->brewSessionConfig->sessionIdentity($sensor);
    }

    public function testSession(): void
    {
        self::assertEquals(
            '100',
            $this->brewSession->sessionId
        );
        self::assertEquals(
            '10-000802be73fa',
            $this->brewSession->ambient
        );
        self::assertIsFloat($this->brewSession->low_limit);
        self::assertIsFloat($this->brewSession->high_limit);
        self::assertEquals(
            15,
            $this->brewSession->low_limit
        );
        self::assertEquals(
            23,
            $this->brewSession->high_limit
        );
    }

    public function testAmbiguousSessionId(): void
    {
        $sensor = $this->sensorFactory->createSensor('10-000802be73fa');
        self::assertEquals(
            new AmbiguousSessionId(),
            $this->brewSessionConfig->sessionIdentity($sensor)
        );
    }
}
