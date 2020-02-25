<?php
declare(strict_types = 1);

namespace steinmb\onewire;

interface Logger
{

    public function __construct(string $logfile, string $directory);

    public function writeLogFile($logString): void;

    public function getLogData(): void;

    public function getData();

    public function getLastReading(): string;

}
