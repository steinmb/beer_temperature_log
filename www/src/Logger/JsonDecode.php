<?php declare(strict_types=1);

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

        if (substr($data, 0, 6) === '<html>') {
            return [];
        }

        try {
            $result = json_decode($data, true, 512);
        } catch (JsonException $e) {
        throw new RuntimeException(
                'Failed to encode data: ' . $e
            );
        }

        if ($result['message'] === 'success') {
            return $result;
        }

        if ($result['message'] === false) {
            throw new RuntimeException(
                'BrewersFriend API error. Description: ' . $result["message"] . ' ' . $result['detail']
            );
        }

        if ($result['message'] === 'unauthorized') {
            throw new RuntimeException(
                'BrewersFriend API error. Description: ' . $result["message"] . ' ' . $result['detail']
            );
        }

        throw new RuntimeException(
            'BrewersFriend unknown API error. Description: ' . $result["message"] . ' ' . $result['detail']
        );
    }
}
