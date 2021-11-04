<?php


namespace App\Telegram\Traits;

use Illuminate\Support\Arr;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

/**
 * @property Api $telegram
 */
trait MessageMethod
{

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
        return Arr::get($this->update(), 'message.chat.id');
    }

    protected function update(): Update
    {
        return $this->telegram->getWebhookUpdate();
    }

    public function sendPhoto(string $photo, ?string $caption = null, array $params = []): Message
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

}
