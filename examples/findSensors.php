<?php

/**
 *   Example on how to find all 1-Wire devices connected to the system.
 */

declare(strict_types=1);

include_once __DIR__ . '/../vendor/autoload.php';

$oneWire = new steinmb\Onewire\OneWire(__DIR__ . '/../tests/data_all_valid');
echo $oneWire . PHP_EOL;
