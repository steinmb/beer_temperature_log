<?php
declare(strict_types = 1);

namespace steinmb\onewire;

interface Logger
{

    public function __construct(File $file);

    public function writeLogFile($logString): void;

    public function getLogData(string $directory, string $fileName): void;

    public function getData();

    public function getLastReading(): string;

}
