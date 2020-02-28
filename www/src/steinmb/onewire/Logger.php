<?php
declare(strict_types = 1);

namespace steinmb\onewire;

interface Logger
{

    public function __construct(File $file);

    public function write($logString): void;

    public function read($fileHandle, string $directory, string $fileName): string;

    public function lastEntry(array $content): string;

}
