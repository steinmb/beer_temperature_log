<?php declare(strict_types=1);

namespace steinmb;

use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * @coversDefaultClass \steinmb\BrewSessionConfig
 */
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

    /**
     * @covers ::sessionIdentity
     */
    public function testUnknownProbe(): void
    {
        $probe = 'unknown';
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Probe: ' . $probe . ' not found.');
        $this->brewSessionConfig->sessionIdentity($probe);
    }

    /**
     * @covers ::sessionIdentity
     */
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

    /**
     * @covers ::sessionIdentity
     */
    public function testAmbiguousSessionId(): void
    {
        self::assertEquals(
            new AmbiguousSessionId(),
            $this->brewSessionConfig->sessionIdentity('10-000802be73fa')
        );
    }
}
