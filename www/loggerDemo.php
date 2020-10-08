<?php
declare(strict_types=1);

use steinmb\Logger\FileStorage;
use steinmb\Logger\Logger;

include_once __DIR__ . '/vendor/autoload.php';

$logger = new Logger('Demo');
$logger2 = $logger->withName('Demo2');
$handle = new FileStorage();
$logger->pushHandler($handle);
$logger->write('Test data');
$logger->close();
