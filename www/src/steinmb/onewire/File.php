<?php
declare(strict_types=1);

namespace steinmb\onewire;

interface File
{
    public function __construct(string $directory, string $fileName);
    public function storage($fileHandle): void;
    public function read();
    public function write(string $logString);
}
