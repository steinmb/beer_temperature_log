<?php declare(strict_types=1);

namespace steinmb\Logger;

use RuntimeException;

final class Curl
{
    private static $retrievableErrorCodes = [];
    /**
     * @var \CurlHandle|false|resource
     */
    private $ch;

    public function curl($ch)
    {
        $retries = 5;
        $closeAfterDone = false;

        while ($retries--) {
            $curlResponse = curl_exec($ch);
            if ($curlResponse === false) {
                $curlErrno = curl_errno($ch);

                if (false === in_array($curlErrno, self::$retrievableErrorCodes, true) || !$retries) {
                    $curlError = curl_error($ch);

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
    }

    public function init(string $url): void
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $this->ch = $ch;
    }

    public function setOption(string $option, $value): void
    {
        curl_setopt($this->ch, $option, $value);
    }

    public function debug(): void
    {
        curl_setopt($this->ch, CURLOPT_VERBOSE, true);
    }

    /**
     * Close cURL resource, and free up system resources.
     *
     * @param $ch
     */
    private function close($ch): void
    {
        curl_close($ch);
    }
}
