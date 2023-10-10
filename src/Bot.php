<?php

namespace VLotysh\BotGram;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class Bot
{
    private const API_URI = 'https://api.telegram.org';

    /**
     * @var Client
     */
    private Client $client;

    /**
     * @param string $key
     * @param Client|null $client
     */
    public function __construct(string $key, Client $client = null)
    {
        $this->client = $client ?: new Client([
            'base_uri' => sprintf('%s/bot%s/', self::API_URI, $key),
            'headers' => ['Content-Type' => 'application/json']
        ]) ;
    }

    /**
     * @param int $chatId
     * @param string $message
     * @param array $replyMarkup
     * @return bool
     * @throws GuzzleException
     */
    public function sendMessage(int $chatId, string $message, array $replyMarkup = []): bool
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $message,
        ];

        if (!empty($replyMarkup)) {
            $data['reply_markup'] = $replyMarkup;
        }

        $this->client->post('sendMessage', [
            RequestOptions::JSON => $data
        ]);

        return true;
    }

    public function editMessage(int $chatId, int $messageId, string $message, array $replyMarkup = []): bool
    {
        $data = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $message,
        ];

        if (!empty($replyMarkup)) {
            $data['reply_markup'] = $replyMarkup;
        }

        $this->client->post('editMessageText', [
            RequestOptions::JSON => $data
        ]);

        return true;
    }

    public function setWebhook(string $webhookUrl): bool
    {
        $this->client->get(
            'setWebhook',
            [
                'query' => [
                    'url' => $webhookUrl,
                ]
            ]
        );

        return true;
    }
}
