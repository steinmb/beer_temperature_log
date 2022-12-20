<?php

declare(strict_types=1);

namespace steinmb\Logger\Handlers;

use JsonException;
use RuntimeException;
use steinmb\Formatters\FormatterInterface;
use steinmb\Logger\Curl;
use steinmb\Logger\JsonDecode;

final class BrewersFriendHandler implements HandlerInterface
{
    private const API_BREWSESSIONS = 'https://api.brewersfriend.com/v1/brewsessions';
    private const API_STREAM = 'https://log.brewersfriend.com/stream';
    private const API_FERMENTATION = 'https://api.brewersfriend.com/v1/fermentation';
    private $messages = [];
    private $lastMessage = '';

    public function __construct(
      private readonly string $sessionId,
      private readonly string $token,
      private readonly JsonDecode $jsonDecode,
      private readonly Curl $curl,
    ) {}

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

    private function fermentation()
    {
        $this->curl->init(self::API_FERMENTATION . '/' . $this->sessionId);
        return $this->curl->curl();
    }

    private function brewSession(): array
    {
        $this->curl->init(self::API_BREWSESSIONS . '/' . $this->sessionId);
        $request = $this->curl->curl();
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

    public function write(array $message, FormatterInterface $formatter = NULL): void
    {
        $payload = $this->message($message);
        $this->curl->init(self::API_STREAM . '/' . $this->token);
        $this->curl->setOption( CURLOPT_POST, 1);
        $this->curl->setOption( CURLOPT_FOLLOWLOCATION, 1);
        $this->curl->setOption(CURLOPT_HTTPHEADER, ['X-API-Key: ' . $this->token]);
        $this->curl->setOption( CURLOPT_POSTFIELDS, $payload);
        $this->curl->setOption( CURLOPT_HTTPHEADER, [
            'X-API-Key: ' . $this->token,
            'Content-Type: application/json',
        ]);
        $this->result($this->curl->curl());
        $this->messages[] = $payload;
        $this->lastMessage = $payload;
    }

    private function result($result): array
    {
        $resultDecoded = $this->jsonDecode->decode($result);

        if ($resultDecoded['message'] === 'success') {
            return $resultDecoded;
        }

        if ($resultDecoded['message'] === false) {
            throw new RuntimeException(
                'BrewersFriend API error. Description: ' . $resultDecoded["message"] . ' ' . $resultDecoded['detail']
            );
        }

        if ($resultDecoded['message'] === 'unauthorized') {
            throw new RuntimeException(
                'BrewersFriend API error. Description: ' . $resultDecoded["message"] . ' ' . $resultDecoded['detail']
            );
        }

        throw new RuntimeException(
            'BrewersFriend unknown API error. Description: ' . $resultDecoded["message"] . ' ' . $resultDecoded['detail']
        );
    }

    public function lastEntry(): string
    {
        return $this->lastMessage;
    }

    public function close(): void
    {}
}