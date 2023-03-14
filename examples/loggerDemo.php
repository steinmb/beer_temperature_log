<?php declare(strict_types=1);

use steinmb\Logger\Handlers\ConsoleHandler;
use steinmb\Logger\Handlers\FileStorageHandler;
use steinmb\Logger\Logger;

include_once __DIR__ . '/../vendor/autoload.php';

$formatter = new \steinmb\Formatters\NormaliseFormatter();
$consoleHandler = new ConsoleHandler();
$fileHandler = new FileStorageHandler('test1.csv');
$logger = new Logger('Demo');

$fileLogger = $logger->pushHandler($fileHandler);
$console = $fileLogger->withName('Console logger')->pushHandler($consoleHandler);

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
