<?php

namespace VLotysh\BotGram;

class Handler
{
    /**
     * @var array
     */
    private array $regexpCallbacks = [];

    /**
     * @param array $updateData
     * @return void
     */
    public function execute(array $updateData): void
    {
        $text = $updateData['message']['text'] ?? '';

        foreach ($this->regexpCallbacks as $callback) {
            list($callbackRegexp, $handler) = $callback;

            if (preg_match($callbackRegexp, $text)) {
                $handler($updateData);

                return;
            }
        }
     }

    /**
     * @param string $regexp
     * @param callable $handler
     * @return void
     */
    public function addCallback(string $regexp, callable $handler): void
    {
        $this->regexpCallbacks[] = [
            $regexp,
            $handler,
        ];
    }

    /**
     * @param string $regexp
     * @return bool
     */
    public function hasCallback(string $regexp): bool
    {
        foreach ($this->regexpCallbacks as $callback) {
            if ($regexp === $callback[0]) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return void
     */
    public function clearCallbacks()
    {
        $this->regexpCallbacks = [];
    }
}