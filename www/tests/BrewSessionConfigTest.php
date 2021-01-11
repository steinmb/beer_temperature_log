<?php declare(strict_types=1);

namespace steinmb;

use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

final class BrewSessionConfigTest extends TestCase
{
    private $brewSessionConfig;

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
        $brewSession = $this->brewSessionConfig->sessionIdentity('28-0000098101de');

        self::assertEquals(
            '100',
            $brewSession->sessionId
        );
        self::assertEquals(
            '10-000802be73fa',
            $brewSession->ambient
        );
        self::assertIsFloat($brewSession->low_limit);
        self::assertIsFloat($brewSession->high_limit);
        self::assertEquals(
            15,
            $brewSession->low_limit
        );
        self::assertEquals(
            23,
            $brewSession->high_limit
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
        self::assertTrue($this->brewSessionConfig->highLimit(28));
    }
}
