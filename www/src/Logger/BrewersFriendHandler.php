<?php declare(strict_types=1);

namespace steinmb\Logger;

use JsonException;
use RuntimeException;

final class BrewersFriendHandler implements HandlerInterface
{
    private const API_BREWSESSIONS = 'https://api.brewersfriend.com/v1/brewsessions';
    private const API_STREAM = 'https://log.brewersfriend.com/stream';
    private const API_FERMENTATION = 'https://api.brewersfriend.com/v1/fermentation';
    private $messages = [];
    private $lastMessage = '';
    private $token;
    private $sessionId;
    private $ch;
    private $jsonDecode;
    private $curl;

    public function __construct(string $sessionId, string $token, JsonDecode $jsonDecode, Curl $curl)
    {
        $this->token = $token;
        $this->sessionId = $sessionId;
        $this->jsonDecode = $jsonDecode;
        $this->curl = $curl;
    }

    public function read(): string
    {
        $brewesssion = $this->brewSession();
        $batchCode = $brewesssion["brewsessions"][0]["batchcode"];
        $recipeTitle = $brewesssion["brewsessions"][0]["recipe_title"];
        $styleName = $brewesssion["brewsessions"][0]["recipe"]["stylename"];
        $currentTemp = $brewesssion["brewsessions"][0]["current_stats"]["temp"];

        $fermentation = $this->fermentation();
        $content = "$batchCode, $recipeTitle, $currentTemp";
        echo $content . ' ºC' . PHP_EOL;
        return $content;
    }

    private function fermentation(): array
    {
        $this->curlInit(self::API_FERMENTATION . '/' . $this->sessionId);
        $request = $this->curl->curl($this->ch);
    }

    private function brewSession(): array
    {
        $this->curlInit(self::API_BREWSESSIONS . '/' . $this->sessionId);
        $request = $this->curl->curl($this->ch);
        return $this->jsonDecode->decode($request);
    }

    private function message(array $context): string
    {
        $payload = '';
        $brewSession = $context['context']['brewSession'];
        $temperature = $context['context']['temperature'];
        $ambient = $context['context']['ambient'];

        try {
            $payload = json_encode([
                'name' => $brewSession->probe,
                'device_source' => 'DS18B20 Sensor',
                'report_source' => 'BrewPi',
                'temp' => $temperature->temperature(),
                'ambient' => $ambient->temperature(),
                'temp_unit' => 'C',
            ], JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
        }

        return $payload;
    }

    public function write(array $message): void
    {
        $payload = $this->message($message);
        $this->curlInit(self::API_STREAM . '/' . $this->token);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $payload);
        $request = $this->curl();
        $result = $this->jsonDecode->decode($request);
        $this->messages[] = $payload;
        $this->lastMessage = $payload;
    }

    private function curlInit(string $url): void
    {
        $this->curl->init($url);
        $this->curl->setOption( 'CURLOPT_POST', 1);
        $this->curl->setOption( 'CURLOPT_FOLLOWLOCATION', 1);
        $this->curl->setOption( 'CURLOPT_HTTPHEADER', [
            'X-API-Key: ' . $this->token,
            'Content-Type: application/json',
        ]);
        $this->curl->setOption('CURLOPT_HTTPHEADER', ['X-API-Key: ' . $this->token]);
    }

    public function lastEntry(): string
    {
        return $this->lastMessage;
    }

    public function close(): void
    {}
}