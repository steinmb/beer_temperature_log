<?php
declare(strict_types=1);

require_once BREW_ROOT . '/test/SensorTest.php';

function testRunner(string $argument): void {
  $test = new SensorTest(
    $argument,
    new OldSensor('./test'),
    new Logger('temperature_test.log')
  );

  if (!$test->testActivated) {
    exit;
  }

  $test->logData();
  exit;
}
