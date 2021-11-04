<?php


namespace App\Telegram\Middlewares;


use App\Models\TelegramUser;
use App\Telegram\StateManager;
use Telegram\Bot\Objects\Update;

class StateMiddleware implements Middleware
{

    public function handle(Update $update, \Closure $next)
    {
        $result = StateManager::user(TelegramUser::current())->run();

        if (!$result) {
            return $next($update);
        }

        return true;
    }
}
