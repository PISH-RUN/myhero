<?php


namespace App\Telegram\Traits;


use Illuminate\Support\Arr;
use Telegram\Bot\Laravel\Facades\Telegram;

trait ChatId
{
    protected ?int $chatId = null;

    public function chatId($updates): ?int
    {
        if ($this->chatId) {
            return $this->chatId;
        }

        $results = data_get($updates, '*.chat.id');

        foreach ($results as $result) {
            if (!is_null($result)) {
                return $this->chatId = $result;
            }
        }

        return null;
    }
}
