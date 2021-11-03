<?php


namespace App\Telegram\Handlers;


use App\Telegram\Traits\ChatId;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;

class UnknownHandler implements Handler
{
    use ChatId;

    public function __construct(public Api $telegram)
    {
    }

    public function handle(): void
    {
        $updates = $this->telegram->getWebhookUpdate(false);

        $chatId = $this->chatId($updates);

        if ($chatId) {
            $this->telegram->sendMessage(['chat_id' => $chatId, 'text' => __('telegram.unknown_message')]);
        }
    }
}
