<?php
declare(strict_types=1);

namespace steinmb\Logger;

use steinmb\Environment;

interface HandlerInterface
{
    public function __construct(Environment $config);
    public function read();
    public function write(string $message);
    public function close();
}
