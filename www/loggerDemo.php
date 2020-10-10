<?php declare(strict_types=1);

use steinmb\Logger\FileStorage;
use steinmb\Logger\Logger;

include_once __DIR__ . '/vendor/autoload.php';

$logService = new Logger('Demo');
$logger = $logService->pushHandler(new FileStorage('test1.csv'));
$logService2 = $logger->withName('Demo2');
$logger2 = $logService2->pushHandler(new FileStorage('test2.csv'));

$logger->write('Logger: Test data');
$logger2->write('Logger 2: Test data');

echo $logger->read();
echo $logger2->read();

$logger->close();
$logger2->close();
