<?php declare(strict_types=1);

use steinmb\Logger\FileStorage;
use steinmb\Logger\Logger;

include_once __DIR__ . '/vendor/autoload.php';

$logService = new Logger('Demo');
$logger = $logService->pushHandler(new FileStorage());
$logger2 = $logger->withName('Demo2');

$logger->write('Test data');
$logger2->write('Test data 2');

echo $logger->read();
echo $logger2->read();

$logger->close();
$logger2->close();
