<?php


namespace App\Telegram\Commands;

use Illuminate\Support\Arr;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;


abstract class Command
{
    protected Update $update;

    public function __construct(public Api $telegram)
    {
        $this->update = $telegram->getWebhookUpdate();
    }

    abstract public function handle();

    public function sendMessage(string $text, array $params = []): Message
    {
        $params = array_merge([
            'chat_id' => $this->chatId(),
            'text' => $text
        ], $params);

        return $this->telegram->sendMessage($params);
    }

    public function sendPhoto(string $photo, string $caption, array $params = []): Message
    {
        $params = array_merge([
            'chat_id' => $this->chatId(),
            'caption' => $caption,
            'photo' => InputFile::create($photo, 'image.jpg')
        ], $params);

        return $this->telegram->sendPhoto($params);
    }

    protected function chatId()
    {
        return Arr::get($this->update, 'message.chat.id');
    }

}
