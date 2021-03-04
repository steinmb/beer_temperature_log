<?php declare(strict_types=1);

namespace steinmb\Logger;

use RuntimeException;

final class TelegramHandler implements HandlerInterface
{
    private const BOT_API = 'https://api.telegram.org/bot';
    private $parseMode;
    private $disableWebPagePreview;
    private $disableNotification;
    private $messages = [];
    private $lastMessage = '';
    private $token;
    private $channel;
    private $curl;

    public function __construct(string $token, string $channel, Curl $curl)
    {
        $this->token = $token;
        $this->channel = $channel;
        $this->curl = $curl;
    }

    public function read(): string
    {
        return '';
    }

    public function write(array $message): void
    {
        $url = self::BOT_API . $this->token . '/SendMessage';
        $this->curl->init($url);
        $this->curl->setOption(CURLOPT_POSTFIELDS, http_build_query([
            'text' => $message['message'],
            'chat_id' => $this->channel,
            'parse_mode' => $this->parseMode,
            'disable_web_page_preview' => $this->disableWebPagePreview,
            'disable_notification' => $this->disableNotification,
        ]));
        $this->result($this->curl->curl());
        $this->messages[] = $message['message'];
        $this->lastMessage = $message['message'];
    }

    private function result($result): array
    {
        $result = json_decode($result, true, 512, JSON_THROW_ON_ERROR);

        if ($result['ok'] === false) {
            throw new RuntimeException(
                'Telegram API error. Description: ' . $result['description']
            );
        }

        return $result;
    }

    public function lastEntry(): string
    {
        return $this->lastMessage;
    }

    public function close(): void
    {
        $this->curl->close();
    }
}
