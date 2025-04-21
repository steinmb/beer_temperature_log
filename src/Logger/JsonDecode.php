<?php

declare(strict_types=1);

namespace steinmb\Logger;

use JsonException;
use RuntimeException;

final class JsonDecode
{
    public function decode(string $data): array
    {
        if (!$data) {
            return [];
        }

        if (str_starts_with($data, '<html>')) {
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
