<?php

declare(strict_types=1);

/**
 *   Example on how to find all 1-Wire devices connected.
 */

include_once __DIR__ . '/../vendor/autoload.php';

$oneWire = new steinmb\Onewire\OneWire();
echo $oneWire . PHP_EOL;
