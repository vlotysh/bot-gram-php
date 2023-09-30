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
        $handler->addTextHandler('/^\/test/',$this->getTextHandlerFunction($result));

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

        $handler->addTextHandler('/^(.+)$/', $this->getTextHandlerFunction($result));

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

        $handler->addTextHandler('/^(.+)$/', $this->getTextHandlerFunction($result));

        $this->assertTrue($handler->hasTextHandler('/^(.+)$/'));
    }

    public function testClearCallback()
    {
        $handler = new Handler();
        $result = '';

        $handler->addTextHandler('/^(.+)$/', $this->getTextHandlerFunction($result));
        $handler->clearTextHandler();

        $this->assertFalse($handler->hasTextHandler('/^(.+)$/'));
    }

    public function testCallback()
    {
        $handler = new Handler();
        $result = '';

        $handler->addCallbackHandler('TestCallback', $this->getCallbackHandlerFunction($result));

        $handler->execute([
            'callback_query' => [
                'data' => 'TestCallback|data',
                'message' => [
                    'chat'=> [
                        'id' => 1,
                    ],
                ],
            ],
        ]);

        $this->assertTrue($result === 'TestCallback|data');
    }

    public function testCondition()
    {
        $handler = new Handler();
        $result1 = '';
        $result2 = '';

        $handler->addConditionsHandler(function (array $update) {
            return true;
        }, $this->getCallbackHandlerFunction($result1));

        $handler->addCallbackHandler('TestCallback', $this->getCallbackHandlerFunction($result2));

        $handler->execute([
            'callback_query' => [
                'data' => 'TestCallback2|data',
                'message' => [
                    'chat'=> [
                        'id' => 1,
                    ],
                ],
            ],
        ]);

        $this->assertTrue($result1 === 'TestCallback2|data');
        $this->assertTrue($result2 === '');

    }

    public function testHandlerContext()
    {
        $handler = new Handler();
        $result = [];

        $handler->addCallbackHandler('TestCallback', $this->getCallbackWithContextFunction($result));

        $updateData = [
            'callback_query' => [
                'data' => 'TestCallback|data',
                'message' => [
                    'chat'=> [
                        'id' => 1,
                    ],
                    'message_id' => 2,
                ],
            ],
        ];
        $handler->execute($updateData);

        $this->assertTrue($result['updateData'] === $updateData);
        $this->assertTrue($result['chatId'] === 1);
        $this->assertTrue($result['callbackParams'] === 'data');
        $this->assertTrue($result['callbackMessageId'] === 2);
    }

    private function getTextHandlerFunction(&$result): callable
    {
        return function (Context $context) use (&$result) {
            $result = $context->getUpdateData()['message']['text'];
        };
    }

    private function getCallbackHandlerFunction(&$result): callable
    {
        return function (Context $context) use (&$result) {
            $result = $context->getUpdateData()['callback_query']['data'];
        };
    }

    private function getCallbackWithContextFunction(&$result): callable
    {
        return function (Context $context) use (&$result) {
            $result = [
                'chatId' => $context->getChatId(),
                'updateData' => $context->getUpdateData(),
                'callbackParams' => $context->getCallbackParams(),
                'callbackMessageId' => $context->getCallbackMessageId(),
            ];
        };
    }
}