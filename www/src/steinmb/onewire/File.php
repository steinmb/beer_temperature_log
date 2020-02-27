<?php
declare(strict_types=1);

namespace steinmb\onewire;

interface File
{
    public function storage(string $directory, string $fileName);
}
