<?php
declare(strict_types=1);

namespace steinmb\onewire;

use UnexpectedValueException;

class FileStorage implements File
{

    public function storage(string $directory, string $fileName)
    {

        if (!file_exists($directory) && !mkdir($directory, 0755,
            true) && !is_dir($directory)) {
            throw new UnexpectedValueException(
              'Unable to create log directory: ' . $directory
            );
        }

        $fqFileName = $directory . '/' . $fileName;
        $fileHandle = fopen($fqFileName, 'wb+');

        if (!$fileHandle) {
            throw new UnexpectedValueException(
              'Unable to open or create log file: ' . $fqFileName
            );
        }

        return $fileHandle;
    }

}