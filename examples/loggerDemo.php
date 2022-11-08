<?php declare(strict_types=1);

use steinmb\Logger\Handlers\ConsoleHandler;
use steinmb\Logger\Handlers\FileStorageHandler;
use steinmb\Logger\Logger;

include_once __DIR__ . '/../vendor/autoload.php';

$logService = new Logger('Demo');
$fileLogger = $logService->pushHandler(new FileStorageHandler('test1.csv'));
$console = $fileLogger->withName('Console logger')->pushHandler(new ConsoleHandler());

$fileLogger->write('Logger: Test data');
$console->write('Test data 1');
$console->write('Test data 2');
$console->write('Test data 3');

echo PHP_EOL . '--- File logger ---' . PHP_EOL;
$result = $fileLogger->read();
echo $result;
echo $fileLogger->lastEntry();

echo PHP_EOL . '--- Console logger ---' . PHP_EOL;
$console->read();
$console->write('Test data 4 - last entry');
$console->lastEntry();

$fileLogger->close();
$console->close();
