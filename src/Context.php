<?php

namespace VLotysh\BotGram;

class Context
{
    /**
     * @var array
     */
    private array $updateData;

    /**
     * @var int
     */
    private int $chatId;

    private ?int $callbackMessageId;

    private ?string $callbackParams;

    /**
     * @param array $updateData
     * @param int $chatId
     * @param string|null $callbackParams
     */
    public function __construct(
        array $updateData,
        int $chatId,
        int $callbackMessageId = null,
        string $callbackParams = null
    ) {
        $this->updateData = $updateData;
        $this->chatId = $chatId;
        $this->callbackMessageId = $callbackMessageId;
        $this->callbackParams = $callbackParams;
    }

    /**
     * @return array
     */
    public function getUpdateData(): array
    {
        return $this->updateData;
    }

    /**
     * @return int
     */
    public function getChatId(): int
    {
        return $this->chatId;
    }

    /**
     * @return int|null
     */
    public function getCallbackMessageId(): ?int
    {
        return $this->callbackMessageId;
    }

    /**
     * @return string|null
     */
    public function getCallbackParams(): ?string
    {
        return $this->callbackParams;
    }
}