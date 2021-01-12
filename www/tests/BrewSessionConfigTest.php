<?php declare(strict_types=1);

namespace steinmb;

use PHPUnit\Framework\TestCase;
use steinmb\Onewire\DataEntity;
use steinmb\Onewire\Temperature;
use UnexpectedValueException;

final class BrewSessionConfigTest extends TestCase
{
    private $brewSessionConfig;
    private $brewSession;

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
        $this->brewSession = $this->brewSessionConfig->sessionIdentity('28-0000098101de');
    }

    public function testUnknownProbe(): void
    {
        $probe = 'unknown';
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Probe: ' . $probe . ' not found.');
        $this->brewSessionConfig->sessionIdentity($probe);
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
        self::assertEquals(
            new AmbiguousSessionId(),
            $this->brewSessionConfig->sessionIdentity('10-000802be73fa')
        );
    }

    public function testHighLimit(): void
    {
        $measurement = '25 00 4b 46 ff ff 07 10 cc : crc=cc YES
                        25 00 4b 46 ff ff 07 10 cc t=24000';
        $entity = new DataEntity(
            $this->brewSession->probe,
            'temperature',
            $measurement,
            new SystemClock()
        );
        $temperature = new Temperature($entity);

        self::assertTrue($this->brewSessionConfig->highLimit($this->brewSession, $temperature));
    }

    public function testLowLimit(): void
    {
        $measurement = '25 00 4b 46 ff ff 07 10 cc : crc=cc YES
                        25 00 4b 46 ff ff 07 10 cc t=14000';
        $entity = new DataEntity(
            $this->brewSession->probe,
            'temperature',
            $measurement,
            new SystemClock()
        );
        $temperature = new Temperature($entity);
        self::assertTrue($this->brewSessionConfig->lowLimit($this->brewSession, $temperature));
    }
}
