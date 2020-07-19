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

    public function testMean(): void
    {
        $result = Calculate::mean([1, 0 , 4, 3]);

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
}
