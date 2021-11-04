<?php

namespace App\Telegram\Middlewares;

use App\Models\TelegramUser;
use App\Telegram\Traits\ChatId;
use Closure;
use Illuminate\Support\Arr;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\Update;

class CheckPhoneNumberMiddleware implements Middleware
{
    use ChatId;

    protected array $except = [
        "/^\/help$/",
        "/^\/rules$/"
    ];

    public function __construct(public Api $telegram)
    {
    }

    public function handle(Update $update, Closure $next)
    {
        $user = TelegramUser::current();

        if ($this->hasPhoneNumber($user) || $this->bypass($update)) {
            return $next($update);
        }

        $this->askPhoneNumber($update);

        return null;
    }

    protected function bypass(Update $update): bool
    {
        $text = Arr::get($update, 'message.text');

        if (is_null($text)) {
            return false;
        }

        foreach ($this->except as $pattern) {
            $result = preg_match($pattern, $text);
            if ($result === 1) {
                return true;
            }
        }

        return false;
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
