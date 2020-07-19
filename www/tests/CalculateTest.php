<?php declare(strict_types=1);

namespace steinmb;

use steinmb\Utils\Calculate;
use PHPUnit\Framework\TestCase;

/**
 * Class CalculateTest
 *
 * @covers \steinmb\Utils\Calculate
 */
class CalculateTest extends TestCase
{
    private $values = [];

    protected function setUp(): void
    {
        $this->values = [1, 0 , 4, 3];
    }

    public function testMean(): void
    {
        $result = Calculate::mean($this->values);

        self::assertEquals(2, $result, 'Mean calculation failed.');
    }

    public function testMeanCannotUseSingleValue(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        Calculate::mean([2]);
    }

    public function testMeanCannotUseEmpty(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        Calculate::mean([]);
    }

    public function testMeanDistance(): void
    {
        $result = Calculate::meanDistance($this->values);
        self::assertEquals(-1, $result[0], 'Failed to calculate mean value distance.');
        self::assertEquals(-2, $result[1], 'Failed to calculate mean value distance.');
        self::assertEquals(2, $result[2], 'Failed to calculate mean value distance.');
        self::assertEquals(1, $result[3], 'Failed to calculate mean value distance.');
    }

    public function testMeanDistanceSquare(): void
    {
        $result = Calculate::meanDistance($this->values);
    }

}
