<?php declare(strict_types=1);

namespace Utils;
//20.351111111111113
//20.351111111113
use PHPUnit\Framework\TestCase;
use steinmb\Utils\Calculate;
use UnexpectedValueException;

/**
 * Class CalculateTest.
 *
 * @covers \steinmb\Utils\Calculate
 */
class CalculateTest extends TestCase
{
  private $values = [];
  private $xResult = [];

  protected function setUp(): void
  {
    $this->values = [1, 0, 4, 3];
    $this->xResult = [12, 13, 14, 15];
  }

  public function testMean(): void
  {
    $result = Calculate::mean($this->values);
    self::assertEquals(2, $result, 'Mean calculation failed.');

    // Check with precision.
    self::assertEquals(
      20.351111111111113,
      Calculate::mean(
        [20.223, 20.400, 20.321, 20.223, 20.220, 20.350, 20.650, 20.550, 20.223]
      )
    );
  }

  public function testMeanCannotUseSingleValue(): void
  {
    $this->expectException(UnexpectedValueException::class);
    Calculate::mean([2]);
  }

  public function testMeanCannotUseEmpty(): void
  {
    $this->expectException(UnexpectedValueException::class);
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
    $xSquare = Calculate::xSquare($result);
    self::assertEquals(1, $xSquare[0], 'Failed to calculate mean value distance square.');
    self::assertEquals(4, $xSquare[1], 'Failed to calculate mean value distance square.');
    self::assertEquals(4, $xSquare[2], 'Failed to calculate mean value distance square.');
    self::assertEquals(1, $xSquare[3], 'Failed to calculate mean value distance square.');
  }

  public function testMean_b1(): void
  {
    $result = Calculate::meanDistance($this->values);
    $xResult = Calculate::b1($result, $this->xResult);
    self::assertEquals(1.5, $xResult[0], 'Doh!');
    self::assertEquals(1, $xResult[1], 'Doh!');
    self::assertEquals(1, $xResult[2], 'Doh!');
    self::assertEquals(1.5, $xResult[3], 'Doh!');
  }

  public function test_b1Summary(): void
  {
    $regression = Calculate::b1Summary($this->xResult, $this->values);
    self::assertEquals(1, $regression);
  }

  public function testTrend(): void
  {
    $lastEntries = <<<LOG
        2021-02-23 09:20:05, 15.687, 15.687
        2021-02-23 09:25:10, 15.687, 16.687
        2021-02-23 09:30:15, 15.687, 16.687
        2021-02-23 09:35:20, 15.687, 17.687
        2021-02-23 09:40:25, 15.687, 17.687
        2021-02-23 09:45:05, 15.687, 17.687
        2021-02-23 09:50:05, 15.687, 17.687
        2021-02-23 09:55:05, 15.687, 17.687
        LOG;
    $calc = new Calculate();
    $trend = $calc->calculateTrend(6, '20', $lastEntries);
    self::assertEquals('0.010302066398', $trend->getTrend(), 'Failed calculating trend data');
  }
}
