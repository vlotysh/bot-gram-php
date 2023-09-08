<?php

namespace VLotysh\BotGram;

class Handler
{
    private array $regexpCallbacks = [];

    public function execute(array $updateData): void
    {
        $text = $updateData['chat']['text'] ?? '';

        foreach ($this->regexpCallbacks as $callback) {
            list($callbackRegexp, $handler) = $callback;

            if (preg_match($callbackRegexp, $text)) {
                $handler($updateData);
            }
        }
     }

    public function addCallback(string $regexp, callable $handler): void
    {
        $this->regexpCallbacks[] = [
            $regexp,
            $handler,
        ];
    }

    public function hasCallback(string $regexp): bool
    {
        foreach ($this->regexpCallbacks as $callback) {
            if ($regexp === $callback[0]) {
                return true;
            }
        }

        return false;
    }

    public function clearCallbacks()
    {
        $this->regexpCallbacks = [];
    }
}