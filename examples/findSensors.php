<?php declare(strict_types = 1);

/**
 * @file list all 1-Wire devices found on device.
 */

use steinmb\Onewire\OneWire;
use steinmb\RuntimeEnvironment;

include_once __DIR__ . '/../vendor/autoload.php';

RuntimeEnvironment::init();

$oneWire = new OneWire();
echo (string) $oneWire;
