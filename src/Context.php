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

    /**
     * @param array $updateData
     * @param int $chatId
     */
    public function __construct(array $updateData, int $chatId)
    {
        $this->updateData = $updateData;
        $this->chatId = $chatId;
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
}