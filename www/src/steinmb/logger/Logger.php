<?php
declare(strict_types = 1);

namespace steinmb\Logger;

interface Logger
{

    public function __construct(File $file);

    public function write(string $message): void;

    public function read(): string;

    public function lastEntry(): string;

}
