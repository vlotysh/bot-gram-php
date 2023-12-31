<?php

namespace VLotysh\BotGram\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use VLotysh\BotGram\Bot;

class BotTest extends TestCase
{
    private const DEFAULT_BOT_KEY = 'bot:test';

    private ?Bot $bot;

    public function setUp(): void
    {
        $mock = new MockHandler([
            new Response(200),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $this->bot = new Bot(self::DEFAULT_BOT_KEY, $client);

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->bot = null;
    }

    public function testSendMessage()
    {
        $this->assertTrue($this->bot->sendMessage(1, 'Hello from bot'));
    }
}