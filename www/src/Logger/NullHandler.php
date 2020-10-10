<?php declare(strict_types = 1);

namespace steinmb\Logger;

class NullHandler implements HandlerInterface
{

    public function __construct()
    {
    }

    public function read(): string
    {
        return 'Test data from NullHandler';
    }

    public function write(string $message)
    {
        // TODO: Implement write() method.
    }

    public function close()
    {
        // TODO: Implement close() method.
    }
}