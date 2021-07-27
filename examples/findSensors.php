<?php declare(strict_types = 1);

/**
 * @file Example: lists all 1-Wire devices found.
 */

use steinmb\Onewire\OneWire;

include_once __DIR__ . '/../vendor/autoload.php';

$oneWire = new OneWire();
echo (string) $oneWire . PHP_EOL;
