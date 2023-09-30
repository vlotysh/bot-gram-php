<?php

namespace VLotysh\BotGram;

use Exception;

class Handler
{
    /**
     * @var array
     */
    private array $textHandlers = [];

    /**
     * @var array
     */
    private array $callbackHandlers = [];

    /**
     * @var array
     */
    private array $conditionsHandlers = [];

    /**
     * @param array $updateData
     * @return void
     * @throws Exception
     */
    public function execute(array $updateData): void
    {
        if ($this->matchConditionHandlers($updateData)) {
            return;
        } elseif (!empty($updateData['message']['text'])) {
            $this->matchTextHandlers($updateData);
        } elseif (!empty($updateData['callback_query']['data'])) {
            $this->matchCallbackHandlers($updateData);
        }
    }

    /**
     * @param string $regexp
     * @param callable $handler
     * @return void
     */
    public function addTextHandler(string $regexp, callable $handler): void
    {
        $this->textHandlers[] = [
            $regexp,
            $handler,
        ];
    }

    /**
     * @param string $callback
     * @param callable $handler
     * @return void
     */
    public function addCallbackHandler(string $callback, callable $handler): void
    {
        $this->callbackHandlers[] = [
            $callback,
            $handler,
        ];
    }

    /**
     * @param callable $condition
     * @param callable $handler
     * @return void
     */
    public function addConditionsHandler(callable $condition, callable $handler)
    {
        $this->conditionsHandlers[] = [
            $condition,
            $handler,
        ];
    }

    /**
     * @param string $regexp
     * @return bool
     */
    public function hasTextHandler(string $regexp): bool
    {
        foreach ($this->textHandlers as $callback) {
            if ($regexp === $callback[0]) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return void
     */
    public function clearTextHandler()
    {
        $this->textHandlers = [];
    }

    /**
     * @param array $updateData
     * @return void
     * @throws Exception
     */
    private function matchTextHandlers(array $updateData)
    {
        $text = $updateData['message']['text'] ?? '';
        $chatId = $updateData['message']['chat']['id'] ?? null;

        if (empty($chatId)) {
            throw new Exception('Empty chat id');
        }

        foreach ($this->textHandlers as $callback) {
            list($callbackRegexp, $handler) = $callback;

            if (preg_match($callbackRegexp, $text)) {
                $handler(new Context($updateData, $chatId));

                return;
            }
        }
    }

    /**
     * @param array $updateData
     * @return void
     * @throws Exception
     */
    private function matchCallbackHandlers(array $updateData)
    {
        $callbackQuery = $updateData['callback_query'];
        list ($key, $params) = explode('|', $callbackQuery['data'] ?? '');
        $chatId = $this->extractChatId($updateData);

        if (empty($chatId)) {
            throw new Exception('Empty chat id');
        }

        foreach ($this->callbackHandlers as $callback) {
            list($callbackKey, $handler) = $callback;

            if ($callbackKey === $key) {
                $handler(new Context($updateData, $chatId, $params));

                return;
            }
        }
    }

    /**
     * @param array $updateData
     * @return bool
     * @throws Exception
     */
    private function matchConditionHandlers(array $updateData): bool
    {
        $chatId = $this->extractChatId($updateData);

        if (empty($chatId)) {
            throw new Exception('Empty chat id');
        }

        foreach ($this->conditionsHandlers as $callback) {
            list($condition, $handler) = $callback;

            if ($condition($updateData)) {
                $handler(new Context($updateData, $chatId));

                return true;
            }
        }

        return false;
    }

    /**
     * @param array $updateData
     * @return int|null
     */
    private function extractChatId(array $updateData):? int
    {
        $context = $updateData['callback_query'] ?? $updateData;

        return $context['message']['chat']['id'] ?? null;
    }
}