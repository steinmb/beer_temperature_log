<?php
declare(strict_types=1);

namespace steinmb\Logger;

interface HandlerInterface
{
    public function __construct();
    public function read(): string;
    public function write(string $message);
    public function lastEntry(): string;
    public function close();
}
