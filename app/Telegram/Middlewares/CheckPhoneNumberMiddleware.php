<?php

namespace App\Telegram\Middlewares;

use App\Models\TelegramUser;
use App\Telegram\Traits\ChatId;
use Closure;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\Update;

class CheckPhoneNumberMiddleware implements Middleware
{
    use ChatId;

    public function __construct(public Api $telegram)
    {
    }

    public function handle(Update $update, Closure $next)
    {
        $user = TelegramUser::current();

        if ($this->hasPhoneNumber($user)) {
            return $next($update);
        }

        $this->askPhoneNumber($update);

        return null;
    }

    protected function hasPhoneNumber(?TelegramUser $user): bool
    {
        return !!$user?->phone_number;
    }

    protected function askPhoneNumber(Update $update)
    {
        $chatId = $this->chatId($update);

        if (!$chatId) {
            return;
        }

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => __('telegram.ask_phone_number'),
            'reply_markup' => $this->replyMarkup()
        ]);
    }

    protected function replyMarkup(): Keyboard
    {
        return Keyboard::make([
            'keyboard' => [[[
                'text' => __('telegram.share_phone_number'),
                'request_contact' => true
            ]]],
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);
    }
}
