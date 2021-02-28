<?php declare(strict_types=1);

namespace steinmb\Logger;

use RuntimeException;

final class Curl
{
    private static $retrievableErrorCodes = [];

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
