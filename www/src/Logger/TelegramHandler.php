<?php declare(strict_types=1);

namespace steinmb\Logger;

use RuntimeException;

class TelegramHandler implements HandlerInterface
{
    private const BOT_API = 'https://api.telegram.org/bot';
    private $parseMode;
    private $disableWebPagePreview;
    private $disableNotification;
    private $ch;
    private static $retrievableErrorCodes = [
        CURLE_COULDNT_RESOLVE_HOST,
        CURLE_COULDNT_CONNECT,
        CURLE_HTTP_NOT_FOUND,
        CURLE_READ_ERROR,
        CURLE_OPERATION_TIMEOUTED,
        CURLE_HTTP_POST_ERROR,
        CURLE_SSL_CONNECT_ERROR,
    ];
    private $messages = [];
    private $lastMessage = '';
    private $token;
    private $channel;

    public function __construct(string $token, string $channel)
    {
        $this->token = $token;
        $this->channel = $channel;
    }

    public function read(): string
    {
        $content = implode(PHP_EOL, $this->messages);
        echo $content . PHP_EOL;
        return $content;
    }

    public function write(array $message): void
    {
        $this->ch = curl_init();
        $url = self::BOT_API . $this->token . '/SendMessage';
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query([
            'text' => $message['message'],
            'chat_id' => $this->channel,
            'parse_mode' => $this->parseMode,
            'disable_web_page_preview' => $this->disableWebPagePreview,
            'disable_notification' => $this->disableNotification,
        ]));

        $result = $this->curl();
        $result = json_decode($result, true, 512, JSON_THROW_ON_ERROR);

        if ($result['ok'] === false) {
            throw new RuntimeException(
                'Telegram API error. Description: ' . $result['description']
            );
        }

        $this->messages[] = $message['message'];
        $this->lastMessage = $message['message'];
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

    public function close(): void
    {
        curl_close($this->ch);
    }

}