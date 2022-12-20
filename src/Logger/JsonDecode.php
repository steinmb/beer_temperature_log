<?php

declare(strict_types=1);

namespace steinmb\Logger;

use JsonException;
use RuntimeException;

final class JsonDecode
{
    /**
     * @param string $data
     * @return array
     */
    public function decode(string $data): array
    {
        if (!$data) {
            return [];
        }

        if (strpos($data, '<html>') === 0) {
            return [];
        }

        try {
            $result = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException(
              'Failed to decode data: ' . $e
            );
        }

        return $result;
    }
}
