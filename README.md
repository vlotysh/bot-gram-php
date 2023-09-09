# bot-gram-php

PHP library for Telegram API

```php
$updateData = file_get_contents("php://input");
$handler = new Handler();
# with bot key
$bot = new Bot('000000:AAAAAAAAAAAAAAAAAAAAAA');

# add command handler
$handler->addCallback('/^\/like/', function () use ($bot, $chatId) {
    $replyMarkup = array(
        'inline_keyboard' => [
            [
                [
                    'text' => "Yes",
                    'callback_data' => 'yes',
                ],
                [
                    'text' => "No",
                    'callback_data' => 'no',
                ],
            ]

        ],
    );


    $bot->sendMessage($chatId, 'Like bot?', $replyMarkup);
});

$handler->execute($updateData);
```