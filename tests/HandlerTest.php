<?php

namespace VLotysh\BotGram\Tests;

use PHPUnit\Framework\TestCase;
use VLotysh\BotGram\Handler;

class HandlerTest extends TestCase
{
    public function testHandleCommand()
    {
        $handler = new Handler();

        $result = '';
        $handler->addCallback('/^\/test/',$this->getCallbackFunction($result));

        $handler->execute([
            'chat' => [
                'text' => '/test'
            ]
        ]);

        $this->assertTrue($result === '/test');
    }

    public function testHandleText()
    {
        $handler = new Handler();
        $result = '';

        $handler->addCallback('/^(.+)$/', $this->getCallbackFunction($result));

        $handler->execute([
            'chat' => [
                'text' => 'any text'
            ]
        ]);

        $this->assertTrue($result === 'any text');
    }

    public function testHasCallback()
    {
        $handler = new Handler();
        $result = '';

        $handler->addCallback('/^(.+)$/', $this->getCallbackFunction($result));

        $this->assertTrue($handler->hasCallback('/^(.+)$/'));
    }

    public function testClearCallback()
    {
        $handler = new Handler();
        $result = '';

        $handler->addCallback('/^(.+)$/', $this->getCallbackFunction($result));
        $handler->clearCallbacks();

        $this->assertFalse($handler->hasCallback('/^(.+)$/'));
    }

    private function getCallbackFunction(&$result): callable
    {
        return function ($updateData) use (&$result) {
            $result = $updateData['chat']['text'];
        };
    }
}