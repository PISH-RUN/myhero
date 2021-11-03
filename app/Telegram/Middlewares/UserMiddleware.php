<?php

namespace App\Telegram\Middlewares;

use App\Models\TelegramUser;
use App\Telegram\Traits\ChatId;
use Closure;
use Illuminate\Support\Arr;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class UserMiddleware implements Middleware
{
    use ChatId;

    public function __construct(public Api $telegram)
    {
    }

    public function handle(Update $update, Closure $next)
    {
        $user = $this->loadTelegramUser($update);

        if ($user) {
            return $next($update);
        }

        $this->sendRegistrationError($update);

        return true;
    }

    protected function loadTelegramUser(Update $updates): ?TelegramUser
    {
        $telegramUser = $this->getTelegramUserArray($updates);

        if (!$telegramUser) {
            return null;
        }

        return TelegramUser::loadUser($telegramUser);
    }

    protected function getTelegramUserArray(Update $updates): ?array
    {
        return Arr::get(data_get($updates->toArray(), "*.from"), 1);
    }

    protected function sendRegistrationError(Update $update)
    {
        $this->telegram->sendMessage([
                'chat_id' => $this->chatId($update),
                'message' => 'telegram.registration_error']
        );
    }
}
