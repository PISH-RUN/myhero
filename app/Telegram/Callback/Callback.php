<?php


namespace App\Telegram\Callback;


use Illuminate\Support\Arr;
use Telegram\Bot\Api;
use Telegram\Bot\BotsManager;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

abstract class Callback
{
    public function __construct(public Api $telegram)
    {
    }

    protected function updates()
    {
        return $this->telegram->getWebhookUpdate();
    }

    public function messageId(): ?int
    {
        return Arr::get($this->updates(), 'callback_query.message.message_id');
    }

    public function callbackQueryId(): string
    {
        return Arr::get($this->updates(), 'callback_query.id');
    }

    public function answerCallbackQuery(string $text, array $params = [])
    {
        $params = array_merge([
            'callback_query_id' => $this->callbackQueryId(),
            'text' => $text
        ], $params);

        return $this->telegram->answerCallbackQuery($params);
    }

    public function sendMessage(string $text, array $params = []): Message
    {
        $params = array_merge([
            'chat_id' => $this->chatId(),
            'text' => $text
        ], $params);

        return $this->telegram->sendMessage($params);
    }

    public function chatId(): ?int
    {
        return Arr::get($this->updates(), 'callback_query.message.chat.id');
    }

    public function sendPhoto(string $photo, ?string $caption, array $params = []): Message
    {
        $params = array_filter(array_merge([
            'chat_id' => $this->chatId(),
            'photo' => InputFile::create($this->normalizePhotoUrl($photo), 'photo.jpg'),
            'caption' => $caption
        ], $params));

        return $this->telegram->sendPhoto($params);
    }

    protected function normalizePhotoUrl(string $photo): string
    {
        return $photo = explode('?', $photo)[0];
    }

    abstract public function handle($value);
}
