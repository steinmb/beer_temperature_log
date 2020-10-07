<?php
declare(strict_types=1);

namespace steinmb\Logger;

interface HandlerInterface
{
    public function __construct();
    public function read();
    public function write(string $message);
}
