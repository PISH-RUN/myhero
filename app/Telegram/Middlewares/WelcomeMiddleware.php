<?php

namespace App\Telegram\Middlewares;

use App\Models\TelegramUser;
use App\Telegram\Traits\ChatId;
use Closure;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class WelcomeMiddleware implements Middleware
{
    use ChatId;

    public function __construct(public Api $telegram)
    {
    }

    protected function welcomeUser(Update $update)
    {
        $user = TelegramUser::current();

        if ($user?->wasRecentlyCreated === true) {
            $this->sendWelcomeMessage($update);
        }
    }

    protected function sendWelcomeMessage(Update $update)
    {
        $this->telegram->sendMessage([
            'chat_id' => $this->chatId($update),
            'text' => 'telegram.welcome'
        ]);
    }

    public function handle(Update $update, \Closure $next)
    {
        $this->welcomeUser($update);

        return $next($update);
    }
}
