<?php
declare(strict_types = 1);

namespace steinmb\onewire;

interface Logger
{

    public function __construct(File $file);

    public function write(string $logString): void;

    public function read(): string;

    public function lastEntry(): string;

}
