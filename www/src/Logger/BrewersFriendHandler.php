<?php declare(strict_types=1);

namespace steinmb\Logger;

use RuntimeException;

class BrewersFriendHandler implements HandlerInterface
{
    private const API_BREWSESSIONS = 'https://api.brewersfriend.com/v1/brewsessions';
    private const API_STREAM = 'https://log.brewersfriend.com/stream';
    private const API_FERMENTATION = 'https://api.brewersfriend.com/v1/fermentation';
    private $messages = [];
    private $lastMessage = '';
    private $token;
    private $sessionId;
    private $ch;

    public function __construct(string $sessionId, string $token)
    {
        $this->token = $token;
        $this->sessionId = $sessionId;
    }

    public function read(): string
    {
        $session = $this->brewSession();
        $this->curlInit(self::API_FERMENTATION . '/' . $this->sessionId);
        $result = $this->curl();
        $result = json_decode($result, true, 512);

        if ($result['message'] === false) {
            throw new RuntimeException(
                'BrewersFriend API error. Description: ' . $result["message"] . ' ' . $result['detail']
            );
        }

        $content = $this->fermentationResult($result);
//        $content = implode(PHP_EOL, $this->messages);
        echo $content . PHP_EOL;
        return $content;
    }

    private function brewSession()
    {
        // https://api.brewersfriend.com/v1/brewsessions/:brew_session_id
        $url = self::API_BREWSESSIONS . '/' . $this->sessionId;
//        $this->curlInit(self::API_BREWSESSIONS . '/' . $this->sessionId);
        $this->curlInit($url);
        $result = $this->curl();
        $result = json_decode($result, true, 512);

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

        return $result;
    }

    private function fermentationResult(array $result): string
    {
        if (!$result) {
            return '';
        }

        $content = '';
        echo $result['message'] . PHP_EOL;

        foreach ($result['readings'] as $reading) {
            echo $reading['eventtype'] . PHP_EOL;
            echo $reading['created_at'] . PHP_EOL;
            if ($reading['temp']) {
                echo $reading['temp'] . $reading['temp_unit'] . PHP_EOL;
            }
        }

        return $content;
    }

    public function write(string $message)
    {
        $this->curlInit(self::API_STREAM . '/' . $this->sessionId);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query([
            'name' => 'BrewPi',
            'temp' => '19.1',
            'temp_unit' => 'C',
        ]));
        $result = $this->curl();
        $result = json_decode($result, true, 512, JSON_THROW_ON_ERROR);

        if ($result["message"] === 'failure') {
            throw new RuntimeException(
                'BrewersFriend API error. Description: ' . $result['detail']
            );
        }

        // TODO: Implement write() method.

        $this->messages[] = $message;
        $this->lastMessage = $message;
    }

    private function curlInit(string $url)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, true);
//        curl_setopt($this->ch, CURLOPT_HEADER, ['X-API-Key: ' . $this->token]);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, ['X-API-Key: ' . $this->token]);
        curl_setopt($this->ch, CURLOPT_VERBOSE, true);
    }

    private function curl()
    {
        $retries = 5;
        $closeAfterDone = false;

        while ($retries--) {
            $curlResponse = curl_exec($this->ch);
            if ($curlResponse === false) {
                $curlErrno = curl_errno($this->ch);

                if (false === in_array($curlErrno, self::$retrievableErrorCodes, true) || !$retries) {
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

        return false;
    }

    public function lastEntry(): string
    {
        return $this->lastMessage;
    }

    public function close()
    {
    }
}