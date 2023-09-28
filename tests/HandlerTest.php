<?php

namespace VLotysh\BotGram\Tests;

use PHPUnit\Framework\TestCase;
use VLotysh\BotGram\Context;
use VLotysh\BotGram\Handler;

class HandlerTest extends TestCase
{
    public function testHandleCommand()
    {
        $handler = new Handler();

        $result = '';
        $handler->addTextHandler('/^\/test/',$this->getCallbackFunction($result));

        $handler->execute([
            'message' => [
                'text' => '/test',
                'chat' => [
                    'id' => 1,
                ],
            ]
        ]);

        $this->assertTrue($result === '/test');
    }

    public function testHandleText()
    {
        $handler = new Handler();
        $result = '';

        $handler->addTextHandler('/^(.+)$/', $this->getCallbackFunction($result));

        $handler->execute([
            'message' => [
                'text' => 'any text',
                'chat' => [
                    'id' => 1,
                ]
            ]
        ]);

        $this->assertTrue($result === 'any text');
    }

    public function testHasCallback()
    {
        $handler = new Handler();
        $result = '';

        $handler->addTextHandler('/^(.+)$/', $this->getCallbackFunction($result));

        $this->assertTrue($handler->hasTextHandler('/^(.+)$/'));
    }

    public function testClearCallback()
    {
        $handler = new Handler();
        $result = '';

        $handler->addTextHandler('/^(.+)$/', $this->getCallbackFunction($result));
        $handler->clearTextHandler();

        $this->assertFalse($handler->hasTextHandler('/^(.+)$/'));
    }

    private function getCallbackFunction(&$result): callable
    {
        return function (Context $context) use (&$result) {
            $result = $context->getUpdateData()['message']['text'];
        };
    }
}