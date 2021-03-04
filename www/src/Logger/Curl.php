<?php declare(strict_types=1);

namespace steinmb\Logger;

use RuntimeException;
use UnexpectedValueException;

final class Curl
{
    private static $retrievableErrorCodes = [
        CURLE_COULDNT_RESOLVE_HOST,
        CURLE_COULDNT_CONNECT,
        CURLE_HTTP_NOT_FOUND,
        CURLE_READ_ERROR,
        CURLE_OPERATION_TIMEOUTED,
        CURLE_HTTP_POST_ERROR,
        CURLE_SSL_CONNECT_ERROR,
    ];
    private $ch;

    public function curl()
    {
        $retries = 5;
        $closeAfterDone = false;

        while ($retries--) {
            $curlResponse = curl_exec($this->ch);
            if ($curlResponse === false) {
                $curlErrno = curl_errno($this->ch);

                if (!$retries || false === in_array($curlErrno, self::$retrievableErrorCodes, true)) {
                    $curlError = curl_error($this->ch);

                    if ($closeAfterDone) {
                        $this->close();
                    }

                    throw new RuntimeException(
                        'Curl failed' . $curlErrno . ' ' . $curlError);
                }
                continue;
            }

            if ($closeAfterDone) {
                $this->close();
            }

            return $curlResponse;
        }

        return '';
    }

    public function init(string $url): void
    {
        $this->ch = curl_init();
        $this->setOption(CURLOPT_URL, $url);
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLOPT_SSL_VERIFYPEER, true);
    }

    public function setOption($option, $value): void
    {
        $result = curl_setopt($this->ch, $option, $value);
        if (!$result) {
            throw new UnexpectedValueException(
                'Setting curl options failed: ' . $option . ' ' . $value
            );
        }
    }

    public function debug(): void
    {
        curl_setopt($this->ch, CURLOPT_VERBOSE, true);
    }

    /**
     * Close cURL resource, and free up system resources.
     */
    public function close(): void
    {
        curl_close($this->ch);
    }
}
